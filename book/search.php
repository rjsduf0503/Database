<?php include("../header.php") ?> 
<?php 
    if(!isset($_SESSION["CNO"])){ //세션값을 확인하여 로그인 했을 경우에만 이용 가능하도록함
        echo '<script>alert("로그인 후 이용 가능합니다.");</script>';
        header("Refresh:0; url=../login/login.php");
    }
    $searchWord = $_GET['searchWord'] ?? "";
    if(isset($_GET["page"])) $page = (int)$_GET["page"];
    else $page = 1;
?> 
<div class="section_div">
<section class="wrap">
        <aside id="menu" class="left">
            <section class="wrap">
                <h3>Category</h3>
                    <ul>
                        <li><a class="category_link" href="../book/search.php" >Search</a></li>
                        <li><a class="category_link" href="../myPage/myPage.php" >My Page</a></li>
                    </ul>
            </section>
        </aside>
        <article>
            <!-- <form class="row">
                <div class="col-10">
                    <label for="searchWord" class="visually-hidden">Search</label>
                    <input type="text" class="form-control" name="searchWord" placeholder="Type your search criteria" value="<?= $searchWord ?>">
                </div>
                <div class="col-auto text-end">
                    <button type="submit" class="btn btn-primary mb-3">Search</button>
                </div>
            </form> -->
            <form>
                <div class="margin_div">
                    <label for="searchWord">Search</label>
                    <input type="text" id="searchWord" class="center" name="searchWord" placeholder="도서명을 검색하세요." value="<?= $searchWord ?>">
                </div>
                <div class="margin_div">
                    <button type="submit" class="search_btn_img"></button>
                </div>
            </form>

            <h1 class="center">Book List</h1>
            <table class="center bookList">
                <thead>
                    <tr>
                        <th>번호</th>
                        <th>고유 번호</th>
                        <th>도서명</th>
                        <th>저자</th>
                        <th>출판사</th>
                        <th>발행년도</th>
                        <th>대출 상태</th>
                    </tr>
                </thead>
                <tbody>
            <?php 
                $author;
                $conn = $orcl->connect();

                // 페이지 당 10개의 도서 씩 출력
                $res = $conn -> query("SELECT COUNT(*) FROM EBOOK WHERE LOWER(TITLE) LIKE '%' || '$searchWord' || '%'");
                $count = $res -> fetchColumn(); //첫 행 가져오기
                $list = 10; //한 페이지에 보여줄 도서 권수
                $block_cnt = 10; //블록 당 보여줄 페이지의 개수
                // $block_num = ceil($page / $block_cnt);
                $block_start = (($page - 1) * $block_cnt) + 1; //한 페이지의 시작 번호
                $block_end = $block_start + $block_cnt - 1;

                $total_page = ceil($count / $list);
                if($block_end > $count%$block_cnt && $page == $total_page) $block_end = $count;
                // $total_block = ceil($total_page / $block_cnt);
                // $page_start = ($page - 1) * $list;
                $stmt;
                $stmt = $conn -> query("SELECT *
                                            FROM(
                                                SELECT E.*, ROWNUM AS ROW_NUM 
                                                FROM EBOOK E
                                                WHERE LOWER(TITLE) LIKE '%' || '$searchWord' || '%'
                                                ORDER BY ROW_NUM
                                                )
                                        WHERE ROW_NUM >= $block_start AND ROW_NUM <= $block_end");
                $stmt -> execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <tr>
                        <td><?=$row["ROW_NUM"]?></td>
                        <td><?=$row["ISBN"] ?></td>
                        <td><a href="bookView.php?isbn=<?=$row["ISBN"]?>"><?=$row["TITLE"] ?></a></td>
                        <td>
                            <?php 
                                $query = "SELECT * FROM AUTHORS WHERE {$row['ISBN']} = ISBN";
                                $author = $orcl->select($query);
                                foreach ($author as $idx => $value) {
                                    if ($idx === array_key_last($author)) {
                                        echo $value["AUTHOR"];
                                    }
                                    else echo $value["AUTHOR"]."/";
                                }
                            ?>
                        </td>
                        <td><?=$row["PUBLISHER"] ?></td>
                        <td><?=$row["YEAR"] ?></td>
                        <td>
                            <?php 
                                if ($row["CNO"] !== NULL) {
                                    echo("대출 중");
                                }
                                else echo("대출 가능");
                            ?>
                        </td>
                    </tr>
                    <?php } ?>  
                    </tbody>
            </table>      

            <div id="paging" class="center">
                <?php 
                    // echo $page;
                    if($page <= 1) {}
                    else {
                        if ($searchWord == "") {
                            echo "<a href='search.php?page=1'>처음</a>";
                        }
                        else{
                            echo "<a href='search.php?searchWord=$searchWord?page=1'>처음</a>";
                        }
                    }
                    if($page <= 1) {}
                    else {
                        $previous = (int)$page - 1;
                        if ($searchWord == "") {
                            echo "<a href='search.php?page=$previous'>이전</a>";
                        }
                        else{
                            echo "<a href='search.php?searchWord=$searchWord?page=$previous'>이전</a>";
                        }
                    }
                    for ($idx=1; $idx <= $total_page ; $idx++) { 
                        if($page == $idx){
                            echo "<b> $idx </b>";
                        }
                        else {
                            if ($searchWord == "") {
                                echo "<a href='search.php?page=$idx'> $idx </a>";
                            }
                            else{
                                echo "<a href='search.php?searchWord=$searchWord?page=$idx'> $idx </a>";
                            }
                        }
                    }
                    if($page >= $total_page) {}
                    else{
                        $next = (int)$page + 1;
                        if ($searchWord == "") {
                            echo "<a href='search.php?page=$next'>다음</a>";
                        }
                        else{
                            echo "<a href='search.php?searchWord=$searchWord?page=$next'>다음</a>";
                        }
                    }
                    if($page >= $total_page) {}
                    else{
                        if ($searchWord == "") {
                            echo "<a href='search.php?page=$total_page'>끝</a>";
                        }
                        else{
                            echo "<a href='search.php?searchWord=$searchWord?page=$total_page'>끝</a>";
                        }
                    }
                ?>
            </div>

        </article>
</section>
</div>
<?php include("../footer.php") ?>
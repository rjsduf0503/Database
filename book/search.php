<?php include("../header.php") ?> 
<?php 
    if(!isset($_SESSION["CNO"])){ //세션값을 확인하여 로그인 했을 경우에만 이용 가능하도록함
        echo '<script>alert("로그인 후 이용 가능합니다.");</script>';
        header("Refresh:0; url=../login/login.php");
    }
    $searchWord = $_GET['searchWord'] ?? "";
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
            <form class="row">
                <div class="col-10">
                    <label for="searchWord" class="visually-hidden">Search</label>
                    <input type="text" class="form-control" name="searchWord" placeholder="Type your search criteria" value="<?= $searchWord ?>">
                </div>
                <div class="col-auto text-end">
                    <button type="submit" class="btn btn-primary mb-3">Search</button>
                </div>
            </form>
            <h2 class="center">Book List</h2>
            <table class="center">
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
                $ebook_query = "SELECT * FROM EBOOK WHERE LOWER(TITLE) LIKE '%' || :searchWord || '%'";
                // $bookList = $orcl->select($ebook_query);
                $conn = $orcl->connect();
                $stmt = $conn -> prepare($ebook_query);
                $stmt -> execute(array($searchWord));
            
                // $author_query = "SELECT * FROM AUTHORS";
                // $authorList = $orcl->select($author_query);
                $author;
                $num = 1;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <tr>
                        <td><?=$num++?></td>
                        <td><?=$row["ISBN"] ?></td>
                        <td><a href="bookView.php?isbn=<?=$row["ISBN"]?>"><?=$row["TITLE"] ?></a></td>
                        <td>
                            <?php 
                                $query = "SELECT * FROM AUTHORS";
                                $author = $orcl->select($query);
                                foreach ($author as $value) {
                                    if ($value["ISBN"] == $row["ISBN"]) {
                                        echo $value["AUTHOR"]."/";
                                    }
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
            search_book
        </article>
</section>
</div>
<?php include("../footer.php") ?>
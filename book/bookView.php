<link rel="stylesheet" href="../book/bookView.css">
<?php include("../aside.php") ?> 
<?php 
    if(!isset($_SESSION["CNO"])){ //세션값을 확인하여 로그인 했을 경우에만 이용 가능하도록함
        echo '<script>alert("로그인 후 이용 가능합니다.");</script>';
        header("Refresh:0; url=../login/login.php");
    }
?> 
        <article>
            <h1 class="center">도서 정보</h1>
            <table class="center bookInfo">
                <thead>
                    <tr>
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
                $url= $_SERVER['REQUEST_URI'];
                $present_isbn = substr($url, 39); //현재 URI로 isbn 값 받아오기

                $conn = $orcl->connect();
                //EBOOK Table에서 현재 page 도서의 isbn값과 같은 ISBN을 가진 행을 SELECT
                $res = $conn -> query("SELECT * FROM EBOOK WHERE ISBN = '$present_isbn'"); 

                //그 행의 값들을 가져와 테이블에 뿌려줌
                $row = $res->fetch(PDO::FETCH_ASSOC);
            ?>
                    <tr>
                        <td><?=$row["ISBN"] ?></td>
                        <td><?=$row["TITLE"] ?></a></td>
                        <td>
                            <?php 
                                //AUTHORS Table에서 현재 page 도서의 isbn과 같은 ISBN을 가진 행들을 SELECT
                                $query = "SELECT * FROM AUTHORS WHERE {$row['ISBN']} = ISBN";
                                $author = $orcl->select($query);
                                //그 행들을 돌며 저자명을 가져와 출력 
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
                                //CNO값으로 대출 상태를 출력
                                if ($row["CNO"] !== NULL) {
                                    echo("대출 중");
                                }
                                else echo("대출 가능");
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>    
            
            <?php 
                //도서의 설명이 적힌 json 파일을 가져와 도서별로 설명 출력
                $file = "../book/bookInfo.json";
                if(file_exists($file)){
                    $file = explode("\n",file_get_contents($file));
                    foreach ($file as $val) {
                        $obj = json_decode($val);
                        if($obj !== null){
                            if($obj->ISBN == $row["ISBN"]){
                                ?>
                                <h1 class="center">도서 설명</h1>
                                <h4 class="book_description">
                                    <?php
                                        echo $obj->DESCRIPTION;
                                    ?>
                                </h4>
                                <?php
                            }
                        }
                    }
                }
            ?>
            <div class="center button_div">
                <form action="POST">
                    <input type="hidden" name="present_isbn1" id="present_isbn1" value="<?php echo $row['ISBN'] ?>">
                    <button type="button" name="rental_btn" id="rental_btn">대여</button>
                    <button type="button" name="reservation_btn" id="reservation_btn">예약</button>
                </form>
            </div>
        </article>
</section>
</div>
<?php include("../footer.php") ?>
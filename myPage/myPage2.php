<!-- 예약 도서 정보 및 예약 취소 페이지 -->
<link rel="stylesheet" href="../myPage/myPage.css"> 
<?php include("../aside.php") ?> 
<?php 
    if(!isset($_SESSION["CNO"])){ //세션값을 확인하여 로그인 했을 경우에만 이용 가능하도록함
        echo '<script>alert("로그인 후 이용 가능합니다.");</script>';
        header("Refresh:0; url=../login/login.php");
    }
?> 
        <article>
            <h1 class="center">회원 정보</h1>
            <table class="center customerInfo">
                <thead>
                    <tr>
                        <th>회원 번호</th>
                        <th>이름</th>
                        <th>예약 권수</th>
                    </tr>
                </thead>
                <tbody>
            <?php 
                //현재 로그인한 고객의 회원 정보 및 예약 권수 출력
                $CNO = $_SESSION["CNO"];
                $conn = $orcl->connect();

                $stmt = $conn -> query("SELECT * FROM CUSTOMER WHERE CNO = '$CNO'");
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $res = $conn -> query("SELECT COUNT(*) FROM RESERVE WHERE CNO = '$CNO'");
                $count = $res -> fetchColumn(); //첫 행 가져오기
            ?>
                    <tr>
                        <td><?=$row["CNO"] ?></td>
                        <td><?=$row["NAME"] ?></td>
                        <td><?=$count?></td>
                    </tr>
                </tbody>
            </table>    

            <h1 class="center">예약 도서 정보</h1>
            <table class="center reservationInfo">
                <thead>
                    <tr>
                        <th>번호</th>
                        <th>고유 번호</th>
                        <th>도서명</th>
                        <th>예약일자</th>
                        <th>예약 취소하기</th>
                    </tr>
                </thead>
                <tbody>
            <?php 
                //현재 로그인한 고객의 예약 도서 정보 출력
                $stmt1 = $conn -> query("SELECT R.*, ROWNUM AS ROW_NUM FROM RESERVE R WHERE R.CNO = '$CNO'");
                while($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){
            ?>
                    <tr>
                        <td><?=$row1["ROW_NUM"] ?></td>
                        <td><?=$row1["ISBN"] ?></td>
                        <td>
                            <?php
                                $stmt2 = $conn -> query("SELECT * FROM EBOOK");
                                while($row2 = $stmt2 -> fetch(PDO::FETCH_ASSOC)){
                                    if($row1["ISBN"] == $row2["ISBN"]){
                                        echo $row2["TITLE"];
                                        ?>
                        </td>
                        <td>
                            <?php
                                        echo $row1["DATETIME"];
                                    }
                                }
                            ?>
                        </td>
                        <td>
                            <button type="button" name="cancelBook_btn" class="cancelBook_btn" value="<?= $row1['ISBN'] ?>">취소</button>
                        </td>
                    </tr>
                </tbody>
                <?php } ?>  
            </table>    
        </article>
</section>
</div>



<?php include("../footer.php") ?>
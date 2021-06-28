<!-- 대여 도서 정보 및 반납, 연장 페이지 -->
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
                        <th>대출 권 수</th>
                    </tr>
                </thead>
                <tbody>
            <?php 
                //현재 로그인한 고객의 회원 정보 및 대출 권수 출력
                $CNO = $_SESSION["CNO"];
                $conn = $orcl->connect();

                $stmt = $conn -> query("SELECT * FROM CUSTOMER WHERE CNO = '$CNO'");
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $res = $conn -> query("SELECT COUNT(*) FROM EBOOK WHERE CNO = '$CNO'");
                $count = $res -> fetchColumn(); //첫 행 가져오기
            ?>
                    <tr>
                        <td><?=$row["CNO"] ?></td>
                        <td><?=$row["NAME"] ?></td>
                        <td><?=$count?></td>
                    </tr>
                </tbody>
            </table>    

            <h1 class="center">대출 도서 정보</h1>
            <table class="center rentalInfo">
                <thead>
                    <tr>
                        <th>번호</th>
                        <th>고유 번호</th>
                        <th>도서명</th>
                        <th>대출일자</th>
                        <th>반납일자</th>
                        <th>연장 횟수</th>
                        <th>반납하기</th>
                        <th>연장하기</th>
                    </tr>
                </thead>
                <tbody>
            <?php 
                //현재 로그인한 고객의 대출 도서 정보 출력
                $stmt1 = $conn -> query("SELECT E.*, ROWNUM AS ROW_NUM FROM EBOOK E WHERE E.CNO = '$CNO'");
                while($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){
            ?>
                    <tr>
                        <td><?=$row1["ROW_NUM"] ?></td>
                        <td><?=$row1["ISBN"] ?></td>
                        <td><?=$row1["TITLE"] ?></td>
                        <td><?=$row1["DATERENTED"] ?></td>
                        <td><?=$row1["DATEDUE"] ?></td>
                        <td><?=$row1["EXTTIMES"] ?></td>
                        <td>
                            <button type="button" name="returnBook_btn" class="returnBook_btn" value="<?=$row1["ISBN"]?>">반납</button>
                        </td>
                        <td>
                            <button type="button" name="extensionBook_btn" class="extensionBook_btn" value="<?=$row1["ISBN"]?>">연장</button>
                        </td>
                    </tr>
                </tbody>
                <?php } ?>  
            </table>    
        </article>
</section>
</div>



<?php include("../footer.php") ?>
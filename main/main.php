<!-- 별 다른 구현은 하지 않았으나 관리자(CNO==0)일 경우에 이 페이지에 유용한 통계 정보를 출력하였음 -->
<link rel="stylesheet" href="../main/main.css"> 
<?php include("../aside.php") ?> 
<?php 
    if(!isset($_SESSION["CNO"])){ //세션값을 확인하여 로그인 했을 경우에만 이용 가능하도록함
        echo '<script>alert("로그인 후 이용 가능합니다.");</script>';
        header("Refresh:0; url=../login/login.php");
    }
?> 
        <article>
            <?php 
                if($_SESSION["CNO"] == 0){ //관리자일 경우
                    ?>
                        <table class="adminTable leftTable">
                            <caption align="center"><과거 대여 기록 중 오래 대여한 순서></caption>
                            <thead>
                                <th>회원 번호</th>
                                <th>도서 번호</th>
                                <th>도서명</th>
                                <th>대여 기간</th>
                            </thead>
                            <tbody>
                                <?php
                                //과거 대여 기록 중 오래 대여한 순서
                                $stmt = $conn -> query("SELECT E.ISBN, E.TITLE, P.CNO, P.DATERETURNED-P.DATERENTED+1 AS GAP
                                                        FROM EBOOK E JOIN PREVIOUSRENTAL P
                                                        ON (E.ISBN = P.ISBN)
                                                        ORDER BY GAP DESC");
                                $stmt -> execute();
                                while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                                ?>
                                    <tr>
                                        <td><?=$row["CNO"]?></td>
                                        <td><?=$row["ISBN"]?></td>
                                        <td><?=$row["TITLE"]?></td>
                                        <td><?=$row["GAP"]?></td>        
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <table class="adminTable rightTable">
                            <caption align="center"><과거 대여 기록 중 가장 최근에 대여한 도서></caption>
                            <thead>
                                <th>회원 번호</th>
                                <th>회원명</th>
                                <th>대여 도서 번호</th>
                                <th>가장 최근에 대여한 도서 번호</th>
                                <th>대여 날짜</th>
                            </thead>
                            <tbody>
                                <?php
                                //과거 대여 기록 중 가장 최근에 대여한 도서
                                $stmt = $conn -> query("SELECT P.CNO, C.NAME, P.ISBN, P.DATERENTED,
                                                            FIRST_VALUE(P.ISBN) OVER
                                                            (PARTITION BY P.CNO ORDER BY P.DATERENTED DESC
                                                            ROWS UNBOUNDED PRECEDING) AS RECENTBOOK
                                                        FROM PREVIOUSRENTAL P, CUSTOMER C
                                                        WHERE P.CNO = C.CNO");
                                $stmt -> execute();
                                while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                                ?>
                                    <tr>
                                        <td><?=$row["CNO"]?></td>
                                        <td><?=$row["NAME"]?></td>
                                        <td><?=$row["ISBN"]?></td>
                                        <td><?=$row["RECENTBOOK"]?></td>
                                        <td><?=$row["DATERENTED"]?></td>        
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <table class="adminTable">
                            <caption align="center"><과거 대여 했던 도서를 현재 대여중인 회원></caption>
                            <thead>
                                <th>회원 번호</th>
                                <th>도서 번호</th>
                                <th>도서명</th>
                                <th>현재 대여일</th>
                                <th>과거 대여일</th>
                                <th>과거 대여 날짜와의 차이</th>
                            </thead>
                            <tbody>
                                <?php
                                //과거 대여 했던 도서를 현재 대여중인 회원 (TP5때 잘못 작성하여 다시 만들었습니다.)
                                $stmt = $conn -> query("SELECT E.ISBN, E.TITLE, P.CNO, E.DATERENTED AS PRESENT, P.DATERENTED AS PREV, E.DATERENTED-P.DATERENTED AS GAP 
                                                        FROM EBOOK E, PREVIOUSRENTAL P
                                                        WHERE E.ISBN = P.ISBN AND E.CNO = P.CNO");
                                $stmt -> execute();
                                while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                                ?>
                                    <tr>
                                        <td><?=$row["CNO"]?></td>
                                        <td><?=$row["ISBN"]?></td>
                                        <td><?=$row["TITLE"]?></td>
                                        <td><?=$row["PRESENT"]?></td>
                                        <td><?=$row["PREV"]?></td>
                                        <td><?=$row["GAP"]?></td>        
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                <?php } ?>
        </article>
</section>
</div>
<?php include("../footer.php") ?>
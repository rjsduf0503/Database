<link rel="stylesheet" href="../book/search.css">
<?php include("../aside.php") ?> 
<?php 
    if(!isset($_SESSION["CNO"])){ //세션값을 확인하여 로그인 했을 경우에만 이용 가능하도록함
        echo '<script>alert("로그인 후 이용 가능합니다.");</script>';
        header("Refresh:0; url=../login/login.php");
    }
    $conn = $orcl->connect();

    //검색 조건 값을 GET으로 가져옴, 입력 값이 없다면 빈 값으로 설정
    $bookName = $_GET["bookName"] ?? "";
    $bookNameOption = $_GET["bookNameOption"] ?? "";
    $author = $_GET["author"] ?? "";
    $authorOption = $_GET["authorOption"] ?? "";
    $publisher = $_GET["publisher"] ?? "";
    $publisherOption = $_GET["publisherOption"] ?? "";
    $bookYearFrom = $_GET["bookYearFrom"] ?? "";
    $bookYearTo = $_GET["bookYearTo"] ?? "";
    $bookYearOption = $_GET["bookYearOption"] ?? "";

    //기본 쿼리
    $sql = "SELECT * FROM (SELECT E.*, A.AUTHOR, ROWNUM AS ROW_NUM FROM EBOOK E, (SELECT ISBN, LISTAGG(AUTHOR, ',') AS AUTHOR FROM AUTHORS GROUP BY ISBN) A WHERE E.ISBN = A.ISBN) ";
    $count = 0; //첫 검색조건 설정을 위한 변수
    //0일 때만 WHERE절로 시작, 나머지는 연산자로 시작

    //도서명 검색조건
    if($bookName !== ""){
        if($count == 0){
            if($bookNameOption === "NOT"){ //NOT
                $sql .= "WHERE TITLE NOT LIKE "."'%".$bookName."%' ";
            }
            else{ //AND or OR
                $sql .= "WHERE TITLE LIKE "."'%".$bookName."%' ";
            }
        }
        else{
            if($bookNameOption === "NOT"){ //NOT
                $sql .= "AND TITLE NOT LIKE "."'%".$bookName."%' ";
            }
            else{ //AND or OR
                $sql .= $bookNameOption." TITLE LIKE "."'%".$bookName."%' ";
            }
        }
        $count++;
    }

    //저자 검색조건
    if($author !== ""){
        if($count == 0){
            if($authorOption === "NOT"){ //NOT
                $sql .= "WHERE AUTHOR NOT LIKE "."'%".$author."%' ";
            }
            else{ //AND or OR
                $sql .= "WHERE AUTHOR LIKE "."'%".$author."%' ";
            }
        }
        else{
            if($authorOption === "NOT"){ //NOT
                $sql .= "AND AUTHOR NOT LIKE "."'%".$author."%' ";
            }
            else{ //AND or OR
                $sql .= $authorOption." AUTHOR LIKE "."'%".$author."%' ";
            }
        }
        $count++;
    }

    
    //출판사 검색조건
    if($publisher !== ""){
        if($count == 0){
            if($publisherOption === "NOT"){ //NOT
                $sql .= "WHERE PUBLISHER NOT LIKE "."'%".$publisher."%' ";
            }
            else{ //AND or OR
                $sql .= "WHERE PUBLISHER LIKE "."'%".$publisher."%' ";
            }
        }
        else{
            if($publisherOption === "NOT"){ //NOT
                $sql .= "AND PUBLISHER NOT LIKE "."'%".$publisher."%' ";
            }
            else{ //AND or OR
                $sql .= $publisherOption." PUBLISHER LIKE "."'%".$publisher."%' ";
            }
        }
        $count++;
    }
    
    //발행년도 검색조건
    //두 조건: 기본 값은 사이, NOT은 사이 제외
    //한 조건: 기본 값은 ~~까지, NOT은 ~~이후
    if($bookYearFrom !== "" && $bookYearTo !== ""){
        if($count == 0){
            if($bookYearOption === "NOT"){ //NOT
                $sql .= "WHERE YEAR NOT BETWEEN TO_DATE("."'".$bookYearFrom."'".", 'YYYY-MM-DD') AND TO_DATE("."'".$bookYearTo."'".", 'YYYY-MM-DD')";
            }
            else{ //AND or OR
                $sql .= "WHERE YEAR BETWEEN TO_DATE("."'".$bookYearFrom."'".", 'YYYY-MM-DD') AND TO_DATE("."'".$bookYearTo."'".", 'YYYY-MM-DD')";
            }
        }
        else{
            if($bookYearOption === "NOT"){ //NOT
                $sql .= "AND YEAR NOT BETWEEN TO_DATE("."'".$bookYearFrom."'".", 'YYYY-MM-DD') AND TO_DATE("."'".$bookYearTo."'".", 'YYYY-MM-DD')";
            }
            else{ //AND or OR
                $sql .= $publisherOption." YEAR BETWEEN TO_DATE("."'".$bookYearFrom."'".", 'YYYY-MM-DD') AND TO_DATE("."'".$bookYearTo."'".", 'YYYY-MM-DD')";
            }
        }
        $count++;
    }
    elseif ($bookYearFrom === "" && $bookYearTo !== "") {
        if($count == 0){ //NOT
            if($bookYearOption === "NOT"){
                $sql .= "WHERE YEAR < TO_DATE("."'".$bookYearTo."'".", 'YYYY-MM-DD')";
            }
            else{ //AND or OR
                $sql .= "WHERE YEAR >= TO_DATE("."'".$bookYearTo."'".", 'YYYY-MM-DD')";
            }
        }
        else{
            if($bookYearOption === "NOT"){ //NOT
                $sql .= "AND YEAR < TO_DATE("."'".$bookYearTo."'".", 'YYYY-MM-DD')";
            }
            else{ //AND or OR
                $sql .= $publisherOption." YEAR >= TO_DATE("."'".$bookYearTo."'".", 'YYYY-MM-DD')";
            }
        }
        $count++;
    }
    elseif ($bookYearFrom !== "" && $bookYearTo === "") {
        if($count == 0){
            if($bookYearOption === "NOT"){ //NOT
                $sql .= "WHERE YEAR < TO_DATE("."'".$bookYearFrom."'".", 'YYYY-MM-DD')";
            }
            else{ //AND or OR
                $sql .= "WHERE YEAR >= TO_DATE("."'".$bookYearFrom."'".", 'YYYY-MM-DD'";
            }
        }
        else{
            if($bookYearOption === "NOT"){ //NOT
                $sql .= "AND YEAR < TO_DATE("."'".$bookYearFrom."'".", 'YYYY-MM-DD')";
            }
            else{ //AND or OR
                $sql .= $publisherOption." YEAR >= TO_DATE("."'".$bookYearFrom."'".", 'YYYY-MM-DD'";
            }
        }
        $count++;
    }
    ?> 
        <article>
            <form method="get" action="search.php">
                <div class="margin_div">
                        <div>
                            <label>도서명</label>
                            <input type="text" name="bookName" id="bookName">
                            <select name="bookNameOption">
                                <option value="AND">AND</option>
                                <option value="OR">OR</option>
                                <option value="NOT">NOT</option>
                            </select>
                        </div>
                        <div>
                            <label>저자</label>
                            <input type="text" name="author">
                            <select name="authorOption">
                                <option value="AND">AND</option>
                                <option value="OR">OR</option>
                                <option value="NOT">NOT</option>
                            </select>
                        </div>
                        <div>
                            <label>출판사</label>
                            <input type="text" name="publisher">
                            <select name="publisherOption">
                                <option value="AND">AND</option>
                                <option value="OR">OR</option>
                                <option value="NOT">NOT</option>
                            </select>
                        </div>
                        <div>
                            <label>발행년도</label>
                            <input type="date" name="bookYearFrom">~
                            <input type="date" name="bookYearTo">
                            <select name="bookYearOption">
                                <option value="AND">AND</option>
                                <option value="OR">OR</option>
                                <option value="NOT">NOT</option>
                            </select>
                        </div>
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
                //검색 결과와 합쳐진 최종 쿼리
                $stmt = $conn -> query($sql);
                $stmt -> execute();
                //쿼리 수행 결과로 나온 행들을 돌며 테이블에 값 뿌리기
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <tr>
                        <td><?=$row["ROW_NUM"]?></td>
                        <td><?=$row["ISBN"] ?></td>
                        <!-- 도서 별 상세 페이지(예약 및 대여 페이지)인 bookView로 가는 링크 -->
                        <td><a class="bookViewLink" href="bookView.php?isbn=<?=$row["ISBN"]?>"><?=$row["TITLE"] ?></a></td>
                        <td>
                            <?php 
                                //AUTHORS Table에서 현재 도서의 ISBN값과 같은 행들을 돌며 저자 출력 
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
                                //CNO값으로 대출 상태를 출력
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
        </article>
</section>
</div>
<?php include("../footer.php") ?>
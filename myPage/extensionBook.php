<?php
// 대출한 도서의 대출 기한 연장을 수행
  function test_input($data) { //validation 함수
    $data = trim($data); //처음과 끝 앞뒤의 공백 제거
    $data = stripslashes($data); //백슬래시 제거
    $data = htmlspecialchars($data); //특수 문자를 HTML 엔티티로 변환
    return $data;
  }
  session_start();
  include '../OracleDB.php';
  $orcl = new OracleDB('127.0.0.1', 'TP201702043', 'password');
  $conn = $orcl->connect();

  $method = $_SERVER['REQUEST_METHOD']; //method는 POST로 받아왔으므로 POST
  $data = $_REQUEST['data']; //data 값을 요청하여 얻음
  $stmt1;
  $canExtension = false;
  $decodedData = json_decode($data); //얻어온 data값은 json화 되어있으므로 decode
    if($method == 'POST'){
        $ISBN = test_input($decodedData->ISBN); //대여 기간 연장할 도서의 ISBN
        $CNO = $_SESSION['CNO']; //현재 로그인한 고객의 CNO

        //연장할 도서가 존재하는지 체크
        $res = $conn -> query("SELECT COUNT(*) FROM EBOOK WHERE CNO = '$CNO'");
        $count = $res -> fetchColumn(); //첫 행 가져오기
        
        //현재 고객이 연장할 도서의 연장횟수와 반납기한
        $stmt = $conn -> query("SELECT EXTTIMES, TO_CHAR(DATEDUE, 'YYYY-MM-DD') AS DATEDUE FROM EBOOK WHERE CNO = '$CNO' AND ISBN ='$ISBN'");
        $row = $stmt -> fetch(PDO::FETCH_ASSOC); 
        $exttimes = $row["EXTTIMES"]; //연장횟수
        $datedue = $row["DATEDUE"]; //반납기한

        //연장할 도서의 예약자 수 체크
        $res1 = $conn -> query("SELECT COUNT(*) FROM RESERVE WHERE ISBN ='$ISBN'");
        $reserveCount = $res1 -> fetchColumn();
        //연장할 도서가 있고, 연장 횟수가 2회 미만, 예약자가 없는 경우에만 연장
        if($count >= 1 && $exttimes < 2 && $reserveCount == 0){
            $exttimes += 1;
            $datedue = date("Y/m/d", strtotime("+10 day", strtotime($datedue)));  
            $stmt1 = $conn->query("UPDATE EBOOK SET EXTTIMES = '$exttimes', DATEDUE = '$datedue' WHERE ISBN = '$ISBN'");
            $stmt1 -> execute();
            $canExtension = true;
        }
    }
    echo $canExtension;



  ?>
<!-- 예약한 도서의 예약 취소를 수행 -->
<?php
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
  $canCancel = false;
  $decodedData = json_decode($data); //얻어온 data값은 json화 되어있으므로 decode
    if($method == 'POST'){
        $ISBN = test_input($decodedData->ISBN); //예약 취소할 도서의 ISBN
        $CNO = $_SESSION['CNO']; //현재 로그인한 고객의 CNO

        //예약 취소할 도서가 존재하는지 체크
        $res = $conn -> query("SELECT COUNT(*) FROM RESERVE WHERE CNO = '$CNO'");
        $count = $res -> fetchColumn(); //첫 행 가져오기
        
        //예약 취소할 도서가 있는 경우에만 취소
        if($count >= 1){
            $stmt = $conn->query("DELETE FROM RESERVE WHERE ISBN = '$ISBN' AND CNO = '$CNO'");
            $stmt -> execute();
            $canCancel = true;
        }
    }
    echo $canCancel;



  ?>
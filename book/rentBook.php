<?php
  function test_input($data) { //validation 함수
    $data = trim($data); //처음과 끝 앞뒤의 공백 제거
    $data = stripslashes($data); //백슬래시 제거
    $data = htmlspecialchars($data); //특수 문자를 HTML 엔티티로 변환
    return $data;
  }
  session_start(); //CNO 값을 가져오기 위한 session start
  include '../OracleDB.php';
  $orcl = new OracleDB('127.0.0.1', 'TP201702043', 'password');
  $conn = $orcl->connect();

  $method = $_SERVER['REQUEST_METHOD']; //method는 POST로 받아왔으므로 POST
  $data = $_REQUEST['data']; //data 값을 요청하여 얻음
  $stmt1;
  $canRent = false; //대여할 수 있는지의 여부
  $decodedData = json_decode($data); //얻어온 data값은 json화 되어있으므로 decode
    if($method == 'POST'){
        $ISBN = test_input($decodedData->ISBN);

        //EBOOK Table에서 data로 넘어온 ISBN과 일치하는 ISBN을 가진 행들을 SELECT(대여를 위한)
        $stmt = $conn->query("SELECT * FROM EBOOK WHERE ISBN = '$ISBN'");
        $stmt -> execute();

        $CNO = $_SESSION['CNO']; //현재 로그인한 고객의 CNO
        //EBOOK Table에서 현재 로그인한 고객의 CNO와 일치하는 CNO의 행의 개수를 SELECT
        //고객 본인의 현재 대여 권수를 체크
        $res = $conn -> query("SELECT COUNT(*) FROM EBOOK WHERE CNO = '$CNO'");
        $count = $res -> fetchColumn(); //첫 행 가져오기

        if($count >= 3){ //대여 한도(3개)를 초과해서 대여할 수 없음
            // continue;
        }
        else{
            $row = $stmt -> fetch(PDO::FETCH_ASSOC);
            //현재 날짜, 10일 뒤
            date_default_timezone_set('Asia/Seoul');
            $today = date('Y/m/d');
            $due_day = date('Y/m/d', strtotime('+10 days'));
            //CNO값이 null일 때(현재 이 책을 대여중인 사람이 없을 때)만 대여 가능
            //최종적으로 현재 이 책이 대여 가능하고, 본인의 대여 한도를 초과하지 않는 경우에 대여 가능
            if($row["CNO"] == null){ 
                $stmt1 = $conn -> query("UPDATE EBOOK SET CNO = '$CNO', DATERENTED = '$today', DATEDUE = '$due_day' WHERE ISBN = '$ISBN'");
                $stmt1 -> execute();
                $canRent = true;
            }
        }
    }
    echo $canRent;



  ?>
<?php
  function test_input($data) { //validation 함수
    $data = trim($data); //처음과 끝 앞뒤의 공백 제거
    $data = stripslashes($data); //백슬래시 제거
    $data = htmlspecialchars($data); //특수 문자를 HTML 엔티티로 변환
    return $data;
  }
  session_start(); //현재 로그인한 고객의 CNO 값을 불러오기 위함
  include '../OracleDB.php';
  $orcl = new OracleDB('127.0.0.1', 'TP201702043', 'password');
  $conn = $orcl->connect();

  $method = $_SERVER['REQUEST_METHOD']; //method는 POST로 받아왔으므로 POST
  $data = $_REQUEST['data']; //data 값을 요청하여 얻음
  $stmt2;
  $canReserve = false; //예약할 수 있는지의 여부
  $decodedData = json_decode($data); //얻어온 data값은 json화 되어있으므로 decode
    if($method == 'POST'){
        $ISBN = test_input($decodedData->ISBN);
        //예약하고 싶은 도서
        $stmt = $conn->query("SELECT * FROM EBOOK WHERE ISBN = '$ISBN'");
        $stmt -> execute();

        //본인의 예약 기록을 보기 위해
        $CNO = $_SESSION['CNO'];
        $stmt1 = $conn->query("SELECT * FROM RESERVE WHERE CNO = '$CNO'");
        $stmt1 -> execute();

        //고객 본인의 예약 권수 체크
        $res = $conn -> query("SELECT COUNT(*) FROM RESERVE WHERE CNO = '$CNO'");
        $count = $res -> fetchColumn(); //첫 행 가져오기

        //고객 본인이 이미 예약한 도서라면 예약 불가능. return
        while($row1 = $stmt1 -> fetch(PDO::FETCH_ASSOC)){
            if($ISBN == $row1["ISBN"]) {
                $canReserve = false;
                echo $canReserve;
                return;
            }
        }

        if($count >= 3){
            $canReserve = false;
        }
        else{
            $row = $stmt -> fetch(PDO::FETCH_ASSOC);
            date_default_timezone_set('Asia/Seoul');
            $today = date('Y/m/d');

            //최종적으로 현재 대여한 사람이 있고, 자신이 대여 및 예약한 도서가 아니고, 본인의 총 예약 권수가 3권 미만이라면 예약 가능
            if($row["CNO"] != null && $row["CNO"] != $CNO){
                // echo "dddd";
                $stmt2 = $conn -> query("INSERT INTO RESERVE VALUES('$ISBN', '$CNO', '$today')");
                $stmt2 -> execute();
                $canReserve = true;
            }
        }
    }
    echo $canReserve;


  ?>
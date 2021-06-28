<?php
  function test_input($data) { //validation 함수
    $data = trim($data); //처음과 끝 앞뒤의 공백 제거
    $data = stripslashes($data); //백슬래시 제거
    $data = htmlspecialchars($data); //특수 문자를 HTML 엔티티로 변환
    return $data;
  }
  include '../OracleDB.php';
  $orcl = new OracleDB('127.0.0.1', 'TP201702043', 'password');
  $orcl->connect();

  $method = $_SERVER['REQUEST_METHOD']; //method는 POST로 받아왔으므로 POST
  $data = $_REQUEST['data']; //data 값을 요청하여 얻음
  $arr = [];
  $jsoninfo = [];
  $decodedData = json_decode($data); //얻어온 data값은 json화 되어있으므로 decode

  // SELECT 문(OracleDB), CUSTOMER Table에서 CNO와 PWD가 일치하는지 확인하기 위함
  $query = "SELECT * FROM CUSTOMER";
  $customer_info = $orcl->select($query);

  $login_ok = false; //로그인 가능한지 여부
  foreach($customer_info as $row){ //CNO와 PWD 둘 다 일치하는 값 있는지 확인
      if (strcmp(test_input($row["CNO"]), test_input($decodedData->cno)) == 0 && 
            strcmp(test_input($row["PASSWD"]), test_input($decodedData->pw)) == 0) {
          $login_ok = true;
          //로그인 성공시 session에 CNO, NAME 저장
          session_start();
          $_SESSION["CNO"] = test_input($row["CNO"]);
          $_SESSION["NAME"] = test_input($row["NAME"]);
          // $_SESSION["PASSWD"] = test_input($row["PASSWD"]);
          // $_SESSION["EMAIL"] = test_input($row["EMAIL"]);
          break;
      }
  
  }

  if($login_ok){ //로그인이 가능할 때 id값을 return
    echo($_SESSION["NAME"]);
  }
  else{ //불가능 하다면 false를 return
    echo(false);
  }
?>
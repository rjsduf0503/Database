<?php
  // 대여 가능 날짜가 지나 자동으로 예약 취소 수행
  function test_input($data) { //validation 함수
    $data = trim($data); //처음과 끝 앞뒤의 공백 제거
    $data = stripslashes($data); //백슬래시 제거
    $data = htmlspecialchars($data); //특수 문자를 HTML 엔티티로 변환
    return $data;
  }
  include("../PHPMailer/mailSend.php"); //PHPMailer 사용을 위함
  session_start();
  include '../OracleDB.php';
  $orcl = new OracleDB('127.0.0.1', 'TP201702043', 'password');
  $conn = $orcl->connect();
  $method = $_SERVER['REQUEST_METHOD']; //method는 POST로 받아왔으므로 POST
  $canCancel = false;
  $today = $_POST["today"]; //POST로 받아온 현재 날짜(가정된 시간)

  if($method == 'POST'){
    //현재 예약중인 도서
    $stmt = $conn -> query("SELECT * FROM RESERVE");
    $stmt -> execute();
    while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
      $ISBN = $row["ISBN"];
      //현재 예약중인 도서중 ISBN별로 가장 최근에 반납된 것 
      $res = $conn -> query("SELECT DATERETURNED FROM PREVIOUSRENTAL WHERE ISBN = '$ISBN' AND ROWNUM = 1 ORDER BY DATERETURNED DESC");
      $res -> execute();
      $row1 = $res -> fetch(PDO::FETCH_ASSOC);
      if($row1){ //값이 있고
        //반납일 + 1이 가정된 시간과 같거나 작다면 즉, 가장 최근에 반납된 날짜에서 하루가 지난 경우
        if(strtotime($row1["DATERETURNED"].'+1 days') <= strtotime($today)){ 
          $DATERETURNED = $row1['DATERETURNED'];
          $CNO = $row['CNO'];
          
          
          $canCancel = true;
          
          //메일 보내기 처리
          //삭제 전 행 개수 저장(먼저하는 이유는 count가 1인 상태에서 0이 되면 메일을 보낼 필요가 없으므로)
          $res = $conn->query("SELECT COUNT(*) FROM RESERVE WHERE ISBN = '$ISBN'");
          $count = $res -> fetchColumn();

          //RESERVE에서 지우기
          $stmt1 = $conn -> query("DELETE FROM RESERVE WHERE ISBN = '$ISBN' AND CNO = '$CNO'");
          $stmt1 -> execute();

          if($count > 1){
            //DATETIME(예약날짜)로 정렬 후 첫 행만 받음(예약 1순위)
            $stmt3 = $conn->query("SELECT R.* FROM RESERVE R WHERE R.ISBN = '$ISBN' AND ROWNUM = 1 ORDER BY DATETIME DESC");
            $stmt3 -> execute();
            $row1 = $stmt3 -> fetch(PDO::FETCH_ASSOC);
            $presentISBN = $row1["ISBN"]; //예약 1순위의 ISBN
            $presentCNO = $row1["CNO"]; //예약 1순위의 CNO
            $presentDatetime = $row1["DATETIME"]; //예약 1순위자의 예약일자
            
            //예약 1순위 고객에게 메일을 보내기 위해 이메일 주소를 알아냄
            $stmt4 = $conn->query("SELECT * FROM CUSTOMER WHERE CNO = '$presentCNO'");
            $stmt4 -> execute();
            $row2 = $stmt4 -> fetch(PDO::FETCH_ASSOC);
            
            //메일 전송
            mailer("온라인 도서관","rjsduf0503@naver.com",$row2["EMAIL"],"[예약 도서 대여 안내]","예약하신 도서의 예약자가 대여하지 않아 다음날 자정까지 대여 가능합니다. 감사합니다.");
          }
        }
      }
    }
  }
  echo $canCancel;
  
  
  
  ?>
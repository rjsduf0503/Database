<?php
// 반납 기한이 지나 자동으로 반납을 수행
  function test_input($data) { //validation 함수
    $data = trim($data); //처음과 끝 앞뒤의 공백 제거
    $data = stripslashes($data); //백슬래시 제거
    $data = htmlspecialchars($data); //특수 문자를 HTML 엔티티로 변환
    return $data;
  }
  include("../PHPMailer/mailSend.php");
  session_start();
  include '../OracleDB.php';
  $orcl = new OracleDB('127.0.0.1', 'TP201702043', 'password');
  $conn = $orcl->connect();
  $method = $_SERVER['REQUEST_METHOD']; //method는 POST로 받아왔으므로 POST
  $canReturn = false;
    if($method == 'POST'){
        $today = $_POST["today"]; //POST로 받아온 현재 날짜(가정된 시간)

        //현재 대여중인 도서 중 반납 기한이 지난 도서를 반납하기 위함 
        $stmt = $conn -> query("SELECT E.*, TO_CHAR(E.DATEDUE, 'YYYY/MM/DD') AS DATEDUE FROM EBOOK E");
        $stmt -> execute();
        while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
            //현재 대여중인 도서이고, 반납 기한이 지난 경우
            if(strtotime($row["DATEDUE"]) <= strtotime($today) && $row["DATEDUE"] != null){
                $ISBN = $row['ISBN']; //반납할 도서의 ISBN
                $CNO = $row['CNO']; //반납할 도서의 CNO
                $DATERENTED = $row['DATERENTED']; //반납할 도서의 대여일

                //반납 가능하다면 PREVIOUSRENTAL에 INSERT(이전 대여 기록에 추가)
                $stmt1 = $conn->query("INSERT INTO PREVIOUSRENTAL VALUES('$ISBN', '$DATERENTED', '$today', '$CNO')");
                
                //그 후 반납 처리
                $stmt2 = $conn->query("UPDATE EBOOK SET CNO = null, EXTTIMES = 0, DATERENTED = null, DATEDUE = null WHERE ISBN = '$ISBN'");
                $stmt2 -> execute();  
                $canReturn = true;

                //예약 1순위의 고객에게 메일 전송
                //메일 보낼 고객이 있는지 체크(예약자가 있는지)
                $res = $conn->query("SELECT COUNT(*) FROM RESERVE WHERE ISBN = '$ISBN'");
                $count = $res -> fetchColumn();
                if($count > 0){
                  //DATETIME(예약날짜)로 정렬 후 첫 행만 받음(예약 1순위)
                  $stmt3 = $conn->query("SELECT R.* FROM RESERVE R WHERE R.ISBN = '$ISBN' ORDER BY DATETIME");
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
                  mailer("온라인 도서관","rjsduf0503@naver.com",$row2["EMAIL"],"[예약 도서 대여 안내]","예약하신 도서의 대출 기한이 만료되어 다음날 자정까지 대여 가능합니다. 감사합니다.");
                }
            }
        }

            
        // }
    }
    echo $canReturn;



  ?>
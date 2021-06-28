var today = new Date("2021-05-15");
var year = `${today.getFullYear()}`;
var month = today.getMonth() + 1;
var day = today.getDate();

month = month < 10 ? `0${month}` : `${month}`;
day = day < 10 ? `0${day}` : `${day}`;
today= `${year}/${month}/${day}`;

// autoReturn();
 
//지정한 시간에 자동 반납 및 예약 취소
function autoReturn(){
  // 반납기한 지났을 때 자동 반납 처리 후 다음 우선순위자에게 통보
  $.ajax({ //ajax로 서버와 통신
    type: "POST", //POST 방식으로 통신
    data: {today : today},
    url: "../myPage/autoReturnBook.php", //autoReturnBook.php와 통신
    success: function(val) { //통신 성공
      if(val){
          alert("자정이 지나 반납 기한이 만료된 도서가 자동 반납되었습니다.");
          location.replace("../myPage/myPage1.php");
      }
    },
    error: function(jqXHR, textStatus, errorThrown) { //통신 실패
      alert("error");
      console.log(jqXHR.responseText);
    }
  });
  //예약을 안하고 하루가 지나 예약이 취소되어 다음 우선순위자에게 통보
  $.ajax({ //ajax로 서버와 통신
    type: "POST", //POST 방식으로 통신
    data: {today : today},
    url: "../myPage/autoCancelBook.php", //autoCancelBook.php와 통신
    success: function(val) { //통신 성공
      if(val){
          alert("자정이 지나 예약하지 않은 도서가 취소되었습니다.");
          location.replace("../myPage/myPage1.php");
      }
    },
    error: function(jqXHR, textStatus, errorThrown) { //통신 실패
      alert("error");
      console.log(jqXHR.responseText);
    }
  });
}

//도서 반납
document.querySelectorAll(".returnBook_btn").forEach(btn => {
    btn.addEventListener("click", event => {
        const ISBN = event.target.value; //클릭한 버튼 행의 도서 번호(ISBN)
        var jsonData = JSON.stringify({ "ISBN" : ISBN });
        $.ajax({ //ajax로 서버와 통신
          type: "POST", //POST 방식으로 통신
          url: "returnBook.php", //returnBook.php와 통신
          data: {data : jsonData}, //data는 json화 한 jsonData를 보냄
          success: function(val) { //통신 성공
            if(val){
                alert("도서 반납 완료");
            }
            else{
                alert("도서 반납 불가능");
            }
            location.replace("../myPage/myPage1.php");
          },
          error: function(jqXHR, textStatus, errorThrown) { //통신 실패
            alert("error");
            console.log(jqXHR.responseText);
          }
        });
    });
});

//도서 연장
document.querySelectorAll(".extensionBook_btn").forEach(btn => {
    btn.addEventListener("click", event => {
        const ISBN = event.target.value; //클릭한 버튼 행의 도서 번호(ISBN)
        var jsonData = JSON.stringify({ "ISBN" : ISBN });
        $.ajax({ //ajax로 서버와 통신
          type: "POST", //POST 방식으로 통신
          url: "extensionBook.php", //extensionBook.php와 통신
          data: {data : jsonData}, //data는 json화 한 jsonData를 보냄
          success: function(val) { //통신 성공
            if(val){
                alert("도서 연장 완료");
            }
            else{
                alert("도서 연장 불가능");
            }
            location.replace("../myPage/myPage1.php");
          },
          error: function(jqXHR, textStatus, errorThrown) { //통신 실패
            alert("error");
            console.log(jqXHR.responseText);
          }
        });
    });
});

//도서 예약 취소
document.querySelectorAll(".cancelBook_btn").forEach(btn => {
    btn.addEventListener("click", event => {
        const ISBN = event.target.value; //클릭한 버튼 행의 도서 번호(ISBN)
        var jsonData = JSON.stringify({ "ISBN" : ISBN });
        $.ajax({ //ajax로 서버와 통신
          type: "POST", //POST 방식으로 통신
          url: "cancelBook.php", //cancelBook.php와 통신
          data: {data : jsonData}, //data는 json화 한 jsonData를 보냄
          success: function(val) { //통신 성공
            if(val){
                alert("도서 예약 취소 완료");
            }
            else{
                alert("도서 예약 취소 불가능");
            }
            location.replace("../myPage/myPage2.php");
          },
          error: function(jqXHR, textStatus, errorThrown) { //통신 실패
            alert("error");
            console.log(jqXHR.responseText);
          }
        });
    });
});
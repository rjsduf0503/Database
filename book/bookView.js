//도서 예약
$(document).ready(function() {
    $("#reservation_btn").on("click", function() { 
      var valid = this.form.checkValidity(); //form의 vaildition을 수행
      if(valid){ //validation이 유효하다면, 사용자가 입력한 값들을 가져와 json화
        var ISBN = $("#present_isbn1").val();
        var jsonData = JSON.stringify({ "ISBN" : ISBN });
        $.ajax({ //그 후 ajax로 서버와 통신
          type: "POST", //POST 방식으로 통신
          url: "../book/reserveBook.php", //reserveBook.php와 통신
          data: {data : jsonData}, //data는 json화 한 jsonData를 보냄
          success: function(val) { //통신 성공
            if(val){
                alert("도서 예약 완료");
            }
            else{
                alert("도서 예약 불가능");
            }
          },
          error: function(jqXHR, textStatus, errorThrown) { //통신 실패
            alert("error");
            console.log(jqXHR.responseText);
          }
        });
      }
    });
  });

  //도서 대여
  $(document).ready(function() { 
    $("#rental_btn").on("click", function() { 
        var valid = this.form.checkValidity(); //form의 vaildition을 수행
        if(valid){ //validation이 유효하다면, 사용자가 입력한 값들을 가져와 json화
        var ISBN = $('#present_isbn1').val();
        var jsonData = JSON.stringify({ "ISBN" : ISBN });
        $.ajax({ //그 후 ajax로 서버와 통신
          type: "POST", //POST 방식으로 통신
          url: "../book/rentBook.php", //rentBook.php와 통신
          data: {data : jsonData}, //data는 json화 한 jsonData를 보냄
          success: function(val) { //통신 성공
              if(val){
                  alert("도서 대여 완료");
              }
              else{
                  alert("도서 대여 불가능");
              }
          },
          error: function(jqXHR, textStatus, errorThrown) { //통신 실패
            alert("error");
            console.log(jqXHR.responseText);
          }
        });
      }
    });
  });

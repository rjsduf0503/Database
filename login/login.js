$(document).ready(function() { //로그인
    $("#login_btn").on("click", function() { 
      var valid = this.form.checkValidity(); //form의 vaildition을 수행
      if(valid){ //validation이 유효하다면, 사용자가 입력한 값들을 가져와 json화
        var cno = $("#cno").val();
        var pw = $("#pw").val();
        var jsonData = JSON.stringify({ "cno": cno, "pw": pw });
        $.ajax({ //그 후 ajax로 서버와 통신
          type: "POST", //POST 방식으로 통신
          url: "../login/confirmLogin.php", //login2.php와 통신
          data: {data : jsonData}, //data는 json화 한 jsonData를 보냄
          success: function(testedName) { //통신 성공
            if(testedName == false){ //아이디 혹은 비밀번호가 틀렸을 경우
              alert("아이디 혹은 비밀번호가 틀렸습니다.");
            }
            else{ //아이디와 비밀번호가 일치할 경우
              alert(testedName + "님 환영합니다.");
              goto_main(); //main page로 이동
            }
          },
          error: function(jqXHR, textStatus, errorThrown) { //통신 실패
            alert("error");
            console.log(jqXHR.responseText);
          }
        });
      }
      else{
        alert("아이디와 비밀번호를 올바르게 입력했는지 확인하세요."); //통신 실패
      }
    });
  });



function goto_main() { //main으로 돌아감
    location.replace("../main/main.php");
}
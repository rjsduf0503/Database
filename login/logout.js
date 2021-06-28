//로그아웃
$(document).ready(function() { 
    $("#logoutLink").on("click", function() {
        $.ajax({ //ajax로 서버와 통신
          type: "POST", //POST 방식으로 통신
          url: "../login/logout.php", //logout.php와 통신
          success: function() { //통신 성공
            alert("로그아웃 되었습니다."); 
            goto_login(); //login page로 이동
          },
          error: function(jqXHR, textStatus, errorThrown) { //통신 실패
            alert("error");
            console.log(jqXHR.responseText);
          }
        });
    });
});

function goto_login() { //login page로 돌아감
    location.replace("../login/login.php");
}
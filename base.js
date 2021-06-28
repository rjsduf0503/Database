//aside의 My Page 눌렀을 때 대출 도서 조회/예약 도서 조회가 보였다 사라졌다 하게끔
$(document).ready(function(){
    $('.more').click(function(){
      if($('.more').hasClass('more')){
         $('.more').addClass('close').removeClass('more');
         $('.board').css('visibility', 'visible');
      }
      else if($('.close').hasClass('close')){
         $('.close').addClass('more').removeClass('close');  
         $('.board').css('visibility', 'hidden');
      }
    });
  });
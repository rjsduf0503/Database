<?php
    session_start(); //세션을 시작하고
    if(isset($_SESSION["CNO"])){ //세션에 CNO값이 존재한다면,
        session_unset(); //세션의 값을 지우고
        echo(session_destroy()); //세션을 삭제한다.
    }
?>
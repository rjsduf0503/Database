<!-- 상단의 header, home link와 Logout 기능이 구현되어 있음 -->
<?php 
    session_start(); 
    include 'OracleDB.php';
    $orcl = new OracleDB('127.0.0.1', 'TP201702043', 'password');
    $conn = $orcl->connect();
?>

<!DOCTYPE html>
<html lang="ko">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online-Library</title>
    <link rel="stylesheet" href="../base.css?after"> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="head">
        <header>
            <div class="wrap">
                <div id="home_logo">
                    <a id="home_link" href="../main/main.php">Library</a>
                    <small>Online-Library</small>
                </div>
                <div class="logout_div">
                        <ul>
                            <!-- SESSION 값이 있을 때만(로그인한 경우에만) LOGOUT link를 표시 -->
                            <?php if(isset($_SESSION["CNO"])) : ?>
                                <li><a class="show logout_link" href="../login/login.php" name="logoutLink" id="logoutLink">LOGOUT</a></li>
                                <li><?php echo($_SESSION["NAME"]) ?>님 환영합니다.</li>
                            <?php else : ?>
                                 <li><a class="hide logout_link" href="../login/login.php" name="logoutLink" id="logoutLink">LOGOUT</a></li>
                            <?php endif; ?>
                        </ul>
                </div>
            </div>
        </header>
    </div>




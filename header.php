<?php 
    session_start(); 
    include 'OracleDB.php';
    $orcl = new OracleDB('127.0.0.1', 'TP201702043', 'Chess00700');
    $orcl->connect();
?>

<!DOCTYPE html>
<html lang="ko">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

        
    <title>Library</title>
    <link rel="stylesheet" href="../base.css"> 
    <!-- <link rel="stylesheet" href="../all.css"> -->
    <!-- <link rel="stylesheet" href="../main/main.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="head">
        <header>
            <div class="wrap">
                <div id="home_logo">
                    <a id="home_link" href="../main/main.php">Library</a>
                    <small>Online Library</small>
                </div>
                <div class="logout_div">
                        <ul>
                            <!-- <li><a class="show logout_link" href="../main/main.php" name="logoutLink" id="logoutLink">LOGOUT</a></li>
                            <li>님 환영합니다.</li> -->
                            <?php if(isset($_SESSION["CNO"])) : ?>
                                <!-- <li><a class="hide" href="../login/login.php" name="loginLink" id="loginLink">LOGIN</a></li> -->
                                <li><a class="show logout_link" href="../login/login.php" name="logoutLink" id="logoutLink">LOGOUT</a></li>
                                <li><?php echo($_SESSION["NAME"]) ?>님 환영합니다.</li>
                            <?php else :
                                    // header("location:../login/login.php");
                                 ?>
                                <!-- <li><a class="show" href="../login/login.php" name="loginLink" id="loginLink">LOGIN</a></li> -->
                                <!-- <li><a class="hide" href="../main/main.php" name="logoutLink" id="logoutLink">LOGOUT</a></li> -->
                            <?php endif; ?>
                        </ul>
                </div>
            </div>
        </header>
    </div>




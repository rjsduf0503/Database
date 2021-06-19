<link rel="stylesheet" href="login.css">
<?php include("../header.php") ?> 
    <section>
    <div class="wrap">
        <div class="info_div center">
            <form method="post">
                CNO : <input type="text" name="cno" id="cno" required size="17"><br>
                PASSWORD : <input type="password" name="pw" id="pw" required size="10"><br>
                <br><button type="reset">Reset</button>
                <button type="button" name="login_btn" id="login_btn">Login</button>
            </form>
        </div>  
    </div>
    </section>
<?php include("../footer.php") ?>
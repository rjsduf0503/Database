<?php include("../header.php") ?> 
<?php 
    if(!isset($_SESSION["CNO"])){ //세션값을 확인하여 로그인 했을 경우에만 이용 가능하도록함
        echo '<script>alert("로그인 후 이용 가능합니다.");</script>';
        header("Refresh:0; url=../login/login.php");
    }
?> 
<div class="section_div">
<section class="wrap">
        <aside id="menu" class="left">
            <section class="wrap">
                <h3>Category</h3>
                    <ul>
                        <li><a class="category_link" href="../book/search.php" >Search</a></li>
                        <li><a class="category_link" href="../myPage/myPage.php" >My Page</a></li>
                    </ul>
            </section>
        </aside>
        <article>
            my_page
        </article>
</section>
</div>

<!-- main 화면. 각각의 section php들은 header와 footer를 include한다. -->


<!-- <script src="../main/main.js"></script> -->
<?php include("../footer.php") ?>
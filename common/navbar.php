<nav class="navbar">
    <?php if(isset($_SESSION["user"])){
        include_once("navbars/login.php");
    } else {
        include_once("navbars/logout.php");
    }?>
</nav>

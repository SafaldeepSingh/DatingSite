<?php
session_start();
if (isset($_GET['name'])) {
    unset($_SESSION['userId']);
}
if (isset($_SESSION['userId'])) {
    //var_dump($_SESSION['userId']);
    header("Location: Login-SignUp/profile.php");
}

?>


<html>
<head>
    <title>Ignite| Dating - Meet New People & Find Your Love</title>
    <link rel="shortcut icon" type="image/jpg" href="assets/images/icon.svg"/>
    <link rel="stylesheet" href="assets/css/bootstrap-4.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/home-page.css">

</head>
<body>
<section class="section-intro">
    <nav class="navbar navbar-expand-md">
        <a class="navbar-brand" href="#">
            <img src="assets/images/icon-white.svg" alt="Ignite Logo">
            Ignite</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
            </ul>
            <a href="./Login-SignUp/login.php">
                <button class="btn btn__login btn__login--1 my-2 my-sm-0" type="submit">Log in</button>
            </a>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row section-intro__bgImg">
            <div class="col-12 text-center section-intro__content">
                <h3 class="section-intro__heading">Find Your Love</h3>
                <a href="Login-SignUp/Register.php">
                    <button class="btn btn__signUp" type="button">CREATE ACCOUNT</button>
                </a>
                <a href="Login-SignUp/Profile.php">
                    <button class="btn btn__signUp" type="button">VIEW PROFILES</button>
                </a>
            </div>
        </div>
    </div>
</section>
</body>
</html>

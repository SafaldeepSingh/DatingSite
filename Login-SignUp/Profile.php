<?php
require_once "../shared/user.php";
require_once "../shared/constants.php";
session_start();
$userDB = new userDB();

if (isset($_SESSION['userId'])) {

    if (is_array($userDB->getAllUsers($_SESSION['userId']))) {
        $allUsersData = $userDB->getAllUsers($_SESSION['userId']);
        $userData = $userDB->getUserInfo($_SESSION['userId']);
    }
} else {
    if (is_array($userDB->getAllUsers(-1))) {
        $allUsersData = $userDB->getAllUsers(-1);
    }
}

?>


<html>

<head>
    <title>Home</title>
<!--    <link rel="shortcut icon" type="image/jpg" href="../assets/images/icon.svg"/>-->
<!--    <link rel="stylesheet" href="../assets/css/bootstrap-4.css">-->
<!--    <link rel="stylesheet" href="../assets/css/styles.css">-->
<!--    <link rel="stylesheet" href="../assets/css/header.css">-->
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"-->
<!--          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"-->
<!--          crossorigin="anonymous">-->

    <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">-->


<!--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
    <link rel="stylesheet" href="../assets/css/Test.css">
    <link rel="shortcut icon" type="image/jpg" href="../assets/images/icon.svg"/>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-4.css">



    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<div class="header">
    <a class="navbar-brand" href="../index.php">
        <img src="../assets/images/icon.svg" alt="Ignite Logo">
        Ignite</a>
    <div class="header-right">

        <?php if (!isset($_SESSION['userId'])) { ?>
            <a href="../Home-Pages/search.php">
                <button class="btn btn__signUp" type="submit">Search</button>
            </a>
            <a href="../Login-SignUp/login.php">
                <button class="btn btn__signUp" type="submit">Log in</button>
            </a>
            <a href="../Login-SignUp/Register.php">
                <button class="btn btn__signUp" type="submit">Register</button>
            </a>
        <?php } else { ?>
            <a href="../Home-Pages/search.php">
                <button class="btn btn__signUp" type="submit">Search</button>
            </a>
            <a href="../messages/index.php">
                <button class="btn btn__signUp" type="submit">Notification and Messages</button>
            </a>
            <a href="../Home-Pages/edit-profile.php">
                <button class="btn btn__signUp" type="submit">Edit Profile</button>
            </a>
            <a href="../index.php?name=logout">
                <button class="btn btn__signUp" type="submit">Logout</button>
            </a>
            <img class="img-fluid img-round" style="width: 50px; height: 50px"
                 src="<?= BASE_URL . $userData[0]['path'] ?>" alt="">

        <?php } ?>

    </div>

</div>
<div class="container">
    <div class="row">
        <?php for ($i = 0; $i < count($allUsersData); $i++) { ?>
            <div class="col-md-4">
                <hr>
                <div class="profile-card-4 text-center">
                    <!--                <img src="http://envato.jayasankarkr.in/code/profile/assets/img/profile-4.jpg" class="img img-responsive">-->
                    <img src=<?= BASE_URL . $allUsersData[$i]['path'] ?> class="img img-responsive">
                    <div class="profile-content">
                        <div class="profile-name"><a
                                    href="../Home-Pages/usersProfilePage.php?id=<?= $allUsersData[$i]['ID']?>" style="color: crimson" > <?= $allUsersData[$i]['first_name'] . " " . $allUsersData[$i]['last_name'] ?></a>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="profile-overview">
                                    <p>City</p>
                                    <h6><?= $allUsersData[$i]['living_in'] ?></h6></div>
                            </div>
                            <div class="col-xs-4">
                                <div class="profile-overview">
                                    <p>Profession</p>
                                    <h6><?= $allUsersData[$i]['profession'] ?></h6></div>
                            </div>
                            <div class="col-xs-4">
                                <div class="profile-overview">
                                    <p>Age</p>
                                    <h6><?= $allUsersData[$i]['age'] ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>


</body>
</html>



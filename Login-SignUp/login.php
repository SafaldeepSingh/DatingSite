<?php

require_once "../shared/user.php";
session_start();
if (isset($_SESSION['userId'])) {
    //var_dump($_SESSION['userId']);
    header("Location: profile.php");
}

$wrongInput = false;
$userNotFound = false;
function validateUser($userName, $password)
{
    global $userNotFound;
    $userDB = new UserDB();
    if (is_array(($userDB->authenticate($userName, $password)))) {
        $userId = ($userDB->authenticate($userName, $password));

        $_SESSION['userId'] = (int)$userId[0]['ID'];
//        header("location: ../Home-Pages/usersProfilePage.php?name=Success");
        header("location: Profile.php");
    } else {
        $userNotFound = true;
    }
}

if (isset($_POST['submit'])) {
    if (!isset($_POST['Email']) || !isset($_POST['Password']) || ($_POST['Email'] == "") || ($_POST['Password'] == "")) {
        $wrongInput = true;
    } else {
        $userName = htmlspecialchars($_POST['Email']);
        $password = htmlspecialchars($_POST['Password']);
        validateUser($userName, $password);
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="shortcut icon" type="image/jpg" href="../assets/images/icon.svg"/>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-4.css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .card {
            margin: 0 auto; /* Added */
            float: none; /* Added */
            margin-bottom: 10px; /* Added */
        }

    </style>
</head>
<body>

<div class="header">
    <a class="navbar-brand" href="../index.php">
        <img src="../assets/images/icon.svg" alt="Ignite Logo">
        Ignite</a>
    <div class="header-right">
        <a href="Register.php">
            <button class="btn btn__signUp" type="submit">Register Here</button>
        </a>
        <!--        <a class="active" href="login.php">Register</a>-->
        <!--        <a href="#contact">Contact</a>-->
        <!--        <a href="#about">About</a>-->
    </div>
</div>
<br/><br/><br/>
<div class="card" style="width: 40rem;">
    <div class="card-header">
        Enter Credentials
    </div>
    <div class="card-body">
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="form-group">
                <label for="Email">Email address</label>
                <input type="email" class="form-control" id="Email" name="Email" aria-describedby="emailHelp"
                       placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                    else.</small>
            </div>
            <div class="form-group">
                <label for="Password">Password</label>
                <input type="password" class="form-control" id="Password" name="Password" placeholder="Password">
            </div>
            <?php if ($wrongInput == true) { ?>
                <div class="alert alert-danger" role="alert">
                    Please enter valid data
                </div>
            <?php } ?>
            <?php if ($userNotFound == true) { ?>
                <div class="alert alert-danger" role="alert">
                    Invalid Email or Password!!
                </div>
            <?php } ?>
            <button type="submit" class="btn btn__signUp" name="submit">Submit</button>
        </form>
    </div>

</div>
</body>
</html>
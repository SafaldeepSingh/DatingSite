<?php
require_once "../shared/user.php";
require_once "../shared/constants.php";
require_once "../Model/Wink.php";

session_start();
$userDB = new userDB();
$winkSent = false;
$ableToSendWink = false;
$ableToAddFavourite = false;
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    if (is_array($userDB->getUserInfo($id))) {
        $selectedUserData = $userDB->getUserInfo($id);
    }

    if (isset($_SESSION['userId'])) {
        if (is_array($userDB->getUserInfo($_SESSION['userId']))) {
            $userData = $userDB->getUserInfo($_SESSION['userId']);
            if (isset($_GET['action'])) {
                if ($_GET['action'] == 'wink') {
                    Wink::sendWink($_SESSION['userId'], $id);
                    $winkSent = true;
                }
                if ($_GET['action'] == 'removeFavorite') {
                    $userDB->removeFavourite(($_SESSION['userId']), $id);
                    $ableToAddFavourite = true;
                }
                if ($_GET['action'] == 'addFavorite') {
                    $userDB->addFavourite(($_SESSION['userId']), $id);
                    $ableToAddFavourite = false;
                }
            }
            $sendWinkCheck = $userDB->getLastWink(($_SESSION['userId']), $id);
            if (is_array($sendWinkCheck) && $sendWinkCheck != null) {
                if ($sendWinkCheck[0]['seen_at'] != null) {
                    $ableToSendWink = true;
                }
            } else {
                $ableToSendWink = true;
            }
            $addFavouriteCheck = $userDB->checkFavourite(($_SESSION['userId']), $id);
            if ($addFavouriteCheck['count'] == 0) {
                $ableToAddFavourite = true;
            }
        }
    }
} else {
    header("location: ../index.php");
    exit();
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="../assets/css/usersProfile.css">
    <link rel="shortcut icon" type="image/jpg" href="../assets/images/icon.svg"/>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="../assets/css/bootstrap-4.css">
</head>
<body>

<div class="header">
    <a class="navbar-brand" href="../Login-SignUp/Profile.php">
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
            <a href="edit-profile.php">
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

<div class="wrapper">
    <div class="profile-card js-profile-card">
        <div class="profile-card__img">
            <img src="<?= BASE_URL . $selectedUserData[0]['path'] ?>"
                 alt="profile card">
        </div>

        <div class="profile-card__cnt js-profile-cnt">
            <div class="profile-card__name"><?= $selectedUserData[0]['first_name'] . " " . $selectedUserData[0]['last_name'] ?></div>
            <div class="profile-card__txt"><?= $selectedUserData[0]['about'] ?> </div>
            <div class="profile-card-loc">
                <span class="profile-card-loc__txt">
                    <?= $selectedUserData[0]['living_in'] . ", Canada" ?> </span>
            </div>
            <div class="profile-card-inf">
                <div class="profile-card-inf__item">
                    <div class="profile-card-inf__txt">Age</div>
                    <div class="profile-card-inf__title"><?= $selectedUserData[0]['age'] ?></div>

                </div>

                <div class="profile-card-inf__item">
                    <div class="profile-card-inf__txt">Gender</div>
                    <div class="profile-card-inf__title">
                        <?php if ($selectedUserData[0]['gender'] == 'M') {
                            echo "Male";
                        } else {
                            echo "Female";
                        }
                        ?>
                    </div>


                </div>

                <div class="profile-card-inf__item">
                    <div class="profile-card-inf__txt">Interested in</div>
                    <div class="profile-card-inf__title">
                        <?php if ($selectedUserData[0]['interested_in'] == 0) {
                            echo "Men";
                        } elseif ($selectedUserData[0]['interested_in'] == 1) {
                            echo "Women";
                        } else {
                            echo "Both";
                        }
                        ?>
                    </div>

                </div>

                <div class="profile-card-inf__item">
                    <div class="profile-card-inf__txt">Profession</div>
                    <div class="profile-card-inf__title"><?= $selectedUserData[0]['profession'] ?></div>

                </div>
            </div>

            <?php if (isset($_SESSION['userId'])) { ?>
                <!--                <div class="profile-card-ctr">-->
                <a href="../messages/index.php?chat=<?= $selectedUserData[0]['ID'] ?>#lastMessage" style="padding:50px">
                    <button class="profile-card__button button--orange js-message-btn">Message</button>
                </a>
                <?php if ($ableToSendWink) { ?>
                    <a href="./usersProfilePage.php?id=<?= $selectedUserData[0]['ID'] ?>&action=wink"
                       style="padding:50px">
                        <button class="profile-card__button button--orange">Send Wink <i class='far fa-grin-wink'></i>
                        </button>
                    </a>
                <?php } else { ?>
                    <a href="./usersProfilePage.php?id=<?= $selectedUserData[0]['ID'] ?>" style="padding:50px">
                        <button class="profile-card__button button--orange">Winked <i class='far fa-grin-wink'></i>
                        </button>
                    </a>
                <?php } ?>

            <?php } ?>
            <?php
            if (isset($_SESSION['userId'])) {
                if ($userData[0]['is_premium'] == 1) { ?>
                    <?php if ($ableToAddFavourite) { ?>
                        <div class="profile-card-ctr">
                            <a href="./usersProfilePage.php?id=<?= $selectedUserData[0]['ID'] ?>&action=addFavorite">
                                <button class="profile-card__button button--orange">Add to Favourites</button>
                            </a>
                        </div>
                    <?php } else { ?>
                        <div class="profile-card-ctr">
                            <a href="./usersProfilePage.php?id=<?= $selectedUserData[0]['ID'] ?>&action=removeFavorite">
                                <button class="profile-card__button button--orange">Remove from Favourites</button>
                            </a>
                        </div>
                    <?php } ?>

                <?php }
            } ?>

        </div>


    </div>

</div>


</body>
</html>

<?php
require_once "../shared/constants.php";
require_once "../Controller/Users.php";
require_once "../Controller/Winks.php";
require_once "../Controller/Messages.php";
require_once "../Controller/Favourites.php";
require_once "../Controller/Notifications.php";
session_start();
if (!isset($_SESSION["userId"])) {
    header("Location: ../");
    exit();
}
$userId = $_SESSION["userId"];


//get user data
$userData = Users::getUserData($userId);
$winks = Winks::getWinks($userId);
$notifications = Notifications::getNotifications($userId, $userData);

//send wink
if (isset($_GET["wink"])) {
    Winks::sendWink($userId, $_GET["wink"]);
}


//remove from favourites
if (isset($_GET["removeFromFavourite"])) {
    Favourites::removeFromFavourites($userId, $_GET["removeFromFavourite"]);
}
$favourites = Favourites::getFavourites($userId);


$chatId = null;
//when user opens chat
if (isset($_GET["chat"])) {
    $chatId = $_GET["chat"];
    if (isset($_GET["message"])) {
        if (!Messages::sendMessage($userId, $chatId, $_GET["message"])) {
            //TODO DISPLAY ALERT ERROR

        }
    }
    $otherUserData = Users::getOtherUserData($userId, $chatId);
    //handle Chat with invalid user
    if (!isset($otherUserData["ID"])) {
        $chatId = null;
    } //handle Chat with new user
    else if ($otherUserData["firstMeet"] == null) {
        $conversation = array();
    } else {
        //fetch chat conversation & other user data
        $conversation = Messages::getConversation($userId, $chatId);
        //update seen messages
        Messages::updateSeenMessages($_GET["chat"]);
    }
}
//get user recent chats
$recentChats = Messages::getRecentChats($userId);


?>
<!doctype html>
<html lang="en">
<head>
    <title>Messages</title>
    <link rel="shortcut icon" type="image/jpg" href="../assets/images/icon.svg"/>
    <link rel="stylesheet" href="../assets/css/fontAwesome.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-4.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/messages.css">
</head>
<body>
<div class="container-fluid messages-container">
    <div class="row">
        <div class="col-5 col-xl-4">
            <div class="row toolbar">
                <div class="col-2 square-img">
                    <a class="toolbar__heading" href="../Home-Pages/edit-profile.php">
                        <img class="img-fluid img-round" src="<?=BASE_URL.$userData["profileImage"]?>" alt="">
                    </a>
                </div>
                <div class="col-6 toolbar__text my-auto">

                   <a class="toolbar__heading" href="../Home-Pages/edit-profile.php">My Profile</a>
                </div>
                <div class="col-4 my-auto text-right pr-4 toolbar__actions">
                    <?php if ($userData["is_premium"]) { ?>
                        <a class="c-pointer" data-toggle="modal" data-target="#favouriteListModal">
                            <i class="fas fa-heart"></i>
                        </a>
                        <div class="dropdown show d-inline-block  mx-3">
                            <a class="c-pointer position-relative" role="button" id="dropdownMenuLink"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php
                                if (count($notifications)) {
                                    ?>
                                    <span class="badge badge-danger notification-count"><?= count($notifications) ?></span>
                                <?php } ?>
                                <i class="fas fa-bell"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <div class="container-fluid">
                                    <?php
                                    foreach ($notifications as $notification) {
                                        ?>
                                        <div class="row wink">
                                            <div class="col-5 square-img">
                                                <a class="c-pointer"
                                                   href="../Home-Pages/usersProfilePage.php?id=<?=$notification["ID"]?>">
                                                <img class="img-fluid img-round "
                                                     src="<?= BASE_URL . $notification["profileImage"] ?>"
                                                     alt="<?= $notification["first_name"] . " " . $notification["last_name"] ?>">
                                                </a>
                                            </div>
                                            <div class="col-7 pl-1">
                                                <div class="wink__name">
                                                    <?= $notification["first_name"] ?>
                                                    <p><?= $notification["detailMessage"] ?></p>
                                                </div>
                                                <div class="wink__time">
                                                    <i class="fas fa-clock"></i>

                                                    <?php
                                                    if ($notification["type"] == "wink")
                                                        echo date("G:i", strtotime($notification["seen_at"]));
                                                    else
                                                        echo date("G:i", strtotime($notification["added_at"]));
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!--                                <a class="dropdown-item" >Action 1</a>-->
                                    <?php }
                                    if (!count($notifications)) { ?>
                                        <div class="row no-notification">
                                            <div class="col-12">
                                                No Notifications to Show
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="dropdown show d-inline-block">
                        <a class="c-pointer position-relative" role="button" id="dropdownMenuLink"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php
                            if (count($winks)) {
                                ?>
                                <span class="badge badge-danger notification-count"><?= count($winks) ?></span>
                            <?php } ?>
                            <i class="fas fa-smile-wink"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <div class="container-fluid">
                                <?php
                                if (!count($winks)) { ?>
                                    <div class="row no-wink">
                                        <div class="col-12">
                                            No Winks to Show
                                        </div>
                                    </div>
                                <?php }
                                foreach ($winks as $wink) {
                                    ?>
                                    <div class="row wink">
                                        <div class="col-5 square-img">
                                            <a class="c-pointer"
                                               href="../Home-Pages/usersProfilePage.php?id=<?=$wink["ID"]?>">
                                            <img class="img-fluid img-round"
                                                 src="<?= BASE_URL . $wink["profileImage"] ?>"
                                                 alt="<?= $wink["first_name"] . " " . $wink["last_name"] ?>">
                                            </a>
                                        </div>
                                        <div class="col-7 pl-1">
                                            <div class="wink__name">
                                                <?= $wink["first_name"] ?>
                                                <p>sent you a wink</p>
                                            </div>
                                            <div class="wink__time">
                                                <i class="fas fa-clock"></i>
                                                <?= date("G:i", strtotime($wink["sent_at"])) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row messages-panel">
                <div class="col-12">
                    <h6 class="messages-heading">Messages</h6>
                    <?php
                    if (!count($recentChats)) {
                        ?>
                        <div class="mt-3 text-center">No Messages to Show</div>
                    <?php }
                    foreach ($recentChats as $recentChat) {
                        ?>
                        <a class="my-link" href="?chat=<?= $recentChat["ID"] ?>#lastMessage">
                            <div class="row chat <?= Messages::isActiveChat($recentChat["ID"]) ? "active" : "" ?>">
                                <div class="col-3 square-img">
                                    <img class="img-fluid img-round" src="<?= BASE_URL . $recentChat["profileImage"] ?>"
                                         alt="">
                                    <?=
                                    Messages::hasUnreadMessage($recentChat["lastMessage"], $userId)
                                        ? '<span class="chat__unread"></span>'
                                        : ""
                                    ?>
                                </div>
                                <div class="col-9 pl-0 my-auto">
                                    <div class="chat__name mb-1"><?= $recentChat["first_name"] ?></div>
                                    <div class="chat__lastText"><?= $recentChat["lastMessage"]["message"] ?></div>
                                </div>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php if ($chatId) { ?>
            <div class="col-7 col-xl-8 conversation">
                <div class="row conversation__header">
                    <div class="col-1 square-img">
                        <img class="img-fluid img-round" src="<?= BASE_URL . $otherUserData["profileImage"] ?>" alt="">
                    </div>
                    <div class="col-9 my-auto pl-0">
                        <?php if (count($conversation)) { ?>
                            You first met <?= $otherUserData["first_name"] ?>
                            on <?= date("j/n/Y", strtotime($otherUserData["firstMeet"])) ?>
                        <?php } else { ?>
                            Say Hi! to <?= $otherUserData["first_name"] ?>
                        <?php } ?>
                    </div>
                    <div class="col-2 text-right pr-4 my-auto">
                        <a href="<?= BASE_URL . "messages" ?>">
                            <i class="far fa-times-circle close-chat"></i>
                        </a>
                    </div>
                </div>
                <div class="row conversation__panel">
                    <?php
                    $conversationDay = null;
                    foreach ($conversation as $index => $message) {
                        if ($conversationDay != Messages::getConversationDay($message["sent_at"])) {
                            $conversationDay = Messages::getConversationDay($message["sent_at"]);

                            ?>
                            <div class="col-12 conversation-day text-center">
                                <span><?= $conversationDay ?></span>
                            </div>
                        <?php } ?>

                        <?php if ($message["sender_id"] == $userId) { ?>
                            <div class="col-12 message--sent"
                                <?= $index == count($conversation) - 1 ? ' id="lastMessage"' : '' ?>
                            >
                                <span>
                                    <?= $message["message"] ?>
                                    <div class="message-details">
                                        <?= date("G:i", strtotime($message["sent_at"])) ?>
                                        <?= $message["seen_at"] != null && $userData["is_premium"]
                                            ? '<i class="fas fa-check-double"></i>'
                                            : ''
                                        ?>

                                    </div>
                                </span>
                            </div>
                        <?php } else { ?>
                            <div class="col-12 message--received"
                                <?= $index == count($conversation) - 1 ? ' id="lastMessage"' : '' ?>
                            >
                                <span>
                                    <?= $message["message"] ?>
                                    <div class="message-details text-right">
                                        <?= date("G:i", strtotime($message["sent_at"])) ?>
                                    </div>
                                </span>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
                <form action="<?= $_SERVER["PHP_SELF"] ?>#lastMessage">
                    <div class="row send-message__panel">
                        <input type="hidden" name="chat" value="<?= $chatId ?>">
                        <div class="col-10">
                        <textarea class="form-control" name="message" id="message"
                                  placeholder="Type a Message"
                                  required
                        ></textarea>
                        </div>
                        <div class="col-2 px-0 my-auto">
                            <button type="submit" class="btn btn__send-message">
                                <i class="fas fa-paper-plane"></i>
                                SEND
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        <?php } else { ?>
            <div class="col-7 col-xl-8 no-conversation">
                <div class="row">
                    <div class="col-12 mt-3 text-right">
                        <a class="btn btn__logout btn-outline-danger" href="../index.php?name=logout">Logout</a>
                    </div>
                </div>
                <div class="row get-started justify-content-center">
                    <div class="col-6 text-center">
                        <h2>Get Started</h2>
                        <img class="img-fluid" src="../assets/images/messages.png" alt="">
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Favourite List Modal -->
<div class="modal fade favourites-list" id="favouriteListModal" tabindex="-1" role="dialog"
     aria-labelledby="favouriteListModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="favouriteListModalLabel">
                    Favourites
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <?php
                    if (!count($favourites)) {
                        ?>
                        <div class="text-center my-2">No Favourites to Show</div>
                    <?php }
                    foreach ($favourites as $favourite) {
                        ?>
                        <div class="row favourite">
                            <div class="col-2 square-img">
                                <img class="img-fluid img-round" src="<?= BASE_URL . $favourite["profileImage"] ?>"
                                     alt="">
                            </div>
                            <div class="col-10 pl-0">
                                <span class="favourite__name">
                                    <?= $favourite["first_name"] . " " . $favourite["last_name"] ?>
                                </span>
                                <span class="favourite__actions">
                                    <?php if (!$favourite["isWinked"]) { ?>
                                        <a href="?wink=<?= $favourite["ID"] ?>" class="btn__send-wink">
                                            <i class="far fa-smile-wink"></i>
                                            Send Wink
                                        </a>
                                    <?php } else { ?>
                                        <a class="btn__send-wink">
                                            <i class="fas fa-grin-wink"></i>
                                            Winked
                                        </a>
                                    <?php } ?>
                                    <a href="?chat=<?= $favourite["ID"] ?>" class="btn__send-message">
                                        <i class="far fa-comment"></i>
                                        Send Message
                                    </a>
                                    <a class="remove-from-fav ml-1"
                                       href="?removeFromFavourite=<?= $favourite["ID"] ?>">
                                        <i class="fas fa-times-circle"></i>
                                    </a>
                                </span>
                            </div>
                        </div>

                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

</body>
</html>


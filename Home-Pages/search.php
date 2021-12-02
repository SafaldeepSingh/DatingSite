<?php
require_once "../shared/user.php";
require_once "../shared/constants.php";
session_start();
$userDB = new userDB();
$invalidSelection = false;
$city = null;
$interested = null;
$profession = null;
$searchAlert = false;
if (isset($_SESSION['userId'])) {

    if (is_array($userDB->getUserInfo($_SESSION['userId']))) {
        $userData = $userDB->getUserInfo($_SESSION['userId']);
    }
}
$allUsersData = null;
if (isset($_POST['submit'])) {
    if ($_POST['city'] == 'Select' & $_POST['profession'] == 'Select' & $_POST['interested'] == 'Select') {
        $invalidSelection = true;
    }else{
        if($_POST['city'] != 'Select'){
            $city =  $_POST['city'];
        }
        if($_POST['profession'] != 'Select'){
            $profession =  $_POST['profession'];
        }
        if($_POST['interested'] != 'Select'){
           if($_POST['interested'] == 'Male'){
               $interested = 'M';
           }
           if(($_POST['interested'] == 'Female')){
               $interested = 'F';
           }
        }
        if (isset($_SESSION['userId'])) {

            if (is_array($userDB->searchCriteria1($city,$profession, $interested, ($_SESSION['userId'])))) {
                $allUsersData = $userDB->searchCriteria1($city,$profession, $interested, ($_SESSION['userId']));
            }
        }else{
            if (is_array($userDB->searchCriteria1($city,$profession, $interested, -1))) {
                $allUsersData = $userDB->searchCriteria1($city,$profession, $interested, -1);
            }
        }
        if($allUsersData == null){
            $searchAlert = true;
        }

    }
}

?>
<html>

<head>
    <title>Search</title>
    <link rel="stylesheet" href="../assets/css/Test.css">
    <link rel="shortcut icon" type="image/jpg" href="../assets/images/icon.svg"/>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-4.css">


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

        <?php if (!isset($_SESSION['userId'])) { ?>
            <a href="../Login-SignUp/Profile.php">
                <button class="btn btn__signUp" type="submit">Home</button>
            </a>
            <a href="../Login-SignUp/login.php">
                <button class="btn btn__signUp" type="submit">Log in</button>
            </a>
            <a href="../Login-SignUp/Register.php">
                <button class="btn btn__signUp" type="submit">Register</button>
            </a>
        <?php } else { ?>
            <a href="../Login-SignUp/Profile.php">
                <button class="btn btn__signUp" type="submit">Home</button>
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
<br><br>

<div class="card" style="width: 50rem;">
    <div class="card-header">
        Please select your preferences
    </div>
    <div class="card-body">
        <form action="./search.php" method="post">
        <div class="form-group row">
            <label for="interested" class="col-sm-2 col-form-label">Interested in</label>
            <div class="col-sm-10">
                <select class="form-control" id="interested" name="interested">
                    <option value="Select">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <br>
            </div>
        </div>
        <div class="form-group row">
            <label for="profession" class="col-sm-2 col-form-label">Profession</label>
            <div class="col-sm-10">
                <select class="form-control" id="profession" name="profession">
                    <option value="Select">Select</option>
                    <option value="Student">Student</option>
                    <option value="Engineer">Engineer</option>
                    <option value="Artist">Artist</option>
                    <option value="Pilot">Pilot</option>
                    <option value="Trading">Trading</option>
                    <option value="Politician">Politician</option>
                    <option value="Unemployed">Unemployed</option>
                </select>
                <br>
            </div>
        </div>
        <div class="form-group row">
            <label for="city" class="col-sm-2 col-form-label">City</label>
            <div class="col-sm-10">
                <select class="form-control" id="city" name="city">
                    <option value="Select">Select</option>
                    <option value="Toronto">Toronto</option>
                    <option value="Montreal">Montreal</option>
                    <option value="Calgary">Calgary</option>
                    <option value="Ottawa">Ottawa</option>
                    <option value="Edmonton">Edmonton</option>
                    <option value="Mississauga">Mississauga</option>
                    <option value="Vancouver">Vancouver</option>
                    <option value="Hamilton">Hamilton</option>
                    <option value="Brampton">Brampton</option>
                    <option value="Surrey">Surrey</option>
                </select>
                <?php if ($invalidSelection == true) {
                    echo "<div class='invalid-feedback d-block'> Please select at least one preference </div>";
                } ?>
                <?php if ($searchAlert == true) {
                    echo "<div class='invalid-feedback d-block'> No result found on your search </div>";
                } ?>
                <br>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10">
                <button type="submit" class="btn btn__signUp" name="submit">Submit</button>
            </div>
        </div>

        </form>
    </div>

</div>
<div class="container">
    <div class="row">
        <?php if ($allUsersData != null){
            for ($i = 0; $i < count($allUsersData); $i++) { ?>
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
        <?php }} ?>
    </div>
</div>
</body>
</html>

</body>
</html>


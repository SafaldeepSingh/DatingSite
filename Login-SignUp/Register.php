<?php
require_once "../shared/constants.php";
require_once "../shared/user.php";
session_start();
if (isset($_SESSION['userId'])) {
    //var_dump($_SESSION['userId']);
    header("Location: profile.php");
}

function removeSpecialChars($value): string
{
    $value = trim($value);
    $value = stripslashes($value);
    return htmlspecialchars($value);
}


function stringValidation(string $value): bool
{
    if (stringCharactersValidation($value) == true) {
        return false;
    }
    if (stringLengthValidation($value) == false) {
        return false;
    }
    return true;
}

function stringLengthValidation(string $value): bool
{
    if (strlen($value) <= 50 & (strlen($value) > 0)) {
        return true;
    } else {
        return false;
    }
}

function stringCharactersValidation(string $value): bool
{
    for ($position = 0; $position < strlen($value); $position++) {
        if (ctype_digit($value[$position])) {
            return true;
        }
    }
    return false;
}


$formPostData = array("inputFirstName" => "", "inputLastName" => "", "inputEmail" => "", "inputPassword" => "",
    "inputDateOfBirth" => -1, "inputGender" => "", "inputInterested" => -1, "inputProfession" => "",
    "inputCity" => "", "inputPremium" => 0, "inputProfilePicPath" => "", "inputTextArea" => "");

$formElementsInvalidityCheck = array("inputFirstName" => false, "inputLastName" => false, "inputEmail" => "", "inputPassword" => false,
    "inputDateOfBirth" => false, "inputGender" => false, "inputInterested" => false, "inputProfession" => false,
    "inputCity" => false, "inputPremium" => false, "inputProfilePicPath" => false, "inputTextArea" => false);

$totalValidAttributes = 0;

if (isset($_POST['submit'])) {

    if (isset($_POST['FirstName'])) {
        $formPostData['inputFirstName'] = removeSpecialChars($_POST['FirstName']);
        if (stringValidation($formPostData['inputFirstName']) == true) {
            $totalValidAttributes += 1;
        } else {
            $formElementsInvalidityCheck['inputFirstName'] = true;
        }
    } else {
        $formElementsInvalidityCheck['inputFirstName'] = true;
    }
    if (isset($_POST['LastName'])) {
        $formPostData['inputLastName'] = removeSpecialChars($_POST['LastName']);
        if (stringValidation($formPostData['inputLastName']) == true) {
            $totalValidAttributes += 1;
        } else {
            $formElementsInvalidityCheck['inputLastName'] = true;
        }
    } else {
        $formElementsInvalidityCheck['inputLastName'] = true;
    }


    if (isset($_POST['email'])) {
        $formPostData['inputEmail'] = removeSpecialChars($_POST['email']);
        $userDB = new UserDB();
        if (str_contains($formPostData['inputEmail'], '@')) {
            if ($userDB->userExist($formPostData['inputEmail'])) {
                $formElementsInvalidityCheck['inputEmail'] = 'userExists';
            } else {
                $totalValidAttributes += 1;
            }
        } else {
            $formElementsInvalidityCheck['inputEmail'] = 'invalid';
        }
    } else {
        $formElementsInvalidityCheck['inputEmail'] = 'invalid';
    }
    if (isset($_POST['password'])) {
        $formPostData['inputPassword'] = removeSpecialChars($_POST['password']);
        if (stringLengthValidation($formPostData['inputPassword']) == true) {
            $totalValidAttributes += 1;
        } else {
            $formElementsInvalidityCheck['inputPassword'] = true;
        }
    } else {
        $formElementsInvalidityCheck['inputPassword'] = true;
    }

    if (isset($_POST['date'])) {
        $UserDOB = removeSpecialChars($_POST['date']);
        $dateToday = new DateTime('now');
        $dateOfUserBirthday = new DateTime($UserDOB);
        $interval = $dateOfUserBirthday->diff($dateToday);
        if ($interval->y < 18) {
            $formElementsInvalidityCheck['inputDateOfBirth'] = true;
        } else {
            $formPostData['inputDateOfBirth'] = $interval->y;
            $totalValidAttributes += 1;
        }
    } else {
        $formElementsInvalidityCheck['inputDateOfBirth'] = true;
    }

    if (isset($_POST['gender']) & $_POST['gender'] != 'Select') {
        if ($_POST['gender'] == 'Male') {
            $formPostData['inputGender'] = 'M';
        } else {
            $formPostData['inputGender'] = 'F';
        }
        $totalValidAttributes += 1;
    } else {
        $formElementsInvalidityCheck['inputGender'] = true;
    }

    if (isset($_POST['interested']) & $_POST['interested'] != 'Select') {
        if ($_POST['interested'] == 'Male') {
            $formPostData['inputInterested'] = 0;
        } elseif ($_POST['interested'] == 'Female') {
            $formPostData['inputInterested'] = 1;
        } else {
            $formPostData['inputInterested'] = 2;
        }
        $totalValidAttributes += 1;
    } else {
        $formElementsInvalidityCheck['inputInterested'] = true;
    }

    if (isset($_POST['profession']) & $_POST['profession'] != 'Select') {
        $formPostData['inputProfession'] = $_POST['profession'];
        $totalValidAttributes += 1;
    } else {
        $formElementsInvalidityCheck['inputProfession'] = true;
    }
    if (isset($_POST['city']) & $_POST['city'] != 'Select') {
        $formPostData['inputCity'] = $_POST['city'];
        $totalValidAttributes += 1;
    } else {
        $formElementsInvalidityCheck['inputCity'] = true;
    }

    if (isset($_POST['premium']) & $_POST['premium'] != 'Select') {
        if ($_POST['premium'] == 'Yes') {
            $formPostData['inputPremium'] = 1;
        } else {
            $formPostData['inputPremium'] = 0;
        }
        $totalValidAttributes += 1;
    } else {
        $formElementsInvalidityCheck['inputPremium'] = true;
    }


    if (isset($_POST['message']) & $_POST['message'] != "") {
        $formPostData["inputTextArea"] = htmlspecialchars($_POST["message"]);
        $totalValidAttributes += 1;
    } else {
        $formElementsInvalidityCheck['inputTextArea'] = true;
    }

    if (isset($_FILES['profile_pic'])) {
        $imageFile = $_FILES['profile_pic'];
        if ($imageFile['size'] <= 3000000 & $imageFile['tmp_name'] != "") {   //Under 3000kb acceptable.
            $allowedTypes = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
            $detectedType = exif_imagetype($imageFile['tmp_name']);
            $error = !in_array($detectedType, $allowedTypes);
            if (!$error) {
                $filename = time() . '_' . $imageFile['name'];
                move_uploaded_file($imageFile['tmp_name'], "./../ignite-content/images/$filename");
                $formPostData["inputProfilePicPath"] = "ignite-content/images/" . $filename;
                $totalValidAttributes += 1;
            } else {
                $formElementsInvalidityCheck['inputProfilePicPath'] = true;
            }
        } else {
            $formElementsInvalidityCheck['inputProfilePicPath'] = true;
        }
    } else {
        $formElementsInvalidityCheck['inputProfilePicPath'] = true;
    }

    if ($totalValidAttributes == count($formElementsInvalidityCheck)) {
        $userDb = new UserDB();
        $profilePicID = $userDb->uploadProfilePic($formPostData["inputProfilePicPath"]);
        $userId = $userDb->uploadUserInfo($formPostData, $profilePicID);
        $_SESSION['userId'] = $userId;
        header("location: Profile.php");
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="shortcut icon" type="image/jpg" href="../assets/images/icon.svg"/>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/header.css">
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
        <a href="login.php">
            <button class="btn btn__signUp" type="submit">Log in</button>
        </a>
        <!--        <a class="active" href="login.php">Register</a>-->
        <!--        <a href="#contact">Contact</a>-->
        <!--        <a href="#about">About</a>-->
    </div>
</div>
<br/><br/>
<div class="card" style="width: 50rem;">

    <div class="card-header">
        Enter Details
    </div>
    <div class="card-body">
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" novalidate>
            <div class="form-group row">
                <label for="FirstName" class="col-sm-2 col-form-label">First Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="FirstName" name="FirstName">
                    <?php if ($formElementsInvalidityCheck["inputFirstName"] == true) {
                        echo "<div class='invalid-feedback d-block'> Maximum Characters limit is 50</div>";
                    } ?>
                    <br>
                </div>
            </div>
            <div class="form-group row">
                <label for="LastName" class="col-sm-2 col-form-label">Last Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="LastName" name="LastName">
                    <?php if ($formElementsInvalidityCheck["inputLastName"] == true) {
                        echo "<div class='invalid-feedback d-block'> Maximum Characters limit is 50</div>";
                    } ?>
                    <br>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="email">
                    <?php if ($formElementsInvalidityCheck["inputEmail"] == 'invalid') {
                        echo "<div class='invalid-feedback d-block'> Email needs to contain @ symbol. </div>";
                    } ?>
                    <?php if ($formElementsInvalidityCheck["inputEmail"] == 'userExists') {
                        echo "<div class='invalid-feedback d-block'> Email address already registered </div>";
                    } ?>
                    <br>
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="password" name="password">
                    <?php if ($formElementsInvalidityCheck["inputPassword"] == true) {
                        echo "<div class='invalid-feedback d-block'> Password can only have maximum 50 Characters </div>";
                    } ?>
                    <br>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Date of Birth</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control" name="date">
                    <?php if ($formElementsInvalidityCheck["inputDateOfBirth"] == true) {
                        echo "<div class='invalid-feedback d-block'> Need to be minimum of 18 years Old </div>";
                    } ?>
                    <br>
                </div>
            </div>
            <div class="form-group row">
                <label for="gender" class="col-sm-2 col-form-label">Gender</label>
                <div class="col-sm-10">
                    <select class="form-control" id="gender" name="gender">
                        <option value="Select">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    <?php if ($formElementsInvalidityCheck["inputGender"] == true) {
                        echo "<div class='invalid-feedback d-block'> Please select one value </div>";
                    } ?>
                    <br>
                </div>
            </div>
            <div class="form-group row">
                <label for="interested" class="col-sm-2 col-form-label">Interested in</label>
                <div class="col-sm-10">
                    <select class="form-control" id="interested" name="interested">
                        <option value="Select">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Both">Both</option>
                    </select>
                    <?php if ($formElementsInvalidityCheck["inputInterested"] == true) {
                        echo "<div class='invalid-feedback d-block'> Please select one value </div>";
                    } ?>
                    <br>
                </div>
            </div>
            <div class="form-group row">
                <label for="profession" class="col-sm-2 col-form-label">Profession</label>
                <div class="col-sm-10">
                    <select class="form-control" id="profession" name="profession">

                        <?php foreach (USER_PROFESSIONS as $profession) {
                            echo "<option value = $profession>$profession</option>";
                        } ?>

                        <!--                        <option value="Select">Select</option>-->
                        <!--                        <option value="Student">Student</option>-->
                        <!--                        <option value="Engineer">Engineer</option>-->
                        <!--                        <option value="Artist">Artist</option>-->
                        <!--                        <option value="Pilot">Pilot</option>-->
                        <!--                        <option value="Trading">Trading</option>-->
                        <!--                        <option value="Politician">Politician</option>-->
                        <!--                        <option value="Unemployed">Unemployed</option>-->

                    </select>
                    <?php if ($formElementsInvalidityCheck["inputProfession"] == true) {
                        echo "<div class='invalid-feedback d-block'> Please select one value </div>";
                    } ?>
                    <br>
                </div>
            </div>
            <div class="form-group row">
                <label for="city" class="col-sm-2 col-form-label">City</label>
                <div class="col-sm-10">
                    <select class="form-control" id="city" name="city">

                        <?php foreach (USER_CITY as $city) {
                            echo "<option value = $city>$city</option>";
                        } ?>

                        <!--                        <option value="Select">Select</option>-->
                        <!--                        <option value="Toronto">Toronto</option>-->
                        <!--                        <option value="Montreal">Montreal</option>-->
                        <!--                        <option value="Calgary">Calgary</option>-->
                        <!--                        <option value="Ottawa">Ottawa</option>-->
                        <!--                        <option value="Edmonton">Edmonton</option>-->
                        <!--                        <option value="Mississauga">Mississauga</option>-->
                        <!--                        <option value="Vancouver">Vancouver</option>-->
                        <!--                        <option value="Hamilton">Hamilton</option>-->
                        <!--                        <option value="Brampton">Brampton</option>-->
                        <!--                        <option value="Surrey">Surrey</option>-->


                    </select>
                    <?php if ($formElementsInvalidityCheck["inputCity"] == true) {
                        echo "<div class='invalid-feedback d-block'> Please select one value </div>";
                    } ?>
                    <br>
                </div>
            </div>

            <div class="form-group row">
                <label for="premium" class="col-sm-2 col-form-label">Premium access</label>
                <div class="col-sm-10">
                    <select class="form-control" id="premium" name="premium">
                        <option value="Select">Select</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                    <?php if ($formElementsInvalidityCheck["inputPremium"] == true) {
                        echo "<div class='invalid-feedback d-block'> Please select one value </div>";
                    } ?>
                    <br>
                </div>
            </div>


            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Message</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="message" required rows="3"
                              placeholder="Describe yourself"></textarea>
                    <?php if ($formElementsInvalidityCheck["inputTextArea"] == true) {
                        echo "<div class='invalid-feedback d-block'> Please Describe yourself  </div>";
                    } ?>
                    <br>
                </div>
            </div>


            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="profile_pic">Profile Picture</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" id="profile_pic" name="profile_pic"/>
                    <?php if ($formElementsInvalidityCheck["inputProfilePicPath"] == true) {
                        echo "<div class='invalid-feedback d-block'> Please upload your profile picture  </div>";
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


</body>
</html>

<?php
require_once "../shared/user.php";
require_once "../shared/constants.php";
session_start();
if (!isset($_SESSION['userId'])) {
    //var_dump($_SESSION['userId']);
    header("Location: ../Login-SignUp/profile.php");
}
$userDB = new UserDB();


$validPicture = false;
$inValidPicture = false;
$inputProfilePicPath = "";
$validUserData = false;

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


$formPostData = array("inputPassword" => "", "inputProfession" => "", "inputCity" => "", "inputPremium" => 0, "inputTextArea" => "");

$formElementsInvalidityCheck = array("inputPassword" => false, "inputProfession" => false,
    "inputCity" => false, "inputPremium" => false, "inputTextArea" => false);

$totalValidAttributes = 0;

if (isset($_POST['submit']) & isset($_SESSION['userId'])) {
    if (isset($_FILES['profile_pic'])) {
        $imageFile = $_FILES['profile_pic'];
        if ($imageFile['size'] <= 3000000 & $imageFile['tmp_name'] != "") {   //Under 3000kb acceptable.
            $allowedTypes = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
            $detectedType = exif_imagetype($imageFile['tmp_name']);
            $error = !in_array($detectedType, $allowedTypes);
            if (!$error) {
                $filename = time() . '_' . $imageFile['name'];
                move_uploaded_file($imageFile['tmp_name'], "./../ignite-content/images/$filename");
                $inputProfilePicPath = "ignite-content/images/" . $filename;
                $validPicture = true;
                $profilePicID = $userDB->uploadProfilePic($inputProfilePicPath);
                $userDB->updateProfilePicture($_SESSION['userId'], $profilePicID);
            } else {
                $inValidPicture = true;
            }
        } else {
            $inValidPicture = true;
        }
    } else {
        $inValidPicture = true;
    }
}

if (isset($_POST['submit2']) & isset($_SESSION['userId'])) {

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

    if ($totalValidAttributes == count($formElementsInvalidityCheck)) {
        $userDB->updateUserData($formPostData, $_SESSION['userId']);
        $validUserData = true;

    }
}

if (isset($_SESSION['userId'])) {

    if (is_array($userDB->getUserInfo($_SESSION['userId']))) {
        $userData = $userDB->getUserInfo($_SESSION['userId']);
        $userCity = $userData[0]['living_in'];
        $userProfession = $userData[0]['profession'];
        $isPremium = $userData[0]['is_premium'];
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
            <a href="../Login-SignUp/Profile.php">
                <button class="btn btn__signUp" type="submit">Home</button>
            </a>
            <a href="../Home-Pages/search.php">
                <button class="btn btn__signUp" type="submit">Search</button>
            </a>
            <a href="../messages/index.php">
                <button class="btn btn__signUp" type="submit">Notification and Messages</button>
            </a>
            <a href="../index.php?name=logout">
                <button class="btn btn__signUp" type="submit">Logout</button>
            </a>
            <img class="img-fluid img-round" style="width: 50px; height: 50px"
                 src="<?= BASE_URL . $userData[0]['path'] ?>" alt="">

        <?php } ?>

    </div>

</div>
<br/><br/>
<div class="card" style="width: 18rem;">

    <div class="card-header">
        Update profile Picture
    </div>
    <img src=<?= BASE_URL . $userData[0]['path'] ?> class="card-img-top" alt="...">
    <div class="card-body">
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" novalidate>
            <div class="form-group row">
                <!--                <label class="col-sm-4 col-form-label" for="profile_pic">Profile Picture</label>-->
                <div class="col-sm-12">
                    <input type="file" class="form-control" id="profile_pic" name="profile_pic"/>
                    <?php if ($inValidPicture == true) {
                        echo "<div class='invalid-feedback d-block'> Please upload your profile picture  </div>";
                    } ?>
                    <?php if ($validPicture == true) {
                        echo "<div class='valid-feedback d-block'> Upload successful</div>";
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

<div class="card" style="width: 50rem;">

    <div class="card-header">
        Enter Details
    </div>
    <div class="card-body">
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" novalidate>
            <fieldset disabled>
                <div class="form-group row">
                    <label for="FirstName" class="col-sm-2 col-form-label">First Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="FirstName"
                               placeholder=<?= $userData[0]['first_name'] ?>>
                        <br>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="LastName" class="col-sm-2 col-form-label">Last Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="LastName"
                               placeholder=<?= $userData[0]['last_name'] ?>>
                        <br>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" placeholder=<?= $userData[0]['email'] ?>>
                        <br>
                    </div>
                </div>
            </fieldset>
            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="password" name="password"
                           value="<?= $userData[0]['password'] ?>">
                    <?php if ($formElementsInvalidityCheck["inputPassword"] == true) {
                        echo "<div class='invalid-feedback d-block'> Password can only have maximum 50 Characters </div>";
                    } ?>
                    <br>
                </div>
            </div>
            <div class="form-group row">
                <label for="profession" class="col-sm-2 col-form-label">Profession</label>
                <div class="col-sm-10">
                    <select class="form-control" id="profession" name="profession">

                        <?php foreach (USER_PROFESSIONS as $profession) {
                            if ($profession == $userProfession) {
                                echo "<option selected value = $profession>$profession</option>";
                            } else {
                                echo "<option value = $profession>$profession</option>";
                            }

                        } ?>
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
                            if ($city == $userCity) {
                                echo "<option selected value = $city>$city</option>";
                            } else {
                                echo "<option value = $city>$city</option>";
                            }

                        } ?>
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
                              placeholder="Describe yourself"><?= $userData[0]['about'] ?></textarea>
                    <?php if ($formElementsInvalidityCheck["inputTextArea"] == true) {
                        echo "<div class='invalid-feedback d-block'> Please Describe yourself  </div>";
                    } ?>
                    <?php if ($validUserData == true) {
                        echo "<div class='valid-feedback d-block'>User data updated successfully</div>";
                    } ?>
                    <br>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn__signUp" name="submit2">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>


</body>
</html>

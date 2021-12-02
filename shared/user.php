<?php
require_once "Database2.php";

class UserDB extends Database2
{

    public function userExist($username): bool
    {
        $query = "select * from ignite.user where email = '$username'";
        $result = $this->execute($query);

        if (count($result) == 0) {
            return false;
        }
        return true;
    }

    public function authenticate($username, $password): array|bool
    {
        $query = "select ID from ignite.user where email = '$username' and password = '$password'";
        $result = $this->execute($query);

        if (count($result) == 0) {
            return false;
        }
        return $result;
    }

    public function getAllUsers(int $userLogged): array|bool
    {
        if ($userLogged == -1) {
            $query = "select user.*,p.path path from user JOIN photo p on p.ID = user.profile_photo_id";
        } else {
            $query = "select user.*,p.path from user JOIN photo p on p.ID = user.profile_photo_id where user.ID != '$userLogged'";
        }
        $result = $this->execute($query);

        if (count($result) == 0) {
            return false;
        }
        return $result;
    }

    public function getUserInfo(int $userLogged): array|bool
    {

        $query = "select user.*,p.path from user JOIN photo p on p.ID = user.profile_photo_id where user.ID = '$userLogged'";
        $result = $this->execute($query);

        if (count($result) == 0) {
            return false;
        }
        return $result;
    }

    public function uploadProfilePic($path): int
    {

        $query = "insert into photo(path) values('$path') ";
        return $this->insertValues($query);
    }

    public function uploadUserInfo($formData, $profilepicId): int
    {

        $query = "INSERT INTO user (first_name, last_name, email, password, age, gender, interested_in, profile_photo_id, is_premium, about, profession, living_in) VALUES('$formData[inputFirstName]', '$formData[inputLastName]', '$formData[inputEmail]', '$formData[inputPassword]', '$formData[inputDateOfBirth]', '$formData[inputGender]', '$formData[inputInterested]', '$profilepicId', '$formData[inputPremium]', '$formData[inputTextArea]', '$formData[inputProfession]','$formData[inputCity]')";
        return $this->insertValues($query);
    }

    public function sendWink($senderId, $receiverId): int
    {
        $query = "insert into wink 
                    (sender_id, receiver_id) 
                    values($senderId,$receiverId)";
        return $this->insertValues($query);
    }

    public function getLastWink($senderId, $receiverId): array|false
    {
        $query = "select *
        from wink where sender_id = '$senderId' and receiver_id = '$receiverId' order by ID desc limit 1;";
        return $this->execute($query);
    }

    public function checkFavourite($userId, $favouriteUserId): array|false
    {
        $query = "select count(*) count from user_favourite 
                    where user_id='$userId' 
                        and favourite_user_id = '$favouriteUserId'
                        and is_removed = 0";
        return $this->execute($query)[0];

    }

    public function removeFavourite($userId, $favouriteUserId): array|false
    {
        $query = "update user_favourite 
                    set is_removed = 1, seen_at=NULL
                    where user_id=$userId and favourite_user_id=$favouriteUserId";
        return $this->execute($query);

    }

    public function addFavourite($userId, $favouriteUserId): array|false
    {
        $query = "select count(*) count from user_favourite where user_id='$userId' and favourite_user_id = '$favouriteUserId'";
        $result = $this->execute($query);


        if ($result[0]['count'] == 0) {
            $query2 = "insert into user_favourite 
                        (user_id, favourite_user_id) 
                        values($userId, $favouriteUserId)";
        } else {
            $query2 = "update user_favourite 
                        set is_removed=0 , seen_at = NULL
                        where user_id=$userId and favourite_user_id = $favouriteUserId";
        }
        return $this->execute($query2);

    }


    public function searchCriteria1($city, $profession, $interested, $userLogged): array|false
    {
        $query = "select user.*,p.path path from user JOIN photo p on p.ID = user.profile_photo_id";
        $conditions = array();
        if ($city != null) {
            array_push($conditions, "living_in = '$city'");
        }
        if ($interested != null) {
            array_push($conditions, "gender = '$interested'");
        }
        if ($profession != null) {
            array_push($conditions, "profession = '$profession'");
        }
        if ($userLogged != -1) {
            array_push($conditions, "user.ID != $userLogged");
        }

        if (count($conditions)) {
            $query .= " where " . implode(" and ", $conditions);
        }
        return $this->execute($query);
    }

    public function updateProfilePicture($userId, $profilePictureId): array|false
    {
        $query = "update user set profile_photo_id = '$profilePictureId' where ID = '$userId'";
        return $this->execute($query);
    }

    public function updateUserData($updatedUserData, $userId)
    {
        $query = "update user set password = '$updatedUserData[inputPassword]',about = '$updatedUserData[inputTextArea]',is_premium = '$updatedUserData[inputPremium]', profession = '$updatedUserData[inputProfession]', living_in = '$updatedUserData[inputCity]' where ID = '$userId'";
        return $this->execute($query);
    }

}






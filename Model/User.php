<?php
require_once "Database.php";

class User
{
    static function getUserData($userId)
    {
        $query = "SELECT user.*,photo.path profileImage FROM user 
                    join photo on photo.ID = user.profile_photo_id
                    where user.id = :userId";
        $params = array(
            "userId" => $userId
        );
        return Database::executeQuery($query, $params, PDO::FETCH_ASSOC, false);
    }

    static function getOtherUserData($userId, $otherUserId)
    {
        $otherUserData = self::getUserData($otherUserId);
        $query = "SELECT sent_at from message
                    where (sender_id=:userId and receiver_id=:otherUserId)
                        or (sender_id=:otherUserId and receiver_id = :userId)
                    order by sent_at";
        $params = array(
            "userId" => $userId,
            "otherUserId" => $otherUserId
        );
        $firstMeet = Database::executeQuery($query,$params, PDO::FETCH_COLUMN
                                        , false, 0);
        $otherUserData["firstMeet"] = $firstMeet;
        return $otherUserData;
    }

}
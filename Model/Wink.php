<?php
require_once "Database.php";

class Wink
{
    static function getWinks($userId){
        //get winks
        $query = "SELECT wink.sent_at,u.first_name,u.ID,u.last_name,p.path as profileImage 
                        FROM wink
                    join user u on wink.sender_id = u.ID
                    join photo p on u.profile_photo_id = p.ID
                    where receiver_id=:userId and seen_at is null";
        $params = array(
            "userId" => $userId
        );
        $winks = Database::executeQuery($query,$params,PDO::FETCH_ASSOC);

        // Mark  winks as seen
        $query = "UPDATE wink
                    set seen_at=NOW()
                    where receiver_id=:userId and seen_at is null";
        Database::executeQuery($query,$params);

        return $winks;

    }
    static function getWinksRead($userId,$userData){
        //Get winks
        $query = "SELECT 'saw your wink' as detailMessage, 'wink' as type, wink.seen_at,u.ID,u.first_name,u.last_name,p.path as profileImage 
                        FROM wink
                    join user u on wink.receiver_id = u.ID
                    join photo p on u.profile_photo_id = p.ID
                    where sender_id=:userId and seen_at is not null and sender_informed = 0";
        $params = array(
            "userId" => $userId
        );
        $winks = Database::executeQuery($query,$params,PDO::FETCH_ASSOC);

        //set winks as informed to sender
        $query = "update wink 
                    set sender_informed = 1
                    where sender_id=:userId and seen_at is not null and sender_informed = 0";
        Database::executeQuery($query,$params);

        return $winks;
    }
    static function sendWink($senderId,$receiverId){
        if(self::isWinked($senderId,$receiverId))
            return;

        $query = "insert into wink 
                        (sender_id, receiver_id) 
                        values(:senderId,:receiverId)";
        $params = array(
            "senderId" => $senderId,
            "receiverId" => $receiverId
        );
        return Database::executeQuery($query, $params);
    }
    static function isWinked($senderId,$receiverId){
        $query = "select count(*) from wink
                    where sender_id = :senderId and receiver_id = :receiverId
                        and seen_at is null";
        $params = array(
            "senderId" => $senderId,
            "receiverId" => $receiverId
        );
        return Database::executeQuery($query,$params,PDO::FETCH_COLUMN,false,0);
    }


}
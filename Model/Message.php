<?php
require_once "Database.php";

class Message
{
    static function getRecentChats($userId)
    {
        $query1 = "SELECT u.*,p.path as profileImage from message 
                        join user u on message.receiver_id = u.ID 
                        join photo p on u.profile_photo_id = p.ID
                        where sender_id=:userId  group by receiver_id";
        $query2 = "SELECT u.*,p.path as profileImage from message 
                        join user u on message.sender_id = u.ID
                        join photo p on u.profile_photo_id = p.ID
                        where receiver_id=:userId group by sender_id";
        $query = $query1 . " UNION " . $query2;
        $params = array(
            "userId" => $userId
        );
        $recentChats = Database::executeQuery($query, $params, PDO::FETCH_ASSOC);
        //for all recent chat's get last message
        foreach ($recentChats as $index => $recentChat) {
            $recentChats[$index]["lastMessage"] = self::getLastMessage($userId, $recentChat["ID"]);
        }
        return $recentChats;

    }

    static function getConversation($userId, $otherUserId)
    {
        $query = "SELECT * from message 
                    where (sender_id=:userId and receiver_id=:otherUserId)
                            or
                          (sender_id=:otherUserId and receiver_id=:userId)";
        $params = array(
            "userId" => $userId,
            "otherUserId" => $otherUserId
        );
        return Database::executeQuery($query, $params, PDO::FETCH_ASSOC, true);
    }

    static function updateSeenMessages($otherUserId){
        $query = "UPDATE message
                    set seen_at=NOW() 
                    where sender_id=$_GET[chat] 
                            and seen_at is null";
        return Database::executeQuery($query);
    }

    static function sendMessage($senderId,$receiverId,$message){
//        if(!$message)
//            return;
        $query = "insert into message 
                        (sender_id, receiver_id, message) 
                        values(:senderId,:receiverId,:message)";
        $params = array(
          "senderId" =>$senderId,
          "receiverId" => $receiverId,
          "message" => $message
        );
        return Database::executeQuery($query,$params);
    }


    private static function getLastMessage($userId, $otherUserId)
    {
        $query = "SELECT * from message 
                    where (
                            (sender_id=:otherUserId and receiver_id=:userId) 
                            or 
                            (receiver_id=:otherUserId and sender_id=:userId)
                          )
                        and sent_at =
                            (SELECT MAX(sent_at) from message 
                            where (sender_id=:otherUserId and receiver_id=:userId) 
                               or (receiver_id=:otherUserId and sender_id=:userId)
                            )";
        $params = array(
            "userId" => $userId,
            "otherUserId" => $otherUserId
        );
        return Database::executeQuery($query, $params, PDO::FETCH_ASSOC, false);
    }

}
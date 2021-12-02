<?php
require "../Model/Message.php";
class Messages
{
    static function sendMessage($senderId,$receiverId,$message){
        if(!$message){
            return false;
        }
        return Message::sendMessage($senderId,$receiverId,$message);
    }
    static function getConversation($userId,$otherUserId){
        return Message::getConversation($userId,$otherUserId);
    }
    static function updateSeenMessages($otherUserId){
        return Message::updateSeenMessages($otherUserId);
    }
    static function getRecentChats($userId){
        $recentChats = Message::getRecentChats($userId);
        usort($recentChats,'Messages::sortRecentChats');
        return $recentChats;
    }

    static function isActiveChat($otherUserId):bool{
        if(!isset($_GET["chat"]))
            return false;
        return $_GET["chat"]==$otherUserId;
    }
    static function hasUnreadMessage($lastMessage,$userId):bool{
        return $lastMessage["sender_id"]!=$userId && !$lastMessage["seen_at"];
    }

    private static function sortRecentChats($a, $b)
    {
        return strtotime($b["lastMessage"]["sent_at"])- strtotime($a["lastMessage"]["sent_at"]);
    }


}
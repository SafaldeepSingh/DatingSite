<?php
require "../Model/Wink.php";
class Winks
{
    static function getWinks($userId){
        return Wink::getWinks($userId);
    }

    static function getWinksRead($userId,$userData){
        return Wink::getWinksRead($userId, $userData);
    }
    static function sendWink($senderId,$receiverId){
        return Wink::sendWink($senderId,$receiverId);
    }
}
<?php
require "../Model/User.php";

class Users
{
    static function getUserData($userId){
        return User::getUserData($userId);
    }
    static function getOtherUserData($userId, $otherUserId){
        return User::getOtherUserData($userId, $otherUserId);
    }
}
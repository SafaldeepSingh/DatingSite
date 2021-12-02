<?php
require_once "../Model/Wink.php";
require_once "../Model/Favourite.php";
class Notifications
{
    static function getNotifications($userId, $userData){
        $winksRead = Wink::getWinksRead($userId, $userData);
        $addedOrRemovedAsFavourite  =Favourite::getAddedOrRemovedNotification($userId, $userData);
        foreach ($addedOrRemovedAsFavourite as $index=>$notification){
            if($notification["is_removed"])
                $addedOrRemovedAsFavourite[$index]["detailMessage"]
                    = "removed you from favorites";
            else
                $addedOrRemovedAsFavourite[$index]["detailMessage"]
                    = "added you in favorites";
        }

        $notifications = array_merge($winksRead,$addedOrRemovedAsFavourite);
        usort($notifications, "Notifications::sortNotifications");
        return $notifications;
    }
    private static function sortNotifications($a, $b)
    {
        //for winks
        if($a["type"]=="wink")
            return strtotime($b["seen_at"])- strtotime($a["seen_at"]);
        else
        //for favourites added or removed
            return strtotime($b["added_at"])- strtotime($a["added_at"]);
    }


}
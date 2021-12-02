<?php
require "../Model/Favourite.php";
class Favourites
{
    static function getAddedOrRemovedNotification($userId,$userData){
        $addedOrRemovedNotification = Favourite::getAddedOrRemovedNotification($userId,$userData);
        foreach ($addedOrRemovedNotification as $index=>$notification){
            if($notification["is_removed"])
                $addedOrRemovedNotification[$index]["detailMessage"]
                    = "removed you from favorites";
            else
                $addedOrRemovedNotification[$index]["detailMessage"]
                    = "added you in favorites";
        }

        return $addedOrRemovedNotification;
    }

    static function removeFromFavourites($userId,$favouriteUserId){
        return Favourite::removeFromFavourites($userId,$favouriteUserId);
    }

    static function getFavourites($userId){
        $favourites = Favourite::getFavourites($userId);
        foreach ($favourites as $index => $favourite){
            $favourites[$index]["isWinked"] = Wink::isWinked($userId,$favourite["ID"]);
        }
        return $favourites;
    }
}
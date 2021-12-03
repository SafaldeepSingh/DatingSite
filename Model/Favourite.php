<?php
require_once "Database.php";
class Favourite
{
    static function getAddedOrRemovedNotification($userId,$userData){
        $query = "SELECT 'favourite' as type, user_favourite.*,u.ID,u.first_name,u.last_name,p.path as profileImage 
                        from user_favourite
                    join user u on u.ID = user_favourite.user_id
                    join photo p on u.profile_photo_id = p.ID
                    where favourite_user_id=:userId and seen_at is null";
        $params = array(
            "userId" => $userId
        );
        $addedOrRemovedNotification = Database::executeQuery($query,$params,PDO::FETCH_ASSOC);

        //to mark as informed to premium user
        $query = "UPDATE user_favourite 
                    set seen_at=NOW()
                    where favourite_user_id=:userId and seen_at is null";
        Database::executeQuery($query,$params);

        return $addedOrRemovedNotification;

    }

    static function getFavourites($userId){
        $query = "SELECT uf.added_at,u.ID,u.first_name,u.last_name,p.path as profileImage 
                        FROM user_favourite uf
                    join user u on uf.favourite_user_id = u.ID
                    join photo p on u.profile_photo_id = p.ID
                    where uf.user_id=:userId and uf.is_removed = 0";
        $params = array(
            "userId" => $userId
        );
        return Database::executeQuery($query,$params,PDO::FETCH_ASSOC);
    }

    static function addToFavourites($userId,$favouriteUserId){
        //check row exist in table or not
        $query = "select count(*) from user_favourite 
                    where user_id=:userId and favourite_user_id = :favouriteUserId";
        $params = array(
            "userId" => $userId,
            "favouriteUserId" => $favouriteUserId
        );
        $result = Database::executeQuery($query,$params,PDO::FETCH_COLUMN,false,0);
        if($result[0] == 0){
            $query = "insert into user_favourite 
                        (user_id, favourite_user_id) 
                        values(:userId, :favouriteUserId)";
        }else{
            $query = "update user_favourite 
                        set is_removed=0 , seen_at = NULL
                        where user_id=:userId and favourite_user_id = :favouriteUserId";
        }
        return Database::executeQuery($query,$params);
    }

    static function removeFromFavourites($userId,$favouriteUserId){
        $query = "update user_favourite 
                    set is_removed = 1, seen_at=NULL
                    where user_id=:userId and favourite_user_id=:favouriteUserId";
        $params = array(
            "userId" => $userId,
            "favouriteUserId" => $favouriteUserId
        );
        return Database::executeQuery($query,$params);
    }

    static function isFavourite($userId,$favouriteUserId){
        $query = "select count(*) from user_favourite 
                    where user_id=:userId 
                        and favourite_user_id = :favouriteUserId
                        and is_removed = 0";
        $params = array(
            "userId" => $userId,
            "favouriteUserId" => $favouriteUserId
        );
        return Database::executeQuery($query,$params,PDO::FETCH_COLUMN,false,0)[0];
    }


}
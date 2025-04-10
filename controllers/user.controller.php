<?php

require_once "utils/pdo.php";

class UserController{


    static public function getAllUsers(int $offset = 0, int $limit = 10){
        try{
            $pdo = createPDO();
            $stmt = $pdo->prepare("select user_id, name, role, profile_picture from users order by created_at limit :offset, :limit");
            $stmt->bindParam("offset", $offset, PDO::PARAM_INT);
            $stmt->bindParam("limit", $limit, PDO::PARAM_INT);
            $stmt->execute();
            dd($stmt->fetchAll(PDO::FETCH_ASSOC));
            $pdo = null;
        }
        catch(PDOException $e){
            //TODO: handle this
            dd($e);
        }
    }
}
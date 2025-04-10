<?php

require_once "utils/pdo.php";

class UserController{


    static public function getAllUsers(int $offset = 0, int $limit = 10){
        try{
            $pdo = createPDO();
            $usersStmt = $pdo->prepare("select user_id, name, role, profile_picture from users order by created_at limit :offset, :limit");
            $usersStmt->bindParam("offset", $offset, PDO::PARAM_INT);
            $usersStmt->bindParam("limit", $limit, PDO::PARAM_INT);
            $usersStmt->execute();
            $data = ($usersStmt->fetchAll(PDO::FETCH_ASSOC));

            $totalStmt = $pdo->prepare("select count(*) as total from users order by user_id");
            $totalStmt->execute();
            $total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            $pdo = null;
            return compact("data", "total");
        }
        catch(PDOException $e){
            //TODO: handle this
            dd($e);
        }
    }
}
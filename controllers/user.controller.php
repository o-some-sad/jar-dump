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
        public function insertIntoUsers(array $values, $path=null)
        {
            $pdo = createPDO();
            if (!$pdo) {
                return [false, "Failed to connect to database."];
            }
                $stmt = $pdo->prepare("
                    INSERT INTO `users` (name, email, password, role, profile_picture)
                    VALUES (:name, :email, :password, :role, :profile_picture)
                ");
                $stmt->bindParam(':name', $values['user_name']);
                $stmt->bindParam(':email', $values['user_email']);
                $stmt->bindParam(':password', $values['user_password']);
                $stmt->bindValue(':role', 'user');
                $stmt->bindParam(':profile_picture', $path);
                $stmt->execute();
                $pdo=null;
        }
}

 
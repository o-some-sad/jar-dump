<?php

require_once "utils/pdo.php";
require_once "utils/common.php";

class UserController{


    static public function getAllUsers(int $offset = 0, int $limit = 10, bool $includeDeleted = false){
        try{
            $pdo = createPDO();
            $whereClause = $includeDeleted ? "" : "where deleted_at is null";
            $usersStmt = $pdo->prepare("select * from users {$whereClause} order by created_at limit :offset, :limit");
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

    static public function deleteUser($id){
        $pdo = createPDO();
        $transactionStarted = $pdo->beginTransaction();
        if(!$transactionStarted){
            throw new Exception("Transaction Failed");
        }
        $updateStmt = $pdo->prepare("update users set deleted_at = now() where user_id = :user_id and deleted_at is not null");
        $updateStmt->bindValue("user_id", (int)$id, PDO::PARAM_INT);
        $updateStmt->execute();
        $pdo->commit();
        $pdo = null;
        return $updateStmt->rowCount();
    }

    static public function updateUser($id, array $data){
        $pdo = createPDO();
        $transactionStarted = $pdo->beginTransaction();
        if(!$transactionStarted){
            throw new Exception("Transaction Failed");
        }
        $safeData = pick($data, ['name', 'role']);
        $updateStmt = $pdo->prepare("update users set name = :name, role = :role where user_id = :user_id and deleted_at is null");
        $updateStmt->bindValue("name", $safeData['name'], PDO::PARAM_STR);
        $updateStmt->bindValue("role", $safeData['role'], PDO::PARAM_STR);
        $updateStmt->bindValue("user_id", (int)$id, PDO::PARAM_INT);
        $updateStmt->execute();
        $pdo->commit();
        $pdo = null;
        return $updateStmt->rowCount();
    }
}
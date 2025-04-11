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

    static public function insertIntoUsers(){
        // $_POST['name attribute of input']
        if(isset($_POST['submit_btn'])){
            $user_name = $_POST['user_name'];
            $user_email = $_POST['user_email'];
            // HASH THE PASSWORD - DONE
            $user_password = $_POST['user_password'];
            $hashedPass = password_hash($user_password, PASSWORD_DEFAULT);
            // $user_profile_pic = $_FILES['user_profile_pic'];
            $pdo = createPDO();
            if($pdo){
                // I put 'role' as 'user' - CORRECT ??
                // add profile pic
                $stmt = $pdo->prepare("insert into `users`(name, email, password, role)
                values(:name,:email,:password,'user')");
                $stmt->bindParam(':name', $user_name);
                $stmt->bindParam(':email', $user_email);
                $stmt->bindParam(':password', $hashedPass);
                // $stmt->bindParam(':profile_picture', $user_profile_pic);
                $stmt->execute();
                if($stmt->rowCount() > 0) {
                    echo "User added successfully!";
                } else {
                    echo "Failed to add user.";
                }
                $pdo = null;
            } else {
                echo "Failed to connect to database.";
            }
        }
    }
}

 
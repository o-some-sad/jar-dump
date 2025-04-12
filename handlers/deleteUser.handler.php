<?php
require_once "utils/validator.php";
require_once "controllers/user.controller.php";

$currentUser = Auth::getUser();
$userID = $_SERVER['params']['id'];

if($userID == $currentUser['user_id']){
    redirectWithValidationResult([], ['_' => "You can't delete yourself"], "/dashboard/users");
    exit;
}

try{
    $update = UserController::deleteUser($userID);
    if(!$update){
        redirectWithValidationResult([], ['_' => "Failed to delete user"], "/dashboard/users");
        exit;
    }
    redirect("/dashboard/users");
}catch(Exception $e){
    redirectWithValidationResult([], ['_' => $e->getMessage()], "/dashboard/users");
    exit;
}
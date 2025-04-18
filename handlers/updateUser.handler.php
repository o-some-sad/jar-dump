<?php
require_once "utils/validator.php";
require_once "controllers/user.controller.php";

$userID = $_SERVER['params']['id'];
$currentUser = Auth::getUser();


$validation = [
    'name' => validate("name", custom: fn($value) => strlen($value) > 3 ?  [true, $value] : [false, "Name must be at least 3 characters"]),
    'role' => validate("role", custom: fn($value) => in_array($value, ["admin", "user"]) ? [true, $value] : [false, "Role must be admin or user"])
];

$values = handleValidationResult($validation, "/admin/users");

if($userID == $currentUser['user_id'] && $values['role'] != $currentUser['role']){
    redirectWithValidationResult($values, ['_' => "You can't change your role"], "/admin/users");
    exit;
}

try {
    $result = UserController::updateUserFromDashboard($userID, $values);
    if (!$result) {
        redirectWithValidationResult($values, ['_' => "Failed to update user"], "/admin/users");
        exit;
    }
    redirect("/admin/users");
    exit;
} catch (Exception $e) {
    redirectWithValidationResult($values, ['_' => $e->getMessage()], "/admin/users");
    exit;
}

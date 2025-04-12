<?php
require_once "utils/validator.php";
require_once "controllers/user.controller.php";

$userID = $_SERVER['params']['id'];

$validation = [
    'name' => validate("name", custom: fn($value) => strlen($value) > 3 ?  [true, $value] : [false, "Name must be at least 3 characters"]),
    'role' => validate("role", custom: fn($value) => in_array($value, ["admin", "user"]) ? [true, $value] : [false, "Role must be admin or user"])
];

$values = handleValidationResult($validation, "/dashboard/users");

try {
    $result = UserController::updateUser($userID, $values);
    if (!$result) {
        redirectWithValidationResult($values, ['_' => "Failed to update user"], "/dashboard/users");
        exit;
    }
    redirect("/dashboard/users");
    exit;
} catch (Exception $e) {
    redirectWithValidationResult($values, ['_' => $e->getMessage()], "/dashboard/users");
    exit;
}

<?php
require_once "utils/validator.php";
$validationResult = [
    'email' => validate(
        "email",
        custom: fn($raw) => [filter_var($raw, FILTER_VALIDATE_EMAIL), "Please enter a valid email"]
    ),
    'password' => validate(
        "password",
        custom: fn($pass)=>[strlen($pass) >= 8, "Password needs to be at least 8 characters"]
    )
];

$values = handleValidationResult($validationResult, "/login");

dd($values);

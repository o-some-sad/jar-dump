<?php
require_once "utils/validator.php";
require_once "controllers/user.controller.php";

$validationResult = [
    'user_email' => validate(
        "email",
        custom: fn($raw) => [filter_var($raw, FILTER_VALIDATE_EMAIL), "Please enter a valid email"]
    ),
    'user_password' => validate(
        "password",
        custom: fn($pass)=>[strlen($pass) >= 8, "Password needs to be at least 8 characters"]
    ),
    'password_match' => matchingPasswords(
        $_POST['user_password'] ?? '', 
        $_POST['user_confirm_password'] ?? ''
    )
];

    // **HASH PASSWORD ??
    // **CONFIRM PASSWORD (check if password = confirm password, if NOT display an error)
    $values = handleValidationResult($validationResult, "/dashboard/users/new");
    $imageValidation = validateFile('user_profile_pic', ['jpg', 'png','jpeg']);
    // 'user_profile_pic' => validate(
    //     "user_profile_pic",
    //     custom: fn($pass)=>[strlen($pass) >= 8, "Password needs to be at least 8 characters"]
    // )
    if($imageValidation[0] === false){
        $fileError = $imageValidation[1];
        redirectWithValidationResult($imageValidation,['user_profile_pic' => $fileError], "/dashboard/users/new");
    } else {
        $uploadedImg = $imageValidation[1];
    }
    // $userName = $_POST['user_name'];
    // $userEmail = $_POST['user_email'];
    UserController::insertIntoUsers();
    // IF THERE'S NO VALDATION ERRORS, THEN CALL INSERT METHOD IN USER CONTROLLER^^ //
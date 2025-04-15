<?php
require_once "utils/validator.php";
require_once "controllers/user.controller.php";

$validationResult = [
    'user_name' => validate(
        "user_name",
        custom: fn($name) => [strlen($name) > 0, "Name is required"]
    ),
    'user_email' => validate(
        "user_email",
        custom: fn($raw) => [filter_var($raw, FILTER_VALIDATE_EMAIL), "Please enter a valid email"]
    ),
    'user_password' => validate(
        "user_password",
        custom: fn($pass)=>[strlen($pass) >= 8, "Password needs to be at least 8 characters"]
    ),
    'user_confirm_password' => validate(
    "user_confirm_password",
    custom: fn($confpass) => [strlen($confpass) >= 8, "Confirmation password needs to be at least 8 characters"]
    )
];

    $values = handleValidationResult($validationResult, "/admin/users/new");
    $passwordMatch = matchingPasswords($values['user_password'], $values['user_confirm_password']);
    if (!$passwordMatch[0]) {
        $errors = ['password_match' => $passwordMatch[1]]; // Add to errors array
        redirectWithValidationResult($values, $errors, "/admin/users/new");
    }
    $imageValidation = validateFile('user_profile_pic', ['jpg', 'png', 'jpeg']);
    if (!$imageValidation[0]) {
        $errors = ['user_profile_pic' => $imageValidation[1]];
        // $_SESSION['file_info'] = $_FILES['user_profile_pic']['name'];
        // $_SESSION['file_infoo']= strtolower(pathinfo($_FILES['user_profile_pic']['name'], PATHINFO_EXTENSION));
        redirectWithValidationResult($values, $errors, "/admin/users/new");
    }
    $path = "NOT AVAILABLE";
    //  MOVING THE IMG TO A PERM. DIR. INSTEAD OF TMP.
    $uploadedImg = $imageValidation[1];
    if ($uploadedImg['tmp_name']) {
        $ext = strtolower(pathinfo($uploadedImg['name'], PATHINFO_EXTENSION));
        $filename = 'userImg'.uniqid() . '.' . $ext; // uniqid() --> for unique filenames
        $destination = 'public/'.$filename;
        $moveImg = move_uploaded_file($uploadedImg['tmp_name'], $destination);
        $path = $destination; // path for db
    }
    $values['user_password'] = password_hash($values['user_password'], PASSWORD_DEFAULT);
    $_SESSION['file_info'] = $values['user_password'];
    unset($values['user_confirm_password']);
    $userController = new UserController();
    try{
        $userController->insertIntoUsers($values,$path);
    } catch (Exception $e) {
        $errors = ['user_email' => $e->getMessage()];
        redirectWithValidationResult($values, $errors, "/admin/users/new");
    }
    // UserController::insertIntoUsers($values);
    // IF THERE'S NO VALDATION ERRORS, THEN CALL INSERT METHOD IN USER CONTROLLER^^ //
<?php
require_once "utils/validator.php";
require_once "utils/pdo.php";
require_once "utils/http.php";

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


try{
    if(Auth::isAuthed()){
        //already authed, just redirect
        redirect($_GET["to"] ?? "/");
        exit;
    }
    $pdo = createPDO();

    $stmt = $pdo->prepare("select user_id, name, email, password, role, profile_picture from users where email = :email and deleted_at is null");
    $stmt->execute([
        'email' => $values['email']
    ]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$user){
        redirectWithValidationResult($values, ['_' => "Email or password is incorrect"], "/login");
        exit;
    }
    if(!password_verify($values['password'], $user['password'])){
        redirectWithValidationResult($values, ['_' => "Email or password is incorrect"], "/login");
        exit;
    }
    //! REMOVE PASSWORD FROM USER ARRAY
    unset($user['password']);
    $_SESSION['logged_in'] = true;
    $_SESSION['user'] = $user;
    redirect($_GET["to"] ?? "/");
    exit;
}
catch(PDOException $e){
    dd($e);
}

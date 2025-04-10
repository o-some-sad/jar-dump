<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $users = file('users.txt');
    $valid_login = false;

    foreach ($users as $user) {
        $user_data = json_decode($user, true);
        if ($user_data['username'] == $username && password_verify($password, $user_data['password'])) {
            $valid_login = true;
            $_SESSION['username'] = $username;
            break;
        }
    }

    if ($valid_login) {
        header("Location: welcome.php");
        exit();
    } else {
        header("Location: login.php?error=invalid_credentials");
        exit();
    }
}
?>
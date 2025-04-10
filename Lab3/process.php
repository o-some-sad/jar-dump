<?php
function validateEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    if (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
        return false;
    }

    return true;
}

function validatePassword($password) {
    if (strlen($password) != 8) {
        return false;
    }

    if (preg_match("/[^a-z0-9_]/", $password)) {
        return false;
    }

    if (preg_match("/[A-Z]/", $password)) {
        return false;
    }

    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $room = $_POST['room'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $profile_pic = $_FILES['profile_pic'];

    if (!validateEmail($email)) {
        die("Invalid email address.");
    }

  
    if (!validatePassword($password)) {
        die("Invalid password. Password must be 8 characters long, contain only lowercase letters, numbers, and underscores.");
    }

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($profile_pic['type'], $allowed_types)) {
        die("Invalid file type. Only JPEG, PNG, and GIF are allowed.");
    }

    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir);
    }
    $profile_pic_path = $upload_dir . basename($profile_pic['name']);

   echo "Upload Directory: $upload_dir<br>";
    echo "Profile Picture Path: $profile_pic_path<br>";
    echo "Temporary File Path: " . $profile_pic['tmp_name'] . "<br>";
    if (move_uploaded_file($profile_pic['tmp_name'], $profile_pic_path)) {
        echo "File uploaded successfully.<br>";
    } else {
        echo "Failed to upload file.<br>";
    }

    $user_data = [
        'email' => $email,
        'room' => $room,
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT), 
        'profile_pic' => $profile_pic_path
    ];

    file_put_contents('users.txt', json_encode($user_data) . PHP_EOL, FILE_APPEND);

    header("Location: login.php?registration=success");
    exit();
}
?>
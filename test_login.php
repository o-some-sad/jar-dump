<?php
session_start();
require_once __DIR__.'/config/db.php'; // Adjust path as needed

// Simulate login
$_SESSION['user_id'] = 1; // Admin user ID
$_SESSION['role'] = 'admin';
$_SESSION['name'] = 'Admin User';
$_SESSION['logged_in'] = true;

header('Location: /admin/products');
exit();
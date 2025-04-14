<?php
require_once __DIR__ . '/auth.php';


function isSessionAuthenticated(): bool {
    return isset($_SESSION['user_id']);
}

function isSessionAdmin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isAuthenticated(): bool {
    return isSessionAuthenticated() || Auth::isAuthed();
}

function isAdmin(): bool {
    if (isSessionAdmin()) return true;
    if (!isAuthenticated()) return false;
    
    try {
        return Auth::getUser()['role'] === Role::Admin->value;
    } catch (Exception $e) {
        error_log("Admin check failed: " . $e->getMessage());
        return false;
    }
}

function requireAuth() {
    if (!isAuthenticated()) {
        $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
        header('Location: /login');
        exit();
    }
}

function requireAdmin() {
    requireAuth();
    if (!isAdmin()) {
        header('Location: /');
        exit();
    }
}
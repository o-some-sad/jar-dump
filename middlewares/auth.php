<?php


/**
 * SESSION STORAGE CONTENT
    1- boolean logged_in
        indicates whether this session has a logged in user or not
    2- User user
        where a user -if logged_in is true- lives
 */

//TODO: move Role enum to a proper place
enum Role: string
{
    case User = "user";
    case Admin = "admin";
};







class Auth
{
    static private bool $is_request_protected = false;
    static private array | null $current_user = null;
    static private function  preventAccess(string $message = "Your'e not allowed here"){
        require_once __DIR__. '/../views/403.php';
        exit;
    }

    static private function redirectToLogin(){
        $currentPath = $_SERVER['REQUEST_URI'];
        redirect("/login?return=$currentPath");
        exit;
    }

    static function isAuthed(){
        // (value of logged_in is present and equals true)
        return (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true);
    }

    /**
     * @param (Role[] | null) $roles
     */
    static function protect(array | null $roles = null)
    {
        if(!static::isAuthed()){
            // static::preventAccess();
            static::redirectToLogin();
        }
        if(!isset($_SESSION['user'])){
            //// TODO: log out the user without panic
            // throw new Exception('The \'user\' object is not found', 1);
            unset($_SESSION['logged_in']);
            unset($_SESSION['user']);
            static::redirectToLogin();
        }
        
        $user = $_SESSION['user'];
        $roles = $roles ? array_map(fn($role)=>$role->value, $roles) : $roles;
        if(!is_null($roles) && !in_array($user['role'], $roles, true)){
            //TODO: find better way to retrieve role name
            $role = print_r($user['role'], true);
            static::preventAccess("Role <code>{$role}</code> is not allowed to access this route");
        }
        static::$is_request_protected = true;
        static::$current_user = $user;
    }



    static function getUser(){
        if(static::$is_request_protected)return static::$current_user;
        throw new Exception("cannot retrieve user without protected route", 1);
    }
}

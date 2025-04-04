<?php


/**
 * SESSION STORAGE CONTENT
    1- boolean logged_in
        indicates whether this session has a logged in user or not
    2- User user
        where a user -if logged_in is true- lives
 */

//TODO: move Role enum to a proper place
enum Role
{
    case User;
    case Admin;
};


//TODO: move this class
class User{
    public Role $role = Role::User;
}




class Auth
{
    static private bool $is_request_protected = false;
    static private User | null $current_user = null;
    static private function  preventAccess(string $message = "Your'e not allowed here"){
        echo $message;
        exit;
    }

    /**
     * @param (Role[] | null) $roles
     */
    static function protect(array | null $roles = null)
    {
        if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true){
            static::preventAccess();
        }
        if(!isset($_SESSION['user'])){
            //TODO: log out the user without panic
            throw new Exception('The \'user\' object is not found', 1);
        }
        /** @var User $user */
        $user = $_SESSION['user'];
        if(!is_null($roles) && !in_array($user->role, $roles, true)){
            //TODO: find better way to retrieve role name
            $role = print_r($user->role, true);
            static::preventAccess("Role <code>{$role}</code> is not allowed to access this route");
        }
        static::$is_request_protected = true;
        static::$current_user = $user;
    }



    static function getUser(): User{
        if(static::$is_request_protected)return static::$current_user;
        throw new Exception("cannot retrieve user without protected route", 1);
        
    }
}

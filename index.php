<!-- https://tania.dev/the-simplest-php-router/ -->
<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

//TODO: discuss using namespaces
require_once "middlewares/auth.php";
require_once "utils/debug.php";
require_once "utils/http.php";
require_once "utils/env.php";


$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request = rtrim($request, "/");
$method = $_SERVER["REQUEST_METHOD"];

loadEnv();
session_start();

function matchRoute($route, $path)
{
    $path = rtrim($path, "/");
    $route = rtrim($route, "/");
    $escapedRoute = addcslashes($route, "/");
    $readyPattern = preg_replace("/\{(.*?)\}/", '(?P<$1>[^\/]*)', $escapedRoute);
    preg_match("/^" . $readyPattern . "$/", $path, $matches);
    if (is_null($matches)) {
        notFound();
        exit;
    }
    return $matches;
}

function userRoutes($request)
{
    global $method;
    $matches =  matchRoute("/dashboard/users/{id}", $request);
    if ($matches && $method == "GET") {
        Auth::protect([Role::Admin]);
        $_REQUEST['params'] = $matches;
        require __DIR__ . '/views/dashboard/edit_user.php';
        exit;
    }
    $matches = matchRoute("/dashboard/users/{id}/delete", $request);
    if ($matches && $method == "POST") {
        require __DIR__ . "/handlers/deleteUser.handler.php";
        exit;
    }
}

switch ($request) {
    case '/':
    case '':
        Auth::protect();
        require __DIR__ . '/views/index.php';
        break;
    case '/login':
        if ($method == "GET") require __DIR__ . '/views/login.php';
        else if ($method == "POST") require __DIR__ . '/handlers/login.handler.php';
        else notFound();
        break;
    case '/dashboard/users':
        Auth::protect([Role::Admin]);
        require __DIR__ . '/views/dashboard/users.php';
        break;
    case '/dashboard/users/new':
        Auth::protect([Role::Admin]);
        echo "<h1>user registration will be here</h1>";
        break;
    default:
        userRoutes($request);
        // if (preg_match("/^\/edit\/(\d+)$/", $request, $match)) {
        //     $_REQUEST["PARAMS"] = array_slice($match, 1);
        //     require __DIR__ . "/views/edit.php";
        //     break;
        // }
        notFound();
        break;
}

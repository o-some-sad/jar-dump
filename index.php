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
$method = $_SERVER["REQUEST_METHOD"];

loadEnv();
session_start();


switch ($request) {
    case '/':
    case '':
        Auth::protect();
        require __DIR__ . '/views/index.php';
        break;
    case '/login':
        if($method == "GET") require __DIR__ . '/views/login.php';
        else if($method == "POST") require __DIR__ . '/handlers/login.handler.php';
        else notFound();
        break;
    default:
        // if (preg_match("/^\/edit\/(\d+)$/", $request, $match)) {
        //     $_REQUEST["PARAMS"] = array_slice($match, 1);
        //     require __DIR__ . "/views/edit.php";
        //     break;
        // }
        notFound();
        break;
}

<!-- https://tania.dev/the-simplest-php-router/ -->
<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


//TODO: discuss using namespaces
require_once "middlewares/auth.php";


session_start();
// $_SESSION['user'] = new User();
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
switch ($request) {
    case '/':
    case '':
        Auth::protect();
        require __DIR__ . '/views/index.php';
        break;
    default:
        // if (preg_match("/^\/edit\/(\d+)$/", $request, $match)) {
        //     $_REQUEST["PARAMS"] = array_slice($match, 1);
        //     require __DIR__ . "/views/edit.php";
        //     break;
        // }
        http_response_code(404);
        require __DIR__ . '/views/404.php';
        break;
}

<?php


function notFound() {
    http_response_code(404);
    require "views/404.php";
    exit;
}

function redirect($to, $status = 302){
    header("Location: $to", true, $status);
    exit;
}

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
    $_SERVER['params'] = $matches;
    return $matches;
}


// class Router
// {
//     private static  function parseRouteVerb($route)
//     {
//         $pattern = "(GET|POST|PUT|PATCH|DELETE|HEAD|OPTIONS|ALL) (.*)";
//         $result = [];
//         preg_match($pattern, $route, $result);
//         dd($result);
//     }

//     public function __construct(array $routes) {}

//     // $routes = [
//     //     'GET /' => __DIR__ . '/views/index.php',
//     //     'GET /login' => __DIR__ . '/views/login.php',
//     //     'ALL **' => __DIR__ . '/views/404.php'
//     // ];

//     // $sample_path = '/login/';
//     // $sample_path = rtrim($sample_path, "/");
//     // $escaped_path = addcslashes($sample_path, "/");
// }


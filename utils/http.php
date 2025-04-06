<?php


function notFound() {
    http_response_code(404);
    require "views/404.php";
    exit;
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


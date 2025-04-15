<?php
require_once __DIR__ . '/../config.php';

function createPDO(){
    $host = DB_HOST;
    $port = DB_PORT;
    $db = DB_NAME;
    $user = DB_USER;
    $pass = DB_PASS;
    
    $connection = "mysql:host=$host:$port;dbname=$db";
    
    return new PDO($connection, $user, $pass);
}
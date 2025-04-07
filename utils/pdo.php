<?php

function createPDO(){
    $host = getenv("DB_HOST");
    $port = getenv("DB_PORT");
    $db = getenv("DB_NAME");
    $user = getenv("DB_USER");
    $pass = getenv("DB_PASS");

    $connection = "mysql:host=$host:$port;dbname=$db";
    
    return new PDO($connection, $user, $pass);
}
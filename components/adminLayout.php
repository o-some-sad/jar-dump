<?php
require_once "head.php";
require_once "middlewares/auth.php";


function adminLayout_open(string $title) {
    $user = Auth::getUser();
    echo 
    '<!DOCTYPE html>
    <html lang="en">';
    head_open();
    echo "<title>$title</title>";
    //write custom head tags here
    head_close();
    echo '<body>';
    echo '<header>';
    echo "<span>Hello {$user['name']}</span>";
    echo '</header>';
}

function adminLayout_close(){
    echo '<script type="module" src="/static/alpine-components.js"></script>';
    echo '<script defer src="/static/alpine.js"></script>';
    echo 
    '</body>
    </html>';
}
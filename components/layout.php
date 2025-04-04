<?php
require_once "head.php";


function layout_open(string $title) {
    echo 
    '<!DOCTYPE html>
    <html lang="en">';
    head_open();
    echo "<title>$title</title>";
    //write custom head tags here
    head_close();
    echo '<body>';
}

function layout_close(){
    echo '<script defer src="/static/alpine.js"></script>';
    echo 
    '</body>
    </html>';
}
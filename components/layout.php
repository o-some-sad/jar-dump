<?php
require_once "head.php";


function layout_open(string $title) {
    echo 
    '<!DOCTYPE html>
    <html lang="en">';
    head_open();
    echo "<title>$title</title>";
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
    //write custom head tags here
    head_close();
    echo '<body>';
    require_once "components/header.php";
}

function layout_close(){
    echo '<script type="module" src="/static/alpine-components.js"></script>';
    echo '<script defer src="/static/alpine.js"></script>';
    echo 
    '</body>
    </html>';
}
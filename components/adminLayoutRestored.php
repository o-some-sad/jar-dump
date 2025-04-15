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
    echo '<link rel="stylesheet" href="/static/style.css">';
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">';
    echo '<script src="https://cdn.tailwindcss.com"></script>';
    //write custom head tags here
    head_close();
    echo '<body>';

    echo '<nav class="navbar navbar-light bg-light p-3">';
    echo '<div class="container-fluid">';
    // echo '<a class="navbar-brand" href="#">Navbar</a>';
    echo "<span>Hello {$user['name']}</span>";
    echo "<form action='/auth/logout' method='POST'> 
        <button class='btn btn-link'>Logout</button>
    </form>";
    echo '</div>';
    echo '</nav>';
    echo '<main class="d-flex">';
    // echo '</header>';
    echo '<aside class="aside">';
        echo '<a href="/admin" data-title="Home"><i class="fa-solid fa-house"></i></a>';
        echo '<a href="/admin/products" data-title="Products"><i class="fa-solid fa-list"></i></a>';
        echo '<a href="/admin/orders" data-title="Orders"><i class="fa-solid fa-shopping-cart"></i></a>';
        echo '<a href="/admin/users" data-title="Users"><i class="fas fa-users me-1"></i></a>';
    echo '</aside>';
}

function adminLayout_close(){
    echo '</main>';
    echo '<script type="module" src="/static/alpine-components.js"></script>';
    echo '<script defer src="/static/alpine.js"></script>';
    echo 
    '</body>
    </html>';
}
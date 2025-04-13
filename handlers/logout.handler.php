<?php
require_once "middlewares/auth.php";
require_once "utils/http.php";

if(!Auth::isAuthed()){
    redirect("/");
    exit;
}

unset($_SESSION['logged_in']);
unset($_SESSION['user']);
redirect("/");
exit;

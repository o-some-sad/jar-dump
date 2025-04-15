<?php
    require_once "controllers/order.controller.php";
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelbtn'])) {
        $id = $_POST['id'];
        OrderController::deleteOrder($id);
        header("Location: orderHistory.php");
        exit();    
    }
    header("Location: orderHistory.php");
?>
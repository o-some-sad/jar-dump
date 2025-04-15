<?php
require_once __DIR__ . '/../utils/pdo.php';
require_once __DIR__. '/../controllers/OrderController.php';
$pdo = createPDO();

$orderController = new OrderController($pdo);
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['order_id']) && ($_POST['status'] === 'out_for_delivery')){
        echo "hello from shit";
        $orderController->updateOrderStatus($_POST['order_id'], 'out_for_delivery');
        header('Location: /admin/orders');
        exit;
    }
    if(isset($_POST['order_id']) && ($_POST['status'] === 'done')){
        echo "hello from done";
        $orderController->updateOrderStatus($_POST['order_id'], 'done');
        header('Location: /admin/orders');
        exit;
    }
}

dd($_POST);

?>
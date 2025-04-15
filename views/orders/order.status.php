<?php
require_once __DIR__. '/../../utils/pdo.php';
require_once __DIR__. '/../../controllers/OrderController.php';

$pdo = createPDO();
$orderController = new OrderController($pdo);
$orders = $orderController->getOrders();
$orderItems = $orderController->getOrderItemsByOrderId(1);
dd($orderItems[0]);
//
//loop over the orders ordered by their date, and then over the items
//switch on the status of the order until it's completed and greyed out.
?>
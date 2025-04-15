<?php

require_once __DIR__ . '/../utils/pdo.php';
require_once __DIR__ . '/../utils/debug.php';

class OrderController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getOrders()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orders");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createOrder($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['user_id'], $data['product_id'], $data['quantity'], $data['total_price']]);
    }
    public function createOrderItem($data){
        $stmt = $this->pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?,?,?)");
        return $stmt->execute([$data['order_id'], $data['product_id'], $data['quantity']]);
    }
}
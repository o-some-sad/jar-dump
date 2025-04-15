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
        $stmt = $this->pdo->prepare("
        INSERT INTO orders 
        (user_id, total_price, status, room_no, ext, notes, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");
    
    return $stmt->execute([
        $data['user_id'],
        $data['total_price'],
        $data['status'] ?? 'processing',  
        $data['room_no'],
        $data['ext'] ?? 1,          
        $data['notes'] ?? "default notes"         
    ]);
    }
    public function createOrderItem($data) {
        $stmt = $this->pdo->prepare("INSERT INTO order_items (product_id, order_id, quantity) VALUES (?, ?, ?)");
        return $stmt->execute([
            $data['product_id'],  
            $data['order_id'],    
            $data['quantity']     
        ]);
    }
}
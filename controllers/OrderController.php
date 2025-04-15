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

    private function getLastOrderIdOfUser($userId){
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLatestOrderItems($userId): array {
        try {
            $latestOrder = $this->getLastOrderIdOfUser($userId);
            if (!$latestOrder) {
                return [];
            }
            
            
            $stmt = $this->pdo->prepare("
                SELECT p.*, oi.quantity 
                FROM products p
                JOIN order_items oi ON p.product_id = oi.product_id
                WHERE oi.order_id = ?
            ");
            $stmt->execute([$latestOrder['order_id']]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: [];
        } catch (Exception $e) {
            error_log("Error getting latest order items: " . $e->getMessage());
            return [];
        }
    }
}
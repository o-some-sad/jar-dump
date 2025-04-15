<?php
require_once "utils/pdo.php";

class OrderController{
    static public function getOrders(){
        try{
            $pdo = createPDO();
            $ordersStmt = $pdo->prepare("select * from orders order by created_at desc");
            // where deleted_at is null ?
            $ordersStmt->execute();
            $data = ($ordersStmt->fetchAll(PDO::FETCH_ASSOC));
            // print_r($data);
            $pdo = null;
            return $data;
        }
        catch(PDOException $e){
            dd($e);
        }
    }
    static public function getOrderByDate($DateFrom, $DateTo) {
        if ($DateFrom === '' || $DateTo === '') {
            throw new InvalidArgumentException("Dates cannot be empty");
        }
        try {
            $pdo = createPDO();
            $startDate = DateTime::createFromFormat('Y-m-d', $DateFrom)->format('Y-m-d 00:00:00');
            $endDate = DateTime::createFromFormat('Y-m-d', $DateTo)->format('Y-m-d 23:59:59'); 
            // dd($endDate);
            // echo $startDate;
            // echo $endDate;   
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE created_at BETWEEN ? AND ? order by created_at desc");
            $stmt->execute([$startDate, $endDate]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            dd($e);
        }
    }
    static public function getOrderContent($id){
        try{
            $pdo = createPDO();
            $displayOrderStmt = $pdo->prepare("
            select p.product_id, o.order_id, o.quantity,p.name,p.price, p.image
            from order_items o
            inner join products p
            on o.product_id = p.product_id
            where {$id} = o.order_id;");
            $displayOrderStmt->execute();
            $data = ($displayOrderStmt->fetchAll(PDO::FETCH_ASSOC));
            // print_r($data);
            $pdo = null;
            return $data;
        }
        catch(PDOException $e){
            dd($e);
        }
    }
    static public function deleteOrder($orderId){
        // SOFT-DELETE
        try{
            $pdo = createPDO();
            $deleteOrder = $pdo->prepare("
            update orders
            set deleted_at = now()
            where order_id = {$orderId}");
            // and status = 'processing'
            // and deleted_at IS NULL;
            // extra checks
            $deleteOrder->execute();
            $data = ($deleteOrder->fetchAll(PDO::FETCH_ASSOC));
            // print_r($data);
            $pdo = null;
            return $data;
            }
            catch(PDOException $e){
                dd($e);
            }
    }
}

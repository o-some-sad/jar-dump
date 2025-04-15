<?php
require_once "utils/pdo.php";

class OrderController{
    static public function getOrders(){
        try{
            $pdo = createPDO();
            $ordersStmt = $pdo->prepare("select * from orders where deleted_at is null");
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
    static public function getOrderByDate($DateFrom, $DateTo){
        try{
            $pdo = createPDO();
            $filterOrdersStmt = $pdo->prepare("
            select * 
            from orders 
            where created_at BETWEEN {$DateFrom} AND {$DateTo}
            ");
            $filterOrdersStmt->execute();
            $data = ($filterOrdersStmt->fetchAll(PDO::FETCH_ASSOC));
            // print_r($data);
            $pdo = null;
            return $data;
        }
        catch(PDOException $e){
            dd($e);
        }
    }
    static public function getOrderContent(){
        try{
            $pdo = createPDO();
            $displayOrderStmt = $pdo->prepare("
            select product_id, order_id, quantity from order_items o
            inner join products p
            on o.product_id = p.product_id
            where o.order_id = p.order_id;");
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
<?php
require_once "utils/pdo.php";

class OrderController{
    public function getOrders(){
        try{
            $pdo = createPDO();
            $ordersStmt = $pdo->prepare("select * from orders order by created_at desc");
            $ordersStmt->execute();
            $data = ($ordersStmt->fetchAll(PDO::FETCH_ASSOC));
            print_r($data);
            $pdo = null;
            return compact("data");
        }
        catch(PDOException $e){
            dd($e);
        }
    }
    public function getOrderByDate($DateFrom, $DateTo){
        try{
        $pdo = createPDO();
        $filterOrdersStmt = $pdo->prepare("
        select * 
        from orders 
        where created_at BETWEEN {$DateFrom} AND {$DateTo}
        ");
        $filterOrdersStmt->execute();
        $data = ($filterOrdersStmt->fetchAll(PDO::FETCH_ASSOC));
        print_r($data);
        $pdo = null;
        return compact("data");
        }
        catch(PDOException $e){
            dd($e);
        }
    }
    public function getOrderContent(){
        try{
        $pdo = createPDO();
        $displayOrderStmt = $pdo->prepare("
        select product_id, order_id, quantity from order_items o
        INNER JOIN products p
        ON o.product_id = p.product_id
        WHERE o.order_id = :order_id;");
        $displayOrderStmt->execute();
        $data = ($displayOrderStmt->fetchAll(PDO::FETCH_ASSOC));
        print_r($data);
        $pdo = null;
        return compact("data");
        }
        catch(PDOException $e){
            dd($e);
        }
    }
}
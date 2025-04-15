<?php
require_once __DIR__ . "/../controllers/OrderController.php";
require_once __DIR__ . "/../controllers/ProductController.php";
require_once __DIR__ . "/../utils/pdo.php";

$pdo = createPDO();
$orderController = new OrderController($pdo);
$productController = new ProductController($pdo);

// dd($_POST);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['selection']) && !empty($_POST['selection'])) {
        try {
            // Get user ID
            $userId = isset($_POST['user']) ? $_POST['user'] : null;
            if (!$userId) {
                throw new Exception("User ID is required");
            }
            
            // Calculate total price from all items
            $totalPrice = $_POST['total_price'];
            

            // Create the main order
            $orderData = [
                'user_id' => $userId,
                'total_price' => $totalPrice,
                'notes' => $_POST['notes'] ?? '',
                'room_no' => $_POST['room'] ?? ''
            ];
            
            // Insert the order and get the order ID
            $orderCreated = $orderController->createOrder($orderData);
            
            if (!$orderCreated) {
                throw new Exception("Failed to create order");
            }
            
            // Get the last inserted order ID
            $orderId = $pdo->lastInsertId();
            
            // Insert each order item
            foreach ($_POST['selection'] as $item) {
                if (isset($item['productName']) && isset($item['productCount'])) {
                    $orderItemData = [
                        'product_id' => $item['product_id'],
                        'order_id' => $orderId,
                        'quantity' => $item['productCount']
                    ];
                    
                    $orderController->createOrderItem($orderItemData);
                }
            }
            
            // Clear the selection from session
            $_SESSION['selection'] = [];
            
            // Set success message
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Order has been successfully created!'
            ];
            
        } catch (Exception $e) {
            // Set error message
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Error creating order: ' . $e->getMessage()
            ];
        }
    } else {
        // No items selected
        $_SESSION['flash'] = [
            'type' => 'warning',
            'message' => 'Please select at least one product for the order'
        ];
    }
    
    // Redirect back to the order page
    // if($_SESSION[])
    // redirect if admin
    // header('Location: /admin/order');
    //redirect if user
    header('Location: /');
    exit;
}

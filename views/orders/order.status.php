<?php
require_once __DIR__ . '/../../utils/pdo.php';
require_once __DIR__ . '/../../controllers/OrderController.php';
require_once __DIR__ . '/../../controllers/user.controller.php';

// Initialize PDO and controllers
$pdo = createPDO();
$orderController = new OrderController($pdo);
$userController = new UserController($pdo);

// Fetch all orders
$orders = $orderController->getOrders();

// Fetch order items and users
$orderItems = []; //list of lists
$users = []; //list of lists
foreach ($orders as $order) {
    $orderItems[$order['order_id']] = $orderController->getOrderItemsByOrderId($order['order_id']);
    $users[$order['order_id']] = $userController->getUserByOrderId($order['order_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .order-card {
            transition: all 0.3s ease;
        }
        .order-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .order-completed {
            opacity: 0.7;
        }
        .order-item {
            border-left: 3px solid #6c757d;
            padding-left: 10px;
            margin-bottom: 8px;
        }
        .status-badge {
            font-size: 0.85rem;
        }
        .status-processing { background-color: #ffc107; }
        .status-out_for_delivery { background-color: #17a2b8; }
        .status-done { background-color: #28a745; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="mb-4 text-center">Order List</h1>

        <?php if (empty($orders)): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <h3 class="mt-3">No orders found</h3>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="card order-card mb-4 <?php if ($order['status'] === 'done') echo 'order-completed'; ?>">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge status-badge <?php echo 'status-' . ($order['status'] ?? 'processing'); ?>">
                                <?php
                                switch ($order['status'] ?? 'processing') {
                                    case 'processing': echo 'Processing'; break;
                                    case 'out_for_delivery': echo 'Out for Delivery'; break;
                                    case 'done': echo 'Completed'; break;
                                    default: echo 'Unknown';
                                }
                                ?>
                            </span>
                            <strong class="ms-2">Order #<?php echo htmlspecialchars($order['order_id']); ?></strong>
                        </div>
                        <div>
                            <span class="fw-bold">Total: EGP <?php echo htmlspecialchars($order['total_price']); ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <h5 class="card-title">Customer Information</h5>
                                <p class="card-text">
                                    <i class="bi bi-person-circle me-2"></i>
                                    <?php echo htmlspecialchars($users[$order['order_id']]['name'] ?? 'Unknown User'); ?>
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-person-circle me-2"></i>
                                    <?php echo htmlspecialchars($users[$order['order_id']]['email'] ?? 'Unknown User'); ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="card-title">Order Items</h5>
                                <div class="order-items-list">
                                    <?php if (empty($orderItems[$order['order_id']])): ?>
                                        <div class="text-muted">No items found for this order</div>
                                    <?php else: ?>
                                        <?php foreach ($orderItems[$order['order_id']] as $item): ?>
                                            <div class="order-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span><?php echo htmlspecialchars($item['name']); ?></span>
                                                    <span class="text-muted ms-2">x<?php echo htmlspecialchars($item['quantity']); ?></span>
                                                </div>
                                                <span>EGP <?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="card-title">Actions</h5>
                                <form method="POST" action="/admin/orders/status">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                    
                                    <?php if ($order['status'] === 'processing'): ?>
                                        <input type="hidden" name="status" value="out_for_delivery">
                                        <button type="submit" class="btn btn-info text-white w-100">
                                            <i class="bi bi-truck me-1"></i> Deliver
                                        </button>
                                    <?php elseif ($order['status'] === 'out_for_delivery'): ?>
                                        <input type="hidden" name="status" value="done">
                                        <button type="submit" class="btn btn-success text-white w-100">
                                            <i class="bi bi-check-circle me-1"></i> Complete
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-secondary w-100" disabled>
                                            <i class="bi bi-check-circle-fill me-1"></i> Completed
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
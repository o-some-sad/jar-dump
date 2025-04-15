<?php
require_once __DIR__ . '/../../components/adminLayout.php';
require_once __DIR__ . '/../../utils/pdo.php';
require_once __DIR__ . '/../../controllers/ProductController.php';
require_once __DIR__ . '/../../controllers/user.controller.php';



$controller = new ProductController($pdo);
$products = $controller->getProducts();
foreach ($products as &$product) {
    $product['count'] = 1; // Initialize count for each product
}
$reqVars = [];
// Initialize selection in session if not exists
if (!isset($_SESSION['selection'])) {
    $_SESSION['selection'] = [];
}

// Handle adding to selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $productId = $_POST['add_product'];
    $product = $controller->getProductById($productId);

    if ($product) {
        $_SESSION['selection'][] = $product;
    }
    header('Location: /admin/order');
    exit;
}

// Handle removing from selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product'])) {
    $index = $_POST['remove_product'];
    if (isset($_SESSION['selection'][$index])) {
        unset($_SESSION['selection'][$index]);
        $_SESSION['selection'] = array_values($_SESSION['selection']); // Reindex array
    }
    header('Location: /admin/order');
    exit;
}

// Handle quantity increment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['increment_quantity'])) {
    $index = $_POST['increment_quantity'];
    if (isset($_SESSION['selection'][$index])) {
        $stock = $_SESSION['selection'][$index]['quantity']; // Stock available
        $currentCount = $_SESSION['selection'][$index]['count'] ?? 1;

        if ($currentCount < $stock) {
            $_SESSION['selection'][$index]['count'] = $currentCount + 1;
        }

    }
    header('Location: /admin/order');
    exit;
}

// Handle quantity decrement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['decrement_quantity'])) {
    $index = $_POST['decrement_quantity'];
    if (isset($_SESSION['selection'][$index]) && ($_SESSION['selection'][$index]['count'] ?? 1) > 1) {
        $_SESSION['selection'][$index]['count']--;
    }
    header('Location: /admin/order');
    exit;
}

// Update total price calculation
$totalPrice = 0;
foreach ($_SESSION['selection'] as $product) {
    $count = $product['count'] ?? 1;
    $totalPrice += $product['price'] * $count;
}

$users = UserController::getAllUsers(0, 10, true)['data'];
?>

<!DOCTYPE html>
<html lang="en">

<head>


    <meta charset="UTF-8">
    <title>Drink Order Template</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .cup {
            width: 70px;
            height: 90px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 8px;
            font-size: 14px;
            cursor: pointer;
        }

        .selected {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .selection-item:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body class="p-4">
    <div class="container">
        <div class="row">
            <!-- Left Section -->
            <div class="col-md-5">
                <h2 class="mb-4">Order Details</h2>
                <div class="mb-3" id="selection-container">
                    <?php foreach ($_SESSION['selection'] as $index => $product): ?>
                        <div class="d-flex align-items-center mb-2 p-2 selection-item">
                            <!-- Add hidden inputs for each selected product -->


                            <span class="me-2 flex-grow-1"><?= htmlspecialchars($product['name']) ?></span>
                            <span
                                class="badge bg-secondary me-2"><?= htmlspecialchars(number_format($product['price'], 2)) ?>
                                EGP</span>

                            <!-- Count Controls -->
                            <div class="d-flex align-items-center me-2">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="decrement_quantity" value="<?= $index ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary"
                                        class="decrement-button">-</button>
                                </form>
                                <span class="mx-2"><?= $product['count'] ?? 1 ?></span>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="increment_quantity" value="<?= $index ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" <?= ($product['count'] ?? 1) >= $product['quantity'] ? 'disabled' : '' ?>>+</button>
                                </form>
                            </div>


                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="remove_product" value="<?= $index ?>">
                                <button type="submit" class="btn btn-sm btn-danger">×</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>

                <form method="POST" action="/admin/order/store" class="mb-4">
                    <!-- Notes -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" id="notes" rows="2"
                            placeholder="e.g., 1 Tea Extra Sugar"></textarea>
                    </div>

                    <!-- Room Selection -->
                    <div class="mb-3">
                        <label for="room" class="form-label">Room</label>
                        <select class="form-select" name="room" id="room">
                            <option value="room1">Room 1</option>
                            <option value="room2">Room 2</option>
                        </select>
                    </div>

                    <!-- Total -->
                    <div class="mb-3 fw-bold fs-5" id="total-price">
                        EGP <?= number_format($totalPrice, 2) ?>
                    </div>
                    <input type="hidden" name="total_price" value="<?= htmlspecialchars($totalPrice) ?>">
                    <!-- Send selection data as JSON -->
                    <input type="hidden" name="selection"
                        value="<?= htmlspecialchars(json_encode($_SESSION['selection'])) ?>">

                    <!-- Confirm -->
                    <button type="submit" name="confirm_order" class="btn btn-primary">Confirm</button>

            </div>

            <!-- Right Section -->
            <div class="col-md-7">
                <!-- User Selection -->
                <div class="mb-3">
                    <label for="user" class="form-label">Add to user</label>
                    <select class="form-select" name="user" id="user">
                        <?php foreach ($users as $user): ?>
                            <option value="<?= htmlspecialchars($user['user_id']) ?>">
                                <?= htmlspecialchars($user['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                </form>

                <!-- Cups / Drinks -->
                <div class="d-flex flex-wrap gap-3 justify-content-start">
                    <?php foreach ($products as $product):
                        $isSelected = in_array($product, $_SESSION['selection']);
                        ?>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="add_product" value="<?= htmlspecialchars($product['product_id']) ?>">
                            <button type="submit" class="cup shadow-sm p-2 <?= $isSelected ? 'selected' : '' ?>"
                                style="border: none;">
                                <span class="product-name fw-bold"><?= htmlspecialchars($product['name']) ?></span>
                                <span class="product-price text-muted">EGP
                                    <?= htmlspecialchars(number_format($product['price'], 2)) ?></span>
                            </button>
                        </form>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
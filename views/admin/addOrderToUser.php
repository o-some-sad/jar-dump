<?php
require_once __DIR__ . "/../../controllers/ProductController.php";
require_once __DIR__ . "/../../controllers/OrderController.php";
require_once __DIR__ . "/../../utils/pdo.php";
$pdo = createPDO(); 
$productController = new ProductController($pdo);
$orderController = new OrderController($pdo);
$products = $productController->getProducts();

// $user = $_SESSION['user'];
// dd($user['name']);
// $latestOrders = $orderController->getLatestOrderItems($user['user_id']);
// dd($latestOrders);
// Initialize session data if needed
if (!isset($_SESSION['selection'])) {
    $_SESSION['selection'] = [];
}

// Handle form submission for order creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    // Order processing logic would go here
    // This would only run when the form is actually submitted
}

$flashMessage = $_SESSION['flash'] ?? null;
if ($flashMessage) {
    // Clear the flash message after displaying
    unset($_SESSION['flash']);
}


require_once __DIR__ . "/../../controllers/user.controller.php";
$users = UserController::getAllUsers(0, 10, true)['data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Order System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
            transition: all 0.2s;
        }
        .cup:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .selected {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .selection-item:hover {
            background-color: #f8f9fa;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="p-4">
    <div class="container" x-data="orderApp()">
       
        <?php if ($flashMessage): ?>
            <div class="alert alert-<?= $flashMessage['type'] ?> alert-dismissible fade show" role="alert">
                <?= $flashMessage['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
    <!-- Left Section - Order Details -->
    <div class="col-md-5">
        <h2 class="mb-4">Order Details</h2>

        <!-- Selected Products -->
        <div class="mb-3" id="selection-container">
            <template x-for="(item, index) in selection" :key="index">
                <div class="d-flex align-items-center mb-2 p-2 selection-item border rounded">
                    <span class="me-2 flex-grow-1" x-text="item.name"></span>
                    <span class="badge bg-secondary me-2" x-text="`EGP ${item.price.toFixed(2)}`"></span>

                    <!-- Quantity Controls -->
                    <div class="d-flex align-items-center me-2">
                        <button @click="decrementQuantity(index)" 
                                class="btn btn-sm btn-outline-secondary"
                                :disabled="item.count <= 1">-</button>
                        <span class="mx-2" x-text="item.count"></span>
                        <button @click="incrementQuantity(index)" 
                                class="btn btn-sm btn-outline-secondary"
                                :disabled="item.count >= item.quantity">+</button>
                    </div>

                    <button @click="removeItem(index)" class="btn btn-sm btn-danger">×</button>
                </div>
            </template>

            <!-- Empty selection message -->
            <div class="alert alert-light text-center" x-show="selection.length === 0">
                No products selected. Please select products from the right panel.
            </div>
        </div>

        <form method="POST" action="/user/order/store" id="orderForm" @submit="prepareSubmission">
            <!-- Notes -->
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" name="notes" id="notes" rows="2" 
                          placeholder="e.g., 1 Tea Extra Sugar" x-model="notes"></textarea>
            </div>

            <!-- Room Selection -->
            <div class="mb-3">
                <label for="room" class="form-label">Room</label>
                <select class="form-select" name="room" id="room" x-model="room">
                    <option value="1">Room 1</option>
                    <option value="2">Room 2</option>
                </select>
            </div>

            <!-- Total -->
            <div class="mb-3 fw-bold fs-5" id="total-price">
                EGP <span x-text="totalPrice.toFixed(2)"></span>
            </div>

            <!-- Hidden fields for form submission -->
            <input type="hidden" name="total_price" x-bind:value="totalPrice">
            <input type="hidden" name="selection" x-bind:value="JSON.stringify(selection)">
            <input type="hidden" name="user" x-bind:value="selectedUser">

            <!-- Confirm Button -->
            <button type="submit" name="confirm_order" class="btn btn-primary" 
                    :disabled="selection.length === 0">
                Confirm Order
            </button>
        </form>
    </div>

    <!-- Right Section - User Dropdown and Products -->
    <div class="col-md-7">
        <div class="mb-3">
            <label for="user" class="form-label">Add to user</label>
            <select class="form-select" name="user" id="user" x-model="selectedUser">
                <?php foreach ($users as $user): ?>
                    <option value="<?= htmlspecialchars($user['user_id']) ?>">
                        <?= htmlspecialchars($user['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <h2 class="mb-4">Available Products</h2>
        
        <!-- Products Grid -->
        <div class="d-flex flex-wrap gap-3 justify-content-start">
            <?php foreach ($products as $index => $product): ?>
                <div class="cup shadow-sm p-2" 
                     :class="{ 'selected': isSelected(<?= $index ?>) }"
                     @click="toggleProduct(<?= htmlspecialchars(json_encode($product)) ?>)">
                    <span class="product-name fw-bold"><?= htmlspecialchars($product['name']) ?></span>
                    <span class="product-price text-muted">EGP <?= htmlspecialchars(number_format($product['price'], 2)) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
 <!-- #region-->
    </div>

    <script>
        function orderApp() {
            return {
                
                products: <?= json_encode($products) ?>, //all the products
                selection: [], //selected products
                notes: '', //for the entire order
                room: '1', //just an initial val
                selectedUser: '<?= $user['user_id'] ?? '' ?>', //REMOVE and replace with last order
                
                
                get totalPrice() {
                let total = 0;
                for (let i = 0; i < this.selection.length; i++) {
                    const item = this.selection[i]; 
                    const itemPrice = item.price; 
                    const itemCount = item.count; 
                    const itemTotal = itemPrice * itemCount; 
                    total = total + itemTotal;
                }
            return total;
        },
                
                // Methods
                toggleProduct(product) {
                    // Check if product is already in selection
                    const index = this.selection.findIndex(item => item.product_id === product.product_id);
                    
                    if (index === -1) {
                        // Add product with count = 1
                        product.count = 1;
                        this.selection.push(product);
                    } else {
                        // Remove product if already selected
                        this.selection.splice(index, 1);
                    }
                },
                
                isSelected(productIndex) {
                    const product = this.products[productIndex];
                    return this.selection.some(item => item.product_id === product.product_id);
                },
                
                incrementQuantity(index) {
                    if (this.selection[index].count < this.selection[index].quantity) {
                        this.selection[index].count++;
                    }
                },
                
                decrementQuantity(index) {
                    if (this.selection[index].count > 1) {
                        this.selection[index].count--;
                    }
                },
                
                removeItem(index) {
                    this.selection.splice(index, 1);
                },
                
                prepareSubmission(e) {
                    // Prevent submission if no products selected
                    if (this.selection.length === 0) {
                        e.preventDefault();
                        alert('Please select at least one product');
                        return false;
                    }
                    
                    // Otherwise let the form submit normally
                    return true;
                }
            };
        }
    </script>
    
    <!-- Bootstrap JS for alert dismissal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
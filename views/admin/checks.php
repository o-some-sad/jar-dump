<?php
require_once __DIR__ . "/../../controllers/ProductController.php";
require_once __DIR__ . "/../../controllers/OrderController.php";
require_once __DIR__ . "/../../utils/pdo.php";
require_once "components/adminLayoutRestored.php";

$orderController = new OrderController($pdo);
$orders = $orderController->getOrders();

if (!isset($_SESSION['checks_user_id'])) {
    $_SESSION['checks_user_id'] = 0; // dummy

}
require_once __DIR__ . "/../../controllers/user.controller.php";
$users = UserController::getAllUsers(0, 10, true)['data'];
foreach ($users as &$user) {
    $user['total_amount'] = 0;
    foreach ($orders as $order) {
        if ($order['user_id'] == $user['user_id']) {
            $user['total_amount'] += $order['total_price'];
            $user['orders'][] = $order; // Store the user's orders
            $user['orders'] = array_map(function ($order) use ($orderController) {
                $order['items'] = $orderController->getOrderItemsByOrderId($order['order_id']);
                return $order;
            }, $user['orders']);

        }
    }
    if (!isset($user['orders'])) {
        $user['orders'] = []; // Initialize orders if none found
    }
}
unset($user); // Clean up reference
?>

<?php adminLayout_open("Checks") ?>

    <div class="container mt-5">
        <h2>Checks</h2>
    <div x-data="userSearch()" class="mb-4">
    <input type="search" list="users" placeholder="Search for a user.." class="form-control mb-3" x-model="selectedName">

    <datalist id="users">
        <?php foreach ($users as $user): ?>
            <option value="<?php echo htmlspecialchars($user['name']); ?>"></option>
        <?php endforeach; ?>
    </datalist>
    <table class="table table-bordered">
    <thead>
        <tr>
        <th class="w-50">Name</th>
<th class="w-50">Total Amount</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <template x-if="selectedName === '' || selectedName === '<?= htmlspecialchars($user['name']) ?>'">
                <tr>
                    <td colspan="2" class="p-0 border-0">
                        <table class="table m-0">
                            <tbody x-data="{ userOrderTableShown: false }">
                                <tr>
                                    <td class="w-50">
                                    <button class="btn btn-sm" @click="userOrderTableShown = !userOrderTableShown" x-text="userOrderTableShown ? '−' : '+'"></button>
                                    <?= htmlspecialchars($user['name']) ?>
                                    </td>
                                    <td class="w-50"><?= htmlspecialchars($user['total_amount'] ?? '0') ?></td>
                                </tr>
                                <tr x-show="userOrderTableShown" x-transition style="display: none;">
                                    <td colspan="2">
                                        <div class="p-2 bg-light border">
                                            <strong>Orders for <?= htmlspecialchars($user['name']) ?>:</strong>
                                            <?php if (empty($user['orders'])): ?>
                                                <p>No orders found.</p>
                                            <?php else : ?>
                                            <table class="table table-sm mt-2">
                                                <thead>
                                                    <tr>
                                                        <th>Order Date</th>
                                                        <th>Total Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($user['orders'] as $order): ?>
                                                        <tbody x-data="{ showItems: false }">
                                                            <tr>
                                                                <td>
                                                                    <button class="btn btn-sm" @click="showItems = !showItems" x-text="showItems ? '−' : '+'">+</button>
                                                                    <?= htmlspecialchars($order['created_at']) ?>
                                                                </td>
                                                                <td><?= htmlspecialchars($order['total_price']) ?></td>
                                                            </tr>
                                                            <tr x-show="showItems" x-transition style="display: none;">
                                                                <td colspan="2">
                                                                    <div class="row row-cols-1 row-cols-md-4 g-4">
                                                                        <?php foreach ($order['items'] as $item):  ?>
                                                                     
                                                                            <div class="col">
                                                                                <div class="card h-100">
                                                                                    <img src="<?= empty($item['image']) ? '/static/no-image.png' : htmlspecialchars($item['image']) ?>" 
                                                                                         class="card-img-top" 
                                                                                         alt="<?= htmlspecialchars($item['name']) ?>"
                                                                                         style="height: 150px; object-fit: cover;">
                                                                                    <div class="card-body">
                                                                                        <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                                                                                        <p class="card-text">
                                                                                            Quantity: <?= htmlspecialchars($item['quantity']) ?><br>
                                                                                            Price: EGP <?= htmlspecialchars(number_format($item['price'], 2)) ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </template>
        <?php endforeach; ?>
    </tbody>
</table>

    </div>
    <script>
    function userSearch() {
        return {
            selectedName: ''
        }
    }
</script>

<?php adminLayout_close() ?>
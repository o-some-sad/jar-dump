<?php
    require_once "controllers/order.controller.php";
    $tableData = OrderController::getOrders();
    require_once "controllers/order.controller.php";
    
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['cancelbtn'])) {
            $id = $_POST['id'];
            OrderController::deleteOrder($id);
            header("Location: /orderHistory");
            exit(); 
        }
    }   
    $selectedOrderId = $_GET['viewid'] ?? null;
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        if(isset($_GET['dateFrom']) && isset($_GET['dateTo'])) {
            try {
                $tableData = OrderController::getOrderByDate($_GET['dateFrom'], $_GET['dateTo']);
            } catch (Exception $e) {
                error_log("Date conversion error: " . $e->getMessage());
            }
        }
    }
    
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Order History</title>
</head>
<body>
<br><h1 class="font-mono text-4xl text-center">Order History</h1><br><br>

<div class="flex contents-center justify-center mb-8">
  <form id="filterForm" action="/orderHistory">
  <div class="flex contents-center gap-8 justify-center mb-8 text-center">
  <div class="flex-1">
    <label for="dateFromInput" class="block text-sm font-medium text-blue-700 mb-1">Date From</label>
    <input 
      type="date" 
      id="dateFromInput" 
      name="dateFrom"
      class="w-full px-4 py-2 rounded-lg border border-gray-300"
    >
  </div>
  <div class="flex-1">
    <label for="dateToInput" class="block text-sm font-medium text-blue-700 mb-1">Date To</label>
    <input 
      type="date" 
      id="dateToInput" 
      name="dateTo"
      class="w-full px-4 py-2 rounded-lg border border-gray-300"
    >
  </div>
</div> 
<div class="flex contents-center justify-center">
  <button
    type="submit" 
    id="applyDate" 
    class="rounded-lg border border-blue-600 px-12 py-2 text-sm font-medium text-blue-600 
           transition-all duration-300 ease-in-out
           hover:bg-blue-800 hover:text-white hover:scale-105 hover:shadow-md
           active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
  >
    Filter
  </button>
</div>
  </form>
</div>

<div class="max-h-46 overflow-x-auto">
  <?php if(!empty($tableData)): ?>
  <table class="min-w-full divide-y-2 divide-gray-200">
    <thead class="text-2xl text-center top-0 bg-white ltr:text-left rtl:text-right">
      <tr class="*:font-medium *:text-gray-900">
        <th class="px-3 py-2 whitespace-nowrap">Order Date</th>
        <th class="px-3 py-2 whitespace-nowrap">Status</th>
        <th class="px-3 py-2 whitespace-nowrap">Amount</th>
        <th class="px-3 py-2 whitespace-nowrap">Details</th>
        <th class="px-3 py-2 whitespace-nowrap">Actions</th>
      </tr>
    </thead>

    <tbody class="divide-y divide-gray-200">
    <?php foreach ($tableData as $orderInfo): ?>
        <tr class="text-gray-900 font-medium hover:bg-gray-200 font-light text-center">
            <td class="px-3 py-2 whitespace-nowrap flex flex-row gap-8 contents-center justify-center"><span> <?php 
            try {
                echo (new DateTimeImmutable($orderInfo['created_at']))->format('M j,Y');
            } catch (Exception $e) {
                echo 'Invalid date';
            }
            ?></td>
            <td class="px-3 py-2 whitespace-nowrap"><?= ucfirst($orderInfo['status']) ?></td>
            <td class="px-3 py-2 whitespace-nowrap">$<?= number_format($orderInfo['total_price'], 2)  ?></td>
            <td>
              <form action="/orderHistory">
                <input type="hidden" name="viewid" value="<?= $orderInfo['order_id'] ?>">
                <button name="viewbtn" type="submit"
                    class="group relative inline-block text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-green-300/50">
                    <span class="absolute inset-0 border-2 border-gray-300/90 group-hover:border-blue-400"></span>
                    <span class="hover:scale-105 transition-all duration-300 ease-in-out text-md block border-2 border-gray-300 bg-gradient-to-br from-blue-500 to-blue-600 px-8 py-2 transition-all duration-200 group-hover:-translate-x-0.5 group-hover:-translate-y-0.5 group-hover:bg-gradient-to-br group-hover:from-blue-600 group-hover:to-blue-500 group-active:translate-x-0 group-active:translate-y-0">
                        <?= ($selectedOrderId == $orderInfo['order_id']) ? '-' : '+' ?>
                    </span>
                </button>
              </form>
            </td>
            <?php if ($orderInfo['deleted_at'] !== null) : ?>
              <td>
                <h3 class="text-gray-500 font-bold">Cancelled</h3>
              </td>
            <?php elseif ($orderInfo['status'] === 'processing') : ?>
            <td class="px-3 py-2 whitespace-nowrap">
            <form action="/orderHistory" method="POST">
              <input type="hidden" name="id" value="<?= $orderInfo['order_id'] ?>">
              <button
                  class="group relative inline-block text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-red-300/50"
                  name="cancelbtn"
                  onclick="return confirm('Are you sure you want to cancel this order ?')">
                  <span class="absolute inset-0 border-2 border-gray-300/90 group-hover:border-red-400"></span>
                  <span class="hover:scale-105 transition-all duration-300 ease-in-out block border-2 border-gray-300 bg-gradient-to-br from-red-500 to-red-600 px-8 py-2 text-xs transition-all duration-200 group-hover:-translate-x-0.5 group-hover:-translate-y-0.5 group-hover:bg-gradient-to-br group-hover:from-red-600 group-hover:to-red-500 group-active:translate-x-0 group-active:translate-y-0">
                      Cancel</span>
              </button>
            </form>
            </td>
            <?php else: ?>
            <td class="px-3 py-2 whitespace-nowrap">
            <!-- <a class="cursor-not-allowed opacity-50 pointer-events-none group relative inline-block text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-red-300/50">
                <span class="absolute inset-0 border-2 border-gray-300/90 group-hover:border-red-400"></span>
                <span class="block border-2 border-gray-300 bg-gradient-to-br from-red-500 to-red-600 px-8 py-2 text-xs transition-all duration-200 group-hover:-translate-x-0.5 group-hover:-translate-y-0.5 group-hover:bg-gradient-to-br group-hover:from-red-600 group-hover:to-red-500 group-active:translate-x-0 group-active:translate-y-0">
                Cancel</span>
            </a> -->
            </td>
            <?php endif; ?>
        </tr>
        
        <?php if ($selectedOrderId == $orderInfo['order_id']): ?>
          <?php
$orderContent = OrderController::getOrderContent($selectedOrderId);
?>
<tr>
  <td colspan="5" class="px-0 py-0">
  <div class="px-4 py-2 bg-gray-50">
  <?php if (!empty($orderContent)): ?>
    <div class="overflow-y-auto max-h-64">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-200 text-xs uppercase text-gray-600">
        <tr>
            <!-- <th class="px-4 py-2">Product ID</th> -->
            <th class="px-4 py-2">Product Image</th>
            <th class="px-4 py-2">Product Name</th>
            <th class="px-4 py-2">Quantity</th>
            <th class="px-4 py-2">Unit Price</th>
            <th class="px-4 py-2">Total</th>
        </tr>
      </thead>
      <tbody class="bg-white text-sm">
        <?php foreach ($orderContent as $content): ?>
            <tr class="hover:bg-gray-100">
                <!-- <td class="px-4 py-2"><?= $content['product_id'] ?></td> -->
                <td class="px-4 py-2">
                <img src="<?= $content['image'] ?>" alt="<?= $content['image'] ?>" class="w-16 h-16 object-contain"></td>                
                <td class="px-4 py-2"><?= $content['name'] ?></td>
                <td class="px-4 py-2"><?= $content['quantity'] ?></td>
                <td class="px-4 py-2">$<?= number_format($content['price'], 2) ?></td>
                <td class="px-4 py-2">$<?= number_format($content['price'] * $content['quantity'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
        </div>
  <?php else: ?>
    <div class="text-md text-gray-600 font-bold text-center py-4">
      No orders found.
    </div>
  <?php endif; ?>
  </div>
  </td>
</tr>
    <?php endif; ?>
<?php endforeach; ?>
</tbody>
</table>
  <?php else : 
    echo
    '<br><br><div class="text-3xl mb-8 py-8 font-serif text-center text-red-700 font-bold animate-[pulse_1.5s_ease-in-out_infinite]">
        No orders found for the selected date range.
    </div>';
  ?>  
  <?php endif ?>
</div><br><br><br><br><br><br>
</body>
</html>
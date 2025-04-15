<?php
require_once "components/adminLayoutRestored.php";
require_once "controllers/user.controller.php";
require_once "controllers/ProductController.php";

try {
    $totalUsers = UserController::getUsersCount();
    $totalProducts = ProductController::getProductsCount();
} catch (Exception $e) {
    dd($e);
}



?>


<?= adminLayout_open("Admin Dashboard") ?>

<section class="flex-grow-1 p-3">
    <h1>Admin Dashboard</h1>
    <section class="row row-cols-4 justify-content-start justify-content-lg-center gap-3">
        <div class="card col-12 col-md-4 col-lg-3">
            <div class="card-body">
                <h5 class="card-title text-end"> <i class="fas fa-users me-1"></i> Users</h5>
                <h1 class="card-text"><?= $totalUsers ?></h1>
                <div class="d-flex align-items-center justify-content-end">
                    <a href="/admin/users/create" class="btn btn-link">
                        <i class="fas fa-plus me-1"></i>
                    </a>
                    <a href="/admin/users" class="btn btn-link">
                        <i class="fas fa-arrow-right me-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card col-12 col-md-4 col-lg-3">
            <div class="card-body">
                <h5 class="card-title text-end"> <i class="fas fa-list me-1"></i> Products</h5>
                <h1 class="card-text"><?= $totalProducts ?></h1>
                <div class="d-flex align-items-center justify-content-end">
                    <a href="/admin/products/create" class="btn btn-link">
                        <i class="fas fa-plus me-1"></i>
                    </a>
                    <a href="/admin/products" class="btn btn-link">
                        <i class="fas fa-arrow-right me-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card col-12 col-md-4 col-lg-3">
            <?php /* TODO: please implement orders stats here */ ?>
            <div class="card-body">
                <h5 class="card-title text-end"> <i class="fa-solid fa-cart-shopping"></i> Orders</h5>
                <h1 class="card-text"><?= $totalProducts ?></h1>
                <div class="d-flex align-items-center justify-content-end">
                    <a href="/admin/orders/create" class="btn btn-link">
                        <i class="fas fa-plus me-1"></i>
                    </a>
                    <a href="/admin/orders" class="btn btn-link">
                        <i class="fas fa-arrow-right me-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
</section>

<?= adminLayout_close() ?>
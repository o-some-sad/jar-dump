<?php require_once __DIR__ . '/../../components/adminLayout.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between mb-4">
        <h2>Product Management</h2>
        <a href="/admin/products/create" class="btn btn-primary">Add Product</a>
    </div>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
            <?= $_SESSION['flash']['message'] ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['product_id'] ?></td>
                <td>
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>" 
                         class="img-thumbnail" style="max-width: 50px">
                </td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td>$<?= number_format($product['price'], 2) ?></td>
                <td><?= $product['quantity'] ?></td>
                <td>
                    <a href="/admin/products/edit/<?= $product['product_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="/admin/products/delete/<?= $product['product_id'] ?>" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>        
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
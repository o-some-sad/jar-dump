<?php
require_once __DIR__ . '/../../controllers/ProductController.php';
require_once __DIR__ . '/../../utils/pdo.php';

$pdo = createPDO();
$controller = new ProductController($pdo);
$categories = $controller->getAllCategories();

if (empty($categories)) {
    $_SESSION['flash'] = [
        'type' => 'danger',
        'message' => 'No categories found. Please create categories first.'
    ];
    header('Location: /admin/products');
    exit;
}

require_once __DIR__ . '/../../components/adminLayout.php';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between mb-4">
        <h2>Create New Product</h2>
        <a href="/admin/products" class="btn btn-secondary">Back to Products</a>
    </div>

    <form action="/admin/products/store" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="action" value="create">
        
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <option value="">Select a category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['category_id']) ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <div class="form-text">Supported formats: JPG, JPEG, PNG, GIF</div>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

        <button type="submit" class="btn btn-primary">Create Product</button>
    </form>
</div>

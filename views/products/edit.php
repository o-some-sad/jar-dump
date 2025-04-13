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

if (!$product) {
    $_SESSION['flash'] = [
        'type' => 'danger',
        'message' => 'Product not found'
    ];
    header('Location: /admin/products');
    exit;
}
?>

<div class="container py-4">
    <div class="d-flex justify-content-between mb-4">
        <h2>Edit Product</h2>
        <a href="/admin/products" class="btn btn-secondary">Back to Products</a>
    </div>

    <form action="/admin/products/update" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?= htmlspecialchars($product['product_id']) ?>">
        
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" 
                   value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['category_id'] ?>" 
                            <?= $category['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" 
                   value="<?= htmlspecialchars($product['price']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" 
                   value="<?= htmlspecialchars($product['quantity']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"
            ><?= htmlspecialchars($product['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <?php if (!empty($product['image_path'])): ?>
                <div class="mb-2">
                    <img src="<?= htmlspecialchars($product['image_path']) ?>" 
                         alt="Current product image" class="img-thumbnail" style="max-width: 200px">
                </div>
            <?php endif; ?>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <div class="form-text">Leave empty to keep current image</div>
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="/admin/products" class="btn btn-secondary">Cancel</a>
    </form>
</div>

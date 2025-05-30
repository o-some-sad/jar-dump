<?php
require_once __DIR__ . '/../../controllers/ProductController.php';
require_once __DIR__ . '/../../utils/pdo.php';
require_once __DIR__ . '/../../utils/validationHelper.php';
require_once __DIR__ . '/../../controllers/CategoryController.php';

$pdo = createPDO();
$controller = new ProductController($pdo);
$categories = (new CategoryController($pdo))->getAllCategories();

if (empty($categories)) {
    $_SESSION['flash'] = [
        'type' => 'danger',
        'message' => 'No categories found. Please create categories first.'
    ];
    header('Location: /admin/products');
    exit;
}

// Get any validation errors and values from session
$errors = $_SESSION['validation_errors'] ?? [];
$values = $_SESSION['validation_values'] ?? [];

// Clear validation data from session
unset($_SESSION['validation_errors']);
unset($_SESSION['validation_values']);

// Get categories for dropdown
$categories = $controller->getAllCategories();

require_once __DIR__ . '/../../components/adminLayoutRestored.php';
?>

<?= adminLayout_open("Create Product") ?>

<div class="container py-4">
    <div class="d-flex justify-content-between mb-4">
        <h2>Create New Product</h2>
        <a href="/admin/products" class="btn btn-secondary">Back to Products</a>
    </div>

    <!-- Show validation errors if any -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $field => $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="/admin/products/store" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="action" value="create">

        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text"
                class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                id="name"
                name="name"
                value="<?= htmlspecialchars($values['name'] ?? '') ?>"
                required>
            <?php if (isset($errors['name'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['name']) ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <div class='d-flex'>
                <select class="form-select <?= isset($errors['category_id']) ? 'is-invalid' : '' ?>"
                    id="category_id"
                    name="category_id"
                    required>
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['category_id']) ?>"
                            <?= ($values['category_id'] ?? '') == $category['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="btn  add-category-link bg-primary text-white" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-circle">+</i>
                </button>
                <?php if (isset($errors['category_id'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['category_id']) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number"
                step="0.01"
                class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>"
                id="price"
                name="price"
                value="<?= htmlspecialchars($values['price'] ?? '') ?>"
                required>
            <?php if (isset($errors['price'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['price']) ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number"
                class="form-control <?= isset($errors['quantity']) ? 'is-invalid' : '' ?>"
                id="quantity"
                name="quantity"
                value="<?= htmlspecialchars($values['quantity'] ?? '') ?>"
                required>
            <?php if (isset($errors['quantity'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['quantity']) ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control"
                id="description"
                name="description"
                rows="3"><?= htmlspecialchars($values['description'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file"
                class="form-control <?= isset($errors['image']) ? 'is-invalid' : '' ?>"
                id="image"
                name="image"
                accept="image/*">
            <div class="form-text">Supported formats: JPG, JPEG, PNG, GIF</div>
            <?php if (isset($errors['image'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['image']) ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select <?= isset($errors['status']) ? 'is-invalid' : '' ?>"
                id="status"
                name="status"
                required>
                <option value="active" <?= ($values['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($values['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
            <?php if (isset($errors['status'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['status']) ?></div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Create Product</button>
    </form>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="categoryForm" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('categoryForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        try {
            const categoryName = document.getElementById('categoryName').value.trim();
            if (!categoryName) {
                throw new Error('Please enter a category name');
            }

            const response = await fetch('/admin/categories/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: categoryName
                })
            });

            // First check if the response is ok
            if (!response.ok) {
                const text = await response.text();
                console.error('Server response:', text);
                throw new Error('Server returned an error');
            }

            // Try to parse the JSON
            let data;
            try {
                data = await response.json();
            } catch (err) {
                console.error('JSON parse error:', err);
                throw new Error('Invalid response format from server');
            }

            // Add new option to select
            const select = document.getElementById('category_id');
            const option = new Option(categoryName, data.category_id);
            select.add(option);
            select.value = data.category_id;

            // Close modal and reset form
            const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
            modal.hide();
            document.getElementById('categoryForm').reset();

            // Show success message
            showAlert('success', 'Category created successfully');

        } catch (error) {
            console.error('Error:', error);
            showAlert('danger', error.message);
        }
    });

    // Helper function to show alerts
    function showAlert(type, message) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
        document.querySelector('.container').insertBefore(alert, document.querySelector('.container').firstChild);
    }
</script>


<?= adminLayout_close() ?>
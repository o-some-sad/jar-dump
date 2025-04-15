<?php
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../middlewares/auth.php';
require_once __DIR__ . '/../middlewares/auth_helpers.php';
require_once __DIR__ . '/../utils/pdo.php';
require_once __DIR__ . '/../utils/productValidator.php';
require_once __DIR__ . '/../utils/validationHelper.php';

// Ensure admin access
// requireAdmin();

try {
    $pdo = createPDO();
    $controller = new ProductController($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        [$isValid, $result] = validateProduct($_POST, $_FILES);

        if (!$isValid) {
            redirectWithValidationResult($_POST, $result, '/admin/products/create');
            exit;
        }

        try {
            $product = $controller->createProduct($result);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Product created successfully'
            ];
            
            header('Location: /admin/products');
            exit;

        } catch (Exception $e) {
            error_log("Product creation error: " . $e->getMessage());
            
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Error creating product: ' . $e->getMessage()
            ];
            
            redirectWithValidationResult($_POST, ['error' => $e->getMessage()], '/admin/products/create');
            exit;
        }
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $_SESSION['flash'] = [
        'type' => 'danger',
        'message' => 'A database error occurred'
    ];
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    $_SESSION['flash'] = [
        'type' => 'danger',
        'message' => 'An error occurred'
    ];
} finally {    
    header('Location: /admin/products');
    exit();
}
?>

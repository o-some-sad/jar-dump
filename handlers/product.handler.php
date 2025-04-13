<?php
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../middlewares/auth.php';
require_once __DIR__ . '/../middlewares/auth_helpers.php';
require_once __DIR__ . '/../utils/pdo.php';

// Ensure admin access
// requireAdmin();

try {
    $pdo = createPDO();
$controller = new ProductController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch($action) {
        case 'create':
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'quantity' => (int)($_POST['quantity'] ?? 0),
        'price' => (float)($_POST['price'] ?? 0),
        'description' => trim($_POST['description'] ?? ''),
                'category_id' => (int)($_POST['category_id'] ?? 0)
                ];
                
                // Validate required fields
                if (empty($data['name']) || empty($data['category_id'])) {
                    $_SESSION['flash'] = [
                        'type' => 'danger',
                        'message' => 'Name and category are required'
                    ];
                    break;
                }

                try {    
    if ($controller->createProduct($data)) {
        $_SESSION['flash'] = [
            'type' => 'success',
         'message' => 'Product created successfully'
        ];
    }
                } catch (Exception $e) {
        $_SESSION['flash'] = [
            'type' => 'danger',
            'message' => 'Failed to create product: ' . $e->getMessage()
        ];
    }
    break;
            
        case 'delete':
            $id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
                    $_SESSION['flash'] = [
                        'type' => 'danger',
                        'message' => 'Invalid product ID'
                    ];
                    break;
                }

                try {
            if ($controller->deleteProduct($id)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Product deleted successfully'
                ];
            }
                } catch (Exception $e) {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Failed to delete product: ' . $e->getMessage()
                ];
            }
            break;
            
        case 'update':
    $id = (int)($_POST['id'] ?? 0);
                if ($id <= 0) {
                    $_SESSION['flash'] = [
                        'type' => 'danger',
                        'message' => 'Invalid product ID'
                    ];
                    break;
                }

    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'quantity' => (int)($_POST['quantity'] ?? 0),
        'price' => (float)($_POST['price'] ?? 0),
        'description' => trim($_POST['description'] ?? '')
    ];
    
try {
    if ($controller->updateProduct($id, $data)) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Product updated successfully'
        ];
    }
                } catch (Exception $e) {
        $_SESSION['flash'] = [
            'type' => 'danger',
            'message' => 'Failed to update product: ' . $e->getMessage()
        ];
    }
    break;
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

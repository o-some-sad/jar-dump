<?php
// https://tania.dev/the-simplest-php-router/

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

//TODO: discuss using namespaces
require_once "middlewares/auth.php";
require_once "utils/debug.php";
require_once "utils/http.php";
require_once "utils/env.php";
require_once "controllers/user.controller.php";
require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/controllers/CategoryController.php';


$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request = rtrim($request, "/");
$method = $_SERVER["REQUEST_METHOD"];

// loadEnv();
//require_once "config.php";
session_start();
try  {
    //TODO: find a better way to do this
    require_once __DIR__ . '/utils/pdo.php';
    $pdo = createPDO();
} catch (Exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed.");
}
// require_once __DIR__ . '/components/adminLayout.php';

function dashboardUserRoutes($request)
{
    global $method;
    $matches = matchRoute("/admin/users/{id}/delete", $request);
    if ($matches && $method == "POST") {
        Auth::protect([Role::Admin]);
        require __DIR__ . "/handlers/deleteUser.handler.php";
        exit;
    }
    $matches = matchRoute("/admin/users/{id}", $request);
    if ($matches && $method == "POST") {
        Auth::protect([Role::Admin]);
        require __DIR__ . "/handlers/updateUser.handler.php";
        exit;
    }
}

switch ($request) {
        case '/': //done
        case '':
        Auth::protect();
        $user = Auth::getUser();
        if ($method == "GET" && $user['role'] == 'user') require __DIR__ . '/views/homepage.php';
        else if ($method == "GET" && $user['role'] == 'admin') redirect('/admin');
        else if($method == 'POST') require __DIR__ . '/handlers/order.user.handler.php';
        else notFound();
        break;
    


    case '/login': //done
        if ($method == "GET") require __DIR__ . '/views/login.php';
        else notFound();
        break;
    case '/auth/login': 
        if ($method == "POST") require __DIR__ . '/handlers/login.handler.php';
        else notFound();
        break;
    case '/auth/logout':
        if ($method == "POST") require __DIR__ . '/handlers/logout.handler.php';
        else notFound();
        break;
    case '/admin':
        Auth::protect([Role::Admin]);
        if ($method != "GET") notFound();
        require __DIR__ . '/views/admin/index.php';
        break;
    case '/admin/users': //done
        Auth::protect([Role::Admin]);
        require __DIR__ . '/views/admin/users.php';
        break;
    case '/admin/users/new': //done
        Auth::protect([Role::Admin]);
        if ($method == "GET") require __DIR__ . '/views/admin/createUser.php';
        else if ($method == "POST") require __DIR__ . '/handlers/user.register.handler.php';
        else notFound();
        break;


   
       

    case '/admin/orders':
        Auth::protect([Role::Admin]);
        if ($method == "GET" || $method == "POST") require __DIR__. '/views/orders/order.status.php';
        else notFound();
        break;


    case '/admin/orders/status':
        Auth::protect([Role::Admin]);
        if ($method == "POST") require __DIR__ . '/handlers/order.status.handler.php';
        else notFound();
        break;

    case '/orderHistory':
        require __DIR__ . '/views/orders/orderHistory.php';
        break;
    case '/admin/products':
        //         //         Auth::protect([Role::Admin]);
        //         //         Auth::protect([Role::Admin]);
        $controller = new ProductController($pdo);
        $products = $controller->getProducts();
        require __DIR__ . '/views/products/index.php';
        break;
    case  '/admin/products/store':
        Auth::protect([Role::Admin]);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/utils/productValidator.php';
            
            try {
                // Validate input data
                [$isValid, $result] = validateProduct($_POST, $_FILES);
                
                if (!$isValid) {
                    $_SESSION['validation_errors'] = $result;
                    $_SESSION['validation_values'] = $_POST;
                    header('Location: /admin/products/create');
                    exit;
                }

                $controller = new ProductController($pdo);
                
                // Handle image upload if present
                $imagePath = null;
                if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
                    $imagePath = $controller->handleImageUpload($_FILES['image']);
                }

                // Merge validated data with image path
                $data = array_merge($result, ['image' => $imagePath]);
                
                $controller->createProduct($data);
                
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Product created successfully'
                ];
                
            } catch (Exception $e) {
                error_log("Product creation error: " . $e->getMessage());
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Error creating product: ' . $e->getMessage()
                ];
                
                // Preserve form data on error
                $_SESSION['validation_values'] = $_POST;
            }
            
            header('Location: /admin/products');
            exit;
        }
        break;




    case '/admin/products/create':
        Auth::protect([Role::Admin]);
        Auth::protect([Role::Admin]);
        $controller = new ProductController($pdo);
        $categories = $controller->getAllCategories();
        require __DIR__ . '/views/products/create.php';
        break;
    case (preg_match('/^\/admin\/products\/edit\/(\d+)$/', $request, $matches) ? true : false):
        Auth::protect([Role::Admin]);
        Auth::protect([Role::Admin]);
        try {
            $id = (int)$matches[1];
            $controller = new ProductController($pdo);
            $product = $controller->getProductById($id);
            $categories = $controller->getAllCategories();


            if (!$product) {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Product not found'
                ];
                header('Location: /admin/products');
                exit;
            }


            require __DIR__ . '/views/products/edit.php';
        } catch (Exception $e) {
            error_log("Edit error: " . $e->getMessage());
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Error loading product'
            ];
            header('Location: /admin/products');
            exit;
        }
        break;
    case (bool)preg_match('/^\/admin\/products\/delete\/(\d+)$/', $request, $matches):
        Auth::protect([Role::Admin]);
        Auth::protect([Role::Admin]);
        try {
            $id = (int)$matches[1];
            $controller = new ProductController($pdo);


            if ($controller->deleteProduct($id)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Product deleted successfully'
                ];
            } else {
                throw new Exception('Failed to delete product');
            }
        } catch (Exception $e) {
            error_log("Delete error: " . $e->getMessage());
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Error deleting product'
            ];
        }
        header('Location: /admin/products');
        exit;
        break;
    case '/admin/products/delete':
        Auth::protect([Role::Admin]);
        Auth::protect([Role::Admin]);
        $controller = new ProductController($pdo);
        $id = $_REQUEST['id'] ?? null;
        if ($id) {
            $controller->deleteProduct($id);
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Product deleted successfully'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Invalid product ID'
            ];
        }
        header('Location: /admin/products');
        exit;
    case '/admin/products/update':
        Auth::protect([Role::Admin]);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $controller = new ProductController($pdo);
                $id = (int)$_POST['id'];
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'category_id' => (int)$_POST['category_id'] ?? 0,
                    'price' => (float)$_POST['price'] ?? 0,
                    'quantity' => (int)$_POST['quantity'] ?? 0,
                    'description' => $_POST['description'] ?? ''
                ];


                if ($controller->updateProduct($id, $data)) {
                    $_SESSION['flash'] = [
                        'type' => 'success',
                        'message' => 'Product updated successfully'
                    ];
                } else {
                    throw new Exception('Failed to update product');
                }
            } catch (Exception $e) {
                error_log("Update error: " . $e->getMessage());
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Error updating product: ' . $e->getMessage()
                ];
            }
            header('Location: /admin/products');
            exit;
        }
        break;
    case '/admin/order':
        Auth::protect([Role::Admin]);
        require __DIR__ . '/views/admin/addOrderToUser.php';
        break;
    case '/admin/order/store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             Auth::protect([Role::Admin]);
            require __DIR__ . '/handlers/order.handler.php';
        }
        break;
    case '/admin/checks':
        Auth::protect([Role::Admin]);
        require __DIR__ . '/views/admin/checks.php';
        break;
    case '/admin/categories/store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            ob_clean();
            header('Content-Type: application/json');
            
            try {
                $input = json_decode(file_get_contents('php://input'), true);
                if (!$input || !isset($input['name'])) {
                    throw new Exception('Invalid input data');
                }

                $controller = new CategoryController($pdo);
                $result = $controller->createCategory($input['name']);

                if (!$result['success'] && isset($result['error']) && $result['error'] === 'duplicate') {
                    http_response_code(409);
                } elseif (!$result['success']) {
                    http_response_code(400);
                }

                echo json_encode($result);

            } catch (Exception $e) {
                error_log("Category creation error: " . $e->getMessage());
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            exit;
        }
        break;
    default:
        dashboardUserRoutes($request);
        notFound();
        break;
}

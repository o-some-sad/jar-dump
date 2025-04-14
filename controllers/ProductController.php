<?php

require_once __DIR__ . '/../utils/pdo.php';
require_once __DIR__ . '/../utils/debug.php';

class ProductController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getProducts() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT p.*,
                       c.name as category_name,
                       CASE 
                           WHEN p.image LIKE '/public/%' THEN p.image
                           WHEN p.image IS NOT NULL THEN CONCAT('/public/uploads/products/', p.image)
                           ELSE '/static/no-image.png'
                       END as image_url
                FROM products p 
                LEFT JOIN category c ON p.category_id = c.category_id 
                WHERE p.deleted_at IS NULL
            ");
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Debug log image paths
            foreach ($products as $product) {
                error_log("Image URL: " . $product['image_url']);
                $absolutePath = __DIR__ . '/..' . $product['image_url'];
                error_log("Absolute path: " . $absolutePath);
                error_log("File exists: " . (file_exists($absolutePath) ? 'yes' : 'no'));
            }
            
            return $products;
        } catch (PDOException $e) {
            error_log("Error fetching products: " . $e->getMessage());
            return [];
        }
    }

    public function getProductById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN category c ON p.category_id = c.category_id 
                WHERE p.product_id = :id AND p.deleted_at IS NULL
            ");
            $stmt->execute(['id' => (int)$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$product) {
                throw new Exception("Product not found");
            }
            
            return $product;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return null;
        }
    }

    public function updateProduct($id, $data) {
        try {
            $sql = "UPDATE products SET 
                    name = :name,
                    category_id = :category_id,
                    price = :price,
                    quantity = :quantity,
                    description = :description";

            $params = [
                'id' => (int)$id,
                'name' => trim($data['name']),
                'category_id' => (int)$data['category_id'],
                'price' => (float)$data['price'],
                'quantity' => (int)$data['quantity'],
                'description' => trim($data['description'])
            ];

            // Handle image if present
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->handleImageUpload($_FILES['image']);
                if ($imagePath) {
                    $sql .= ", image = :image";
                    $params['image'] = $imagePath;
                }
            }

            $sql .= " WHERE product_id = :id";
            
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error updating product: " . $e->getMessage());
            throw $e;
        }
    }

    public function createProduct($data) {
        try {
            $sql = "INSERT INTO products (name, category_id, price, quantity, description) 
                    VALUES (:name, :category_id, :price, :quantity, :description)";
            
            $params = [
                'name' => $data['name'],
                'category_id' => (int)$data['category_id'],
                'price' => (float)$data['price'],
                'quantity' => (int)$data['quantity'],
                'description' => $data['description']
            ];

            // Add image if present
            if (isset($data['image'])) {
                $sql = "INSERT INTO products (name, category_id, price, quantity, description, image) 
                        VALUES (:name, :category_id, :price, :quantity, :description, :image)";
                $params['image'] = $data['image'];
            }

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error creating product: " . $e->getMessage());
            throw $e;
        }
    }

    public function getAllCategories() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM category ORDER BY name");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching categories: " . $e->getMessage());
            return [];
        }
    }

    public function index() {
        $products = $this->getProducts();
        require_once __DIR__ . '/../views/products/index.php';
    }

    

    public function edit($id) {
        $product = $this->getProductById($id);
        $categories = $this->getAllCategories();
        require_once __DIR__ . '/../views/products/edit.php';
    }

    public function deleteProduct($id) {
        try {
            // Get image path before deletion for cleanup
            $stmt = $this->pdo->prepare("SELECT image FROM products WHERE product_id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            // Soft delete the product
            $stmt = $this->pdo->prepare("UPDATE products SET deleted_at = NOW() WHERE product_id = ?");
            if ($stmt->execute([$id])) {
                // If product had an image, delete the file
                if (!empty($product['image'])) {
                    $imagePath = __DIR__ . '/../public/uploads/products/' . basename($product['image']);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error deleting product: " . $e->getMessage());
            throw $e;
        }
    }

  

    public function store() {
        try {
            $imagePath = null;
            if (isset($_FILES['image'])) {
                $imagePath = $this->handleImageUpload($_FILES['image']);
            }

            $data = [
                'name' => $_POST['name'],
                'quantity' => (int)$_POST['quantity'],
                'price' => (float)$_POST['price'],
                'description' => $_POST['description'],
                'category_id' => (int)$_POST['category_id'],
                'image' => $imagePath ?? 'default.jpg'
            ];

            $sql = "INSERT INTO products (name, quantity, price, description, category_id, image, created_at) 
                    VALUES (:name, :quantity, :price, :description, :category_id, :image, NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Product created successfully'
            ];

            header('Location: /admin/products');
            exit();
        } catch (PDOException $e) {
            error_log("Error creating product: " . $e->getMessage());
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Error creating product. Please try again.'
            ];
            header('Location: /admin/products/create');
            exit();
        }
    }

    public function update() {
        try {
            $id = (int)$_POST['id'];
            $data = [
                'name' => $_POST['name'],
                'category_id' => (int)$_POST['category_id'],
                'quantity' => (int)$_POST['quantity'],
                'price' => (float)$_POST['price'],
                'description' => $_POST['description']
            ];

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->handleImageUpload($_FILES['image']);
                if ($imagePath) {
                    $data['image'] = $imagePath;
                }
            }

            if ($this->updateProduct($id, $data)) {
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
                'message' => 'Error updating product'
            ];
        }
        
        header('Location: /admin/products');
        exit;
    }

    public function handleImageUpload($file) {
        try {
            if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
                return null;
            }

            // Debug upload directory
            $uploadDir = __DIR__ . '/../public/uploads/products/';
            error_log("Upload directory: " . $uploadDir);
            error_log("Directory exists: " . (file_exists($uploadDir) ? 'yes' : 'no'));
            error_log("Directory writable: " . (is_writable($uploadDir) ? 'yes' : 'no'));

            if (!file_exists($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    throw new Exception('Failed to create upload directory');
                }
            }

            $fileInfo = pathinfo($file['name']);
            $extension = strtolower($fileInfo['extension']);
            
            // Validate file type
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($extension, $allowedTypes)) {
                throw new Exception('Invalid file type');
            }

            // Generate unique filename
            $filename = uniqid('product_') . '.' . $extension;
            $targetPath = $uploadDir . $filename;

            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                error_log("Failed to move uploaded file. Error: " . error_get_last()['message']);
                return null;
            }

            // Return web-accessible path
            return '/public/uploads/products/' . $filename;
        } catch (Exception $e) {
            error_log("Image upload error: " . $e->getMessage());
            return null;
        }
    }
}

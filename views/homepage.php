<?php
session_start();
$user = $_SESSION['user'];
require_once __DIR__ . "/../controllers/ProductController.php";
require_once "C:/xampp/htdocs/utils/pdo.php";
$pdo = createPDO();
$productController = new ProductController($pdo);
$products = $productController->getProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
</head>
<body>
    <h1>user is <?php echo $user['name'] ?> </h1>
    <h1>Product List</h1>
    <ul>
        <?php foreach ($products as $product): ?>
            <li>
                <h2><?php echo $product['name']; ?> <h2>
            </li>
        <?php endforeach;?>
    </ul>
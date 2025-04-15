
<?php
require_once __DIR__ . '/../utils/pdo.php';

// Check admin authentication (simple version)
$isAdmin = isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin';


// if (!$isAdmin) {
//     header('Location: /login');
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?= $pageTitle ?? 'Dashboard' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Admin CSS -->
    <link href="/static/assets/admin.css" rel="stylesheet">
</head>
<body>
    <!-- Admin Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/admin">
                <i class="fas fa-cog me-2"></i>Admin
            </a>
            
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/products">
                            <i class="fas fa-box me-1"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/users">
                            <i class="fas fa-users me-1"></i> Users
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center">
                    <span class="text-light me-3">
                        <i class="fas fa-user-circle me-1"></i>
                        <?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?>
                    </span>
                    <a href="/logout.php" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-3">
        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show">
                <?= $_SESSION['flash_message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php 
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
            ?>
        <?php endif; ?>
        
        <?= $content ?? '' ?>
    </div>

    <footer class="mt-5 py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">Admin Panel &copy; <?= date('Y') ?></span>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Admin JS -->
    <script src="/static/assets/admin.js"></script>
</body>
</html>
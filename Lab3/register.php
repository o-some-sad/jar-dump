<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border-radius: 15px 15px 0 0;
        }
        .form-control {
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 5px rgba(106, 17, 203, 0.5);
        }
        .btn-primary {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
            border-radius: 10px;
            padding: 10px;
            font-size: 16px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
        }
        .text-muted {
            font-size: 0.9em;
        }
        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-header text-white text-center py-4">
                        <h3 class="card-title mb-0"><i class="fas fa-user-plus me-2"></i>Registration Form</h3>
                    </div>
                    <div class="card-body p-4">
                        <form action="process.php" method="post" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label for="email" class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                                <small class="text-muted">Enter a valid email address.</small>
                            </div>

                            <div class="mb-4">
                                <label for="room" class="form-label"><i class="fas fa-door-open me-2"></i>Room Number</label>
                                <select class="form-select" id="room" name="room" required>
                                    <option value="Application1">Application1</option>
                                    <option value="Application2">Application2</option>
                                    <option value="Cloud">Cloud</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="profile_pic" class="form-label"><i class="fas fa-camera me-2"></i>Profile Picture</label>
                                <input type="file" class="form-control" id="profile_pic" name="profile_pic" accept="image/*" required>
                                <small class="text-muted">Only JPEG, PNG, or GIF allowed.</small>
                            </div>

                            <div class="mb-4">
                                <label for="username" class="form-label"><i class="fas fa-user me-2"></i>Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                                <small class="text-muted">Password must be 8 characters long, contain only lowercase letters, numbers, and underscores.</small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-user-plus me-2"></i>Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
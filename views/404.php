<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>404 Not Found</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .error-wrapper {
      min-height: 100vh;
      background-color: #f8f9fa;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .error-box {
      text-align: center;
      max-width: 500px;
      padding: 40px;
      border-radius: 16px;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .error-box .error-code {
      font-size: 8rem;
      font-weight: 800;
      color: #0d6efd;
    }
    .error-box .error-message {
      font-size: 1.25rem;
      color: #6c757d;
    }
    .error-box .btn-home {
      margin-top: 25px;
    }
  </style>
</head>
<body>

  <div class="error-wrapper">
    <div class="error-box">
      <div class="error-code">404</div>
      <div class="error-message">Page not found!</div>
      <a href="/" class="btn btn-primary btn-home">
        <i class="fas fa-home me-1"></i> Go Back Home
      </a>
    </div>
  </div>

</body>
</html>

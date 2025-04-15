<?php
http_response_code(404);
require_once "components/layout.php";
?>

<?= layout_open("404") ?>
<div class="error-wrapper">
  <div class="error-box">
    <div class="error-code">404</div>
    <div class="error-message">Page not found!</div>
    <a href="/" class="btn btn-primary btn-home">
      <i class="fas fa-home me-1"></i> Go Back Home
    </a>
  </div>
</div>

<?= layout_close() ?>
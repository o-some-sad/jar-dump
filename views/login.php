<?php
require_once "components/layout.php";
require_once "utils/validator.php";

if(Auth::isAuthed()){
    redirect($_GET["return"] ?? "/");
    exit;
}

[$errors, $values] = getValidationReturn();

?>

<?= layout_open("Login") ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Login</h5>
                    <form method="post" action="/auth/login">
                        <?= $errors['_'] ?? "" ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input value="<?= $values["email"] ?? "" ?>" type="email" name="email" class="form-control" id="email" placeholder="Email">
                            <span class="text-danger"><?= $errors['email'] ?? "" ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input value="<?= $values["password"] ?? "" ?>" type="password" name="password" class="form-control" id="password" placeholder="Password">
                            <span class="text-danger"><?= $errors['password'] ?? "" ?></span>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= layout_close() ?>

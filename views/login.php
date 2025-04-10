<?php
require_once "components/layout.php";
require_once "utils/validator.php";

[$errors, $values] = getValidationReturn();


?>

<?= layout_open("Login") ?>

<form method="post">
    <label>
        Email
        <input value="<?= $values["email"] ?? "" ?>" type="email" name="email" placeholder="Email">
        <span><?= $errors['email'] ?? "" ?></span>
    </label>
    <label>
        Password
        <input value="<?= $values["password"] ?? ""?>" type="password" name="password" placeholder="Password">
        <span><?= $errors['password'] ?? "" ?></span>

    </label>
    <button type="submit">Login</button>
</form>


<?= layout_close() ?>
<?php
require_once "components/layout.php";
require_once "utils/validator.php";

[$errors, $values] = getValidationReturn();
// session_start();
if(isset($_SESSION) && $_SESSION['logged_in']){
    echo "Already authed";
    header("location:../homepage");
}
?>

<?= layout_open("Login") ?>

<form method="post" action="/auth/login">
    <?= $errors['_'] ?? "" ?>
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
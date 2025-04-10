<?php
require_once "components/adminLayout.php";
require_once "controllers/user.controller.php";
require_once "utils/html.php";

$result = UserController::getAllUsers();

$users = $result['data'];

$headers = [
    'name' => 'Name',
    'profile_picture' => 'Picture'
];

$render = [
    'profile_picture' => fn($src) => h("img", compact("src"))
]

?>


<?= adminLayout_open("Users"); ?>

<div>
    <h1>All users</h1>
    <?= renderTable($users, $headers, $render); ?>
    <a href="/dashboard/users/new">Add user</a>
</div>
<div>
</div>

<?= adminLayout_close(); ?>
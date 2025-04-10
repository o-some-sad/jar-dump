<?php
require_once "components/adminLayout.php";
require_once "controllers/user.controller.php";
require_once "utils/html.php";

$result = UserController::getAllUsers();
$actionsCell = fn($row) => [
    h(
        "a",
        [
            'href' => "/dashboard/users/{$row['user_id']}"
        ],
        "Edit"
    ),
    h("form", [
        'method' => "POST",
        'action' => "/dashboard/users/{$row['user_id']}/delete"
    ], h("button", null, "Delete")),
];
$users = array_map(fn($row) => array_merge($row, ['actions' => $actionsCell($row)]), $result['data']);

$headers = [
    'user_id' => "ID",
    'name' => 'Name',
    'role' => "Role",
    'profile_picture' => 'Picture',
    'actions' => "Actions"
];

$render = [
    'profile_picture' => fn($src) => h("img", compact("src"))
];

?>


<?= adminLayout_open("Users"); ?>

<div>
    <h1>All users</h1>
    <a href="/dashboard/users/new">Add user</a>
</div>
<?= renderTable($users, $headers, $render); ?>

<?= adminLayout_close(); ?>
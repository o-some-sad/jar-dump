<?php
require_once "components/adminLayout.php";
require_once "controllers/user.controller.php";
require_once "utils/html.php";
require_once "utils/validator.php";
require_once "utils/common.php";

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

[$errors, $_] = getValidationReturn();


?>


<?= adminLayout_open("Users"); ?>

<div>
    <h1>All users</h1>
    <a href="/dashboard/users/new">Add user</a>
</div>
<?= map(array_values($errors), fn($error) => ph("p", null, $error)) ?>
<!-- <?= renderTable($users, $headers, $render); ?> -->

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody x-data='{editing: null}'>
        <?= map($users, fn($row) => ph("tr", [], [
            "<form id='editing_{$row['user_id']}' method='POST' action='/dashboard/users/{$row['user_id']}'></form>",
            "<td>
                {$row['user_id']}
            </td>",
            "<td>
                <template x-if='editing === {$row['user_id']}'>
                    <input name='name' form='editing_{$row['user_id']}' value='{$row['name']}' type='text'>
                </template>
                <template x-if='editing !== {$row['user_id']}'>
                    <span>{$row['name']}</span>
                </template>
            </td>",
            h("td", null, $row['email']),
            "<td>
                <template x-if='editing === {$row['user_id']}'>
                    <select name='role' form='editing_{$row['user_id']}'>"
                . 
                h("option", [$row['role'] == "admin" ? "selected" : '' => '', 'value' => "admin"], "Admin")
                . 
                h("option", [$row['role'] == "user" ? "selected" : '' => '', 'value' => "user"], "User") 
                .
                "</select>
                </template>
                <template x-if='editing !== {$row['user_id']}'>
                    <span>{$row['role']}</span>
                </template>
            </td>",

            "<td>
               <template x-if='editing === {$row['user_id']}'>
                    <button type='submit' form='editing_{$row['user_id']}'>Save</button>
                </template>
                <template x-if='editing !== {$row['user_id']}'>
                 <button @click='editing = {$row['user_id']}'>Edit</button>
                </template>
            </td>",
        ])) ?>
    </tbody>
</table>
<?= adminLayout_close(); ?>
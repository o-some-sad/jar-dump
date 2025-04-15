<?php
// require_once "components/adminLayout.php";
require_once "components/adminLayoutRestored.php";
require_once "controllers/user.controller.php";
require_once "utils/html.php";
require_once "utils/validator.php";
require_once "utils/common.php";

$limit = 10;
$currentPage = (int)(isset($_GET['page']) ? $_GET['page'] : 1) - 1;
if ($currentPage < 0) {
    redirect("/admin/users");
}
$offset = $currentPage * $limit;
$result = UserController::getAllUsers($offset, $limit);

$users = $result['data'];

$total = $result['total'];
$totalPages = ceil($total / $limit);
if ($currentPage >= $totalPages) {
    redirect("/admin/users");
}

[$errors, $_] = getValidationReturn();


?>


<?= adminLayout_open("Users"); ?>
<section class="flex-grow-1 p-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>All users</h1>
        <a href="/admin/users/new">Add user</a>
    </div>
    <?php if (count($errors)): ?>
        <div class="alert alert-danger" role="alert">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if (!count($users)): ?>
        <p>No users found</p>
    <?php else: ?>
        <table class="table">
            <thead class="thead-dark">
                <tr class="table-dark">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody x-data='{editing: null}'>
                <?php foreach ($users as $row): ?>
                    <!-- thanks @aya for telling me about this foreach syntax  -->
                    <tr>
                        <form id="editing_<?= $row['user_id'] ?>" action="/admin/users/<?= $row['user_id'] ?>" method="POST"></form>
                        <td><?= $row['user_id'] ?></td>
                        <td>
                            <template x-if='editing === <?= $row['user_id'] ?>'>
                                <input name='name' form='editing_<?= $row['user_id'] ?>' value='<?= $row['name'] ?>'>
                            </template>
                            <template x-if='editing !== <?= $row['user_id'] ?>'>
                                <span><?= $row['name'] ?></span>
                            </template>
                        </td>
                        <td><?= $row['email'] ?></td>
                        <td>
                            <template x-if='editing === <?= $row['user_id'] ?>'>
                                <select class="form-select" aria-label="Default select example" name='role' form='editing_<?= $row['user_id'] ?>'>
                                    <option value="admin" <?= $row['role'] == "admin" ? "selected" : '' ?>>Admin</option>
                                    <option value="user" <?= $row['role'] == "user" ? "selected" : '' ?>>User</option>
                                </select>
                            </template>
                            <template x-if='editing !== <?= $row['user_id'] ?>'>
                                <span><?= $row['role'] ?></span>
                            </template>
                        </td>
                        <td class="d-flex gap-2">
                            <template x-if='editing === <?= $row['user_id'] ?>'>
                                <button class="btn btn-success" type='submit' form='editing_<?= $row['user_id'] ?>'>Save</button>
                            </template>
                            <button class="btn btn-secondary" x-cloak x-show='editing === <?= $row['user_id'] ?>' type='button' @click='editing = null'>Cancel</button>
                            <template x-if='editing !== <?= $row['user_id'] ?>'>
                                <button class="btn btn-primary" type='button' @click='editing = <?= $row['user_id'] ?>'>Edit</button>
                            </template>
                            <form action="/admin/users/<?= $row['user_id'] ?>/delete" method="POST">
                                <button class="btn btn-danger" type='submit'>Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif; ?>
    <div class="d-flex justify-content-center">
        <div>
            <?php foreach (range(1, $totalPages) as $page): ?>
                <a href="<?= $page != $currentPage + 1 ? "/admin/users?page=$page" : "#" ?>"><?= $page ?></a>
            <?php endforeach ?>
        </div>
    </div>

    <script>
        function confirmUserDelete(json, form) {
            //TODO: show modal
            if (confirm("Are you sure you want to delete this user?")) {
                console.log(form);

                if (!(form instanceof HTMLFormElement)) {
                    alert("Cannot delete this user, please contact system admin");
                    return;
                }
                form.submit();

            }

        }
    </script>
</section>
<?= adminLayout_close(); ?>
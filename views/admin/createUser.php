<!-- DISPLAY ERROR MESSAGES -->

<?php
// echo '<pre>';
// var_dump($_SESSION['values']);
// var_dump($_SESSION['errors']);
// echo '<pre>';
// echo '<br><br><br>';
// isset($_SESSION['errors']['user_profile_pic']);
// var_dump($_SESSION['file_infoo']);
// var_dump($_SESSION['file_info']);
// echo '<br><br><br>';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>User Registration Form</title>
</head>
<body class="bg-white flex items-center justify-center min-h-screen">
  <div class="flex flex-row items-center justify-center h-[800px]">
    <div class="text-white shadow h-full flex items-center justify-center text-xl font-bold w-[700px]">
        <div class="bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <h1 class="text-xl font-bold text-center mt-6 leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
          Add User
        </h1><br>
        <!-- <form class="space-y-4 md:space-y-6 flex flex-col items-center justify-center w-[400px]" action="/dashboard/users/new" method="POST" enctype="multipart/form-data"> -->
        <form class="space-y-6 md:space-y-8 flex flex-col items-center justify-center w-[400px]" action="/admin/users/new" method="POST" enctype="multipart/form-data">
  
        <div class="w-[300px]">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
            <input type="text" name="user_name" id="name" value="<?= htmlspecialchars($_SESSION['values']['user_name'] ?? '') ?>" class="bg-gray-50 border <?= isset($_SESSION['errors']['user_name']) ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter your name ...">
            <?php if (isset($_SESSION['errors']['user_name'])): ?>
                <p class="mt-1 text-sm text-red-600"><?= $_SESSION['errors']['user_name'] ?></p>
            <?php endif; ?>
          </div>
          <div class="w-[300px]">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
            <input type="text" name="user_email" id="email" value="<?= htmlspecialchars($_SESSION['values']['user_email'] ?? '') ?>" class="bg-gray-50 border <?= isset($_SESSION['errors']['user_email']) ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com">
            <?php if (isset($_SESSION['errors']['user_email'])): ?>
                <p class="mt-1 text-sm text-red-600"><?= $_SESSION['errors']['user_email'] ?></p>
            <?php endif; ?>
          </div>
          <div class="w-[300px]">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
            <input type="password" value="<?= htmlspecialchars($_SESSION['values']['user_password'] ?? '') ?>" name="user_password" id="password" class="bg-gray-50 border <?= isset($_SESSION['errors']['user_password']) ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="••••••••">
            <?php if (isset($_SESSION['errors']['user_password'])): ?>
                <p class="mt-1 text-sm text-red-600"><?= $_SESSION['errors']['user_password'] ?></p>
            <?php endif; ?>
          </div>
          <div class="w-[300px]">
            <label for="confirm-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm password</label>
            <input type="password" name="user_confirm_password" value="<?= htmlspecialchars($_SESSION['values']['user_confirm_password'] ?? '')?>" id="confirm-password" class="bg-gray-50 border <?= isset($_SESSION['errors']['user_confirm_password']) ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="••••••••">
            <?php if (isset($_SESSION['errors']['user_confirm_password'])): ?>
                <p class="mt-1 text-sm text-red-600"><?= $_SESSION['errors']['user_confirm_password']?></p>
            <?php endif;?>
            <?php if (isset($_SESSION['errors']['password_match'])): ?>
                <p class="mt-1 text-sm text-red-600"><?= $_SESSION['errors']['password_match']?></p>
            <?php endif; ?>
          </div>
          <div class="w-[300px]">
            <label for="profilePic" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Profile Picture</label>
            <input type="file" name="user_profile_pic" id="profilePic" class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <?php if (isset($_SESSION['errors']['user_profile_pic'])): ?>
                <p class="mt-1 text-sm text-red-600"><?= $_SESSION['errors']['user_profile_pic'] ?></p>
            <?php endif; ?>
          </div>
          <div class="flex flex-row items-center justify-center gap-6">
            <button type="submit" name="submit_btn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Save</button>
            <button type="reset" name="reset_btn" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">Reset</button>
          </div><br>
        </form>
      </div>
    </div>
    <div class="bg-blue-500 text-white h-full flex items-center justify-center text-xl font-bold w-[400px]">
      <img class="w-[300px] h-[300px] object-contain" src="https://cdn-icons-png.flaticon.com/512/921/921359.png" alt="">
    </div>
  </div>
  <!-- ?? -->
  <?php unset($_SESSION['errors'], $_SESSION['values']); ?>
</body>
</html>

<!-- POINTS LEFT TO DO: -->
<!-- 
QUESTION: WHY DO WE USE $_values[''] instead of $_POST['']
QUESTION: understand $_SESSION[errors] &&  $_SESSION[values]
1. When I add the user successfully with NO errors, redirect to a page where it says 'user added successfully' and
a 'back' button where it goes to the home page OR display it as a message 
2. Image error are NOT displayed if there are other errors in the form
3. When I click on reset, the password/confirm-password are not reset
4. DON'T the 'choose-file' input when other fields are incorrect
5. Display a message when the email-input exists in the DB, error down below
Fatal error: Uncaught PDOException: SQLSTATE[23000]:
Integrity constraint violation: 1062 Duplicate entry 'jana@gmail.com' for key 'users.email' in /Applications/MAMP/htdocs/controllers/user.controller.php:42 
Stack trace: #0 /Applications/MAMP/htdocs/controllers/user.controller.php(42):
PDOStatement->execute() #1 /Applications/MAMP/htdocs/handlers/user.register.handler.php(51): 
UserController->insertIntoUsers(Array, 'images/67f9d27f...') #2 /Applications/MAMP/htdocs/index.php(67): 
require('/Applications/M...') #3 {main} thrown in /Applications/MAMP/htdocs/controllers/user.controller.php 
on line 42
6. nav bar
-->
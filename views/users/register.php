
<?php
// $user_name = $_POST['user_name'];
// $user_email = $_POST['user_email'];
// $user_password = $_POST['user_password'];
// $user_pp = $_FILES['user_profile_pic'];
// var_dump( $user_name);
// var_dump( $user_email);
// var_dump( $user_password);
// var_dump( $user_pp);
?>

<!-- <html lang="en">
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
        <form class="space-y-4 md:space-y-6 flex flex-col items-center justify-center w-[400px]" action="/dashboard/users/new" method="POST">
          <div class="w-[300px]">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
            <input type="text" name="user_name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter your name ..." required="">
          </div>
          <div class="w-[300px]">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
            <input type="email" name="user_email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required="">
          </div>
                    <div class="w-[300px]">
            <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
            <div class="flex items-center gap-6">
              <div class="flex items-center">
                <input id="admin" type="radio" value="admin" name="user_role" class="p-2.5 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="admin" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">admin</label>
              </div>
              <div class="flex items-center">
                <input id="user" type="radio" value="user" name="user_role" class="text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="user" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">user</label>
              </div>
            </div>
          </div>
          <div class="w-[300px]">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
            <input type="password" name="user_password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
          </div>
          <div class="w-[300px]">
            <label for="confirm-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm password</label>
            <input type="password" name="confirm-password" id="confirm-password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
          </div>
          <div class="w-[300px]">
            <label for="profilePic" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Profile Picture</label>
            <input type="file" name="user_profile_pic" id="profilePic" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
          </div>
          <div class="flex flex-row items-center justify-center gap-6">
          <div>
          <button type="button" name="submit_btn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Save</button>
          </div> 
          <div>
          <button type="reset" name="reset_btn" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">Reset</button>
          </div>  
        </div>
  
        </form>    
      </div>
    </div>
    <div class="bg-blue-500 text-white h-full flex items-center justify-center text-xl font-bold w-[400px]">
    <img class="w-[300px] h-[300px] object-contain" src="https://cdn-icons-png.flaticon.com/512/921/921359.png" alt="">
</div>
  </div>
</body>
</html> -->

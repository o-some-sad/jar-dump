<?php
    require_once "controllers/order.controller.php";
    $tableData = OrderController::getOrders();
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelbtn'])) {
      $id = $_POST['id'];
      OrderController::deleteOrder($id);
      header("Location: /orderHistory");
      exit();    
  }

  var_dump($_POST);

  isset( $_POST['applyDate']);
  // && isset($_POST['custom-datepicker-apply']) && isset($_POST['applyButton'])
  if($_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['custom-datepicker-input']) && isset( $_POST['datepicker'])) {
    $dateFrom = DateTime::createFromFormat('m/d/Y', $_POST['custom-datepicker-input'])->format('Y-m-d H:i:s');
    $dateTo = DateTime::createFromFormat('m/d/Y', $_POST['datepicker'])->format('Y-m-d H:i:s');
    echo "<pre style='background:#f0f0f0;padding:10px;border:1px solid #ccc;'>";
    echo "DEBUG:\n";
    echo "Raw Input From: " . htmlspecialchars($_POST['custom-datepicker-input']) . "\n";
    echo "Raw Input To: " . htmlspecialchars($_POST['datepicker']) . "\n";
    echo "Formatted From: " . $dateFrom . "\n";
    echo "Formatted To: " . $dateTo . "\n";
    echo "</pre>";
    die();
    OrderController::getOrderByDate($dateFrom, $dateTo);
    header("Location: /login");
    exit();    
    // IF THERE'S NO ORDERS WITHIN THIS RANGE --> DISPLAY 'NO ORDERS FOUND'
    // custom-datepicker-input 1
    // datepicker 2
    // custom-datepicker-apply - apply button 1
    // applyButton - apply button 2
  }

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Order History</title>
</head>
<body>
<br><h1 class="font-mono text-4xl text-center">Order History</h1>
<h1><?php isset($dateFrom) ?></h1>

<!-- <div class="flex flex-row gap-8 justify-center items-center"> -->
<div class="flex flex-row items-center justify-center">
<div class="pt-16 pb-8 lg:pt-[120px] lg:pb-[120px] dark:bg-dark">
  <div class="container mx-auto">
    <div class="-mx-4 flex flex-wrap">
      <div class="w-full px-4 md:w-1/2 lg:w-1/3">
        <div class="mb-12">
          <label for="" class="mb-[10px] block text-base font-medium text-dark dark:text-white">
          </label>

          <div class="relative">
            <!-- Datepicker Input with Icons -->
            <div class="relative flex items-center">
              <span class="absolute left-0 pl-5 text-dark-5">
                <!-- Calendar Icon -->
                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M17.5 3.3125H15.8125V2.625C15.8125 2.25 15.5 1.90625 15.0937 1.90625C14.6875 1.90625 14.375 2.21875 14.375 2.625V3.28125H5.59375V2.625C5.59375 2.25 5.28125 1.90625 4.875 1.90625C4.46875 1.90625 4.15625 2.21875 4.15625 2.625V3.28125H2.5C1.4375 3.28125 0.53125 4.15625 0.53125 5.25V16.125C0.53125 17.1875 1.40625 18.0937 2.5 18.0937H17.5C18.5625 18.0937 19.4687 17.2187 19.4687 16.125V5.25C19.4687 4.1875 18.5625 3.3125 17.5 3.3125ZM2.5 4.71875H4.1875V5.34375C4.1875 5.71875 4.5 6.0625 4.90625 6.0625C5.3125 6.0625 5.625 5.75 5.625 5.34375V4.71875H14.4687V5.34375C14.4687 5.71875 14.7812 6.0625 15.1875 6.0625C15.5937 6.0625 15.9062 5.75 15.9062 5.34375V4.71875H17.5C17.8125 4.71875 18.0625 4.96875 18.0625 5.28125V7.34375H1.96875V5.28125C1.96875 4.9375 2.1875 4.71875 2.5 4.71875ZM17.5 16.6562H2.5C2.1875 16.6562 1.9375 16.4062 1.9375 16.0937V8.71875H18.0312V16.125C18.0625 16.4375 17.8125 16.6562 17.5 16.6562Z"
                    fill="" />
                  <path
                    d="M9 9.59375H8.3125C8.125 9.59375 8 9.71875 8 9.90625V10.5938C8 10.7813 8.125 10.9062 8.3125 10.9062H9C9.1875 10.9062 9.3125 10.7813 9.3125 10.5938V9.90625C9.3125 9.71875 9.15625 9.59375 9 9.59375Z"
                    fill="" />
                  <path
                    d="M11.8438 9.59375H11.1562C10.9687 9.59375 10.8438 9.71875 10.8438 9.90625V10.5938C10.8438 10.7813 10.9687 10.9062 11.1562 10.9062H11.8438C12.0313 10.9062 12.1562 10.7813 12.1562 10.5938V9.90625C12.1562 9.71875 12.0313 9.59375 11.8438 9.59375Z"
                    fill="" />
                  <path
                    d="M14.6875 9.59375H14C13.8125 9.59375 13.6875 9.71875 13.6875 9.90625V10.5938C13.6875 10.7813 13.8125 10.9062 14 10.9062H14.6875C14.875 10.9062 15 10.7813 15 10.5938V9.90625C15 9.71875 14.875 9.59375 14.6875 9.59375Z"
                    fill="" />
                  <path
                    d="M6 12H5.3125C5.125 12 5 12.125 5 12.3125V13C5 13.1875 5.125 13.3125 5.3125 13.3125H6C6.1875 13.3125 6.3125 13.1875 6.3125 13V12.3125C6.3125 12.125 6.15625 12 6 12Z"
                    fill="" />
                  <path
                    d="M9 12H8.3125C8.125 12 8 12.125 8 12.3125V13C8 13.1875 8.125 13.3125 8.3125 13.3125H9C9.1875 13.3125 9.3125 13.1875 9.3125 13V12.3125C9.3125 12.125 9.15625 12 9 12Z"
                    fill="" />
                  <path
                    d="M11.8438 12H11.1562C10.9687 12 10.8438 12.125 10.8438 12.3125V13C10.8438 13.1875 10.9687 13.3125 11.1562 13.3125H11.8438C12.0313 13.3125 12.1562 13.1875 12.1562 13V12.3125C12.1562 12.125 12.0313 12 11.8438 12Z"
                    fill="" />
                  <path
                    d="M14.6875 12H14C13.8125 12 13.6875 12.125 13.6875 12.3125V13C13.6875 13.1875 13.8125 13.3125 14 13.3125H14.6875C14.875 13.3125 15 13.1875 15 13V12.3125C15 12.125 14.875 12 14.6875 12Z"
                    fill="" />
                  <path
                    d="M6 14.4062H5.3125C5.125 14.4062 5 14.5312 5 14.7187V15.4063C5 15.5938 5.125 15.7187 5.3125 15.7187H6C6.1875 15.7187 6.3125 15.5938 6.3125 15.4063V14.7187C6.3125 14.5312 6.15625 14.4062 6 14.4062Z"
                    fill="" />
                  <path
                    d="M9 14.4062H8.3125C8.125 14.4062 8 14.5312 8 14.7187V15.4063C8 15.5938 8.125 15.7187 8.3125 15.7187H9C9.1875 15.7187 9.3125 15.5938 9.3125 15.4063V14.7187C9.3125 14.5312 9.15625 14.4062 9 14.4062Z"
                    fill="" />
                  <path
                    d="M11.8438 14.4062H11.1562C10.9687 14.4062 10.8438 14.5312 10.8438 14.7187V15.4063C10.8438 15.5938 10.9687 15.7187 11.1562 15.7187H11.8438C12.0313 15.7187 12.1562 15.5938 12.1562 15.4063V14.7187C12.1562 14.5312 12.0313 14.4062 11.8438 14.4062Z"
                    fill="" />
                </svg>
              </span>

              <input id="custom-datepicker-input" type="text"
                class="w-20px bg-transparent pl-[50px] pr-8 py-2.5 border rounded-lg text-dark-2 dark:text-dark-6 border-stroke dark:border-dark-3 outline-none transition focus:border-primary dark:focus:border-primary"
                placeholder="Date From" readonly>
              <span class="absolute right-0 pr-4 text-dark-5 cursor-pointer" id="custom-datepicker-toggle">
                <!-- Arrow Down Icon -->
                <!-- <svg class="fill-current stroke-current" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M2.29635 5.15354L2.29632 5.15357L2.30055 5.1577L7.65055 10.3827L8.00157 10.7255L8.35095 10.381L13.701 5.10603L13.701 5.10604L13.7035 5.10354C13.722 5.08499 13.7385 5.08124 13.7499 5.08124C13.7613 5.08124 13.7778 5.08499 13.7963 5.10354C13.8149 5.12209 13.8187 5.13859 13.8187 5.14999C13.8187 5.1612 13.815 5.17734 13.7973 5.19552L8.04946 10.8433L8.04945 10.8433L8.04635 10.8464C8.01594 10.8768 7.99586 10.8921 7.98509 10.8992C7.97746 10.8983 7.97257 10.8968 7.96852 10.8952C7.96226 10.8929 7.94944 10.887 7.92872 10.8721L2.20253 5.2455C2.18478 5.22733 2.18115 5.2112 2.18115 5.19999C2.18115 5.18859 2.18491 5.17209 2.20346 5.15354C2.222 5.13499 2.2385 5.13124 2.2499 5.13124C2.2613 5.13124 2.2778 5.13499 2.29635 5.15354Z"
                    fill="" stroke="" />
                </svg> -->
              </span>
            </div>
          
            <!-- Datepicker Container -->
            <div id="custom-datepicker-container" class="absolute mt-2 bg-white dark:bg-dark-2 border border-stroke dark:border-dark-3 rounded-xl shadow-datepicker pt-5 hidden">
              <div class="flex items-center justify-between px-5">
                <button id="custom-datepicker-prev" class="px-2 py-2 text-dark dark:text-white hover:bg-gray-2 dark:hover:bg-dark rounded-md">
                  <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M13.5312 17.9062C13.3437 17.9062 13.1562 17.8438 13.0312 17.6875L5.96875 10.5C5.6875 10.2187 5.6875 9.78125 5.96875 9.5L13.0312 2.3125C13.3125 2.03125 13.75 2.03125 14.0312 2.3125C14.3125 2.59375 14.3125 3.03125 14.0312 3.3125L7.46875 10L14.0625 16.6875C14.3438 16.9688 14.3438 17.4062 14.0625 17.6875C13.875 17.8125 13.7187 17.9062 13.5312 17.9062Z"
                      fill="" />
                  </svg>
                </button>
                <div id="custom-datepicker-month" class="text-lg font-medium text-dark-3 dark:text-white"></div>
                <button id="custom-datepicker-next" class="px-2 py-2 text-dark dark:text-white hover:bg-gray-2 dark:hover:bg-dark rounded-md">
                  <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M6.46875 17.9063C6.28125 17.9063 6.125 17.8438 5.96875 17.7188C5.6875 17.4375 5.6875 17 5.96875 16.7188L12.5312 10L5.96875 3.3125C5.6875 3.03125 5.6875 2.59375 5.96875 2.3125C6.25 2.03125 6.6875 2.03125 6.96875 2.3125L14.0313 9.5C14.3125 9.78125 14.3125 10.2187 14.0313 10.5L6.96875 17.6875C6.84375 17.8125 6.65625 17.9063 6.46875 17.9063Z"
                      fill="" />
                  </svg>
                </button>
              </div>
              <div class="grid grid-cols-7 gap-2 mt-6 mb-4 px-5">
                <div class="text-center text-sm font-medium text-secondary-color">Sun</div>
                <div class="text-center text-sm font-medium text-secondary-color">Mon</div>
                <div class="text-center text-sm font-medium text-secondary-color">Tue</div>
                <div class="text-center text-sm font-medium text-secondary-color">Wed</div>
                <div class="text-center text-sm font-medium text-secondary-color">Thu</div>
                <div class="text-center text-sm font-medium text-secondary-color">Fri</div>
                <div class="text-center text-sm font-medium text-secondary-color">Sat</div>
              </div>
              <div id="custom-datepicker-days" class="grid grid-cols-7 gap-2 mt-2 px-5"></div>
              <!-- Buttons -->
              <div class="flex justify-end mt-5 space-x-2.5 p-5 border-t border-stroke dark:border-dark-3">
                <button id="custom-datepicker-cancel" class="px-5 py-2.5 text-base font-medium text-primary rounded-lg border border-primary hover:bg-blue-light-5">Cancel</button>
                <!-- <form action="/orderHistory" method="POST"> -->
                  <button id="custom-datepicker-apply" class="px-5 py-2.5 text-base font-medium bg-primary text-white rounded-lg hover:bg-[#1B44C8]">Apply</button>
                <!-- </form> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="pt-20 pb-10 lg:pt-[120px] lg:pb-[120px] dark:bg-dark">
  <div class="container mx-auto">
    <div class="-mx-4 flex flex-wrap">
      <div class="w-full px-4 md:w-1/2 lg:w-1/3">
        <div class="mb-12">
          <label for="" class="mb-[10px] block text-base font-medium text-dark dark:text-white">
            
          </label>

          <div class="relative">
            <!-- Datepicker Input with Icons -->
            <div class="relative flex items-center">
              <span class="absolute left-0 pl-5 text-dark-5">
                <!-- Calendar Icon -->
                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M17.5 3.3125H15.8125V2.625C15.8125 2.25 15.5 1.90625 15.0937 1.90625C14.6875 1.90625 14.375 2.21875 14.375 2.625V3.28125H5.59375V2.625C5.59375 2.25 5.28125 1.90625 4.875 1.90625C4.46875 1.90625 4.15625 2.21875 4.15625 2.625V3.28125H2.5C1.4375 3.28125 0.53125 4.15625 0.53125 5.25V16.125C0.53125 17.1875 1.40625 18.0937 2.5 18.0937H17.5C18.5625 18.0937 19.4687 17.2187 19.4687 16.125V5.25C19.4687 4.1875 18.5625 3.3125 17.5 3.3125ZM2.5 4.71875H4.1875V5.34375C4.1875 5.71875 4.5 6.0625 4.90625 6.0625C5.3125 6.0625 5.625 5.75 5.625 5.34375V4.71875H14.4687V5.34375C14.4687 5.71875 14.7812 6.0625 15.1875 6.0625C15.5937 6.0625 15.9062 5.75 15.9062 5.34375V4.71875H17.5C17.8125 4.71875 18.0625 4.96875 18.0625 5.28125V7.34375H1.96875V5.28125C1.96875 4.9375 2.1875 4.71875 2.5 4.71875ZM17.5 16.6562H2.5C2.1875 16.6562 1.9375 16.4062 1.9375 16.0937V8.71875H18.0312V16.125C18.0625 16.4375 17.8125 16.6562 17.5 16.6562Z"
                    fill="" />
                  <path
                    d="M9 9.59375H8.3125C8.125 9.59375 8 9.71875 8 9.90625V10.5938C8 10.7813 8.125 10.9062 8.3125 10.9062H9C9.1875 10.9062 9.3125 10.7813 9.3125 10.5938V9.90625C9.3125 9.71875 9.15625 9.59375 9 9.59375Z"
                    fill="" />
                  <path
                    d="M11.8438 9.59375H11.1562C10.9687 9.59375 10.8438 9.71875 10.8438 9.90625V10.5938C10.8438 10.7813 10.9687 10.9062 11.1562 10.9062H11.8438C12.0313 10.9062 12.1562 10.7813 12.1562 10.5938V9.90625C12.1562 9.71875 12.0313 9.59375 11.8438 9.59375Z"
                    fill="" />
                  <path
                    d="M14.6875 9.59375H14C13.8125 9.59375 13.6875 9.71875 13.6875 9.90625V10.5938C13.6875 10.7813 13.8125 10.9062 14 10.9062H14.6875C14.875 10.9062 15 10.7813 15 10.5938V9.90625C15 9.71875 14.875 9.59375 14.6875 9.59375Z"
                    fill="" />
                  <path
                    d="M6 12H5.3125C5.125 12 5 12.125 5 12.3125V13C5 13.1875 5.125 13.3125 5.3125 13.3125H6C6.1875 13.3125 6.3125 13.1875 6.3125 13V12.3125C6.3125 12.125 6.15625 12 6 12Z"
                    fill="" />
                  <path
                    d="M9 12H8.3125C8.125 12 8 12.125 8 12.3125V13C8 13.1875 8.125 13.3125 8.3125 13.3125H9C9.1875 13.3125 9.3125 13.1875 9.3125 13V12.3125C9.3125 12.125 9.15625 12 9 12Z"
                    fill="" />
                  <path
                    d="M11.8438 12H11.1562C10.9687 12 10.8438 12.125 10.8438 12.3125V13C10.8438 13.1875 10.9687 13.3125 11.1562 13.3125H11.8438C12.0313 13.3125 12.1562 13.1875 12.1562 13V12.3125C12.1562 12.125 12.0313 12 11.8438 12Z"
                    fill="" />
                  <path
                    d="M14.6875 12H14C13.8125 12 13.6875 12.125 13.6875 12.3125V13C13.6875 13.1875 13.8125 13.3125 14 13.3125H14.6875C14.875 13.3125 15 13.1875 15 13V12.3125C15 12.125 14.875 12 14.6875 12Z"
                    fill="" />
                  <path
                    d="M6 14.4062H5.3125C5.125 14.4062 5 14.5312 5 14.7187V15.4063C5 15.5938 5.125 15.7187 5.3125 15.7187H6C6.1875 15.7187 6.3125 15.5938 6.3125 15.4063V14.7187C6.3125 14.5312 6.15625 14.4062 6 14.4062Z"
                    fill="" />
                  <path
                    d="M9 14.4062H8.3125C8.125 14.4062 8 14.5312 8 14.7187V15.4063C8 15.5938 8.125 15.7187 8.3125 15.7187H9C9.1875 15.7187 9.3125 15.5938 9.3125 15.4063V14.7187C9.3125 14.5312 9.15625 14.4062 9 14.4062Z"
                    fill="" />
                  <path
                    d="M11.8438 14.4062H11.1562C10.9687 14.4062 10.8438 14.5312 10.8438 14.7187V15.4063C10.8438 15.5938 10.9687 15.7187 11.1562 15.7187H11.8438C12.0313 15.7187 12.1562 15.5938 12.1562 15.4063V14.7187C12.1562 14.5312 12.0313 14.4062 11.8438 14.4062Z"
                    fill="" />
                </svg>
              </span>

              <input id="datepicker" type="text"
                class="w-20px bg-transparent pl-[50px] pr-8 py-2.5 border rounded-lg text-dark-2 dark:text-dark-6 border-stroke dark:border-dark-3 outline-none transition focus:border-primary dark:focus:border-primary"
                placeholder="Date To" readonly>
              <span class="absolute right-0 pr-4 text-dark-5 cursor-pointer" id="toggleDatepicker">
                <!-- Arrow Down Icon -->
                <!-- <svg class="fill-current stroke-current" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M2.29635 5.15354L2.29632 5.15357L2.30055 5.1577L7.65055 10.3827L8.00157 10.7255L8.35095 10.381L13.701 5.10603L13.701 5.10604L13.7035 5.10354C13.722 5.08499 13.7385 5.08124 13.7499 5.08124C13.7613 5.08124 13.7778 5.08499 13.7963 5.10354C13.8149 5.12209 13.8187 5.13859 13.8187 5.14999C13.8187 5.1612 13.815 5.17734 13.7973 5.19552L8.04946 10.8433L8.04945 10.8433L8.04635 10.8464C8.01594 10.8768 7.99586 10.8921 7.98509 10.8992C7.97746 10.8983 7.97257 10.8968 7.96852 10.8952C7.96226 10.8929 7.94944 10.887 7.92872 10.8721L2.20253 5.2455C2.18478 5.22733 2.18115 5.2112 2.18115 5.19999C2.18115 5.18859 2.18491 5.17209 2.20346 5.15354C2.222 5.13499 2.2385 5.13124 2.2499 5.13124C2.2613 5.13124 2.2778 5.13499 2.29635 5.15354Z"
                    fill="" stroke="" />
                </svg> -->
              </span>
            </div>
          
            <!-- Datepicker Container -->
            <div id="datepicker-container" class="absolute mt-2 bg-white dark:bg-dark-2 border border-stroke dark:border-dark-3 rounded-xl shadow-datepicker pt-5 hidden">
              <div class="flex items-center justify-between px-5">
                <button id="prevMonth" class="px-2 py-2 text-dark dark:text-white hover:bg-gray-2 dark:hover:bg-dark rounded-md">
                  <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M13.5312 17.9062C13.3437 17.9062 13.1562 17.8438 13.0312 17.6875L5.96875 10.5C5.6875 10.2187 5.6875 9.78125 5.96875 9.5L13.0312 2.3125C13.3125 2.03125 13.75 2.03125 14.0312 2.3125C14.3125 2.59375 14.3125 3.03125 14.0312 3.3125L7.46875 10L14.0625 16.6875C14.3438 16.9688 14.3438 17.4062 14.0625 17.6875C13.875 17.8125 13.7187 17.9062 13.5312 17.9062Z"
                      fill="" />
                  </svg>
                </button>
                <div id="currentMonth" class="text-lg font-medium text-dark-3 dark:text-white"></div>
                <button id="nextMonth" class="px-2 py-2 text-dark dark:text-white hover:bg-gray-2 dark:hover:bg-dark rounded-md">
                  <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M6.46875 17.9063C6.28125 17.9063 6.125 17.8438 5.96875 17.7188C5.6875 17.4375 5.6875 17 5.96875 16.7188L12.5312 10L5.96875 3.3125C5.6875 3.03125 5.6875 2.59375 5.96875 2.3125C6.25 2.03125 6.6875 2.03125 6.96875 2.3125L14.0313 9.5C14.3125 9.78125 14.3125 10.2187 14.0313 10.5L6.96875 17.6875C6.84375 17.8125 6.65625 17.9063 6.46875 17.9063Z"
                      fill="" />
                  </svg>
                </button>
              </div>
              <div class="grid grid-cols-7 gap-2 mt-6 mb-4 px-5">
                <div class="text-center text-sm font-medium text-secondary-color">Sun</div>
                <div class="text-center text-sm font-medium text-secondary-color">Mon</div>
                <div class="text-center text-sm font-medium text-secondary-color">Tue</div>
                <div class="text-center text-sm font-medium text-secondary-color">Wed</div>
                <div class="text-center text-sm font-medium text-secondary-color">Thu</div>
                <div class="text-center text-sm font-medium text-secondary-color">Fri</div>
                <div class="text-center text-sm font-medium text-secondary-color">Sat</div>
              </div>
              <div id="days-container" class="grid grid-cols-7 gap-2 mt-2 px-5"></div>
              <!-- Buttons -->
              <div class="flex justify-end mt-5 space-x-2.5 p-5 border-t border-stroke dark:border-dark-3">
                <button id="cancelButton" class="px-5 py-2.5 text-base font-medium text-primary rounded-lg border border-primary hover:bg-blue-light-5">Cancel</button>
                <!-- <form action="/orderHistory" method="POST"> -->
                <button id="applyButton" class="px-5 py-2.5 text-base font-medium bg-primary text-white rounded-lg hover:bg-[#1B44C8]">Apply</button>
                <!-- </form>  -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</div>
<!-- </div> -->
<div class="flex items-center justify-center mt-[-120px] mb-8">
  <form id="filterForm" action="/orderHistory" method="POST">
    <button type="submit" id="applyDate" class="rounded-lg inline-block border border-indigo-600 px-12 py-3 text-sm font-medium text-indigo-600 hover:bg-indigo-800 hover:text-white">
      Filter</button>
  </form>
</div>

<script>
  // First Datepicker
  const dateInput1 = document.getElementById('custom-datepicker-input');
  const datePicker1 = document.getElementById('custom-datepicker-container');
  const daysGrid1 = document.getElementById('custom-datepicker-days');
  const monthDisplay1 = document.getElementById('custom-datepicker-month');
  const prevBtn1 = document.getElementById('custom-datepicker-prev');
  const nextBtn1 = document.getElementById('custom-datepicker-next');
  const cancelBtn1 = document.getElementById('custom-datepicker-cancel');
  const applyBtn1 = document.getElementById('custom-datepicker-apply');
  const toggleBtn1 = document.getElementById('custom-datepicker-toggle');

  // Second Datepicker
  const dateInput2 = document.getElementById('datepicker');
  const datePicker2 = document.getElementById('datepicker-container');
  const daysGrid2 = document.getElementById('days-container');
  const monthDisplay2 = document.getElementById('currentMonth');
  const prevBtn2 = document.getElementById('prevMonth');
  const nextBtn2 = document.getElementById('nextMonth');
  const cancelBtn2 = document.getElementById('cancelButton');
  const applyBtn2 = document.getElementById('applyButton');
  const toggleBtn2 = document.getElementById('toggleDatepicker');

  let currentMonth1 = new Date();
  let selectedDay1 = null;
  let currentMonth2 = new Date();
  let selectedDay2 = null;

  function renderCalendar1() {
    const year = currentMonth1.getFullYear();
    const month = currentMonth1.getMonth();

    monthDisplay1.textContent = currentMonth1.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

    daysGrid1.innerHTML = '';
    const firstDay = new Date(year, month, 1).getDay();
    const daysCount = new Date(year, month + 1, 0).getDate();

    for (let i = 0; i < firstDay; i++) {
      daysGrid1.innerHTML += `<div></div>`;
    }

    for (let i = 1; i <= daysCount; i++) {
      daysGrid1.innerHTML += `<div class="flex items-center justify-center cursor-pointer w-[46px] h-[46px] text-dark-3 dark:text-dark-6 rounded-full hover:bg-primary hover:text-white hover:bg-blue-400">${i}</div>`;
    }

    document.querySelectorAll('#custom-datepicker-days div').forEach(day => {
      day.addEventListener('click', function () {
        selectedDay1 = `${month + 1}/${this.textContent}/${year}`;
        document.querySelectorAll('#custom-datepicker-days div').forEach(d => d.classList.remove('bg-primary', 'text-white', 'dark:text-white'));
        this.classList.add('bg-primary', 'text-white', 'dark:text-white');
      });
    });
  }

  function renderCalendar2() {
    const year = currentMonth2.getFullYear();
    const month = currentMonth2.getMonth();

    monthDisplay2.textContent = currentMonth2.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

    daysGrid2.innerHTML = '';
    const firstDay = new Date(year, month, 1).getDay();
    const daysCount = new Date(year, month + 1, 0).getDate();

    for (let i = 0; i < firstDay; i++) {
      daysGrid2.innerHTML += `<div></div>`;
    }

    for (let i = 1; i <= daysCount; i++) {
      daysGrid2.innerHTML += `<div class="flex items-center justify-center cursor-pointer w-[46px] h-[46px] text-dark-3 dark:text-dark-6 rounded-full hover:bg-primary hover:text-white hover:bg-blue-400">${i}</div>`;
    }

    document.querySelectorAll('#days-container div').forEach(day => {
      day.addEventListener('click', function () {
        selectedDay2 = `${month + 1}/${this.textContent}/${year}`;
        document.querySelectorAll('#days-container div').forEach(d => d.classList.remove('bg-primary', 'text-white', 'dark:text-white'));
        this.classList.add('bg-primary', 'text-white', 'dark:text-white');
      });
    });
  }

  // First Datepicker Event Listeners
  dateInput1.addEventListener('click', function () {
    datePicker1.classList.toggle('hidden');
    renderCalendar1();
  });

  toggleBtn1.addEventListener('click', function () {
    datePicker1.classList.toggle('hidden');
    renderCalendar1();
  });

  prevBtn1.addEventListener('click', function () {
    currentMonth1.setMonth(currentMonth1.getMonth() - 1);
    renderCalendar1();
  });

  nextBtn1.addEventListener('click', function () {
    currentMonth1.setMonth(currentMonth1.getMonth() + 1);
    renderCalendar1();
  });

  cancelBtn1.addEventListener('click', function () {
    selectedDay1 = null;
    datePicker1.classList.add('hidden');
  });

  applyBtn1.addEventListener('click', function () {
    if (selectedDay1) {
      dateInput1.value = selectedDay1;
    }
    datePicker1.classList.add('hidden');
  });

  // Second Datepicker Event Listeners
  dateInput2.addEventListener('click', function () {
    datePicker2.classList.toggle('hidden');
    renderCalendar2();
  });

  toggleBtn2.addEventListener('click', function () {
    datePicker2.classList.toggle('hidden');
    renderCalendar2();
  });

  prevBtn2.addEventListener('click', function () {
    currentMonth2.setMonth(currentMonth2.getMonth() - 1);
    renderCalendar2();
  });

  nextBtn2.addEventListener('click', function () {
    currentMonth2.setMonth(currentMonth2.getMonth() + 1);
    renderCalendar2();
  });

  cancelBtn2.addEventListener('click', function () {
    selectedDay2 = null;
    datePicker2.classList.add('hidden');
  });

  applyBtn2.addEventListener('click', function () {
    if (selectedDay2) {
      dateInput2.value = selectedDay2;
    }
    datePicker2.classList.add('hidden');
  });

  // Close datepickers when clicking outside
  document.addEventListener('click', function (event) {
    if (!dateInput1.contains(event.target) && !datePicker1.contains(event.target)) {
      datePicker1.classList.add('hidden');
    }
    if (!dateInput2.contains(event.target) && !datePicker2.contains(event.target)) {
      datePicker2.classList.add('hidden');
    }
  });
</script>

<div class="max-h-46 overflow-x-auto">
  <table class="min-w-full divide-y-2 divide-gray-200">
    <thead class="text-2xl text-center top-0 bg-white ltr:text-left rtl:text-right">
      <tr class="*:font-medium *:text-gray-900">
        <th class="px-3 py-2 whitespace-nowrap">Order Date</th>
        <th class="px-3 py-2 whitespace-nowrap">Status</th>
        <th class="px-3 py-2 whitespace-nowrap">Amount</th>
        <!-- <th class="px-3 py-2 whitespace-nowrap">Actions</th> -->
      </tr>
    </thead>

    <tbody class="divide-y divide-gray-200">
      <!-- <tr class="*:text-gray-900 *:first:font-medium hover:bg-blue-100"> -->
        <!-- for loop -->
        <!-- dd($tableData); -->
        <?php foreach ($tableData as $orderInfo): ?>
            <tr class="text-gray-900 font-medium hover:bg-gray-200 font-light text-center">
                <td class="px-3 py-2 whitespace-nowrap flex flex-row gap-8 items-center justify-center"><span> <?php 
                try {
                    echo (new DateTimeImmutable($orderInfo['created_at']))->format('M j,Y');
                } catch (Exception $e) {
                    echo 'Invalid date';
                }
                ?></td>
                <td class="px-3 py-2 whitespace-nowrap"><?= ucfirst($orderInfo['status']) ?></td>
                <td class="px-3 py-2 whitespace-nowrap">$<?= number_format($orderInfo['total_price'], 2)  ?>
                <td><a
                class="group relative inline-block text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-green-300/50"
                href="#"
              >
                <span class="absolute inset-0 border-2 border-gray-300/90 group-hover:border-green-400"></span>
                <span
                  class="text-md block border-2 border-gray-300 bg-gradient-to-br from-green-500 to-green-600 px-8 py-2 transition-all duration-200 group-hover:-translate-x-0.5 group-hover:-translate-y-0.5 group-hover:bg-gradient-to-br group-hover:from-green-600 group-hover:to-green-500 group-active:translate-x-0 group-active:translate-y-0"
                >
                  +
                </span>
              </a></td>
                <?php if ($orderInfo['status'] === 'processing') : ?>
                <td class="px-3 py-2 whitespace-nowrap">
                <form action="/orderHistory" method="POST">
                <input type="hidden" name="id" value="<?= $orderInfo['order_id'] ?>">
                <button
                class="group relative inline-block text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-red-300/50"
                name="cancelbtn"
                onclick="return confirm('Are you sure you want to cancel this order ?')"
              >
                <span class="absolute inset-0 border-2 border-gray-300/90 group-hover:border-red-400"></span>
                <span
                  class="block border-2 border-gray-300 bg-gradient-to-br from-red-500 to-red-600 px-8 py-2 text-xs transition-all duration-200 group-hover:-translate-x-0.5 group-hover:-translate-y-0.5 group-hover:bg-gradient-to-br group-hover:from-red-600 group-hover:to-red-500 group-active:translate-x-0 group-active:translate-y-0"
                >
                  Cancel
                </span>
                </button>
              </form></td>
              <?php else: ?>
              <td class="px-3 py-2 whitespace-nowrap">
                <a
                class="cursor-not-allowed opacity-50 pointer-events-none group relative inline-block text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-red-300/50"
              >
                <span class="absolute inset-0 border-2 border-gray-300/90 group-hover:border-red-400"></span>
                <span
                  class="block border-2 border-gray-300 bg-gradient-to-br from-red-500 to-red-600 px-8 py-2 text-xs transition-all duration-200 group-hover:-translate-x-0.5 group-hover:-translate-y-0.5 group-hover:bg-gradient-to-br group-hover:from-red-600 group-hover:to-red-500 group-active:translate-x-0 group-active:translate-y-0"
                >
                  Cancel
                </span>
              </a></td>
              <?php endif; ?>
            </tr>
        <?php endforeach; ?>
      </tr>
    </tbody>
  </table>
</div><br><br><br><br><br><br>
</body>
</html>
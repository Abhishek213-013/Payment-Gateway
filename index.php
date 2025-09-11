<?php
include 'Database.php';
include 'User.php';

$db = new Database();
$userObj = new User($db->conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = $_POST['name'];
    $mobile = $_POST['number'];
    $amount = $_POST['amount'];

    $userId = $userObj->create($name, $mobile, $amount);

    header("Location: payment.php?id=$userId");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Gateway</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white shadow-lg rounded-xl w-full max-w-sm overflow-hidden p-6"> 

    <!-- Header -->
    <div class="w-full bg-gray-200 px-4 py-6 text-center border-b">
      <img src="pst.png" alt="Logo" class="h-12 w-12 mx-auto mb-3">
      <h2 class="text-lg font-bold text-black-700">PayStation</h2>
    </div>

    <!-- Form -->
    <form id="paymentForm" method="POST" class="px-4 py-4 space-y-4"> 
      <div>
        <label class="block text-sm font-medium text-gray-700">Name *</label>
        <input type="text" name="name" required 
          class="w-full mt-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" 
          placeholder="Enter your name">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Mobile Number *</label>
        <input type="tel" name="number" required pattern="[0-9]{11}" maxlength="11" 
          class="w-full mt-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" 
          placeholder="e.g. 01XXXXXXXXX">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Amount (BDT) *</label>
        <input type="number" name="amount" required step="0.01" 
          class="w-full mt-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" 
          placeholder="e.g. 4000 or 1800.50">
      </div>

      <button type="submit" 
        class="w-full bg-purple-700 text-white font-semibold py-3 rounded-lg hover:bg-purple-500">
        Pay
      </button>
      <button type="button" 
        onclick="document.getElementById('paymentForm').reset();" 
        class="w-full bg-red-700 text-white font-semibold py-3 rounded-lg hover:bg-red-500">
        Cancel / Reset
      </button>
    </form>

    <!-- Go Back to Home Button -->
    <div class="text-center mt-6">
      <a href="landing.php" 
         class="inline-block bg-gray-300 text-gray-800 font-semibold px-6 py-3 rounded-lg hover:bg-gray-400">
        ⬅ Go Back to Home
      </a>
    </div>

    <!-- Footer -->
    <div class="px-4 py-3 border-t text-center text-xs text-gray-500 mt-6">
      <img src="PS_banner_final.png" class="mx-auto mb-1"/>
      <span class="mt-1 block">
        Powered by <span class="font-bold text-blue-700">Abhishek</span>
      </span>
    </div>

  </div>
</body>
</html>

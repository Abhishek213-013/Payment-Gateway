<?php
session_start();
include 'Database.php';
include 'Admin.php';

$db = new Database();
$adminObj = new Admin($db->conn);

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'] ?? '';
    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? '';
    $phone    = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if email already exists
    if ($adminObj->emailExists($email)) {
        $error = "Email address is already registered. Please use a different one.";
    } else {
        // Proceed with registration
        if ($adminObj->register($name, $username, $email, $phone, $password)) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Error registering account. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Registration</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white shadow-lg rounded-xl w-full max-w-sm overflow-hidden p-6"> 
    <div class="w-full bg-gray-200 px-4 py-6 text-center border-b"> 
      <img src="pst.png" alt="Logo" class="h-12 w-12 mx-auto mb-3">
      <h2 class="text-lg font-bold text-black">PayStation</h2>
    </div>

    <!-- Form -->
    <div class="px-4 py-4"> 
      <?php if (!empty($error)): ?>
        <p class="text-red-600 text-sm my-4 text-center"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" class="space-y-4 px-2 py-2"> 
        <input type="text" name="name" placeholder="Name" required 
               class="w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">

        <input type="text" name="username" placeholder="Username" required 
               class="w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">

        <input type="email" name="email" placeholder="Email Address" required 
               class="w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">

        <input type="text" name="phone" placeholder="Phone Number" required 
               class="w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">

        <input type="password" name="password" placeholder="Password" required 
               class="w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">

        <button type="submit" 
                class="w-full bg-blue-700 text-white py-3 rounded-lg hover:bg-blue-800">
          Sign Up
        </button>
      </form>

      <p class="mt-4 text-center text-sm text-gray-600">
        Already have an account? 
        <a href="login.php" class="text-blue-600 font-bold">Sign In</a>
      </p>

      <div class="mt-6 text-center">
        <a href="index.php" 
           class="inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
          Go to Home
        </a>
      </div>
    </div>

    <div class="px-4 py-3 border-t text-center text-xs text-gray-500 mt-6">
      <img src="PS_banner_final.png" />
      <span class="mt-1 block">
        Powered by <span class="font-bold text-blue-700">Abhishek</span>
      </span>
    </div>
  </div>
</body>
</html>

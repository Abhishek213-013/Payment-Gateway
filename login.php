<?php
session_start();
include 'Database.php';
include 'Admin.php';

$db = new Database();
$adminObj = new Admin($db->conn);

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];

    $result = $adminObj->login($identifier, $password);

    if ($result === false) {
        $error = "Invalid password.";
    } elseif ($result === null) {
        $error = "No account found with this username/email.";
    } else {
        $_SESSION['admin_id'] = $result;
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white shadow-lg rounded-xl w-full max-w-sm overflow-hidden p-6"> 
    <div class="w-full bg-gray-200 rounded-t-sm px-4 py-6 text-center border-b"> 
      <img src="pst.png" alt="Logo" class="h-12 w-12 mx-auto mb-3">
      <h2 class="text-lg font-bold text-black-700">PayStation</h2>
    </div>

    <?php if (!empty($error)): ?>
      <p class="text-red-600 text-sm my-4 text-center"><?= htmlspecialchars($error) ?></p> 
    <?php endif; ?>

    <form method="POST" class="space-y-4 px-4 py-4"> 
      <input type="text" name="identifier" placeholder="Username or Email" required 
             class="w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">
      <input type="password" name="password" placeholder="Password" required 
             class="w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">
      <button type="submit" class="w-full bg-blue-700 text-white py-3 rounded-lg hover:bg-blue-800">Log in</button>
    </form>

    <p class="mt-4 text-center text-sm text-gray-600">
      Don’t have an account? 
      <a href="registration.php" class="text-blue-600 font-bold">Sign Up</a>
    </p>

    <div class="mt-4 text-center">
        <a href="index.php" class="inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
            Go to Home
        </a>
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

<?php
include 'Database.php';
include 'User.php';

$db = new Database();
$userObj = new User($db->conn);

if (!isset($_GET['id'])) die("User ID missing.");
$userId = $_GET['id'];

// Fetch user info
$user = $userObj->getUserById($userId);

if (!$user) die("Transaction not found.");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-xl w-full max-w-sm text-center p-6">

        <div class="mb-4">
            <img src="successful.png" alt="Success" class="mx-auto h-24 w-24">
        </div>

        <h2 class="text-2xl font-bold text-green-600 mb-2">Payment Done Successfully!</h2>
        <p class="text-gray-600 mb-4">
            Thank you, <?= htmlspecialchars($user['name']) ?>. Your transaction has been completed.
        </p>

        <p class="text-sm text-gray-500 mb-2">
            Transaction ID: <?= htmlspecialchars($user['id']) ?>
        </p>
        <p class="text-sm text-gray-500">
            Invoice Number: <?= htmlspecialchars($user['invoice']) ?>
        </p>
        <p class="text-sm text-gray-500">
            Paid Amount: BDT <?= htmlspecialchars($user['amount']) ?>
        </p>

        <button onclick="window.location.href='index.php';" class="mt-6 w-full bg-blue-700 text-white font-semibold py-2 rounded-lg hover:bg-blue-800">
            Go to Home
        </button>

        <div class="px-4 py-3 border-t text-center text-xs text-gray-500 mt-6">
            <img src="PS_banner_final.png" />
            <span class="mt-1 block">
                Powered by <span class="font-bold text-blue-700">Abhishek</span>
            </span>
        </div>
    </div>
</body>
</html>

<?php
    if (!isset($_GET['id'])) die("User ID missing.");
    $userId = $_GET['id'];
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
        <p class="text-gray-600 mb-4">Thank you for your payment. Your transaction has been completed.</p>

        <p class="text-sm text-gray-500">Transaction ID: <?= htmlspecialchars($userId) ?></p>

        <button onclick="window.location.href='index.php';" class="mt-6 w-full bg-blue-700 text-white font-semibold py-2 rounded-lg hover:bg-blue-800">
        Go to Home
        </button>
    </div>
</body>
</html>

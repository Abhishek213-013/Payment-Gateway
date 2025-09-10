<?php
session_start();
include 'Database.php';
include 'Transaction.php';

$db = new Database();
$conn = $db->conn;

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$transactionId = (int)$_GET['id'];
$error = "";

$transactionObj = new Transaction($conn);
$transaction = $transactionObj->getById($transactionId);

if (!$transaction) {
    header("Location: dashboard.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $amount = (float)$_POST['amount'];
    $invoice = trim($_POST['invoice']);
    $payment_status = (int)$_POST['payment_status'];

    if ($transactionObj->invoiceExists($invoice, $transactionId)) {
        $error = "Invoice number already exists. Please choose a different one.";
    } else {
        if ($transactionObj->update($transactionId, $name, $amount, $invoice, $payment_status)) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Failed to update transaction. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Transaction</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white shadow-lg rounded-xl w-full max-w-sm overflow-hidden">
    
    <div class="w-full bg-gray-200 px-6 py-6 text-center border-b">
      <img src="pst.png" alt="Logo" class="h-12 w-12 mx-auto mb-2">
      <h2 class="text-lg font-bold text-black">Edit Transaction</h2>
    </div>

    <div class="px-6 py-6">
      <?php if (!empty($error)): ?>
        <p class="text-red-600 text-sm mb-4 text-center"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" class="space-y-4">
        <input type="text" name="name" placeholder="Name" required
         class="w-full border rounded-lg px-4 py-3 text-sm bg-gray-100 cursor-not-allowed"
         value="<?= htmlspecialchars($transaction['name']) ?>" readonly>

        <input type="number" name="amount" placeholder="Amount" required step="0.01"
         class="w-full border rounded-lg px-4 py-3 text-sm bg-gray-100 cursor-not-allowed"
         value="<?= htmlspecialchars($transaction['amount']) ?>" readonly>

        <input type="text" name="invoice" placeholder="Invoice" required
         class="w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500"
         value="<?= htmlspecialchars($transaction['invoice']) ?>">

        <select name="payment_status" required
          class="w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">
            <option value="0" <?= $transaction['payment_status'] == 0 ? "selected" : "" ?>>Pending ⏳</option>
            <option value="1" <?= $transaction['payment_status'] == 1 ? "selected" : "" ?>>Paid ✅</option>
            <option value="2" <?= $transaction['payment_status'] == 2 ? "selected" : "" ?>>Canceled ❌</option>
        </select>

        <button type="submit"
          class="w-full bg-blue-700 text-white py-3 rounded-lg hover:bg-blue-800">
            Update Transaction
        </button>
    </form>


      <div class="mt-6 text-center space-x-2">
        <a href="dashboard.php"
           class="inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
          Back to Dashboard
        </a>
        <a href="index.php"
           class="inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
          Go to Home
        </a>
      </div>

      <div class="px-4 py-2 border-t text-center text-xs text-gray-500 mt-4">
        <img src="PS_banner_final.png" class="mx-auto h-5 mb-1" />
        <span class="block">
            Powered by <span class="font-bold text-blue-700">Abhishek</span>
        </span>
      </div>

    </div>
  </div>
</body>
</html>

<?php
session_start();
include 'Database.php';
include 'Admin.php';

$db = new Database();
$conn = $db->conn;

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin = new Admin($conn);
$adminInfo = $admin->getAdminById((int)$_SESSION['admin_id']);
$adminUsername = $adminInfo['username'] ?? 'Admin';

// Get user ID
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}
$userId = (int)$_GET['id'];

// Fetch user details
$stmt = $conn->prepare("SELECT id, name, amount, invoice, payment_status FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "User not found!";
    exit();
}

// Update on form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice = $_POST['invoice'] ?? '';
    $status = $_POST['payment_status'] ?? 0;

    $stmt = $conn->prepare("UPDATE users SET invoice=?, payment_status=? WHERE id=?");
    $stmt->bind_param("sii", $invoice, $status, $userId);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Transaction - PayStation</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

  <!-- Header -->
  <nav class="bg-gray-200 px-6 py-4 flex items-center justify-between shadow sticky top-0 z-50">
    <div class="flex items-center gap-3">
      <a href="landing.php" class="flex items-center gap-2">
        <img src="pst.png" alt="Logo" class="h-8 w-8 hover:opacity-80 transition">
        <span class="text-lg font-bold">PayStation</span>
      </a>
    </div>
    <div class="text-gray-700">Hello, <span class="font-semibold"><?= htmlspecialchars($adminUsername) ?></span></div>
    <div>
      <a href="dashboard.php?logout=1" 
         class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
        Logout
      </a>
    </div>
  </nav>

  <!-- Content -->
  <main class="flex-1 flex items-center justify-center px-6 py-6">
    <div class="bg-white shadow-lg rounded-xl w-full max-w-md p-6">
      <h1 class="text-xl font-bold mb-4">Edit Transaction</h1>
      <form method="POST" class="space-y-4">
        
        <div>
          <label class="block text-sm font-medium">Name</label>
          <input type="text" value="<?= htmlspecialchars($user['name']) ?>" 
                 class="w-full border rounded-lg px-3 py-2 bg-gray-100" disabled>
        </div>

        <div>
          <label class="block text-sm font-medium">Amount (BDT)</label>
          <input type="number" value="<?= htmlspecialchars($user['amount']) ?>" 
                 class="w-full border rounded-lg px-3 py-2 bg-gray-100" disabled>
        </div>

        <div>
          <label class="block text-sm font-medium">Invoice</label>
          <input type="text" name="invoice" value="<?= htmlspecialchars($user['invoice']) ?>" 
                 class="w-full border rounded-lg px-3 py-2">
        </div>

        <div>
          <label class="block text-sm font-medium">Status</label>
          <select name="payment_status" class="w-full border rounded-lg px-3 py-2">
            <option value="0" <?= $user['payment_status']==0 ? 'selected' : '' ?>>⏳ Pending</option>
            <option value="1" <?= $user['payment_status']==1 ? 'selected' : '' ?>>✅ Paid</option>
            <option value="2" <?= $user['payment_status']==2 ? 'selected' : '' ?>>❌ Canceled</option>
          </select>
        </div>

        <div class="flex justify-between mt-4">
          <a href="dashboard.php" class="bg-gray-300 px-4 py-2 rounded-lg">Cancel</a>
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Save</button>
        </div>
      </form>
    </div>
  </main>

</body>
</html>

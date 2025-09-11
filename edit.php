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

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Delete user
if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit();
}

// Fetch transactions
$stmt = $conn->prepare("SELECT id, name, amount, invoice, payment_status FROM users ORDER BY id ASC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - PayStation</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

  <!-- Header -->
  <nav class="bg-gray-200 px-6 py-4 flex items-center justify-between shadow sticky top-0 z-50">
    <div class="flex items-center gap-3">
      <img src="pst.png" alt="Logo" class="h-8 w-8">
      <span class="text-lg font-bold">PayStation</span>
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
  <main class="flex-1 flex flex-col items-center justify-center px-6 py-6">
    <div class="bg-white shadow-lg rounded-xl w-full max-w-6xl overflow-auto max-h-[70vh]">
      <table class="w-full border text-sm rounded-lg overflow-hidden">
        <thead class="bg-gray-200 sticky top-0 z-40">
          <tr class="text-left">
            <th class="px-4 py-2 border">Sr No.</th>
            <th class="px-4 py-2 border">Tr ID</th>
            <th class="px-4 py-2 border">Name</th>
            <th class="px-4 py-2 border">Invoice</th>
            <th class="px-4 py-2 border">Amount</th>
            <th class="px-4 py-2 border">Status</th>
            <th class="px-4 py-2 border text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $counter = 1; 
          while ($row = $result->fetch_assoc()): ?>
          <tr class="hover:bg-gray-50">
            <td class="border px-4 py-2"><?= $counter++ ?></td>
            <td class="border px-4 py-2"><?= $row['id'] ?></td>
            <td class="border px-4 py-2"><?= htmlspecialchars($row['name']) ?></td>
            <td class="border px-4 py-2"><?= htmlspecialchars($row['invoice']) ?></td>
            <td class="border px-4 py-2">BDT <?= htmlspecialchars($row['amount']) ?></td>
            <td class="border px-4 py-2">
              <?php if ($row['payment_status'] == 1): ?>
                <span class="text-green-600 font-semibold">✅ Paid</span>
              <?php elseif ($row['payment_status'] == 2): ?>
                <span class="text-red-600 font-semibold">❌ Canceled</span>
              <?php else: ?>
                <span class="text-yellow-600 font-semibold">⏳ Pending</span>
              <?php endif; ?>
            </td>
            <td class="border px-4 py-2 text-center">
              <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-600 font-bold hover:underline">Edit</a> |
              <a href="dashboard.php?delete=<?= $row['id'] ?>" 
                 onclick="return confirm('Delete this transaction?')" 
                 class="text-red-600 font-bold hover:underline">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- Go to Home Button -->
    <div class="mt-6 text-center">
      <a href="index.php" 
         class="inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
        Go to Home
      </a>
    </div>
  </main>

  <!-- Footer -->
  <footer class="px-4 py-3 border-t text-center text-xs text-gray-500 mt-6">
    <img src="PS_banner_final.png" class="mx-auto mb-2" />
    <span>Powered by <span class="font-bold text-blue-700">Abhishek</span></span>
  </footer>

</body>
</html>

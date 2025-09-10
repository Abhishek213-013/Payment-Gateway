<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT username FROM admins WHERE id=?");
$stmt->bind_param("i", $_SESSION['admin_id']);
$stmt->execute();
$stmt->bind_result($adminUsername);
$stmt->fetch();
$stmt->close();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$deleteId");
    header("Location: dashboard.php");
    exit();
}

$result = $conn->query("SELECT id, name, amount, invoice, payment_status FROM users ORDER BY id ASC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

  
  <nav class="bg-gray-200 px-6 py-4 flex items-center justify-between shadow sticky top-0 z-50">
    <div class="text-center text-lg font-semibold">
      Hello, <?= htmlspecialchars($adminUsername) ?>.
    </div>
    <div>
      <a href="dashboard.php?logout=1" 
         class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
        Logout
      </a>
    </div>
  </nav>

  
  <div class="flex-1 flex items-center justify-center px-6 py-6">
    <div class="bg-white shadow-lg rounded-xl w-full max-w-5xl overflow-auto max-h-[75vh]">

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
            <td class="border px-4 py-2"><?= $row['invoice'] ?></td>
            <td class="border px-4 py-2">BDT <?= $row['amount'] ?></td>
            <td class="border px-4 py-2">
              <?= $row['payment_status'] == 1 ? "✅ Paid" : ($row['payment_status'] == 2 ? "❌ Canceled" : "⏳ Pending") ?>
            </td>
            <td class="border px-4 py-2 text-center">
              <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-600 font-bold">Edit</a> |
              <a href="dashboard.php?delete=<?= $row['id'] ?>" 
                 onclick="return confirm('Delete this transaction?')" 
                 class="text-red-600 font-bold">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

    </div>
  </div>

  <footer class="bg-gray-200 w-full py-4 text-center text-xs text-gray-500 mt-auto shadow-inner">
    <img src="PS_banner_final.png" class="mx-auto mb-1" />
    Powered by <span class="font-bold text-blue-700">Abhishek</span>
  </footer>

</body>
</html>

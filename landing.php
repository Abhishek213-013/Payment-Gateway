<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PayStation</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

  <!-- Navbar -->
  <nav class="bg-white shadow px-6 py-4 flex items-center justify-between sticky top-0 z-50">
    <!-- Left: Logo + Name -->
    <div class="flex items-center space-x-2">
      <img src="pst.png" alt="PayStation Logo" class="h-8 w-8">
      <span class="text-xl font-bold text-gray-800">PayStation</span>
    </div>

    <!-- Right: Buttons -->
    <div class="space-x-3">
      <a href="login.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        Join as Admin
      </a>
      <a href="index.php" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
        Go to Payment Gateway
      </a>
    </div>
  </nav>

  <!-- Hero Section -->
  <main class="flex-1 flex flex-col items-center justify-center text-center px-6 py-12 bg-gradient-to-b from-blue-50 to-gray-100">
    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
      Welcome to PayStation
    </h1>
    <p class="text-gray-600 max-w-2xl mb-6">
      Your trusted platform for managing transactions and payments. 
      Secure, fast, and reliable – whether you are an Admin or a User.
    </p>

    <!-- Buttons -->
    <div class="flex gap-4 mb-10">
      <a href="login.php" 
         class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
        Join as Admin
      </a>
      <a href="index.php" 
         class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300">
        Go to Payment Gateway
      </a>
    </div>

    <!-- Features Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl w-full">
      <!-- Feature 1 -->
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
        <div class="text-blue-600 text-3xl mb-4">💳</div>
        <h3 class="font-semibold text-lg text-gray-800 mb-2">Easy Transactions</h3>
        <p class="text-gray-600 text-sm">
          Simplify payments with a user-friendly and efficient gateway system.
        </p>
      </div>
      <!-- Feature 2 -->
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
        <div class="text-green-600 text-3xl mb-4">🔒</div>
        <h3 class="font-semibold text-lg text-gray-800 mb-2">Secure Payments</h3>
        <p class="text-gray-600 text-sm">
          All transactions are encrypted and protected to ensure maximum security.
        </p>
      </div>
      <!-- Feature 3 -->
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
        <div class="text-purple-600 text-3xl mb-4">🛠️</div>
        <h3 class="font-semibold text-lg text-gray-800 mb-2">Admin Control</h3>
        <p class="text-gray-600 text-sm">
          Manage users, invoices, and payments with a powerful admin dashboard.
        </p>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="text-center py-4 border-t text-sm text-gray-500">
    Powered by <span class="font-bold text-blue-700">Abhishek</span>
  </footer>

</body>
</html>

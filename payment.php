<?php
include 'db.php';

if (!isset($_GET['id'])) die("User ID missing.");
$userId = $_GET['id'];

if (isset($_GET['cancel']) && $_GET['cancel'] == 1) {
    $stmt = $conn->prepare("UPDATE users SET payment_status=2 WHERE id=?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("SELECT name, amount FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$invoiceNumber = 'INV-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));

$stmt = $conn->prepare("UPDATE users SET invoice=? WHERE id=?");
$stmt->bind_param("si", $invoiceNumber, $userId);
$stmt->execute();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_number = $_POST['cardNumber'];
    $expMM = $_POST['expMM'];
    $expYY = $_POST['expYY'];
    $cvv = $_POST['cvv'];
    $save_next = isset($_POST['save']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE users SET card_number=?, expMM=?, expYY=?, cvv=?, save_next_payment=?, payment_status=1 WHERE id=?");
    $stmt->bind_param("ssssii", $card_number, $expMM, $expYY, $cvv, $save_next, $userId);
    $stmt->execute();
    $stmt->close();

    header("Location: success.php?id=$userId");
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
  <div class="bg-white shadow-lg rounded-xl w-full max-w-sm">
    <div class="relative border-b px-4 py-4 flex items-center justify-between">
      <button onclick="window.history.back();" class="text-gray-600 hover:text-gray-900 font-bold text-xl">&larr;</button>

      <h2 class="text-lg font-semibold text-blue-700">Pay Station</h2>
      <button onclick="window.location.href='index.php';" class="text-gray-600 hover:text-gray-900 font-bold text-xl">&times;</button>
    </div>


    <div class="px-4 py-4 flex items-center justify-between bg-gray-100 mb-4">
      <p class="font-semibold">Pay Station</p>
      <p class="text-gray-500 text-sm">Invoice: <?= $invoiceNumber ?></p>
    </div>

    <form method="POST" id="paymentForm" class="px-4 py-4">
      <h3 class="text-sm font-semibold text-center text-blue-400 mb-4">Card Information</h3>

      <div class="flex space-x-4 mb-4">
        <img src="mastercard-logo-png.png" id="mastercard" class="h-14 w-24 cursor-pointer border-2 border-gray-400 rounded-lg p-2">
        <img src="visa-logo-png_seeklogo-149684.png" id="visa" class="h-14 w-24 cursor-pointer border-2 border-gray-200 rounded-lg p-2">
      </div>

      <input id="cardHolder" type="text" name="cardHolder" value="<?= htmlspecialchars($user['name']) ?>" readonly class="w-full border rounded-lg px-4 py-3 mb-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

      <input id="cardNumber" type="text" name="cardNumber" placeholder="Card Number" maxlength="16" class="w-full border rounded-lg px-4 py-3 mb-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>

      <div class="flex space-x-3 mb-4">
        <input id="expMM" type="text" name="expMM" placeholder="MM" maxlength="2" class="w-1/3 border rounded-lg px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <input id="expYY" type="text" name="expYY" placeholder="YY" maxlength="2" class="w-1/3 border rounded-lg px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <input id="cvv" type="text" name="cvv" placeholder="CVV" maxlength="3" class="w-1/3 border rounded-lg px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
      </div>

      <div class="flex items-center mb-4">
        <input type="checkbox" name="save" id="save" class="mr-2">
        <label for="save" class="text-sm font-bold text-gray-600">Save for next payment</label>
      </div>

      <button type="submit" class="w-full bg-blue-700 text-white font-semibold py-3 rounded-lg hover:bg-blue-800 mb-2">Pay BDT <?= $user['amount'] ?></button>
      <button type="button" onclick="window.location.href='payment.php?cancel=1&id=<?= $userId ?>';" class="w-full border border-gray-400 text-gray-600 font-semibold py-3 rounded-lg hover:bg-gray-100">
        Cancel
      </button>
    </form>

    <div class="px-4 py-3 border-t text-center text-xs text-gray-500">
      <img src="PS_banner_final.png" />
      Powered by <span class="font-bold text-blue-700">Abhishek</span>
    </div>
  </div>

  <script>
    const mastercard = document.getElementById("mastercard");
    const visa = document.getElementById("visa");
    const cards = [mastercard, visa];
    let selectedCard = "mastercard"; 

    cards.forEach(card => {
      card.addEventListener("click", () => {
        cards.forEach(c => c.classList.remove("border-gray-400"));
        card.classList.add("border-gray-400");
        selectedCard = card.id;
      });
    });

    const cardNumber = document.getElementById("cardNumber");
    const expMM = document.getElementById("expMM");
    const expYY = document.getElementById("expYY");
    const cvv = document.getElementById("cvv");

    cardNumber.addEventListener("input", () => { cardNumber.value = cardNumber.value.replace(/\D/g,''); if(cardNumber.value.length===16) expMM.focus(); });
    expMM.addEventListener("input", () => { expMM.value = expMM.value.replace(/\D/g,''); if(expMM.value.length===2) expYY.focus(); });
    expYY.addEventListener("input", () => { expYY.value = expYY.value.replace(/\D/g,''); if(expYY.value.length===2) cvv.focus(); });
    cvv.addEventListener("input", () => { cvv.value = cvv.value.replace(/\D/g,''); });
  </script>
</body>
</html>

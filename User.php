<?php
class User {
    private $conn;

    public function __construct($dbConn) {
        $this->conn = $dbConn;
    }

    public function create($name, $mobile, $amount) {
        $stmt = $this->conn->prepare("INSERT INTO users (name, mobile, amount) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("ssd", $name, $mobile, $amount);
        if ($stmt->execute()) {
            $userId = $stmt->insert_id;
            $stmt->close();
            return $userId;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function updateInvoice($id, $invoice) {
        $stmt = $this->conn->prepare("UPDATE users SET invoice=? WHERE id=?");
        $stmt->bind_param("si", $invoice, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function processPayment($id, $card_number, $expMM, $expYY, $cvv, $save_next) {
        $stmt = $this->conn->prepare("UPDATE users SET card_number=?, expMM=?, expYY=?, cvv=?, save_next_payment=?, payment_status=1 WHERE id=?");
        $stmt->bind_param("ssssii", $card_number, $expMM, $expYY, $cvv, $save_next, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function cancelPayment($id) {
        $stmt = $this->conn->prepare("UPDATE users SET payment_status=2 WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    public function updateTransaction($id, $name, $amount, $invoice, $status) {
        $stmt = $this->conn->prepare("UPDATE users SET name=?, amount=?, invoice=?, payment_status=? WHERE id=?");
        $stmt->bind_param("ssdii", $name, $amount, $invoice, $status, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function isInvoiceExists($invoice, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE invoice=? AND id!=?");
            $stmt->bind_param("si", $invoice, $excludeId);
        } else {
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE invoice=?");
            $stmt->bind_param("s", $invoice);
        }
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }
}
?>

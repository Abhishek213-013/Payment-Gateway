<?php
class Transaction {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function getById(int $id): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id=?");
        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $transaction = $result->fetch_assoc();
        $stmt->close();

        return $transaction ?: null;
    }

    public function invoiceExists(string $invoice, ?int $excludeId = null): bool {
        if ($excludeId !== null) {
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE invoice=? AND id!=?");
            if (!$stmt) return false;

            $stmt->bind_param("si", $invoice, $excludeId);
        } else {
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE invoice=?");
            if (!$stmt) return false;

            $stmt->bind_param("s", $invoice);
        }

        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    public function update(int $id, string $name, float $amount, string $invoice, int $payment_status): bool {
        $stmt = $this->conn->prepare(
            "UPDATE users SET name=?, amount=?, invoice=?, payment_status=? WHERE id=?"
        );
        if (!$stmt) return false;

        $stmt->bind_param("ssdii", $name, $amount, $invoice, $payment_status, $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
?>

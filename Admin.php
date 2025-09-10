<?php
class Admin {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function login(string $identifier, string $password): int|false|null {
        $stmt = $this->conn->prepare(
            "SELECT id, password FROM admins WHERE username=? OR email=?"
        );
        if (!$stmt) return false;

        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $stmt->close();
            return null;
        }

        $id = 0;
        $hashedPassword = '';
        $stmt->bind_result($id, $hashedPassword);
        $stmt->fetch();
        $stmt->close();

        return (is_string($hashedPassword) && password_verify($password, $hashedPassword)) ? $id : false;
    }

    public function register(string $username, string $email, string $phone, string $password): bool {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare(
            "INSERT INTO admins (username, email, phone, password) VALUES (?, ?, ?, ?)"
        );
        if (!$stmt) return false;

        $stmt->bind_param("ssss", $username, $email, $phone, $hashedPassword);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function getAdminById(int $adminId): ?array {
        $stmt = $this->conn->prepare("SELECT id, username, email, phone FROM admins WHERE id=?");
        if (!$stmt) return null;

        $stmt->bind_param("i", $adminId);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        $stmt->close();
        return $admin ?: null;
    }

    public function updatePassword(int $adminId, string $newPassword): bool {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE admins SET password=? WHERE id=?");
        if (!$stmt) return false;

        $stmt->bind_param("si", $hashedPassword, $adminId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function deleteAdmin(int $adminId): bool {
        $stmt = $this->conn->prepare("DELETE FROM admins WHERE id=?");
        if (!$stmt) return false;

        $stmt->bind_param("i", $adminId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
?>

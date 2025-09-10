<?php
class Admin {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    /**
     * Login admin by username or email
     * Returns admin ID on success, false on wrong password, null if not found
     */
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

    /**
     * Check if email already exists
     */
    public function emailExists(string $email): bool {
        $stmt = $this->conn->prepare("SELECT id FROM admins WHERE email=?");
        if (!$stmt) return false;

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    /**
     * Register new admin
     */
    public function register(string $name, string $username, string $email, string $phone, string $password): bool {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check for duplicate email first
        if ($this->emailExists($email)) {
            return false;
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO admins (name, username, email, phone, password) VALUES (?, ?, ?, ?, ?)"
        );
        if (!$stmt) return false;

        $stmt->bind_param("sssss", $name, $username, $email, $phone, $hashedPassword);

        try {
            $success = $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            // Handle duplicate email gracefully
            if ($e->getCode() === 1062) { // Duplicate entry
                $stmt->close();
                return false;
            } else {
                throw $e;
            }
        }

        $stmt->close();
        return $success;
    }

    /**
     * Fetch admin info by ID
     */
    public function getAdminById(int $adminId): ?array {
        $stmt = $this->conn->prepare(
            "SELECT id, name, username, email, phone FROM admins WHERE id=?"
        );
        if (!$stmt) return null;

        $stmt->bind_param("i", $adminId);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        $stmt->close();

        return $admin ?: null;
    }

    /**
     * Update admin password
     */
    public function updatePassword(int $adminId, string $newPassword): bool {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE admins SET password=? WHERE id=?");
        if (!$stmt) return false;

        $stmt->bind_param("si", $hashedPassword, $adminId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    /**
     * Delete admin by ID
     */
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

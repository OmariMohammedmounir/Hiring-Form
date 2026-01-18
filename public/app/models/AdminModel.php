<?php


class AdminModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Verify admin credentials
    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    }

    // To allow other controllers/models to get the PDO connection
    public function getDb() {
        return $this->db;
    }
    
}
?>

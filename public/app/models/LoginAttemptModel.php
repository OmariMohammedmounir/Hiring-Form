<?php


class LoginAttemptModel {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Enregistre une tentative échouée
    public function recordAttempt($username, $ip) {
        $stmt = $this->db->prepare("INSERT INTO login_attempts (username, ip_address) VALUES (?, ?)");
        $stmt->execute([$username, $ip]);
    }
    
    // Retourne le nombre de tentatives dans les $minutes dernières minutes
    public function getAttemptsCount($username, $ip, $minutes = 15) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM login_attempts WHERE username = ? AND ip_address = ? AND attempt_time > DATE_SUB(NOW(), INTERVAL ? MINUTE)");
        $stmt->execute([$username, $ip, $minutes]);
        return (int)$stmt->fetchColumn();
    }
    
    // Efface les tentatives pour un utilisateur et une IP donnée (en cas de connexion réussie)
    public function clearAttempts($username, $ip) {
        $stmt = $this->db->prepare("DELETE FROM login_attempts WHERE username = ? AND ip_address = ?");
        $stmt->execute([$username, $ip]);
    }
}
?>

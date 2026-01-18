<?php


class TokenModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Crée un nouveau token et l'enregistre dans la table "weetel"
    public function createToken() {
        $token = bin2hex(random_bytes(16));
        $stmt = $this->db->prepare("INSERT INTO weetel (token) VALUES (?)");
        $stmt->execute([$token]);
        return $token;
    }

    // Vérifie si le token existe, n'est pas utilisé et a été créé il y a moins de 24 heures
    public function isValidToken($token) {
        $stmt = $this->db->prepare("SELECT * FROM weetel WHERE token = ? AND used = 0 AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Marque le token comme utilisé
    public function markAsUsed($token) {
        $stmt = $this->db->prepare("UPDATE weetel SET used = 1 WHERE token = ?");
        $stmt->execute([$token]);
    }
}
?>

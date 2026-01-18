<?php
class RecruitmentModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllApplications() {
        $stmt = $this->db->query("SELECT * FROM recrutement ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertApplication($data) {
        $stmt = $this->db->prepare("INSERT INTO recrutement (
            nom, prenom, date_de_naissance, lieu_de_naissance, ville_de_naissance, pays, nationalite, num_titre_sejour,
            date_expiration_titre_sejour, num_securite, num_permis, date_expiration_permis,
            adresse, code_postal, ville_residence, pointeur_chasseur, telephone, fax, email, identifiant_france_travail,
            code_postal_agence, fichier_pdf, passport, p_id, date_embauche, documents_photo, date_contrat, token
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?, ?
        )");

        return $stmt->execute([
            $data['nom'], $data['prenom'], $data['date_de_naissance'], $data['lieu_de_naissance'], $data['ville_de_naissance'], $data['pays'], $data['nationalite'],
            $data['num_titre_sejour'],
            $data['date_expiration_titre_sejour'],
            $data['num_securite'], $data['num_permis'], $data['date_expiration_permis'],
            $data['adresse'], $data['code_postal'], $data['ville_residence'], $data['pointeur_chasseur'], $data['telephone'], $data['fax'], $data['email'], $data['identifiant_france_travail'],
            $data['code_postal_agence'], $data['fichier_pdf'],
            $data['passport'] ?? '',
            $data['p_id'] ?? '',
            $data['date_embauche'], $data['documents_photo'], $data['date_contrat'], $data['token']
        ]);
    }
}
?>

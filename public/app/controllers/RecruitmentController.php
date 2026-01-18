<?php
// app/controllers/RecruitmentController.php

require_once __DIR__ . '/../models/TokenModel.php';
require_once __DIR__ . '/../models/RecruitmentModel.php';

class RecruitmentController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Affiche le formulaire après vérification du token
    public function showForm() {
        $token = $_GET['token'] ?? '';
        $tokenModel = new TokenModel($this->db);
        $tokenData = $tokenModel->isValidToken($token);
        if (!$tokenData) {
            echo "Token invalide ou déjà utilisé.";
            exit();
        }
        require_once __DIR__ . '/../views/recruitment/form.php';
    }

    // Traitement du formulaire de recrutement
    public function processForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier que le token est fourni
            $token = $_POST['token'] ?? '';
            if (empty($token)) {
                $error = "Token manquant. Veuillez utiliser le lien de recrutement valide.";
                require_once __DIR__ . '/../views/recruitment/form.php';
                exit();
            }
            
            $tokenModel = new TokenModel($this->db);
            $tokenData  = $tokenModel->isValidToken($token);
            if (!$tokenData) {
                $error = "Token invalide ou déjà utilisé.";
                require_once __DIR__ . '/../views/recruitment/form.php';
                exit();
            }
            
            // Récupération des champs textes
            $data = [
                'nom'                        => $_POST['nom'] ?? '',
                'prenom'                     => $_POST['prenom'] ?? '',
                'date_de_naissance'          => $_POST['date_de_naissance'] ?? '',
                'lieu_de_naissance'          => $_POST['lieu_de_naissance'] ?? '',
                'ville_de_naissance'         => $_POST['ville_de_naissance'] ?? '',
                'pays'                       => $_POST['pays'] ?? '',
                'nationalite'                => $_POST['nationalite'] ?? '',
                'num_titre_sejour'           => $_POST['num_titre_sejour'] ?? '',
                'date_expiration_titre_sejour' => $_POST['date_expiration_titre_sejour'] ?? '',
                'num_securite'               => $_POST['num_securite'] ?? '',
                'num_permis'                 => $_POST['num_permis'] ?? '',
                'date_expiration_permis'     => $_POST['date_expiration_permis'] ?? '',
                'adresse'                    => $_POST['adresse'] ?? '',
                'code_postal'                => $_POST['code_postal'] ?? '',
                'ville_residence'            => $_POST['ville_residence'] ?? '',
                'pointeur_chasseur'          => $_POST['pointeur_chasseur'] ?? '',
                'telephone'                  => $_POST['telephone'] ?? '',
                'fax'                        => $_POST['fax'] ?? '',
                'email'                      => $_POST['email'] ?? '',
                'identifiant_france_travail' => $_POST['identifiant_france_travail'] ?? '',
                'code_postal_agence'         => $_POST['code_postal_agence'] ?? '',
                'token'                      => $token
            ];
            
            // Upload du CV (fichier_pdf)
            if (isset($_FILES['fichier_pdf']) && $_FILES['fichier_pdf']['error'] === 0) {
                $maxFileSize = 2 * 1024 * 1024;
                if ($_FILES['fichier_pdf']['size'] > $maxFileSize) {
                    $error = "Le fichier PDF est trop volumineux. Maximum 2MB.";
                    require_once __DIR__ . '/../views/recruitment/form.php';
                    exit();
                }
                $allowedMimeTypes = ['application/pdf'];
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($_FILES['fichier_pdf']['tmp_name']);
                if (!in_array($mimeType, $allowedMimeTypes)) {
                    $error = "Type de fichier non autorisé pour le CV.";
                    require_once __DIR__ . '/../views/recruitment/form.php';
                    exit();
                }
                $allowedExtensions = ['pdf'];
                $ext = strtolower(pathinfo($_FILES['fichier_pdf']['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowedExtensions)) {
                    $error = "Extension de fichier non autorisée pour le CV.";
                    require_once __DIR__ . '/../views/recruitment/form.php';
                    exit();
                }
                // Générer un nom unique pour éviter les duplications
                $pdfName = uniqid('', true) . "_" . basename($_FILES['fichier_pdf']['name']);
                $uploadDir = __DIR__ . '/../../uploads/';
                if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
                $uploadFile = $uploadDir . $pdfName;
                if (move_uploaded_file($_FILES['fichier_pdf']['tmp_name'], $uploadFile)) {
                    chmod($uploadFile, 0644);
                    $data['fichier_pdf'] = $pdfName;
                } else {
                    $error = "Erreur lors de l'upload du CV.";
                    require_once __DIR__ . '/../views/recruitment/form.php';
                    exit();
                }
            } else {
                $error = "Le fichier PDF du CV est requis.";
                require_once __DIR__ . '/../views/recruitment/form.php';
                exit();
            }
            
            // Pour les non français (si la case "Je suis français" n'est pas cochée)
            if (!isset($_POST['is_français']) || $_POST['is_français'] != 1) {
                // Upload du passeport (optionnel)
                if (isset($_FILES['passeport']) && $_FILES['passeport']['error'] === 0) {
                    $maxFileSize = 2 * 1024 * 1024;
                    if ($_FILES['passeport']['size'] > $maxFileSize) {
                        $error = "Le fichier du passeport est trop volumineux. Maximum 2MB.";
                        require_once __DIR__ . '/../views/recruitment/form.php';
                        exit();
                    }
                    $allowedMimeTypes = ['application/pdf'];
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mimeType = $finfo->file($_FILES['passeport']['tmp_name']);
                    if (!in_array($mimeType, $allowedMimeTypes)) {
                        $error = "Type de fichier non autorisé pour le passeport.";
                        require_once __DIR__ . '/../views/recruitment/form.php';
                        exit();
                    }
                    $allowedExtensions = ['pdf'];
                    $ext = strtolower(pathinfo($_FILES['passeport']['name'], PATHINFO_EXTENSION));
                    if (!in_array($ext, $allowedExtensions)) {
                        $error = "Extension de fichier non autorisée pour le passeport.";
                        require_once __DIR__ . '/../views/recruitment/form.php';
                        exit();
                    }
                    $passportName = uniqid('', true) . "_" . basename($_FILES['passeport']['name']);
                    $uploadDir = __DIR__ . '/../../uploads/';
                    if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
                    $uploadFile = $uploadDir . $passportName;
                    if (move_uploaded_file($_FILES['passeport']['tmp_name'], $uploadFile)) {
                        chmod($uploadFile, 0644);
                        $data['passport'] = $passportName;
                    } else {
                        $error = "Erreur lors de l'upload du passeport.";
                        require_once __DIR__ . '/../views/recruitment/form.php';
                        exit();
                    }
                }
                // Upload de la pièce d'identité (obligatoire)
                if (isset($_FILES['p_id']) && $_FILES['p_id']['error'] === 0) {
                    $maxFileSize = 2 * 1024 * 1024;
                    if ($_FILES['p_id']['size'] > $maxFileSize) {
                        $error = "Le fichier de la pièce d'identité est trop volumineux. Maximum 2MB.";
                        require_once __DIR__ . '/../views/recruitment/form.php';
                        exit();
                    }
                    $allowedMimeTypes = ['application/pdf'];
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mimeType = $finfo->file($_FILES['p_id']['tmp_name']);
                    if (!in_array($mimeType, $allowedMimeTypes)) {
                        $error = "Type de fichier non autorisé pour la pièce d'identité.";
                        require_once __DIR__ . '/../views/recruitment/form.php';
                        exit();
                    }
                    $allowedExtensions = ['pdf'];
                    $ext = strtolower(pathinfo($_FILES['p_id']['name'], PATHINFO_EXTENSION));
                    if (!in_array($ext, $allowedExtensions)) {
                        $error = "Extension de fichier non autorisée pour la pièce d'identité.";
                        require_once __DIR__ . '/../views/recruitment/form.php';
                        exit();
                    }
                    $pidName = uniqid('', true) . "_" . basename($_FILES['p_id']['name']);
                    $uploadDir = __DIR__ . '/../../uploads/';
                    if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
                    $uploadFile = $uploadDir . $pidName;
                    if (move_uploaded_file($_FILES['p_id']['tmp_name'], $uploadFile)) {
                        chmod($uploadFile, 0644);
                        $data['p_id'] = $pidName;
                    } else {
                        $error = "Erreur lors de l'upload de la pièce d'identité.";
                        require_once __DIR__ . '/../views/recruitment/form.php';
                        exit();
                    }
                } else {
                    $error = "La pièce d'identité est requise pour les non français.";
                    require_once __DIR__ . '/../views/recruitment/form.php';
                    exit();
                }
            }
            
            // Gestion de la section salarié (facultative)
            if (isset($_POST['isSalarie']) && $_POST['isSalarie'] == 1) {
                $data['date_embauche'] = $_POST['date_embauche'] ?? '';
                if (isset($_FILES['documents_photo']) && $_FILES['documents_photo']['error'] === 0) {
                    $photoName = uniqid('', true) . "_" . basename($_FILES['documents_photo']['name']);
                    $uploadDir = __DIR__ . '/../../uploads/';
                    if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
                    $uploadPhoto = $uploadDir . $photoName;
                    if (move_uploaded_file($_FILES['documents_photo']['tmp_name'], $uploadPhoto)) {
                        chmod($uploadPhoto, 0644);
                        $data['documents_photo'] = $photoName;
                    } else {
                        $error = "Erreur lors de l'upload des documents photo.";
                        require_once __DIR__ . '/../views/recruitment/form.php';
                        exit();
                    }
                } else {
                    $data['documents_photo'] = null;
                }
                $data['date_contrat'] = $_POST['date_contrat'] ?? '';
            } else {
                $data['date_embauche'] = null;
                $data['documents_photo'] = null;
                $data['date_contrat'] = null;
            }
            
            $recruitmentModel = new RecruitmentModel($this->db);
            if ($recruitmentModel->insertApplication($data)) {
                $tokenModel->markAsUsed($token);
                echo "Votre candidature a été enregistrée avec succès.";
            } else {
                echo "Erreur lors de l'enregistrement de la candidature.";
            }
        }
    }
}
?>

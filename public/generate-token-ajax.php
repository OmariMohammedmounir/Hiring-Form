<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Accès non autorisé.']);
    exit;
}

// Set JSON header
header('Content-Type: application/json');

// Include the database connection and TokenModel
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/TokenModel.php';

// Instantiate the TokenModel
$tokenModel = new TokenModel($db);

// Generate a new token
$token = $tokenModel->createToken();

// Dynamically build the base URL using server variables
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
// You can adjust the base path if necessary.
$basePath = BASE_URL.'recrutement';

// Build the dynamic link with the token
$link = $protocol . $host . $basePath . '?token=' . $token;

// Return the token link as JSON
echo json_encode(['link' => $link]);
?>

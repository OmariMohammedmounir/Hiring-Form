<?php
// public/view_pdf.php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: ".BASE_URL."login");
    exit();
}

// Validate the 'file' parameter
if (!isset($_GET['file'])) {
    echo "Fichier non spécifié.";
    exit();
}

// Sanitize the file name to prevent directory traversal attacks
$file = basename($_GET['file']);
$filePath = __DIR__ . '/../public/uploads/' . $file;


if (!file_exists($filePath)) {
    echo "Fichier introuvable.";
    exit();
}

// Set the appropriate headers and output the PDF
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $file . '"');
header('Content-Length: ' . filesize($filePath));
readfile($filePath);
exit();
?>

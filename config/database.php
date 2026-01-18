<?php
// config/database.php
define('BASE_URL', 'http://localhost/weetel/public/');
$host    = 'localhost';
$dbname  = 'mymvc';
$user    = 'root';      // default XAMPP username
$password = '';         // default XAMPP password

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>

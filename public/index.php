<?php
require_once __DIR__ . '/../config/database.php';
// Set session lifetime to 15 minutes (900 seconds)
ini_set('session.gc_maxlifetime', 900);
session_set_cookie_params(900);
session_start();

// Automatic logout after 15 minutes of inactivity
$inactive = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive) {
    // Session expired; clear and destroy session
    session_unset();
    session_destroy();
    header("Location:".BASE_URL."login");
    exit();
}
// Update last activity timestamp
$_SESSION['last_activity'] = time();


$url = $_GET['url'] ?? 'login';
$urlParts = explode('/', $url);
$action = $urlParts[0];

switch ($action) {
    case 'login':
        require_once __DIR__ . '/app/controllers/AdminController.php';
        $adminController = new AdminController($db);
        $adminController->login();
        break;
    case 'dashboard':
        require_once __DIR__ . '/app/controllers/AdminController.php';
        $adminController = new AdminController($db);
        $adminController->dashboard();
        break;
    case 'generate-token':
        require_once __DIR__ . '/app/controllers/AdminController.php';
        $adminController = new AdminController($db);
        $adminController->generateToken();
        break;
    case 'generate-token-ajax':
        require_once __DIR__ . '/app/controllers/AdminController.php';
        $adminController = new AdminController($db);
        $adminController->generateTokenAjax();
        break;
    case 'logout':
        require_once __DIR__ . '/app/controllers/AdminController.php';
        $adminController = new AdminController($db);
        $adminController->logout();
        break;
    case 'recrutement':
        require_once __DIR__ . '/app/controllers/RecruitmentController.php';
        $recruitmentController = new RecruitmentController($db);
        $recruitmentController->showForm();
        break;
    case 'process-recruitment':
        require_once __DIR__ . '/app/controllers/RecruitmentController.php';
        $recruitmentController = new RecruitmentController($db);
        $recruitmentController->processForm();
        break;
    default:
        echo "404 - Page not found.";
        break;
}
?>

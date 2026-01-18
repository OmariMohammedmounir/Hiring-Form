<?php
require_once __DIR__ . '/../models/AdminModel.php';


class AdminController {
    private $adminModel;

    public function __construct($db) {
        $this->adminModel = new AdminModel($db);
    }

    public function login() {
        if (isset($_SESSION['admin'])) {
            header("Location: ".BASE_URL."dashboard");
            exit();
        }
        require_once __DIR__ . '/../models/LoginAttemptModel.php';
        $attemptModel = new LoginAttemptModel($this->adminModel->getDb());
        $username = $_POST['username'] ?? '';
        $ip = $_SERVER['REMOTE_ADDR'];
        $attempts = $attemptModel->getAttemptsCount($username, $ip, 15);
        if ($attempts >= 5) {
            $error = "Trop de tentatives échouées. Veuillez réessayer plus tard.";
            require_once __DIR__ . '/../views/admin/login.php';
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'];
            $admin = $this->adminModel->login($username, $password);
            if ($admin) {
                $attemptModel->clearAttempts($username, $ip);
                $_SESSION['admin'] = $admin;
                header("Location: ".BASE_URL."dashboard");
                exit();
            } else {
                $attemptModel->recordAttempt($username, $ip);
                $error = "Identifiants incorrects.";
                require_once __DIR__ . '/../views/admin/login.php';
            }
        } else {
            require_once __DIR__ . '/../views/admin/login.php';
        }
    }

    public function dashboard() {
        if (!isset($_SESSION['admin'])) {
            header("Location: ".BASE_URL."login");
            exit();
        }
        require_once __DIR__ . '/../models/RecruitmentModel.php';
        $recruitmentModel = new RecruitmentModel($this->adminModel->getDb());
        $applications = $recruitmentModel->getAllApplications();
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function generateToken() {
        if (!isset($_SESSION['admin'])) {
            header("Location: ".BASE_URL."login");
            exit();
        }
        require_once __DIR__ . '/../models/TokenModel.php';
        $tokenModel = new TokenModel($this->adminModel->getDb());
        $token = $tokenModel->createToken();
        $lien = BASE_URL."recrutement?token=" . $token;
        require_once __DIR__ . '/../models/RecruitmentModel.php';
        $recruitmentModel = new RecruitmentModel($this->adminModel->getDb());
        $applications = $recruitmentModel->getAllApplications();
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function generateTokenAjax() {
        if (!isset($_SESSION['admin'])) {
            http_response_code(403);
            echo json_encode(["error" => "Non authentifié"]);
            exit();
        }
        require_once __DIR__ . '/../models/TokenModel.php';
        $tokenModel = new TokenModel($this->adminModel->getDb());
        $token = $tokenModel->createToken();
        $lien = BASE_URL."recrutement?token=" . $token;
        header('Content-Type: application/json');
        echo json_encode(["link" => $lien]);
        exit();
    }

    public function logout() {
        session_destroy();
        header("Location: ".BASE_URL."login");
        exit();
    }
}
?>

<?php
class FormController {
    public function displayForm() {
        if (!isset($_GET['token'])) {
            echo "Invalid access.";
            exit;
        }
        $tokenValue = $_GET['token'];
        $tokenData = Token::validate($tokenValue);
        if (!$tokenData) {
            echo "Token is invalid or expired.";
            exit;
        }
        require_once '../app/views/form/display_form.php';
    }


    public function submitForm() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tokenValue = $_POST['token'];
            $tokenData = Token::validate($tokenValue);
            if (!$tokenData) {
                echo "Invalid or expired token.";
                exit;
            }
            // Save the form submission
            FormSubmission::create($_POST, $tokenData['id']);
            // Optionally, mark the token as used
            Token::markUsed($tokenData['id']);
            require_once '../app/views/form/success.php';
        }
    }
}

<?php
require_once '../controllers/authController.php';
require_once '../controllers/modelController.php';
require_once '../controllers/freeDayController.php';
require_once '../controllers/sessionController.php';

$authController = new AuthController();
$modelController = new ModelController();
$freeDayController = new FreeDayController();
$sessionController = new SessionController();

function showErrorMessage($message) {
    if (is_array($message)) {
        $message = implode('<br>', $message);
    }
    echo "<div style='background-color: #ffdddd; color: #d8000c; border: 1px solid #d8000c; padding: 15px; margin: 20px; border-radius: 5px; font-family: Arial, sans-serif; text-align: center; font-size: 16px;'>
        <strong>Błąd: </strong> $message
    </div>";
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $result = null;

    switch ($action) {
        case 'register':
            $result = $authController->register();
            break;
        case 'login':
            $result = $authController->login();
            break;
        case 'logout':
            $authController->logout();
            header('Location: ../views/login.php');
            exit;
        case 'update':
            $result = $authController->updateProfile();
            break;
        case 'addmodelprofile':
            $result = $modelController->register();
            break;
        case 'updatemodelprofile':
            $result = $modelController->updateModelProfile();
            break;
        case 'addNewFreeDay':
            $result = $freeDayController->addFreeDay();
            break;
        case 'addNewSession':
            $result = $sessionController->addSession();
            break;
        default:
            showErrorMessage("Nieznana akcja.");
            exit;
    }

    if (is_array($result) && isset($result['success'])) {
        if ($result['success']) {
            $redirect = '../views/profile.php';
            switch ($action) {
                case 'register':
                    $redirect = '../views/login.php';
                    break;
                case 'addmodelprofile':
                case 'updatemodelprofile':
                    $redirect = '../views/models.php';
                    break;
                case 'addNewFreeDay':
                    $redirect = '../views/admin_panel.php';
                    break;
                case 'addNewSession':
                    $redirect = '../views/makeAppointmeny.php';
                    break;
            }
            header("Location: $redirect");
            exit;
        } else {
            showErrorMessage($result['message'] ?? "Wystąpił nieznany błąd.");
        }
    } elseif (is_string($result)) {
        showErrorMessage($result);
    } else {
        showErrorMessage("Wystąpił nieoczekiwany błąd.");
    }
} else {
    showErrorMessage("Brak akcji.");
}

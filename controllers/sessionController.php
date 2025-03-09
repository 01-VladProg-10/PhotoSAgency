<?php
require_once '../models/session.php';
require_once '../services/sessionService.php';
require_once '../config/db.php';
class SessionController{
    private $sessionService;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->sessionService = new sessionService($db);
    }
    public function addSession() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Błąd: Nieprawidłowe żądanie.'];
        }
    
        if (!isset($_POST['selected-date']) || empty($_POST['selected-date'])) {
            return ['success' => false, 'message' => 'Błąd: Brak wybranej daty.'];
        }
    
        if (!isset($_POST['available-times']) || empty($_POST['available-times'])) {
            return ['success' => false, 'message' => 'Błąd: Brak dostępnego czasu.'];
        }
    
        if (!isset($_COOKIE['user_id']) || empty($_COOKIE['user_id'])) {
            return ['success' => false, 'message' => 'Błąd: Brak ID użytkownika. Zaloguj się ponownie.'];
        }
    
        try {
            session_start();
            $userId = $_COOKIE['user_id'];
            $selectedTime = $_POST['available-times'];
            $selectedTime = substr($selectedTime, 0, -3);
            
            $dateTime = new DateTime($selectedTime);
            $dateTime->modify('+1 day');
            $dateTime->modify('-1 hour');
    
            $result['message'] = $this->sessionService->addSession($userId, $dateTime->format('Y-m-d H:i'));
    
            if ($result['message']) {
                return ['success' => true, 'message' => $result['message']];
            } else {
                return ['success' => false, 'message' => 'Nie udało się dodać sesji.'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Błąd serwera: ' . $e->getMessage()];
        }
    }
    
}
?>
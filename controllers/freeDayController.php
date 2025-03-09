<?php
require_once '../models/freeDay.php';
require_once '../services/FreeDayService.php';
require_once '../config/db.php';
class FreeDayController{
    private $freeDayService;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->freeDayService = new FreeDayService($db);
    }
    public function addFreeDay() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Błąd: Nieprawidłowe żądanie.'];
        }

        if (!isset($_POST['datetime']) || empty($_POST['datetime'])) {
            return ['success' => false, 'message' => 'Błąd: Brak wymaganych danych (datetime).'];
        }

        try {
            $data['date'] = $_POST['datetime'];
            $freeDay = new FreeDay($data);
            $result = $this->freeDayService->addFreeDay($freeDay);

            return is_string($result) 
                ? ['success' => false, 'message' => $result] 
                : ['success' => true, 'message' => 'Nowy wolny termin został dodany!'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Błąd serwera: ' . $e->getMessage()];
        }
    } 
}
?>
<?php
require_once '../models/freeDay.php';
require_once '../config/db.php';
class FreeDayService{
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function addFreeDay($data) {
        // Sprawdzenie, czy data już istnieje
        $checkQuery = "SELECT COUNT(*) FROM available_dates WHERE date = :date";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bindParam(':date', $data->date);
        $checkStmt->execute();
        
        if ($checkStmt->fetchColumn() > 0) {
            return "Błąd: Ten termin już istnieje!";
        }
    
        // Jeśli nie istnieje, dodaj nowy termin
        $query = "INSERT INTO available_dates (date, is_booked) VALUES (:date, 0)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':date', $data->date);
    
        if (!$stmt->execute()) {
            return "Błąd przy dodawaniu nowego wolnego terminu!";
        }
    }
    
}
?>
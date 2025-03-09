<?php
require_once '../models/session.php';
require_once '../config/db.php';
class SessionService{
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function addSession($userId, $selectedDate) {
        try {
            $sql = "SELECT id FROM available_dates WHERE date = :date AND is_booked = 0 LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':date', $selectedDate);
            $stmt->execute();
    
            // Sprawdź, czy znaleziono dostępny termin
            if ($stmt->rowCount() > 0) {
                $dateRecord = $stmt->fetch(PDO::FETCH_ASSOC);
                $availableDateId = $dateRecord['id'];
    
                // Krok 2: Zaktualizuj status 'is_booked' na 1 (zarezerwowany)
                $updateSql = "UPDATE available_dates SET is_booked = 1 WHERE id = :id";
                $updateStmt = $this->db->prepare($updateSql);
                $updateStmt->bindParam(':id', $availableDateId);
                $updateStmt->execute();
    
                // Krok 3: Dodaj nową sesję do tabeli sessions
                $insertSql = "INSERT INTO sessions (user_id, available_date_id) VALUES (:user_id, :available_date_id)";
                $insertStmt = $this->db->prepare($insertSql);
                $insertStmt->bindParam(':user_id', $userId);
                $insertStmt->bindParam(':available_date_id', $availableDateId);
                $insertStmt->execute();
    
                return "Sesja została pomyślnie dodana.";
            } else {
                throw new Exception("Brak dostępnych terminów w wybranej dacie.");
            }
        } catch (PDOException $e) {
            // Obsługuje błędy PDO (baza danych)
            throw new Exception("Błąd bazy danych: " . $e->getMessage());
        } catch (Exception $e) {
            // Obsługuje inne błędy
            throw new Exception("Błąd: " . $e->getMessage());
        }
    }    
}
?>
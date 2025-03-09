<?php

class Database {
    private $host = 'localhost';        // Adres hosta bazy danych
    private $db_name = 'photo'; // Nazwa bazy danych
    private $username = 'root';         // Nazwa użytkownika bazy danych
    private $password = '';             // Hasło użytkownika bazy danych
    private $conn;                      // Zmienna przechowująca połączenie

    /**
     * Pobranie połączenia z bazą danych
     * 
     * @return PDO|null
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Obsługa wyjątków
        } catch (PDOException $exception) {
            echo "Błąd połączenia z bazą danych: " . $exception->getMessage();
        }

        return $this->conn;
    }
}

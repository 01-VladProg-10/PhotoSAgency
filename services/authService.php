<?php
require_once '../models/user.php';

class UserService {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function register(UserModel $user) {
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $user->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'Użytkownik o podanym adresie e-mail już istnieje.'];
        }

        $hashedPassword = password_hash($user->password, PASSWORD_BCRYPT);

        $query = "INSERT INTO users (username, email, password, role, specialization, photo_main, photo_bg) 
                  VALUES (:username, :email, :password, :role, :specialization, :profile_photo, :cover_photo)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $user->username);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $user->role);
        $stmt->bindParam(':specialization', $user->specialization);
        $stmt->bindParam(':profile_photo', $user->profilePhoto, PDO::PARAM_LOB);
        $stmt->bindParam(':cover_photo', $user->coverPhoto, PDO::PARAM_LOB);

        if ($stmt->execute()) {
            $userId = $this->db->lastInsertId();
            setcookie('user_id', $userId, time() + (86400 * 30), '/');
            return ['success' => true, 'message' => 'Rejestracja zakończona sukcesem.', 'user_id' => $userId];
        } else {
            return ['success' => false, 'message' => 'Wystąpił błąd podczas rejestracji.'];
        }
    }

    public function authenticate($email, $password) {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData && password_verify($password, $userData['password'])) {
            setcookie('user_id', $userData['id'], time() + (86400 * 30), '/');
            return ['success' => true, 'message' => 'Zalogowano pomyślnie.', 'user_id' => $userData['id']];
        } else {
            return ['success' => false, 'message' => 'Nieprawidłowy email lub hasło.'];
        }
    }

    public function findUserById($user_id) {
        $query = "SELECT * FROM users WHERE id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(UserModel $user) {
        // Start the query with the common fields
        $query = "UPDATE users 
                  SET username = :username,
                      email = :email,
                      role = :role,
                      specialization = :specialization";
        
        // Add password update conditionally
        if (!empty($user->password)) {
            $query .= ", password = :password";
        }
        
        // Add profile photo update conditionally
        if (!empty($user->profilePhoto)) {
            $query .= ", photo_main = :profile_photo";
        }
        
        // Add cover photo update conditionally
        if (!empty($user->coverPhoto)) {
            $query .= ", photo_bg = :cover_photo";
        }
    
        // Adding the WHERE clause
        $query .= " WHERE id = :id";
    
        // Prepare the statement
        $stmt = $this->db->prepare($query);
    
        // Bind the parameters that are always present
        $stmt->bindParam(':username', $user->username);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':role', $user->role);
        $stmt->bindParam(':specialization', $user->specialization);
        $stmt->bindParam(':id', $user->id, PDO::PARAM_INT);
    
        // Bind optional parameters (only if they exist)
        if (!empty($user->password)) {
            $hashedPassword = password_hash($user->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $hashedPassword);
        }
        if (!empty($user->profilePhoto)) {
            $stmt->bindParam(':profile_photo', $user->profilePhoto, PDO::PARAM_LOB);
        }
        if (!empty($user->coverPhoto)) {
            $stmt->bindParam(':cover_photo', $user->coverPhoto, PDO::PARAM_LOB);
        }
        // Execute the statement
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Profil został zaktualizowany.'];
        } else {
            $errorInfo = $stmt->errorInfo();
            return ['success' => false, 'message' => 'Błąd podczas aktualizacji profilu. ' . $errorInfo[2]];
        }
    }
}

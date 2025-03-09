<?php
// services/UserService.php

require_once '../models/model.php';

class modelService
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function getModelIdIfExists($user_id)
    {
    $query = "SELECT id FROM models WHERE user_id = :user_id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $model = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If the model exists, return the id_model; otherwise, return false
    return $model ? $model['id'] : false;
    }

    public function isModelOwnedByUser($userId, $modelId)
{
    try {
        $query = $this->db->prepare("SELECT user_id FROM models WHERE id = :model_id");
        $query->bindParam(':model_id', $modelId, PDO::PARAM_INT);
        $query->execute();
        
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($result && $result['user_id'] == $userId) {
            return true;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Błąd przy sprawdzaniu właściciela modela: " . $e->getMessage());
        return false;
    }
}


    public function getModelProfileById($userId)
    {
        try {
            $query = $this->db->prepare("SELECT * FROM models WHERE user_id = :user_id");
            $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $query->execute();

            $profile = $query->fetch(PDO::FETCH_ASSOC);
            return $profile ? $profile : null;
        } catch (PDOException $e) {
            error_log("Błąd przy pobieraniu profilu modela: " . $e->getMessage());
            return null;
        }
    }


    public function getModels() {
        $query = "
           SELECT models.*, users.username, users.photo_main, users.specialization
           FROM models
           JOIN users ON models.user_id = users.id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $models = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        return $models;

    }

    public function register(Model $user) {
        try {
            // Sprawdzanie, czy użytkownik o podanym numerze telefonu już istnieje
            $query = "SELECT id FROM models WHERE phone_number = :phone_number";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':phone_number', $user->phone_number);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Użytkownik o podanym numerze telefonu już istnieje.'];
            }
    
            // Transakcja: zaczynamy operację wstawiania danych
            $this->db->beginTransaction();
    
            // Przygotowanie zapytania SQL do wstawienia użytkownika
            $query = "INSERT INTO models (hobbies, age, height, dimensions, eye_color, hair_color, experience, phone_number, instagram, user_id) 
                      VALUES (:hobbies, :age, :height, :dimensions, :eye_color, :hair_color, :experience, :phone_number, :instagram, :user_id)";
            $stmt = $this->db->prepare($query);
    
            // Przypisywanie parametrów
            $stmt->bindParam(':hobbies', $user->hobbies);
            $stmt->bindParam(':age', $user->age, PDO::PARAM_INT);
            $stmt->bindParam(':height', $user->height);
            $stmt->bindParam(':dimensions', $user->dimensions);
            $stmt->bindParam(':eye_color', $user->eye_color);
            $stmt->bindParam(':hair_color', $user->hair_color);
            $stmt->bindParam(':experience', $user->experience);
            $stmt->bindParam(':phone_number', $user->phone_number);
            $stmt->bindParam(':instagram', $user->instagram);
            $stmt->bindParam(':user_id', $user->user_id, PDO::PARAM_INT);
    
            // Wykonanie zapytania
            if ($stmt->execute()) {
                $userId = $this->db->lastInsertId(); // Pobranie ID nowego użytkownika
    
                // Dodanie dodatkowych zdjęć do tabeli 'model_photos' (jeśli istnieją)
                if (!empty($user->photos)) {
                    $photoQuery = "INSERT INTO model_photos (model_id, photo_path) VALUES (:model_id, :photo_path)";
                    $photoStmt = $this->db->prepare($photoQuery);
                    $photoStmt->bindParam(':model_id', $userId, PDO::PARAM_INT);
                    $photoStmt->bindParam(':photo_path', $user->photos);
                    
                        if (!$photoStmt->execute()) {
                            // Jeśli zapis zdjęcia się nie powiedzie, cofamy transakcję
                            $this->db->rollBack();
                            return ['success' => false, 'message' => 'Nie udało się zapisać zdjęć dodatkowych.'];
                        }
                }
    
                // Dodanie wideo do tabeli 'model_videos' (jeśli wideo zostało przesłane)

                if (!empty($user->video)) {
                    $videoQuery = "INSERT INTO videos (model_id, file_path) VALUES (:model_id, :video_path)";
                    $videoStmt = $this->db->prepare($videoQuery);

                    $videoStmt->bindParam(':model_id', $userId, PDO::PARAM_INT);
                    $videoStmt->bindParam(':video_path', $user->video);
                    if (!$videoStmt->execute()) {
                        $this->db->rollBack();
                        return ['success' => false, 'message' => 'Nie udało się zapisać wideo.'];
                    }
                }
    
                // Zatwierdzenie transakcji
                $this->db->commit();
    
                return [
                    'success' => true,
                    'message' => 'Rejestracja zakończona sukcesem.',
                    'user_id' => $userId,
                ];
            } else {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Wystąpił błąd podczas dodawania użytkownika.'];
            }
        } catch (\Exception $e) {
            // W przypadku błędu cofamy transakcję
            $this->db->rollBack();
            return [
                'success' => false,
                'message' => 'Wystąpił błąd: ' . $e->getMessage(),
            ];
        }
    }
    public function getModelById(int $modelId): ?array
{
    $sql = "
    SELECT
        u.id, 
        u.username, 
        u.email AS contact_email, 
        u.specialization,
        u.photo_main,
        m.hobbies, 
        m.age, 
        m.height, 
        m.dimensions, 
        m.eye_color, 
        m.hair_color, 
        m.experience, 
        m.phone_number, 
        m.instagram AS instagram_handle,
        -- Adding video and photo paths
        GROUP_CONCAT(DISTINCT mv.file_path) AS video_paths,  
        GROUP_CONCAT(DISTINCT mp.photo_path) AS photo_paths
    FROM 
        models m
    INNER JOIN 
        users u 
        ON m.user_id = u.id
    LEFT JOIN 
        videos mv 
        ON mv.model_id = m.id
    LEFT JOIN 
        model_photos mp 
        ON mp.model_id = m.id
    WHERE 
        m.id = :model_id
    GROUP BY 
        m.id, u.id";

    // Prepare the SQL statement
    $stmt = $this->db->prepare($sql);

    // Bind the model_id parameter
    $stmt->bindParam(':model_id', $modelId, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Fetch the result
    $model = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no model is found, return null
    if (!$model) {
        return null;
    }

    // Process video and photo paths into arrays
    $model['video_paths'] = !empty($model['video_paths']) ? explode(',', $model['video_paths']) : [];
    $model['photo_paths'] = !empty($model['photo_paths']) ? explode(',', $model['photo_paths']) : [];

    return $model;
}

public function updateModelProfile($data)
{
    // Przygotowanie danych do zapisu
    $hobbies = $data->hobbies;
    $age = $data->age;
    $height = $data->height;
    $dimensions = $data->dimensions;
    $eye_color = $data->eye_color;
    $hair_color = $data->hair_color;
    $experience = $data->experience;
    $phone_number = $data->phone_number;
    $instagram = $data->instagram;
    $user_id = $_COOKIE['user_id'];

    // Zapisywanie danych tekstowych
    $stmt = $this->db->prepare("UPDATE models
        SET hobbies = :hobbies, age = :age, height = :height, dimensions = :dimensions,
            eye_color = :eye_color, hair_color = :hair_color, experience = :experience,
            phone_number = :phone_number, instagram = :instagram
        WHERE user_id = :user_id");

    $stmt->bindParam(':hobbies', $hobbies);
    $stmt->bindParam(':age', $age);
    $stmt->bindParam(':height', $height);
    $stmt->bindParam(':dimensions', $dimensions);
    $stmt->bindParam(':eye_color', $eye_color);
    $stmt->bindParam(':hair_color', $hair_color);
    $stmt->bindParam(':experience', $experience);
    $stmt->bindParam(':phone_number', $phone_number);
    $stmt->bindParam(':instagram', $instagram);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Obsługa zdjęć
        if (!empty($data->photos)) {
            $this->updatePhotos($user_id, $data->photos);
        }

        // Obsługa wideo
        if (!empty($data->video)) {
            $this->updateVideo($user_id, $data->video);
        }

        return ['success' => true, 'message' => 'Profil modela został zaktualizowany.'];
    } else {
        $errorInfo = $stmt->errorInfo();
        return ['success' => false, 'message' => 'Błąd podczas aktualizacji profilu modela. ' . $errorInfo[2]];
    }
}

private function updatePhotos($user_id, $photos)
{
    // Pobranie model_id na podstawie user_id
    $stmt = $this->db->prepare("SELECT id FROM models WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $model = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jeśli model_id nie istnieje, kończymy metodę
    if (!$model) {
        
        return ['success' => false, 'message' => 'Nie znaleziono modelu dla tego użytkownika.'];
    }
    $model_id = $model['id'];

    // Usuwamy poprzednie zdjęcia na podstawie model_id
    $stmt = $this->db->prepare("DELETE FROM model_photos WHERE model_id = :model_id");
    $stmt->bindParam(':model_id', $model_id);
    $stmt->execute();

    // Dodajemy nowe zdjęcia
    $photoQuery = "INSERT INTO model_photos (model_id, photo_path) VALUES (:model_id, :photo_path)";
    $photoStmt = $this->db->prepare($photoQuery);
    $photoStmt->bindParam(':model_id', $model_id, PDO::PARAM_INT);
    $photoStmt->bindParam(':photo_path', $photos);
    if (!$photoStmt->execute()) {
        // Jeśli zapis zdjęcia się nie powiedzie, cofamy transakcję
        $this->db->rollBack();
        return ['success' => false, 'message' => 'Nie udało się zapisać zdjęć dodatkowych.'];
    }
}

// Metoda do aktualizacji wideo
private function updateVideo($user_id, $video)
{
    // Pobranie model_id na podstawie user_id
    $stmt = $this->db->prepare("SELECT id FROM models WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $model = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jeśli model_id nie istnieje, kończymy metodę
    if (!$model) {
        return ['success' => false, 'message' => 'Nie znaleziono modelu dla tego użytkownika.'];
    }

    $model_id = $model['id'];

    // Usuwamy poprzednie wideo na podstawie model_id
    $stmt = $this->db->prepare("DELETE FROM videos WHERE model_id = :model_id");
    $stmt->bindParam(':model_id', $model_id);
    $stmt->execute();

    $videoQuery = "INSERT INTO videos (model_id, file_path) VALUES (:model_id, :video_path)";
    $videoStmt = $this->db->prepare($videoQuery);

    $videoStmt->bindParam(':model_id', $model_id, PDO::PARAM_INT);
    $videoStmt->bindParam(':video_path', $video);
    if (!$videoStmt->execute()) {
        $this->db->rollBack();
        return ['success' => false, 'message' => 'Nie udało się zapisać wideo.'];
    }
}
 
}
<?php

require_once '../services/modelService.php';
require_once '../config/db.php';
require_once '../models/model.php';

class ModelController
{
    private $modelService;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->modelService = new modelService($db);
    }

    public function checkIfModelExists($user_id)
    {
        return $this->modelService->getModelIdIfExists($user_id);
    }

    public function showModels() {
        $models = $this->modelService->getModels();
        return $models;
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $_POST['id'] ?? null,
                'hobbies' => $_POST['hobbies'] ?? '',
                'age' => (int)$_POST['age'] ?? 0,
                'height' => (int)$_POST['height'] ?? 0,
                'dimensions' => $_POST['dimensions'] ?? '',
                'eye_color' => $_POST['eye_color'] ?? '',
                'hair_color' => $_POST['hair_color'] ?? '',
                'experience' => $_POST['experience'] ?? '',
                'phone_number' => $_POST['phone_number'] ?? '',
                'instagram' => $_POST['instagram'] ?? '',
                'user_id' => (int)$_POST['user_id'] ?? null,
            ];

            if (empty($data['phone_number'])) {
                return ['success' => false, 'message' => 'Numer telefonu jest wymagany.'];
            }
            if (empty($data['instagram'])) {
                return ['success' => false, 'message' => 'Instagram jest wymagany.'];
            }
            if ($data['age'] <= 0 || $data['age'] > 100) {
                return ['success' => false, 'message' => 'Wiek musi być w zakresie od 18 do 100 lat.'];
            }

            if (isset($_FILES['photos'])) {
                $additionalPhotos = $this->uploadMultipleFiles($_FILES['photos'],$data['instagram']);
                if ($additionalPhotos === false) {
                    return ['success' => false, 'message' => 'Nie udało się przesłać zdjęć dodatkowych.'];
                }
                $data['photo_paths'] = implode(',', $additionalPhotos);
            }

            if (isset($_FILES['video'])) {
                $video = $this->uploadFile($_FILES['video'], $data['instagram']);
                if ($video === false) {
                    return ['success' => false, 'message' => 'Nie udało się przesłać wideo.'];
                }
                $data['video'] = $video;
            }

            $model = new Model($data);

            try {
                $result = $this->modelService->register($model);
                if ($result['success']) {
                    return [
                        'success' => true,
                        'message' => 'Rejestracja zakończona sukcesem.',
                        'data' => $result['data'] ?? null,
                    ];
                }
                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Nie udało się zarejestrować użytkownika.',
                ];
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => 'Wystąpił błąd podczas rejestracji: ' . $e->getMessage(),
                ];
            }
        }

        return ['success' => false, 'message' => 'Nieprawidłowa metoda żądania.'];
    }

    public function updateModelProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $_POST['id'] ?? null,
                'hobbies' => $_POST['hobbies'] ?? '',
                'age' => (int)$_POST['age'] ?? 0,
                'height' => (int)$_POST['height'] ?? 0,
                'dimensions' => $_POST['dimensions'] ?? '',
                'eye_color' => $_POST['eye_color'] ?? '',
                'hair_color' => $_POST['hair_color'] ?? '',
                'experience' => $_POST['experience'] ?? '',
                'phone_number' => $_POST['phone_number'] ?? '',
                'instagram' => $_POST['instagram'] ?? '',
                'user_id' => (int)$_POST['user_id'] ?? null,
            ];
    
            if (empty($data['phone_number'])) {
                return ['success' => false, 'message' => 'Telefon jest wymagany, aby zaktualizować profil.'];
            }
            if (empty($data['instagram'])) {
                return ['success' => false, 'message' => 'Instagram jest wymagany, aby uzupełnić profil.'];
            }
            if ($data['age'] <= 0 || $data['age'] > 100) {
                return ['success' => false, 'message' => 'Wiek musi mieścić się w zakresie od 18 do 100 lat.'];
            }
    
            if (isset($_FILES['photos']) && !empty($_FILES['photos']['name'][0])) {
                // Sprawdzamy, czy zostały przesłane jakieś zdjęcia
                $additionalPhotos = $this->uploadMultipleFiles($_FILES['photos'], $data['instagram']);
                if ($additionalPhotos === false) {
                    return ['success' => false, 'message' => 'Wystąpił problem z przesyłaniem zdjęć. Spróbuj ponownie.'];
                }
                $data['photo_paths'] = implode(',', $additionalPhotos);
            }
            
            if (isset($_FILES['video']) && !empty($_FILES['video']['name'])) {
                // Sprawdzamy, czy zostało przesłane wideo
                $video = $this->uploadFile($_FILES['video'], $data['instagram']);
                if ($video === false) {
                    return ['success' => false, 'message' => 'Nie udało się przesłać wideo. Spróbuj ponownie.'];
                }
                $data['video'] = $video;
            }
            
            $model = new Model($data);
    
            try {
                $result = $this->modelService->updateModelProfile($model);
                if ($result['success']) {
                    return [
                        'success' => true,
                        'message' => 'Twój profil został zaktualizowany pomyślnie.',
                        'data' => $result['data'] ?? null,
                    ];
                }
                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Aktualizacja profilu nie powiodła się. Spróbuj ponownie.',
                ];
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => 'Błąd podczas aktualizacji: ' . $e->getMessage(),
                ];
            }
        }
    
        return ['success' => false, 'message' => 'Niepoprawna metoda żądania. Tylko POST jest obsługiwany.'];
    }
    

    
    public function isProfileOwner($userId, $modelId)
    {
        return $this->modelService->isModelOwnedByUser($userId, $modelId);
    }

    public function getModelProfileData($userId)
    {
        if (!$userId || !is_numeric($userId)) {
            return null;
        }
        return $this->modelService->getModelProfileById($userId);
    }

    public function showModelProfile(int $modelId): ?array
    {
        $model = $this->modelService->getModelById($modelId);
        if (!$model) {
            http_response_code(404);
            return null;
        }
        return $model;
    }

    private function uploadMultipleFiles($files, $username) {
        $uploadedPaths = [];
        $uploadDir = __DIR__ . "/../uploads/model_$username/";

        // Sprawdzanie katalogu
        if (is_dir($uploadDir)) {
            $this->deleteDirectory($uploadDir);
        }

        if (!mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
            error_log("Nie udało się utworzyć katalogu: $uploadDir");
            return false;
        }

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                error_log("Błąd przesyłania pliku {$files['name'][$i]}: Kod błędu {$files['error'][$i]}");
                continue;
            }

            $tmpName = $files['tmp_name'][$i];
            $fileName = uniqid() . "_" . basename($files['name'][$i]);
            $targetPath = $uploadDir . $fileName;

            if (is_uploaded_file($tmpName) && move_uploaded_file($tmpName, $targetPath)) {
                $uploadedPaths[] = "uploads/model_$username/" . $fileName;
            } else {
                error_log("Nie udało się przenieść pliku: $tmpName do $targetPath");
            }
        }

        return !empty($uploadedPaths) ? $uploadedPaths : false;
    }

    private function uploadFile($file, $username) {
        $uploadDir = __DIR__ . "/../uploads/modelVideo_$username/";

        if (is_dir($uploadDir)) {
            $this->deleteDirectory($uploadDir);
        }

        // Sprawdzanie katalogu
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
            error_log("Nie udało się utworzyć katalogu: $uploadDir");
            return false;
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $uploadFile = $uploadDir . $fileName;

        if (is_uploaded_file($file['tmp_name']) && move_uploaded_file($file['tmp_name'], $uploadFile)) {
            return "uploads/modelVideo_$username/" . $fileName;
        }

        error_log("Nie udało się przenieść pliku do $uploadFile");
        return false;
    }

    private function deleteDirectory($dir) {
        if (!is_dir($dir)) return;

        $items = array_diff(scandir($dir), ['.', '..']);
        foreach ($items as $item) {
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        rmdir($dir);
    }
}

<?php
require_once '../services/authService.php';
require_once '../models/user.php';
require_once '../config/db.php';

class AuthController {
    private $userService;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->userService = new UserService($db);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm-password'];
            $role = $_POST['role'];
            $specialization = $_POST['specialization'];

            if ($password !== $confirmPassword) {
                return ['success' => false, 'message' => 'Hasła nie pasują do siebie.'];
            }

            $profilePhoto = isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK
                ? file_get_contents($_FILES['profile_photo']['tmp_name'])
                : null;

            $coverPhoto = isset($_FILES['cover_photo']) && $_FILES['cover_photo']['error'] === UPLOAD_ERR_OK
                ? file_get_contents($_FILES['cover_photo']['tmp_name'])
                : null;

                $user = new UserModel([
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'role' => $role,
                    'specialization' => $specialization,
                    'profilePhoto' => $profilePhoto,
                    'coverPhoto' => $coverPhoto
                ]);
            return $this->userService->register($user);
        }
        return ['success' => false, 'message' => 'Nieprawidłowa metoda żądania.'];
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            return $this->userService->authenticate($email, $password);
        }
        return ['success' => false, 'message' => 'Nieprawidłowa metoda żądania.'];
    }

    public function logout() {
        if (isset($_COOKIE['user_id'])) {
            setcookie('user_id', '', time() - 3600, '/');
        }
        return ['success' => true, 'message' => 'Wylogowano pomyślnie.'];
    }

    public function getUserData($user_id) {
        return $this->userService->findUserById($user_id);
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_COOKIE['user_id'];
            $username = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];
            $specialization = $_POST['specialization'];

            $profilePhoto = isset($_FILES['profile-picture']) && $_FILES['profile-picture']['error'] == UPLOAD_ERR_OK
                ? file_get_contents($_FILES['profile-picture']['tmp_name'])
                : null;

            $coverPhoto = isset($_FILES['cover-photo']) && $_FILES['cover-photo']['error'] == UPLOAD_ERR_OK
                ? file_get_contents($_FILES['cover-photo']['tmp_name'])
                : null;

            $hash = password_hash($password, PASSWORD_BCRYPT);
            $id = $_COOKIE['user_id'];
            $user = new UserModel([
                'id' => $id,
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'role' => $role,
                'specialization' => $specialization,
                'profilePhoto' => $profilePhoto,
                'coverPhoto' => $coverPhoto
            ]);

            $result = $this->userService->update($user);

            if ($result['success']) {
                header('Location: ../views/profile.php');
            } else {
                return $result['message'];
            }
        }
    }
}

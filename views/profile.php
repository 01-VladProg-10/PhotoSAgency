<?php 
// Wczytanie kontrolera i konfiguracji bazy danych
require_once '../controllers/authController.php';
require_once '../config/db.php';

// Sprawdzenie ciasteczka user_id
if (!isset($_COOKIE['user_id'])) {
    header("Location: ./login.php"); // Przekierowanie do strony logowania
    exit();
}

$user_id = $_COOKIE['user_id']; // Pobranie ID użytkownika z ciasteczka

$userController = new authController();

// Pobranie danych użytkownika
$user = $userController->getUserData($user_id);

// Obsługa przypadku, gdy użytkownik nie istnieje
if (!$user) {
    echo "Nie znaleziono użytkownika.";
    exit();
}

// Przypisanie danych użytkownika do zmiennych
$user_name = !empty($user['username']) ? htmlspecialchars($user['username']) : 'Nieznany użytkownik';
$user_email = !empty($user['email']) ? htmlspecialchars($user['email']) : 'Brak emaila';
$user_specialization = !empty($user['specialization']) ? htmlspecialchars($user['specialization']) : 'Brak specjalizacji';
$user_bio = !empty($user['bio']) ? htmlspecialchars($user['bio']) : '';
$user_main_photo = $user['photo_main']; // BLOB for main photo
$user_photo_bg = $user['photo_bg'];     // BLOB for background photo
?>
<?php include '../includes/language.php' ?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['user_profile'] ?></title>
    <link rel="stylesheet" href="../assets/styles/reset.css">
    <link rel="stylesheet" href="../assets/styles/profile.css">
    <link rel="stylesheet" href="../assets/styles/headerAndFooter.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</head>
<body>

<?php include '../includes/header.php'; ?>

<main>
    <section id="hero" class="full-height" style="background-image: url('data:image/jpeg;base64,<?php echo base64_encode($user_photo_bg); ?>'); color:white;">
        <div class="hero-content">
            <h1><?= $translations['user_profile'] ?></h1>
            <p><?= $translations['community_intro'] ?></p>
        </div>
    </section>

    <!-- Sekcja z informacjami o użytkowniku -->
    <section class="user-info-container">
        <div class="user-info">
            <div class="user-photo">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($user_main_photo); ?>" alt="<?= $translations['user_photo_alt'] ?>">
            </div>
            <div class="user-details">
                <h2><?php echo $user_name; ?></h2>
                <p class="bio"><?php echo $user_bio; ?></p>
                <ul>
                    <li><strong><?= $translations['email'] ?>:</strong> <?php echo $user_email; ?></li>
                    <li><strong><?= $translations['specialization'] ?>:</strong> <?php echo $user_specialization; ?></li>
                </ul>
                <div class="button-container">
                    <a href="./edit_profile.php" class="btn edit"><?= $translations['edit_profile'] ?></a>
                    <form method="post" action="../controllers/routing.php?action=logout">
                        <button type="submit" class="btn logout"><?= $translations['logout'] ?></button>
                    </form>
                    <?php if ($user_email === 'nikitukivanna36@gmail.com'): ?>
                        <a href="./admin_panel.php" class="btn admin"><?= $translations['admin_panel'] ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php' ?>

<script src="../assets/scripts/translator.js"></script>
</body>
</html>

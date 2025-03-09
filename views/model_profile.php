<?php
require_once '../config/db.php'; // Ładowanie konfiguracji bazy danych
require_once '../controllers/modelController.php'; // Ładowanie kontrolera

// Sprawdź, czy parametr model_id został przekazany w URL
if (!isset($_GET['model_id'])) {
    die('Nie podano identyfikatora modela.');
}

$modelId = intval($_GET['model_id']); // Konwersja model_id na liczbę całkowitą dla bezpieczeństwa

$modelController = new ModelController();

// Pobranie danych modela za pomocą kontrolera
$model = $modelController->showModelProfile($modelId);

// Sprawdź, czy model istnieje
if (!$model) {
    die('Nie znaleziono modela o podanym ID.');
}

// Konwertuj BLOB na Base64 (jeśli dane są dostępne)
$photoBase64 = null;
$photoMimeType = null;
if (!empty($model['photo_main'])) {
    $photoBase64 = base64_encode($model['photo_main']);
    $finfo = new finfo(FILEINFO_MIME_TYPE); // Określ MIME typu obrazu
    $photoMimeType = $finfo->buffer($model['photo_main']);
} else {
    // Domyślna ikona użytkownika
    $photoMimeType = 'image/png';
    $photoBase64 = base64_encode(file_get_contents('../assets/images/default-avatar.png'));
}

// Rozdziel portfolio na tablicę (zakładamy, że obrazy są przechowywane jako ścieżki rozdzielone przecinkami)
$portfolio_images = !empty($model['photo_paths']) ? $model['photo_paths'] : null;

// Rozdzielamy video_paths na tablicę, ale weźmiemy tylko pierwszą ścieżkę (jeśli jest dostępna)
$video_url = !empty($model['video_paths']) ? $model['video_paths'] : null;

$isOwner = false;
$user_id = $_COOKIE['user_id'];
if ($modelId) {
    $isOwner = $modelController->isProfileOwner($user_id, $modelId);
}

?>
<?php include '../includes/language.php'?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= str_replace("{username}", htmlspecialchars($model['username']), $translations['model_profile_title']) ?></title>
    <link rel="stylesheet" href="../assets/styles/reset.css">
    <link rel="stylesheet" href="../assets/styles/model_profile.css">
    <link rel="stylesheet" href="../assets/styles/headerAndFooter.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <section id="model-hero" class="full-height">
        <?php if (!empty($video_url)): ?>
            <video autoplay muted loop id="background-video">
                <source src="<?= "../" . htmlspecialchars($video_url[0]) ?>" type="video/mp4">
                Twoja przeglądarka nie obsługuje HTML5 video.
            </video>
        <?php endif; ?>
        <div class="model-hero-content">
            <img 
                src="data:<?= htmlspecialchars($photoMimeType) ?>;base64,<?= htmlspecialchars($photoBase64) ?>" 
                alt="<?= htmlspecialchars($model['username']) ?>" 
                class="model-avatar">
            <h1><?= htmlspecialchars($model['username']) ?></h1>
            <p class="model-title"><?= str_replace("{specialization}", htmlspecialchars($model['specialization']), $translations['specialization']) ?></p>
        </div>
    </section>
        <section id="model-info">
            <div class="container">
                <div class="model-details">
                    <h2><?= $translations['about_me'] ?></h2>
                    <p><?= htmlspecialchars($model['experience']) ?></p>
                    <ul class="model-stats">
                        <li><strong><?= $translations['age'] ?>:</strong> <?= htmlspecialchars($model['age']) ?> lat</li>
                        <li><strong><?= $translations['height'] ?>:</strong> <?= htmlspecialchars($model['height']) ?> cm</li>
                        <li><strong><?= $translations['dimensions'] ?>:</strong> <?= htmlspecialchars($model['dimensions']) ?></li>
                        <li><strong><?= $translations['eye_color'] ?>:</strong> <?= htmlspecialchars($model['eye_color']) ?></li>
                        <li><strong><?= $translations['hair_color'] ?>:</strong> <?= htmlspecialchars($model['hair_color']) ?></li>
                    </ul>
                </div>
                <div class="model-experience">
                    <h2><?= $translations['experience'] ?></h2>
                    <p><?= htmlspecialchars($model['experience']) ?></p>
                </div>
                <?php if ($isOwner): ?>
                    <a href="./edit_model_profile.php?model_id=<?= $modelId ?>" class="btn-edit-profile">
                        <button class="btn"><?= $translations['edit_profile'] ?></button>
                    </a>
                <?php endif; ?>
            </div>
        </section>

        <section id="model-portfolio">
            <div class="container">
                <h2><?= $translations['portfolio'] ?></h2>
                <div class="portfolio-grid">
                    <?php foreach ($portfolio_images as $image): ?>
                        <img src="../<?= htmlspecialchars(trim($image)) ?>" alt="<?= $translations['portfolio_image'] ?>">
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section id="contact-model">
            <div class="container">
                <h2><?= $translations['contact'] ?></h2>
                <p><?= $translations['contact_info'] ?></p>
                <ul class="contact-info">
                    <li><i class="fas fa-envelope"></i> <?= htmlspecialchars($model['contact_email']) ?></li>
                    <li><i class="fas fa-phone"></i> <?= htmlspecialchars($model['phone_number']) ?></li>
                    <li><i class="fab fa-instagram"></i> <a href="https://instagram.com/<?= htmlspecialchars($model['instagram_handle']) ?>" target="_blank">@<?= htmlspecialchars($model['instagram_handle']) ?></a></li>
                </ul>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'?>
    <script src="../assets/scripts/translator.js"></script>
</body>
</html>
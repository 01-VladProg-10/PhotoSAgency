<?php
require_once '../controllers/authController.php';
require_once '../controllers/modelController.php';
require_once '../config/db.php';

if (!isset($_COOKIE['user_id'])) {
    header("Location: ./login.php");
    exit();
}

$user_id = $_COOKIE['user_id'];

$userController = new authController();
$modelController = new modelController();

$modelProfile = $modelController->getModelProfileData($user_id);

if (!$modelProfile) {
    echo $translations['model_profile_not_found']; // Add this translation to your JSON files
    exit();
}

// Wypełnianie danych z profilu modela
$hobbies = htmlspecialchars($modelProfile['hobbies'] ?? '');
$age = htmlspecialchars($modelProfile['age'] ?? '');
$height = htmlspecialchars($modelProfile['height'] ?? '');
$dimensions = htmlspecialchars($modelProfile['dimensions'] ?? '');
$eye_color = htmlspecialchars($modelProfile['eye_color'] ?? '');
$hair_color = htmlspecialchars($modelProfile['hair_color'] ?? '');
$experience = htmlspecialchars($modelProfile['experience'] ?? '');
$phone_number = htmlspecialchars($modelProfile['phone_number'] ?? '');
$instagram = htmlspecialchars($modelProfile['instagram'] ?? '');
?>
<?php include '../includes/language.php'?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['edit_model_profile_page_title']; ?></title>
    <link rel="stylesheet" href="../assets/styles/reset.css">
    <link rel="stylesheet" href="../assets/styles/edit_model.css">
    <link rel="stylesheet" href="../assets/styles/edit_profile.css">
    <link rel="stylesheet" href="../assets/styles/headerAndFooter.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <style>
        .preview-images img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin: 5px;
        }

        .preview-video {
            width: 200px;
            margin: 5px;
        }
    </style>
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <main>
        <section id="edit-model-profile" class="full-height">
            <div class="container">
                <h1><?= $translations['edit_model_profile_heading']; ?></h1>
                <form id="edit-model-profile-form" action="../controllers/routing.php?action=updatemodelprofile" method="POST" enctype="multipart/form-data">
                    
                    <div class="form-group">
                        <label for="hobbies"><?= $translations['hobbies_label']; ?></label>
                        <textarea id="hobbies" name="hobbies" rows="3" required><?= $hobbies; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="age"><?= $translations['age_label']; ?></label>
                        <input type="number" id="age" name="age" value="<?= $age; ?>" min="18" max="100" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="height"><?= $translations['height_label']; ?></label>
                        <input type="number" id="height" name="height" value="<?= $height; ?>" min="100" max="250" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="dimensions"><?= $translations['dimensions_label']; ?></label>
                        <input type="text" id="dimensions" name="dimensions" value="<?= $dimensions; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="eye_color"><?= $translations['eye_color_label']; ?></label>
                        <input type="text" id="eye_color" name="eye_color" value="<?= $eye_color; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="hair_color"><?= $translations['hair_color_label']; ?></label>
                        <input type="text" id="hair_color" name="hair_color" value="<?= $hair_color; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="experience"><?= $translations['experience_label']; ?></label>
                        <textarea id="experience" name="experience" rows="4"><?= $experience; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone_number"><?= $translations['phone_number_label']; ?></label>
                        <input type="tel" id="phone_number" name="phone_number" value="<?= $phone_number; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="instagram"><?= $translations['instagram_label']; ?></label>
                        <input type="text" id="instagram" name="instagram" value="<?= $instagram; ?>" placeholder="@twoj_instagram">
                    </div>

                    <div class="form-group">
                        <label for="photos"><?= $translations['photos_label']; ?></label>
                        <input type="file" id="photos" name="photos[]" accept="image/*" multiple onchange="previewImages()">
                        <small><?= $translations['photos_max_limit']; ?></small>
                    </div>

                    <div class="form-group">
                        <label for="video"><?= $translations['video_label']; ?></label>
                        <input type="file" id="video" name="video" accept="video/*" onchange="previewVideo()">
                        <small><?= $translations['video_max_limit']; ?></small>
                    </div>

                    <div class="preview-images" id="image-preview"></div>
                    <div id="video-preview"></div>

                    <input type="hidden" id="user_id" name="user_id" value="<?= $user_id; ?>">
                    <button type="submit" class="btn"><?= $translations['save_changes_button']; ?></button>
                </form>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/scripts/translator.js"></script>
</body>
</html>

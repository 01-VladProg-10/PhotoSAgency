<?php
require_once '../controllers/authController.php';
require_once '../config/db.php';

if (!isset($_COOKIE['user_id'])) {
    header("Location: ./login.php");
    exit();
}

$user_id = $_COOKIE['user_id'];

$userController = new authController();
$user = $userController->getUserData($user_id);

if (!$user) {
    echo "Не знайдено користувача.";
    exit();
}

$user_name = !empty($user['username']) ? htmlspecialchars($user['username']) : 'Невідомий користувач';
$user_email = !empty($user['email']) ? htmlspecialchars($user['email']) : 'Немає електронної пошти';
$user_specialization = !empty($user['specialization']) ? htmlspecialchars($user['specialization']) : 'Немає спеціалізації';

$imageData = $user['photo_main'];
$imageBase64 = base64_encode($imageData);
$imageSrc = "data:image/jpeg;base64," . $imageBase64;

$bgImageData = $user['photo_bg']; 
$bgImageBase64 = base64_encode($bgImageData);
$bgImageSrc = !empty($bgImageData) ? "data:image/jpeg;base64," . $bgImageBase64 : '';

?>
<?php include '../includes/language.php'?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translations['edit_profile_page_title']; ?></title>
    <link rel="stylesheet" href="../assets/styles/reset.css">
    <link rel="stylesheet" href="../assets/styles/edit_profile.css">
    <link rel="stylesheet" href="../assets/styles/headerAndFooter.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</head>
<body>
    
    <?php include '../includes/header.php';?>

    <main>
        <section id="edit-profile" class="full-height">
            <div class="container">
                <h1><?php echo $translations['edit_profile_heading']; ?></h1>
                <form id="edit-profile-form" action="../controllers/routing.php?action=update" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="profile-picture"><?php echo $translations['profile_picture_label']; ?></label>
                        <div class="profile-picture-container">
                            <img src="<?php echo $imageSrc; ?>" alt="<?php echo $translations['profile_picture_current_image_alt']; ?>" id="current-profile-picture">
                            <input type="file" id="profile-picture" name="profile-picture" accept="image/*">
                            <label for="profile-picture" class="upload-btn"><?php echo $translations['profile_picture_upload_label']; ?></label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="cover-photo"><?php echo $translations['cover_photo_label']; ?></label>
                        <div class="profile-picture-container">
                            <img src="<?php echo $bgImageSrc; ?>" alt="<?php echo $translations['cover_photo_current_image_alt']; ?>" id="current-profile-picture_bg">
                            <input type="file" id="cover-photo" name="cover-photo" accept="image/*" onchange="showFileName()">
                            <label for="cover-photo" class="upload-btn"><?php echo $translations['cover_photo_upload_label']; ?></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name"><?php echo $translations['name_label']; ?></label>
                        <input type="text" id="name" name="name" value="<?php echo $user_name; ?>" placeholder="<?php echo $translations['name_placeholder']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email"><?php echo $translations['email_label']; ?></label>
                        <input type="email" id="email" name="email" value="<?php echo $user_email; ?>" placeholder="<?php echo $translations['email_placeholder']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password"><?php echo $translations['password_label']; ?></label>
                        <input type="password" id="password" name="password" placeholder="<?php echo $translations['password_placeholder']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="role"><?php echo $translations['role_label']; ?></label>
                        <select id="role" name="role" required>
                            <option value="photographer" <?php echo ($user['role'] == 'photographer') ? 'selected' : ''; ?>><?php echo $translations['role_photographer']; ?></option>
                            <option value="model" <?php echo ($user['role'] == 'model') ? 'selected' : ''; ?>><?php echo $translations['role_model']; ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="specialization"><?php echo $translations['specialization_label']; ?></label>
                        <input type="text" id="specialization" name="specialization" value="<?php echo $user_specialization; ?>" placeholder="<?php echo $translations['specialization_placeholder']; ?>">
                    </div>
                    <button type="submit" class="btn"><?php echo $translations['save_changes_button']; ?></button>
                </form>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>

    <script>
        function showFileName() {
            var fileInput = document.getElementById('bg-picture');
            var fileName = fileInput.files[0] ? fileInput.files[0].name : '';
            document.getElementById('file-name').textContent = fileName ? '<?php echo $translations['file_name_display']; ?>' + fileName : '';
        }
    </script>
    <script src="../assets/scripts/translator.js"></script>
</body>
</html>

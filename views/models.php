<?php
require_once '../controllers/modelController.php';
require_once '../config/db.php'; // Ensure DB connection is correctly configured

// Check for user_id cookie
if (!isset($_COOKIE['user_id'])) {
    header("Location: ./login.php");
    exit();
}

$user_id = $_COOKIE['user_id'];

// Initialize controller
$modelController = new modelController();

// Check if model exists for the user
$modelExists = $modelController->checkIfModelExists($user_id);

// Fetch models (assuming getModels() is defined to return all models)
$models = $modelController->showModels(); // Changed to getModels()

function convertBlobToBase64($blob) {
    return base64_encode($blob);
}

?>
<?php include '../includes/language.php'?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title class="lang" data-key="models_page_title">Modele - Portfolio Fotografa</title>
    <link rel="stylesheet" href="../assets/styles/reset.css">
    <link rel="stylesheet" href="../assets/styles/models.css">
    <link rel="stylesheet" href="../assets/styles/headerAndFooter.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</head>
<body>

    <?php include '../includes/header.php' ?>

    <main>
        <section id="hero" class="full-height">
            <div class="hero-content">
                <h1 class="lang" data-key="models_heading">Modele</h1>
                <p class="lang" data-key="discover_beauty">Odkryj piękno uchwycone w kadrze</p>
                <section id="model-profile-action">
                    <?php if ($modelExists): ?>
                        <a href="./model_profile.php?model_id=<?php echo $modelExists; ?>" class="btn lang" data-key="enter_model_profile">Enter your model profile</a>
                    <?php else: ?>
                        <a href="./create_model_profile.php<?php echo $modelExists; ?>" class="btn lang" data-key="create_model_profile">Create your model profile</a>
                    <?php endif; ?>
                </section>
            </div>
        </section>

        <section id="models-grid">
            <?php foreach ($models as $model): ?> <!-- Iterate through the models array -->
            <div class="model-item">
                <?php
                // Convert BLOB image to base64
                $imageData = convertBlobToBase64($model['photo_main']);
                $imageSrc = 'data:image/jpeg;base64,' . $imageData;
                ?>
                <img src="<?php echo $imageSrc; ?>" alt="<?php echo $model['username']; ?>" class="model-photo">
                <div class="model-info">
                    <h2><?php echo $model['username']; ?></h2>
                    <p class="lang" data-key="specialization">Specjalizacja: <?php echo $model['specialization']; ?></p>
                    <a href="./model_profile.php?model_id=<?php echo $model['id']; ?>" class="btn lang" data-key="view_portfolio">Zobacz portfolio</a>
                </div>
            </div>
            <?php endforeach; ?>
        </section>

    </main>

    <?php include '../includes/footer.php' ?>

    <script src="../assets/scripts/translator.js"></script>
</body>
</html>

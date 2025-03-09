<?php include '../includes/language.php'?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['register_title'] ?></title>
    <link rel="stylesheet" href="../assets/styles/reset.css">
    <link rel="stylesheet" href="../assets/styles/register.css">
    <link rel="stylesheet" href="../assets/styles/headerAndFooter.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</head>
<body>

    <?php include '../includes/header.php' ?>

    <main>
        <section id="register" class="full-height">
            <div class="register-container">
                <h1><?= $translations['register_title'] ?></h1>
                <p><?= $translations['join_community'] ?></p>
                <form id="register-form" method="post" action="../controllers/routing.php?action=register" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username"><?= $translations['username_reg'] ?></label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email"><?= $translations['email_reg'] ?></label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password"><?= $translations['password_reg'] ?></label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password"><?= $translations['confirm_password_reg'] ?></label>
                        <input type="password" id="confirm-password" name="confirm-password" required>
                    </div>
                    <div class="form-group">
                        <label for="role"><?= $translations['role_reg'] ?></label>
                        <select id="role" name="role" required>
                            <option value=""><?= $translations['select_role_reg'] ?></option>
                            <option value="photographer"><?= $translations['photographer_reg'] ?></option>
                            <option value="model"><?= $translations['model_reg'] ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="specialization"><?= $translations['specialization_reg'] ?></label>
                        <input type="text" id="specialization" name="specialization" placeholder="<?= $translations['specialization_placeholder_reg'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="profile_photo"><?= $translations['profile_photo_reg'] ?></label>
                        <input type="file" id="profile_photo" name="profile_photo" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="cover_photo"><?= $translations['cover_photo_reg'] ?></label>
                        <input type="file" id="cover_photo" name="cover_photo" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn"><?= $translations['register_button_reg'] ?></button>
                </form>

                <p class="login-link"> <a href="login.php"><?= $translations['login_link_reg'] ?></a></p>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'?>

    <script src="../assets/scripts/translator.js"></script>
</body>
</html>

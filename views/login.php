<?php include '../includes/language.php' ?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['login_title'] ?></title>
    <link rel="stylesheet" href="../assets/styles/reset.css">
    <link rel="stylesheet" href="../assets/styles/login.css">
    <link rel="stylesheet" href="../assets/styles/headerAndFooter.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</head>
<body>

    <?php include '../includes/header.php' ?>

    <main>
        <section id="login" class="full-height">
            <div class="login-container">
                <h1><?= $translations['login_title'] ?></h1>
                <p><?= $translations['login_greeting'] ?></p>
                <form id="login-form" method="post" action="../controllers/routing.php?action=login">
                    <div class="form-group">
                        <label for="email"><?= $translations['email_label'] ?></label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password"><?= $translations['password_label'] ?></label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn"><?= $translations['login_button'] ?></button>
                </form>
                <p class="register-link"><?= $translations['register_link'] ?> <a href="register.php"><?= $translations['register_link_text'] ?></a></p>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php' ?>
    <script src="../assets/scripts/translator.js"></script>
</body>
</html>

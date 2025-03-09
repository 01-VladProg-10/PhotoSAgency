<?php include '../includes/language.php'; ?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['contact_title'] ?></title>
    <link rel="stylesheet" href="../assets/styles/reset.css">
    <link rel="stylesheet" href="../assets/styles/contact.css">
    <link rel="stylesheet" href="../assets/styles/headerAndFooter.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</head>
<body>

    <?php include '../includes/header.php' ?>

    <main>
        <section id="contact">
            <h1><?= $translations['contact_title'] ?></h1>
            <form id="contact-form">
                <div class="form-group">
                    <label for="name"><?= $translations['fullname'] ?></label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email"><?= $translations['emailplease'] ?></label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="subject"><?= $translations['subject'] ?></label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                <div class="form-group">
                    <label for="message"><?= $translations['message'] ?></label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit"><?= $translations['send_message'] ?></button>
            </form>
        </section>
    </main>

    <?php include '../includes/footer.php' ?>
    <script src="../assets/scripts/translator.js"></script>
</body>
</html>

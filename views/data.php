<?php include '../includes/language.php' ?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title class="lang" data-key="my_data_page_title">Moje Dane - Portfolio Fotografa</title>
    <link rel="stylesheet" href="../assets/styles/reset.css">
    <link rel="stylesheet" href="../assets/styles/data.css">
    <link rel="stylesheet" href="../assets/styles/headerAndFooter.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</head>
<body>

    <?php include '../includes/header.php' ?>
    <main>
        <section id="hero" class="full-height">
            <div class="hero-content">
                <h1 class="text-appear lang" data-key="name">IVANNA NIKITUK</h1>
                <p class="text-appear lang" data-key="profession">Fotograf | Artysta | Podróżnik</p>
            </div>
        </section>
        
        <section id="about" class="full-height">
            <div class="content-wrapper">
                <h2 class="text-appear lang" data-key="about_me_heading">O mnie</h2>
                <p class="text-appear lang" data-key="about_me_text1">Jestem fotografem z pasją do uchwycenia piękna świata. Moja przygoda z fotografią rozpoczęła się ponad 2 lata temu i od tego czasu nieustannie rozwijam swoje umiejętności, eksperymentując z różnymi stylami i technikami.</p>
                <p class="text-appear lang" data-key="about_me_text2">Specjalizuję się w fotografii krajobrazowej i portretowej, starając się zawsze uchwycić esencję chwili i emocje towarzyszące każdemu kadrowi.</p>
                
                <!-- Dodany przycisk -->
                <a class="book-session-btn lang" data-key="book_session_button" href="./makeAppointmeny.php">Umów fotosesję</a>

            </div>
        </section>       
    </main>

    <?php include '../includes/footer.php'?>    

    <script src="../assets/scripts/data.js"></script>
    <script src="../assets/scripts/translator.js"></script> 

</body>
</html>

<?php include '../includes/language.php'?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['captured_moments'] ?></title>
    <link rel="stylesheet" href="../assets/styles/reset.css">
    <link rel="stylesheet" href="../assets/styles/index.css">
    <link rel="stylesheet" href="../assets/styles/headerAndFooter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
</head>
<body>

    <?php include '../includes/header.php' ?>   

    <main>
        <section id="hero">
            <div class="carousel">
                <div><img src="../assets/images/Liza_main.jpg" alt="<?= $translations['carousel_images'][0] ?>"></div>
                <div><img src="../assets/images/Sofa_main.jpg" alt="<?= $translations['carousel_images'][1] ?>"></div>
                <div><img src="../assets/images/Anna_main.jpg" alt="<?= $translations['carousel_images'][2] ?>"></div>
                <div><img src="../assets/images/Albina_main.jpg" alt="<?= $translations['carousel_images'][3] ?>"></div>
            </div>
            <h1><?= $translations['captured_moments'] ?></h1>
            <p><?= $translations['professional_photography'] ?></p>
        </section>

        <?php
        // Definiowanie katalogu, w którym znajdują się zdjęcia
        $image_dir = "../assets/images/models/";
        $images = [];

        // Pobieranie wszystkich plików z katalogu
        if ($handle = opendir($image_dir)) {
            while (false !== ($file = readdir($handle))) {
                // Filtrujemy tylko pliki zdjęć (np. JPG, PNG)
                if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                    $images[] = $file;
                }
            }
            closedir($handle);
        }

        // Losowanie 9 zdjęć
        $random_images = array_rand($images, 12);

        // Sortowanie w tablicy
        $selected_images = [];
        foreach ((array)$random_images as $index) {
            $selected_images[] = $images[$index];
        }

        ?>

        <section id="models">
            <h2><?= $translations['our_clients'] ?></h2>
            <div class="gallery">
                <?php
                // Zakładając, że masz tablicę z nazwami zdjęć
                $rows = array_chunk($selected_images, 3); // Podziel zdjęcia na grupy po 3 zdjęcia

                // Iterowanie przez grupy zdjęć
                foreach ($rows as $index => $row) {
                    echo '<div class="row">'; // Tworzymy kontener dla każdego wiersza

                    // Sprawdzamy, czy mamy wystarczającą ilość zdjęć w danej grupie
                    if (count($row) == 3) {
                        // Jeśli mamy 3 zdjęcia w grupie, tworzymy odpowiedni układ
                        if ($index % 2 == 0) {
                            // Pierwszy wiersz: jedno duże zdjęcie po lewej, dwa małe po prawej
                            echo '<div class="image-container large"><img src="' . $image_dir . $row[0] . '" alt="Model"></div>';
                            echo '<div class="image-container small"><img src="' . $image_dir . $row[1] . '" alt="Model"></div>';
                            echo '<div class="image-container small"><img src="' . $image_dir . $row[2] . '" alt="Model"></div>';
                        } else {
                            // Drugi wiersz: dwa małe zdjęcia po lewej, jedno duże po prawej
                            echo '<div class="image-container small"><img src="' . $image_dir . $row[0] . '" alt="Model"></div>';
                            echo '<div class="image-container small"><img src="' . $image_dir . $row[1] . '" alt="Model"></div>';
                            echo '<div class="image-container large"><img src="' . $image_dir . $row[2] . '" alt="Model"></div>';
                        }
                    } else {
                        // Jeśli w danej grupie nie ma 3 zdjęć, po prostu wyświetlamy dostępne zdjęcia
                        foreach ($row as $image) {
                            echo '<div class="image-container small"><img src="' . $image_dir . $image . '" alt="Model"></div>';
                        }
                    }

                    echo '</div>'; // Zamykanie kontenera wiersza
                }
                ?>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="../assets/scripts/index.js"></script>
    <script src="../assets/scripts/translator.js"></script>
</body>
</html>

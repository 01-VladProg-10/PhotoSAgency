<?php include '../includes/language.php'?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['create_model_profile_page_title']; ?></title>
    <link rel="stylesheet" href="../assets/styles/reset.css">
    <link rel="stylesheet" href="../assets/styles/create_model.css">
    <link rel="stylesheet" href="../assets/styles/headerAndFooter.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <style>
        /* Styl dla miniatur zdjęć */
        .preview-images img {
            width: 150px;  /* Szerokość miniatury */
            height: 150px; /* Wysokość miniatury */
            object-fit: cover; /* Zapewnia, że zdjęcie zachowa proporcje, ale nie wykraczać poza box */
            margin: 5px; /* Odstęp między miniaturami */
        }
        /* Styl dla podglądu wideo */
        .preview-video {
            width: 200px;
            margin: 5px;
        }
    </style>
</head>
<body>

    <?php include '../includes/header.php' ?>

    <main>
        <section id="create-model-profile" class="full-height">
            <div class="container">
                <h1><?= $translations['create_model_profile_heading']; ?></h1>
                <form id="create-model-profile-form" action="../controllers/routing.php?action=addmodelprofile" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="hobbies"><?= $translations['hobbies_label']; ?></label>
                        <textarea id="hobbies" name="hobbies" rows="3" placeholder="<?= $translations['hobbies_label']; ?>"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="age"><?= $translations['age_label']; ?></label>
                        <input type="number" id="age" name="age" min="18" max="100" required>
                    </div>
                    <div class="form-group">
                        <label for="height"><?= $translations['height_label']; ?></label>
                        <input type="number" id="height" name="height" min="100" max="250" required>
                    </div>
                    <div class="form-group">
                        <label for="dimensions"><?= $translations['dimensions_label']; ?></label>
                        <input type="text" id="dimensions" name="dimensions" placeholder="90-60-90" required>
                    </div>
                    <div class="form-group">
                        <label for="eye_color"><?= $translations['eye_color_label']; ?></label>
                        <input type="text" id="eye_color" name="eye_color" required>
                    </div>
                    <div class="form-group">
                        <label for="hair_color"><?= $translations['hair_color_label']; ?></label>
                        <input type="text" id="hair_color" name="hair_color" required>
                    </div>
                    <div class="form-group">
                        <label for="experience"><?= $translations['experience_label']; ?></label>
                        <textarea id="experience" name="experience" rows="4" placeholder="<?= $translations['experience_label']; ?>"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="phone_number"><?= $translations['phone_number_label']; ?></label>
                        <input type="tel" id="phone_number" name="phone_number" placeholder="+48 123 456 789" required>
                    </div>
                    <div class="form-group">
                        <label for="instagram"><?= $translations['instagram_label']; ?></label>
                        <input type="text" id="instagram" name="instagram" placeholder="@twoj_instagram">
                    </div>

                    <!-- Pole dla dodatkowych zdjęć (do 10) -->
                    <div class="form-group">
                        <label for="photos"><?= $translations['photos_label']; ?></label>
                        <input type="file" id="photos" name="photos[]" accept="image/*" multiple onchange="previewImages()">
                        <small><?= $translations['photos_max_limit']; ?></small>
                    </div>

                    <!-- Pole dla wideo -->
                    <div class="form-group">
                        <label for="video"><?= $translations['video_label']; ?></label>
                        <input type="file" id="video" name="video" accept="video/*" onchange="previewVideo()">
                        <small><?= $translations['video_max_limit']; ?></small>
                    </div>

                    <!-- Wyświetlanie miniatur zdjęć -->
                    <div class="preview-images" id="image-preview"></div>

                    <!-- Wyświetlanie podglądu wideo -->
                    <div id="video-preview"></div>

                    <!-- Ukryte pole dla user_id -->
                    <input type="hidden" id="user_id" name="user_id">
                    <button type="submit" class="btn"><?= $translations['create_profile_button']; ?></button>
                </form>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php' ?>

</body>
<script>
    // Funkcja do podglądu wybranych zdjęć
    function previewImages() {
        const preview = document.getElementById('image-preview');
        const files = document.getElementById('photos').files;

        // Przechodzimy przez wszystkie wybrane pliki
        const fileCount = files.length;

        // Sprawdzamy czy wybrano więcej niż 10 zdjęć
        if (fileCount > 10) {
            alert("Możesz dodać maksymalnie 10 zdjęć.");
            return;
        }

        // Dodajemy zdjęcia bez nadpisywania poprzednich
        for (let i = 0; i < fileCount; i++) {
            const file = files[i];

            // Wyświetlanie miniatury
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img); // Dodajemy nową miniaturę
            }

            reader.readAsDataURL(file);
        }
    }

    // Funkcja do podglądu wybranego wideo
    function previewVideo() {
        const preview = document.getElementById('video-preview');
        const file = document.getElementById('video').files[0];

        // Sprawdzamy czy wybrano plik wideo
        if (file) {
            const videoElement = document.createElement('video');
            videoElement.setAttribute('controls', 'controls');
            videoElement.setAttribute('width', '200');  // Szerokość podglądu
            const videoURL = URL.createObjectURL(file);
            videoElement.src = videoURL;
            preview.innerHTML = '';  // Czyścimy poprzedni podgląd
            preview.appendChild(videoElement);  // Dodajemy nowe wideo
        }
    }

    // Pobranie wartości ciasteczka
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`); 
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    // Ustawienie wartości user_id w ukrytym polu
    document.addEventListener('DOMContentLoaded', function () {
        const userId = getCookie('user_id');
        if (userId) {
            document.getElementById('user_id').value = userId;
        }
    });
</script>
<script src="../assets/scripts/translator.js"></script>
</html>

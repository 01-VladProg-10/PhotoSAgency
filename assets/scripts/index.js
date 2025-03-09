$(document).ready(function(){
    // Funkcja dla przełączania pełnego ekranu
    $('.image-container').click(function() {
        if ($(this).hasClass('fullscreen')) {
            $(this).removeClass('fullscreen');
            $('body').removeClass('fullscreen-active'); 
        } else {
            $(this).addClass('fullscreen');
            $('body').addClass('fullscreen-active'); 
        }
    });

    // Inicjalizacja karuzeli (Slick)
    $('.carousel').slick({
        autoplay: true,
        autoplaySpeed: 2000,
        arrows: false,
        dots: false,
    });

    // Załaduj header i footer po załadowaniu strony
    loadHTML('../includes/header.php', 'header-container');
    loadHTML('../includes/footer.php', 'footer-container');
});

// Funkcja do dynamicznego ładowania HTML
function loadHTML(url, elementId) {
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(data => {
            document.getElementById(elementId).innerHTML = data;
        })
        .catch(error => {
            console.error("Wystąpił błąd podczas ładowania pliku:", error);
        });
}

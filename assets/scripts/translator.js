document.getElementById('languageSwitcher').addEventListener('change', function() {
    const selectedLang = this.value;
    
    // Ustawiamy ciasteczko z wybranym językiem
    document.cookie = `lang=${selectedLang}; path=/; max-age=${30 * 24 * 60 * 60}`;  // Ciasteczko ważne przez 30 dni

    // Zmieniamy adres URL, aby dodać parametr lang
    window.location.href = window.location.pathname + `?lang=${selectedLang}`;
});

// Funkcja ładująca tłumaczenia z pliku JSON
function loadTranslations(lang) {
    fetch(`../assets/lang/${lang}.json`)
        .then(response => response.json())
        .then(data => {
            // Zmieniamy teksty w menu
            document.querySelectorAll('a').forEach(element => {
                const href = element.getAttribute('href');
                if (href) {
                    const key = href.replace('.php', '');
                    if (data[key]) {
                        element.textContent = data[key];
                    }
                }
            });

            // Zmieniamy również inne teksty z klasą .lang
            document.querySelectorAll('.lang').forEach(element => {
                const key = element.getAttribute('data-key');
                if (data[key]) {
                    element.textContent = data[key];
                }
            });
        })
        .catch(error => console.error("Błąd ładowania tłumaczeń:", error));
}

// Funkcja pobierająca język z ciasteczka
function getLangFromCookie() {
    const match = document.cookie.match(new RegExp('(^| )lang=([^;]+)'));
    return match ? match[2] : 'pl'; // Zwróć 'pl' domyślnie, jeśli nie ma ciasteczka
}

// Pobieramy język z ciasteczka
const lang = getLangFromCookie();

// Na początku ładujemy tłumaczenia na podstawie języka z ciasteczka
loadTranslations(lang);

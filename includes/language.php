<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    setcookie('lang', $lang, time() + (30 * 24 * 60 * 60), "/");
    $_SESSION['lang'] = $lang;
} elseif (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
    $_SESSION['lang'] = $lang;
} else {
    $lang = 'pl';
    $_SESSION['lang'] = $lang;
}


// Wczytanie odpowiedniego pliku z tłumaczeniami na podstawie wybranego języka
$translations = json_decode(file_get_contents("../assets/lang/$lang.json"), true);
$current_page = basename($_SERVER['PHP_SELF']);
?>

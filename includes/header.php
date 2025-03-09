<header>
    <nav>
        <div class="logo">
            <i class="fas fa-camera"></i>
            <span>PhotoStudio</span>
        </div>
        <ul>
            <?php
            // Lista elementów menu
            $menu_items = [
                'index.php' => 'home',
                'data.php' => 'my_data',
                'models.php' => 'models',
                'contact.php' => 'contact',
                'profile.php' => 'profile'
            ];

            // Dynamiczne wyświetlanie elementów menu na podstawie tłumaczenia
            foreach ($menu_items as $file => $key) {
                if ($file === $current_page) {
                    continue; // Pomijamy aktualną stronę w menu
                }
                echo "<li><a href='./$file'>{$translations[$key]}</a></li>";
            }
            ?>
            <!-- Dropdown do zmiany języka -->
             <li>
             <form action="set_language.php" method="POST">
                <select name="language" id="languageSwitcher" onchange="this.form.submit()">
                    <option value="pl" <?= $lang == 'pl' ? 'selected' : '' ?>>Polski</option>
                    <option value="en" <?= $lang == 'en' ? 'selected' : '' ?>>English</option>
                    <option value="uk" <?= $lang == 'uk' ? 'selected' : '' ?>>Українська</option>
                </select>
            </form>
             </li>
        </ul>
    </nav>
</header>

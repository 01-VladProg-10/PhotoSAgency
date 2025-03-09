<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora - Sesje Fotograficzne</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
        }
        input[type="date"], input[type="time"], input[type="number"], textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #333;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px; /* Dodaje odstęp nad przyciskiem */
        }

        button:hover {
            background-color: #444;
        }
        .session-times {
            margin-top: 10px;
        }
        .session-time {
            background-color: #f0f0f0;
            padding: 5px;
            margin: 5px 0;
            border-radius: 3px;
        }
        #bannerToggle {
            margin-right: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Panel Administratora - Sesje Fotograficzne</h1>
        
        <!-- Przycisk powrotu na stronę główną -->
        <button onclick="window.location.href='./profile.php';">Powrót na stronę profila</button>

        <form id="adminForm" method="post" action="../controllers/routing.php?action=addNewFreeDay">
            <div class="form-group">
                <h2>Dodaj wolne terminy na sesje zdjęciowe</h2>
                <label for="sessionDate">Data:</label>
                <input type="date" id="sessionDate" name="date" required>
                
                <label for="sessionTime">Godzina:</label>
                <input type="time" id="sessionTime" name="time" step="3600" required> <!-- step="3600" oznacza pełne godziny -->

                <button type="submit">Dodaj termin</button>
                
                <div id="sessionTimes" class="session-times"></div>
            </div>

            <div class="form-group">
                <h2>Ustawienia banera</h2>
                <label>
                    <input type="checkbox" id="bannerToggle"> Włącz baner
                </label>
                
                <label for="bannerContent">Treść banera:</label>
                <textarea id="bannerContent" rows="3"></textarea>
            </div>

            <div class="form-group">
                <h2>Ustawienia rabatu</h2>
                <label for="discountPercentage">Procent rabatu:</label>
                <input type="number" id="discountPercentage" min="0" max="100">
            </div>

            <button type="submit">Zapisz zmiany</button>
        </form>
    </div>

    <script>
        document.getElementById("adminForm").addEventListener("submit", function(event) {
            // Pobierz wartości z pól formularza
            const sessionDate = document.getElementById("sessionDate").value;
            const sessionTime = document.getElementById("sessionTime").value;

            if (sessionDate && sessionTime) {
                // Połącz datę i godzinę w format MySQL DATETIME (YYYY-MM-DD HH:MM:SS)
                const datetime = sessionDate + " " + sessionTime;

                // Utwórz ukryte pole w formularzu, które prześle pełny format datetime
                const hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "datetime"; // Nowa nazwa dla pełnego datetime
                hiddenInput.value = datetime;

                // Dodaj ukryte pole do formularza
                this.appendChild(hiddenInput);
            }
        });
    </script>
</body>

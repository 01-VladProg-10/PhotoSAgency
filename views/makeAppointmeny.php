<?php 
// Ensure the database connection is initialized properly
require_once '../config/db.php';

// Include the language translations file
include '../includes/language.php';

// Create an instance of the Database class and get the connection
$database = new Database();
$conn = $database->getConnection(); // Now $conn is properly initialized

// Get current year and month (for initial view)
$today = new DateTime();
$currentYear = $today->format('Y');
$currentMonth = $today->format('m');

// Check if 'year' and 'month' parameters are provided for dynamic calendar
$year = isset($_GET['year']) ? $_GET['year'] : $currentYear;
$month = isset($_GET['month']) ? str_pad($_GET['month'], 2, '0', STR_PAD_LEFT) : $currentMonth;

// Query to fetch available dates and times
$sql = "SELECT `date` FROM available_dates 
        WHERE `date` LIKE '$year-$month%' 
        AND is_booked = 0";

// Use PDO to query the database
$stmt = $conn->prepare($sql);
$stmt->execute();

// Check if the query returned any results
if (!$stmt) {
    echo json_encode(["error" => "Database query failed: " . $conn->errorInfo()]);
    exit;
}

// Fetch the available dates and times
$dates = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If there are no dates, set an empty array
if ($dates === false) {
    $dates = [];
}

// Extract the dates and times into an array
$dateArray = [];
foreach ($dates as $row) {
    // Correct the date and time here
    $dateTime = new DateTime($row['date'], new DateTimeZone('UTC')); // Assuming the database stores in UTC
    $dateTime->setTimezone(new DateTimeZone('Europe/Warsaw')); // Adjust to your timezone
    
    // Adjust the date and time to make sure it is correct
    $dateTime->modify('-1 day'); // Subtract one day to correct the date issue
    
    $dateArray[] = $dateTime->format('Y-m-d H:i:s');  // Store the corrected DateTime
}
// Convert available dates and times into JSON format to pass into JavaScript
$availableDatesJson = json_encode($dateArray);
?>
<?php include '../includes/language.php'?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['book_session_title'] ?></title>
    <link rel="stylesheet" href="../assets/styles/reset.css">
    <link rel="stylesheet" href="../assets/styles/makeAppointmeny.css">
    <link rel="stylesheet" href="../assets/styles/index.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</head>
<body>
    <?php include '../includes/header.php' ?>

    <main>
        <section id="booking-hero" class="full-height">
            <div class="hero-content">
                <h1 class="text-appear"><?= $translations['book_photo_session'] ?></h1>
                <p class="text-appear"><?= $translations['capture_special_moments'] ?></p>
            </div>
        </section>
        
        <section id="booking-form" class="full-height">
            <div class="content-wrapper">
                <h2 class="text-appear"><?= $translations['booking_form'] ?></h2>
                <form id="photo-session-form" action="../controllers/routing.php?action=addNewSession" method="post" class="text-appear">
                    <div class="right-column">
                        <div class="form-group">
                            <label for="date"><?= $translations['session_date'] ?>:</label>
                            <div id="calendar" class="calendar"></div>
                            <input type="hidden" id="selected-date" name="selected-date">
                        </div>
                        <div class="form-group">
                            <label for="available-times"><?= $translations['available_times'] ?>:</label>
                            <select id="available-times" name="available-times">
                                <option value=""><?= $translations['select_time'] ?></option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn"><?= $translations['book_session_button_'] ?></button>
                </form>
            </div>
        </section>       
    </main>

    <?php include '../includes/footer.php' ?>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById("photo-session-form");
            const calendar = document.getElementById("calendar");
            const selectedDateInput = document.getElementById("selected-date");
            const timeSelect = document.getElementById("available-times");

            // Available dates fetched from the PHP server-side code
            const availableDates = <?php echo $availableDatesJson; ?>;

            // Format date to 'YYYY-MM-DD'
            function formatDate(date) {
                const d = new Date(date);
                return d.toISOString().split('T')[0]; // Returns 'YYYY-MM-DD'
            }

            // Format time to 'HH:mm'
            function formatTime(date) {
                const d = new Date(date);
                return d.toISOString().split('T')[1].slice(0, 5); // Returns 'HH:mm'
            }

            const today = new Date();
            let currentYear = today.getFullYear();
            let currentMonth = today.getMonth();

            // Fetch available dates for a given year and month
            function fetchAvailableDates(year, month) {
                const formattedMonth = month < 9 ? `0${month + 1}` : `${month + 1}`; // Ensure month is 2 digits
                const formattedYearMonth = `${year}-${formattedMonth}`;

                // Filter available dates for the given year and month
                return availableDates.filter(d => {
                    const formattedDate = formatDate(d);
                    return formattedDate.startsWith(formattedYearMonth);
                });
            }

            // Render the calendar
            function renderCalendar(year, month) {
                calendar.innerHTML = "";
                const date = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0).getDate();

                // Header with navigation
                const headerDiv = document.createElement("div");
                headerDiv.className = "calendar-header";
                headerDiv.innerHTML = ` 
                    <button id="prev-month">&lt;</button>
                    <span>${date.toLocaleString("default", { month: "long" })} ${year}</span>
                    <button id="next-month">&gt;</button>
                `;
                calendar.appendChild(headerDiv);

                // Weekdays header
                const weekdays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                weekdays.forEach(day => {
                    const dayDiv = document.createElement("div");
                    dayDiv.className = "calendar-day";
                    dayDiv.textContent = day;
                    calendar.appendChild(dayDiv);
                });

                // Empty spaces before the first day of the month
                for (let i = 0; i < date.getDay(); i++) {
                    const emptyDiv = document.createElement("div");
                    calendar.appendChild(emptyDiv);
                }

                // Add days of the month
                for (let i = 1; i <= lastDay; i++) {
                    const dayDiv = document.createElement("div");
                    dayDiv.className = "calendar-day";
                    dayDiv.textContent = i;

                    const currentDate = new Date(year, month, i);

                    // Check if the date is available
                    if (fetchAvailableDates(year, month).some(d => formatDate(d) === formatDate(currentDate))) {
                        dayDiv.classList.add("available");
                        dayDiv.addEventListener("click", function () {
                            document.querySelectorAll(".calendar-day").forEach(d => d.classList.remove("selected"));
                            this.classList.add("selected");
                            selectedDateInput.value = currentDate.toISOString().split("T")[0]; // Set the selected date value

                            // Show available times
                            showAvailableTimes(currentDate);
                        });
                    } else {
                        dayDiv.classList.add("disabled");
                    }

                    calendar.appendChild(dayDiv);
                }

                // Handle previous and next month buttons
                document.getElementById("prev-month").addEventListener("click", () => {
                    if (month === 0) {
                        renderCalendar(year - 1, 11);
                    } else {
                        renderCalendar(year, month - 1);
                    }
                });

                document.getElementById("next-month").addEventListener("click", () => {
                    if (month === 11) {
                        renderCalendar(year + 1, 0);
                    } else {
                        renderCalendar(year, month + 1);
                    }
                });
            }

            // Function to show available times for a selected date
            function showAvailableTimes(selectedDate) {
                const selectedDateString = selectedDate.toISOString().split('T')[0];  // Format 'YYYY-MM-DD'
                const availableTimes = availableDates.filter(date => formatDate(date) === selectedDateString);

                // Clear previous times
                timeSelect.innerHTML = "<option value=''><?= $translations['select_time'] ?></option>";

                // Populate available times
                availableTimes.forEach(date => {
                    const timeOption = document.createElement('option');
                    timeOption.value = date;
                    timeOption.textContent = formatTime(date);
                    timeSelect.appendChild(timeOption);
                });
            }

            // Initially render the current month and year
            renderCalendar(currentYear, currentMonth);
        });
    </script>
    <script src="../assets/scripts/translator.js"></script>
</body>
</html>

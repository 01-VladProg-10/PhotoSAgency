document.addEventListener("DOMContentLoaded", () => {
  console.log("JavaScript is running");

  const form = document.getElementById("photo-session-form");
  const calendar = document.getElementById("calendar");
  const selectedDateInput = document.getElementById("selected-date");

  // Format date to 'YYYY-MM-DD'
  function formatDate(date) {
      const d = new Date(date);
      return d.toISOString().split('T')[0]; // Returns 'YYYY-MM-DD'
  }

  const today = new Date();
  let currentYear = today.getFullYear();
  let currentMonth = today.getMonth();

  function fetchAvailableDates(year, month) {
      const formattedMonth = month < 9 ? `0${month + 1}` : `${month + 1}`; // Ensure month is 2 digits
      const formattedYearMonth = `${year}-${formattedMonth}`;

      // Filter available dates for the given year and month
      return availableDates.filter(d => formatDate(d).startsWith(formattedYearMonth));
  }

  function renderCalendar(year, month) {
      calendar.innerHTML = "";
      const date = new Date(year, month, 1);
      const lastDay = new Date(year, month + 1, 0).getDate();

      const headerDiv = document.createElement("div");
      headerDiv.className = "calendar-header";
      headerDiv.innerHTML = ` 
          <button id="prev-month">&lt;</button>
          <span>${date.toLocaleString("default", { month: "long" })} ${year}</span>
          <button id="next-month">&gt;</button>
      `;
      calendar.appendChild(headerDiv);

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
          if (fetchAvailableDates(year, month).some(d => formatDate(d) === formatDate(currentDate))) {
              dayDiv.classList.add("available");
              dayDiv.addEventListener("click", function () {
                  document.querySelectorAll(".calendar-day").forEach(d => d.classList.remove("selected"));
                  this.classList.add("selected");
                  selectedDateInput.value = currentDate.toISOString().split("T")[0];
              });
          } else {
              dayDiv.classList.add("disabled");
          }

          calendar.appendChild(dayDiv);
      }

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

  renderCalendar(currentYear, currentMonth);

  form.addEventListener("submit", (e) => {
      e.preventDefault();
      console.log("Form submitted", new FormData(form));
      alert("Thank you for your booking! We will contact you shortly.");
      form.reset();
      document.querySelectorAll(".calendar-day").forEach(d => d.classList.remove("selected"));
  });
});
document.addEventListener("DOMContentLoaded", function() {
  // Function to fetch and populate log table based on log level and date range
  function populateLogTable(logLevel, startDate, endDate) {
    // Fetch log file using Fetch API
    fetch('get_error_log.php')
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.text();
      })
      .then(logData => {
        // Split the log data into an array of lines
        const logLines = logData.split("\n");

        // Filter out lines containing the specified log level and empty lines
        let filteredLogLines = logLines.filter(line => line.trim() !== "" && line.toLowerCase().includes(logLevel));

        // Filter logs within the specified date range if dates are provided
        if (startDate && endDate) {
          filteredLogLines = filteredLogLines.filter(line => {
            const timestamp = line.split("] ")[0].substring(1);
            const logDate = new Date(timestamp);
            return logDate >= new Date(startDate) && logDate <= new Date(endDate);
          });
        }

        // Reverse order of log entries
        filteredLogLines.reverse();

        // Get table body element
        var tableBody = document.getElementById("logBody");

        // Clear existing table rows
        tableBody.innerHTML = "";

        // Populate table rows with log data
        filteredLogLines.forEach(function (log) {
          var splitLog = log.split("] ");
          var timestamp = splitLog[0].substring(1);
          // Parse the timestamp string into a Date object
          const date = new Date(timestamp);

          // Get the month, day, year, hours, minutes, and seconds
          const month = date.toLocaleString("default", { month: "short" });
          const day = date.getDate();
          const year = date.getFullYear();
          const hours = String(date.getHours()).padStart(2, "0");
          const minutes = String(date.getMinutes()).padStart(2, "0");
          const seconds = String(date.getSeconds()).padStart(2, "0");
          const formattedTimestamp = `${month} ${day} ${year}, ${hours}:${minutes}:${seconds}`;

          var description = splitLog.slice(1).join("] ");

          var row = tableBody.insertRow();
          var cell1 = row.insertCell(0);
          var cell2 = row.insertCell(1);

          cell1.textContent = formattedTimestamp;
          cell2.textContent = description;
        });
      })
      .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
      });
  }

  // Event listener for the buttons
  document.getElementById("notice_Button").addEventListener("click", function() {
    const startDate = document.getElementById("startDate").value;
    const endDate = document.getElementById("endDate").value;
    populateLogTable("notice", startDate, endDate);
  });

  document.getElementById("warn_Button").addEventListener("click", function() {
    const startDate = document.getElementById("startDate").value;
    const endDate = document.getElementById("endDate").value;
    populateLogTable("warn", startDate, endDate);
  });

  document.getElementById("error_Button").addEventListener("click", function() {
    const startDate = document.getElementById("startDate").value;
    const endDate = document.getElementById("endDate").value;
    populateLogTable("error", startDate, endDate);
  });

  // Event listener for the "Show All Logs" button
  document.getElementById("all_Log_Button").addEventListener("click", function() {
    const startDate = document.getElementById("startDate").value;
    const endDate = document.getElementById("endDate").value;
    populateLogTable("", startDate, endDate); // Passing an empty string fetches all logs
  });
});

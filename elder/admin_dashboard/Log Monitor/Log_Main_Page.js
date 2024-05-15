// Get the navigation entry
function getNavigationEntry() {
  const entries = window.performance.getEntriesByType("navigation");
  return entries.length > 0 ? entries[0] : null;
}

// Calculate time in milliseconds
function getTimeInMilliseconds(startTime, endTime) {
  return endTime - startTime;
}

// Measure Navigation Time
function measureNavigationTime() {
  const navigationEntry = getNavigationEntry();
  if (navigationEntry) {
    const startTime =
      navigationEntry.unloadEventStart || navigationEntry.fetchStart;
    const endTime = navigationEntry.domComplete;
    const navigationTime = getTimeInMilliseconds(startTime, endTime);

    if (navigationTime < 100) {
      document.getElementById("CPU_Performance").innerHTML = "Fast";
      document.getElementById("CPU_Performance").style.color = "green";
    } else if (100 < navigationTime < 200) {
      document.getElementById("CPU_Performance").innerHTML = "Normal";
      document.getElementById("CPU_Performance").style.color = "black";
    } else if (navigationTime > 200) {
      document.getElementById("CPU_Performance").innerHTML = "Slow";
      document.getElementById("CPU_Performance").style.color = "red";
    }
  } else {
    console.log("Navigation entry not found");
  }
}

// Count number of warn log and error log
function countEntries() {
  // Fetch log file using Fetch API
  fetch("get_error_log.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.text();
    })
    .then((logData) => {
      // Split the log data into an array of lines
      const logLines = logData.split("\n");

      // Filter out lines containing "PHP" and empty lines
      const filteredLogLines = logLines.filter(
        (line) => line.trim() !== "" && !line.includes("PHP")
      );

      // Initialize count variables
      let warnCount = 0;
      let errorCount = 0;

      // Process each log entry
      filteredLogLines.forEach((line) => {
        // Check if the log entry contains "warn" or "error"
        if (line.includes("warn")) {
          warnCount++;
        } else if (line.includes("error")) {
          errorCount++;
        }
      });

      // Update the HTML elements with the counts
      document.getElementById("Warning_Count").innerHTML = warnCount;
      document.getElementById("Error_Count").innerHTML = errorCount;
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
}

// Fetch the latest timestamp from the log file
function fetchLatestTimestamp() {
  fetch("get_error_log.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.text();
    })
    .then((logData) => {
      // Split the log data into an array of lines
      const logLines = logData.split("\n");

      // Find the latest timestamp
      let latestTimestamp = null;
      for (const line of logLines) {
        if (line.trim() !== "") {
          const timestamp = line.split("] ")[0].substring(1);
          const logDate = new Date(timestamp);
          if (!latestTimestamp || logDate > latestTimestamp) {
            latestTimestamp = logDate;
          }
        }
      }

      // Calculate the time since the last update
      if (latestTimestamp) {
        const currentTime = new Date();
        const timeSinceLastUpdate = Math.floor(
          (currentTime - latestTimestamp) / 1000
        ); // Time in seconds
        const days = Math.floor(timeSinceLastUpdate / 86400);
        const hours = Math.floor((timeSinceLastUpdate % 86400) / 3600);
        const minutes = Math.floor((timeSinceLastUpdate % 3600) / 60);
        const seconds = timeSinceLastUpdate % 60;

        const timeSinceLastUpdateStr = `${days} days, ${hours} hours, ${minutes} minutes, ${seconds} seconds`;
        document.getElementById(
          "Last_Update_Label_1"
        ).textContent = `Time since last update: ${timeSinceLastUpdateStr}`;
        document.getElementById(
          "Last_Update_Label_2"
        ).textContent = `Time since last update: ${timeSinceLastUpdateStr}`;
        document.getElementById(
          "Last_Update_Label_3"
        ).textContent = `Time since last update: ${timeSinceLastUpdateStr}`;
      } else {
        document.getElementById("Last_Update_Label_1").textContent =
          "No logs found.";
        document.getElementById("Last_Update_Label_2").textContent =
          "No logs found.";
        document.getElementById("Last_Update_Label_3").textContent =
          "No logs found.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
}

window.addEventListener("load", () => {
  measureNavigationTime();
  countEntries();
  fetchLatestTimestamp();
  setInterval(fetchLatestTimestamp, 10000);
});

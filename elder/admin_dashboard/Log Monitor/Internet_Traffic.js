function generateLogChart() {
  // Fetch log file using XMLHttpRequest
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var logEntries = xhr.responseText.split("\n");

        // Extract data for line chart
        const logCountByDate = {};
        logEntries.forEach((entry) => {
          if (entry.trim() !== "") {
            const parts = entry.split(" ");
            const timestampParts = parts[3]
              .replace("[", "")
              .replace("]", "")
              .split("/");
            const day = timestampParts[0];
            const month = timestampParts[1];
            const year = timestampParts[2].split(":")[0];
            const time = timestampParts[2].split(":").slice(1).join(":");
            const timestamp = `${month}/${day}/${year} ${time}`;
            const date = new Date(timestamp);
            const dateKey = `${date.getFullYear()}-${String(
              date.getMonth() + 1
            ).padStart(2, "0")}-${String(date.getDate()).padStart(2, "0")}`;
            logCountByDate[dateKey] = (logCountByDate[dateKey] || 0) + 1;
          }
        });

        const chartData = Object.entries(logCountByDate).map(
          ([date, count]) => ({
            date: new Date(date),
            count,
          })
        );
        console.log(chartData);
        // Filter out invalid data points
        const validData = chartData.filter(
          (d) => !isNaN(d.date.getTime()) && isFinite(d.count)
        );

        // Draw line chart using D3.js
        renderLogChart(validData);
      } else {
        console.error("Failed to fetch log file: " + xhr.status);
      }
    }
  };
  xhr.open("GET", "get_access_log.php", true);
  xhr.send();
}

function renderLogChart(data) {
  // Set up dimensions and margins for the chart
  const margin = { top: 20, right: 20, bottom: 50, left: 70 }; // Increased bottom and left margins
  const width = 1700 - margin.left - margin.right;
  const height = 800 - margin.top - margin.bottom;

  // Create the SVG element and append it to the body
  const svg = d3
    .select("#chart_container")
    .append("svg")
    .attr("width", width + margin.left + margin.right + 200)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform", `translate(${margin.left + 200}, ${margin.top})`);

  // Define the scales
  const xScale = d3.scaleTime().range([0, width]);
  const yScale = d3.scaleLinear().range([height, 0]);

  // Set the domains for the scales
  xScale.domain(d3.extent(data, (d) => d.date));
  yScale.domain([0, d3.max(data, (d) => d.count)]);

  // Create the line and path elements
  const line = d3
    .line()
    .x((d) => xScale(d.date))
    .y((d) => yScale(d.count));

  const path = svg
    .append("path")
    .datum(data)
    .attr("fill", "none")
    .attr("stroke", "steelblue")
    .attr("stroke-linejoin", "round")
    .attr("stroke-linecap", "round")
    .attr("stroke-width", 1.5)
    .attr("d", line);

  // Create the x and y axes
  const xAxis = d3.axisBottom(xScale).ticks(10);
  const yAxis = d3.axisLeft(yScale);

  // Add gridlines
  svg
    .append("g")
    .attr("class", "grid")
    .attr("transform", `translate(0, ${height})`)
    .call(d3.axisBottom(xScale).tickSize(-height).tickFormat(""));

  svg
    .append("g")
    .attr("class", "grid")
    .call(d3.axisLeft(yScale).tickSize(-width).tickFormat(""));

  // Append the axes to the SVG
  svg.append("g").attr("transform", `translate(0, ${height})`).call(xAxis);
  svg.append("g").call(yAxis);

  // Add y-axis label
  svg
    .append("text")
    .attr("transform", "rotate(-90)")
    .attr("y", 0 - (margin.left + 40))
    .attr("x", 0 - height / 2)
    .attr("dy", "1em")
    .style("text-anchor", "middle")
    .text("Access Count");

  // Add x-axis label
  svg
    .append("text")
    .attr(
      "transform",
      `translate(${width / 2}, ${height + margin.bottom - 10})`
    )
    .style("text-anchor", "middle")
    .text("Date");
}

window.onload = generateLogChart;

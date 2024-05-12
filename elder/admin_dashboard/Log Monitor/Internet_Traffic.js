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
              const timestamp = entry.substring(1, 25);
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
  
          // Draw line chart using D3.js
          renderLogChart(chartData);
        } else {
          console.error("Failed to fetch log file: " + xhr.status);
        }
      }
    };
    // Modify the URL to point to the PHP script that serves the log file
    xhr.open("GET", "get_log.php", true);
    xhr.send();
}

function renderLogChart(data) {
  // Set up dimensions and margins for the chart
  const margin = { top: 20, right: 20, bottom: 30, left: 50 };
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
  const xAxis = d3
    .axisBottom(xScale)
    .tickFormat(d3.timeFormat("%Y-%m-%d"))
    .ticks(6);
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
}

window.onload = generateLogChart;

<?php
include "base.php";
// Connect to the database
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "projecttp_bis";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Run a SQL query to get data for histogram
$sql = "SELECT flaw_type, severity, COUNT(*) as count FROM flaw GROUP BY flaw_type, severity";
$result = $conn->query($sql);

// Initialize data arrays
$electricity_high = 0;
$electricity_medium = 0;
$electricity_low = 0;
$scratch_high = 0;
$scratch_medium = 0;
$scratch_low = 0;
$painting_high = 0;
$painting_medium = 0;
$painting_low = 0;
$robustness_high = 0;
$robustness_medium = 0;
$robustness_low = 0;

// Loop through query results and populate data arrays
while($row = $result->fetch_assoc()) {
  switch($row['flaw_type']) {
    case 'electricity':
      switch($row['severity']) {
        case 'high':
          $electricity_high = $row['count'];
          break;
        case 'medium':
          $electricity_medium = $row['count'];
          break;
        case 'low':
          $electricity_low = $row['count'];
          break;
      }
      break;
    case 'scratch':
      switch($row['severity']) {
        case 'high':
          $scratch_high = $row['count'];
          break;
        case 'medium':
          $scratch_medium = $row['count'];
          break;
        case 'low':
          $scratch_low = $row['count'];
          break;
      }
      break;
    case 'painting':
      switch($row['severity']) {
        case 'high':
          $painting_high = $row['count'];
          break;
        case 'medium':
          $painting_medium = $row['count'];
          break;
        case 'low':
          $painting_low = $row['count'];
          break;
      }
      break;
    case 'robustness':
      switch($row['severity']) {
        case 'high':
          $robustness_high = $row['count'];
          break;
        case 'medium':
          $robustness_medium = $row['count'];
          break;
        case 'low':
          $robustness_low = $row['count'];
          break;
      }
      break;
  }
}

// Create and display the histogram using Google Charts API
echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {"packages":["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ["Flaw Type", "High Severity", "Medium Severity", "Low Severity"],
          ["Electricity", ' . $electricity_high . ', ' . $electricity_medium . ', ' . $electricity_low . '],
          ["Scratch", ' . $scratch_high . ', ' . $scratch_medium . ', ' . $scratch_low . '],
          ["Painting", ' . $painting_high . ', ' . $painting_medium . ', ' . $painting_low . '],
          ["Robustness", ' . $robustness_high . ', ' . $robustness_medium . ', ' . $robustness_low . ']
          ]);
          var options = {
            title: "Flaw Severity by Type",
            legend: { position: "top" },
            colors: ["#F44336", "#FFC107", "#4CAF50"],
            vAxis: {
              title: "Count",
              minValue: 0
            }
          };
      
          var chart = new google.visualization.ColumnChart(document.getElementById("chart_div"));
          chart.draw(data, options);
        }
      </script>';

      // Display the HTML for the histogram
echo '<div id="chart_div" style="width: 100%; height: 500px;"></div>';

// Close the database connection
$conn->close();
?>

<!-- HTML code for displaying the histogram -->
<div id="chart_div" style="width: 100%; height: 500px;"></div>
<!-- Include the Google Charts API JavaScript library -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 

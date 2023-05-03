<?php
include "base.php";
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "projecttp_bis";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

    $sql = "SELECT * FROM quality_page";
    $result = $conn->query($sql);
  
    if ($result->num_rows > 0) {
      echo "<h1>Visualize quality tests </h1>";
      echo "<table><tr><th>Order ID</th><th>Product Type</th><th>Product ID</th><th>State</th><th>Flaw type</th><th>Severity</th></tr>";
      while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["order_id"]."</td><td>".$row["product_type"]."</td><td>".$row["product_id"]."</td><td>".$row["state"]."</td><td>".$row["flaw_type"]."</td><td>".$row["severity"]."</td></tr>";
      }
      echo "</table>";
    } else {
      echo "No cart data available.";
    }

    if (isset($_POST['submit'])) {

      $order_id = intval($_POST['order_id']);
      $product_type = $_POST['product_type'];
      $product_id = intval($_POST['product_id']);
      $state = $_POST['state'];
      $flaw_type = $_POST['flaw_type'];
      $flaw_id = intval($_POST['flaw_id']);
      $severity = $_POST['severity'];
  
      $sql1 = "INSERT INTO quality_test (order_id, product_type, product_id, state, flaw_id) VALUES ($order_id, '$product_type', $product_id, '$state', '$flaw_id')";
      $sql2 = "INSERT INTO flaw (id, flaw_type, severity) VALUES ($flaw_id, '$flaw_type', '$severity')";
  
      if (mysqli_query($conn, $sql1) and mysqli_query($conn, $sql2)) {
          echo "New record added successfully.";
      } else {
          echo "Error: " . $sql1 . "<br>" . mysqli_error($conn);
          echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
      }
  
    } 

    
?>

<!DOCTYPE html>
<html>
<head>
	<title>Quality Test Form</title>
	<script>

		function disableFields() {
			var state = document.getElementById("state").value;
			var flawId = document.getElementById("flaw_id");
			var flawType = document.getElementById("flaw_type");
			var severity = document.getElementById("severity");

			if (state === "passed") {
				flawId.disabled = true;
				flawType.disabled = true;
				severity.disabled = true;
			} else {
				flawId.disabled = false;
				flawType.disabled = false;
				severity.disabled = false;
			}
		}
	</script>
  
	<title>Quality Test Form</title>
	<link rel="stylesheet" href="quality_page.css">
</head>
<body>
	<h1>Quality Test Form</h1>
	<form method="post">
    <label for="order_id">Order ID:</label>
    <input type="number" name="order_id" required><br>

    <label for="product_type">Product Type:</label>
    <select name="product_type" required>
        <option value="industrial_box">Industrial Box</option>
        <option value="cart">Cart</option>
    </select><br>

    <label for="product_id">Product ID:</label>
    <input type="number" name="product_id" required><br>

    <label for="state">State:</label>
    <select name="state" required>
        <option value="passed">Passed</option>
        <option value="failed">Failed</option>
        <option value="minor_flaw">Minor Flaw</option>
    </select><br>

    <label for="flaw_id">Flaw ID:</label>
    <input type="number" name="flaw_id" required><br>

    <label for="flaw_type">Flaw Type:</label>
    <select name="flaw_type" required>
        <option value="electricity">Electricity</option>
        <option value="scratch">Scratch</option>
        <option value="painting">Painting</option>
        <option value="robustness">Robustness</option>
    </select><br>

    <label for="severity">Severity:</label>
    <select name="severity" required>
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
    </select><br>

    <input type="submit" name="submit" value="Add Stat">
</form>

</body>
</html>


<?php

mysqli_close($conn);

?>


<?php
include "base.php";

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "projecttp_bis";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}



?>



<!DOCTYPE html>
<html>
<head>
  <title>See Cart/Box Data</title>
  <link rel="stylesheet" href="completed_orders.css">
</head>
<body>

  <h1>See Cart/Box Data</h1>

  <form method="post">
    <input type="submit" name="see_carts" value="See Carts">
    <input type="submit" name="see_boxes" value="See Boxes">
  </form>

  <?php
  // Display cart data if "See Carts" button was clicked
  if (isset($_POST['see_carts'])) {
    // Run a SQL query to get cart data
    $sql = "SELECT * FROM cart WHERE cart_status = 'completed'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // Display the cart data in a table
      echo "<table>";
      echo "<tr><th>ID</th><th>Color</th><th>Type</th><th>Cart Status</th></tr>";
      while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id"]."</td><td>".$row["wheel_color"]."</td><td>".$row["wheel_type"]."</td><td>".$row["cart_status"]."</td></tr>";
      }
      echo "</table>";
    } else {
      echo "<p>No cart data available.</p>";
    }
  }

  // Display box data if "See Boxes" button was clicked
  if (isset($_POST['see_boxes'])) {
    // Run a SQL query to get box data
    $sql = "SELECT * FROM box WHERE box_status = 'completed'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // Display the box data in a table
      echo "<table>";
      echo "<tr><th>Box ID</th><th>Box Status</th></tr>";
      while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id"]."</td><td>".$row["box_status"]."</td></tr>";
      }
      echo "</table>";
    } else {
      echo "<p>No box data available.</p>";
    }
  }
  ?>

</body>
</html>

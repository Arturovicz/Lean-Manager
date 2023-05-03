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

if (isset($_POST['process_next_cart'])) {
  $sql_temp = "CREATE TEMPORARY TABLE temp_cart SELECT MIN(id) as min_id FROM cart WHERE cart_status = 'ongoing'";
  $conn->query($sql_temp);
  
  $sql_update = "UPDATE cart SET cart_status = 'completed' WHERE id = (SELECT min_id FROM temp_cart)";
  if ($conn->query($sql_update) === TRUE) {
    echo "<p>Cart processed successfully.</p>";
  } else {
    echo "Error updating record: " . $conn->error;
  }
  
  $sql_drop = "DROP TEMPORARY TABLE IF EXISTS temp_cart";
  $conn->query($sql_drop);
}

if (isset($_POST['process_next_box'])) {

  $sql_temp = "CREATE TEMPORARY TABLE temp_box SELECT MIN(id) as min_id FROM box WHERE box_status = 'ongoing'";
  $conn->query($sql_temp);
  
  $sql_update = "UPDATE box SET box_status = 'completed' WHERE id = (SELECT min_id FROM temp_box)";
  if ($conn->query($sql_update) === TRUE) {
    echo "<p>Item processed successfully.</p>";
  } else {
    echo "Error updating record: " . $conn->error;
  }
  
  $sql_drop = "DROP TEMPORARY TABLE IF EXISTS temp_box";
  $conn->query($sql_drop);
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>See Cart/Box Data</title>
  <link rel="stylesheet" href="waiting_orders.css">
</head>
<body>

  <h1>See Cart/Box Data</h1>

  <form method="post">
    <input type="submit" name="see_carts" value="See Carts">
    <input type="submit" name="see_boxes" value="See Boxes">
    <input type="submit" name="process_next_cart" value="Process next cart">
    <input type="submit" name="process_next_box" value="Process next box">
  </form>

  <?php
  if (isset($_POST['see_carts'])) {
    $sql = "SELECT * FROM cart WHERE cart_status = 'ongoing'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
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

  if (isset($_POST['see_boxes'])) {
    $sql = "SELECT * FROM box WHERE box_status = 'ongoing'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
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


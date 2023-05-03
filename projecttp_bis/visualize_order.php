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

?>

<!DOCTYPE html>
<html>
<head>
  <title>Get Data by Type and ID</title>
  <link rel="stylesheet" href="visualize_order.css">

</head>
<body>

  <h1>Get Data by Type and ID</h1>

  <form method="post">
    <label for="type">Type:</label>
    <select id="type" name="type">
      <option value="industrial_box">Industrial Box</option>
      <option value="cart">Cart</option>
    </select>
    <br>
    <label for="id">ID:</label>
    <input type="number" id="id" name="id">
    <br>
    <input type="submit" value="Submit">
  </form>

  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $type = $_POST['type'];
  $id = $_POST['id'];

  if ($type === 'industrial_box') {
    $sql = "SELECT 
                composition.box_id, 
                relays.relay_type, 
                sensors.sensor_type, 
                composition.sensor_amount, 
                composition.relay_amount 
            FROM 
                composition
            JOIN relays ON composition.relay_id = relays.relay_id 
            JOIN sensors ON composition.sensor_id = sensors.sensor_id
            WHERE box_id =".$id;
  } else if ($type === 'cart') {
    $sql = "SELECT * FROM cart WHERE id = ".$id;
  } else {
    echo "Invalid type selected.";
    exit;
  }

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {  
    echo "<table><tr>";
    while($fieldinfo = mysqli_fetch_field($result)) {
      echo "<th>".$fieldinfo->name."</th>";
    }
    echo "</tr>";
    while($row = $result->fetch_assoc()) {
      echo "<tr>";
      foreach ($row as $value) {
        echo "<td>".$value."</td>";
      }
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "No data found for the given type and ID.";
  }
}
?>

</body>
</html>

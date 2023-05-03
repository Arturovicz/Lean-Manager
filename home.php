<!DOCTYPE html>
<html>
<head>
	<title>Home Page</title>
    <?php include "base.php";?>
	<link rel="stylesheet" href="home.css">

</head>
<body>
	<h1>Welcome to Lean Manager 1.0</h1>
	<p>Please select an option below:</p>
	<form method="get" action="waiting_orders.php">
		<button type="submit">View Waiting Orders</button>
	</form>
	<br>
	<form method="get" action="completed_orders.php">
		<button type="submit">View Completed Orders</button>
	</form>
</body>
</html>
<?php
session_start();

// Include your database connection
include('db_connection.php');

// Check if the user is logged in as a customer
if (!(isset($_SESSION["user_id"]) && isset($_SESSION["username"]) && $_SESSION["user_type"] == "customer")) {
    header("Location: login.php"); // Redirect to login page if not logged in as a customer
    exit();
}

// Get the order ID from the query parameter
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

// Fetch order details from the database
$sql = "SELECT * FROM orders WHERE order_id = $order_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
} else {
    // Redirect to an error page or handle accordingly
    header("Location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        /* Add your CSS styles here */
    </style>
</head>

<body>
    <h2>Order Confirmation</h2>

    <p>Thank you for your order!</p>

    <h3>Order Details</h3>
    <p>Order ID: <?php echo $order['order_id']; ?></p>
    <p>Total Amount: $<?php echo number_format($order['total_amount'], 2); ?></p>

    <h3>Delivery Information</h3>
    <!-- Add delivery information here, if applicable -->

    <h3>Payment Information</h3>
    <!-- Add payment information here, if applicable -->

    <p>Your order will be processed shortly.</p>

    <a href="customer_dashboard.php">Back to Dashboard</a>
</body>

</html>

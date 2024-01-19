<?php
session_start();

// Check if the user is logged in as a customer
if (!(isset($_SESSION["user_id"]) && isset($_SESSION["username"]) && $_SESSION["user_type"] == "customer")) {
    header("Location: login.php"); // Redirect to login page if not logged in as a customer
    exit();
}

// Include your database connection
include('db_connection.php');

// Fetch order history for the logged-in user
$userId = $_SESSION["user_id"];
$sql = "SELECT * FROM orders WHERE user_id = $userId";
$result = $conn->query($sql);

$orderHistory = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orderHistory[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <style>
        /* Add your CSS styles here */
    </style>
</head>

<body>
    <h2>Order History</h2>

    <?php if (!empty($orderHistory)) : ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total Amount</th>
                    <th>Order Status</th>
                    <!-- Add more columns as needed -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderHistory as $order) : ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo $order['total_amount']; ?></td>
                        <td><?php echo $order['order_status']; ?></td>
                        <!-- Add more columns as needed -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No orders found in the order history.</p>
    <?php endif; ?>

    <a href="customer_dashboard.php">Back to Dashboard</a>
</body>

</html>

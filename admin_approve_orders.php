<?php
session_start();

// Check if the user is logged in as an admin
if (!(isset($_SESSION["user_id"]) && isset($_SESSION["username"]) && $_SESSION["user_type"] == "admin")) {
    header("Location: login.php"); // Redirect to login page if not logged in as admin
    exit();
}

// Include your database connection
include('db_connection.php');

// Fetch pending orders from the database
$sql = "SELECT * FROM orders WHERE order_status = 'pending'";
$result = $conn->query($sql);

$pendingOrders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pendingOrders[] = $row;
    }
}

// Handle order approval or delivery status update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve_order'])) {
        $orderId = $_POST['order_id'];

        // Update order status to 'approved' in the database
        $updateSql = "UPDATE orders SET order_status = 'approved' WHERE order_id = $orderId";
        $conn->query($updateSql);

        // Redirect to the same page to refresh the order list
        header("Location: admin_approve_orders.php");
        exit();
    } elseif (isset($_POST['deliver_order'])) {
        $orderId = $_POST['order_id'];

        // Update order status to 'delivered' in the database
        $updateSql = "UPDATE orders SET order_status = 'delivered' WHERE order_id = $orderId";
        $conn->query($updateSql);

        // Redirect to the same page to refresh the order list
        header("Location: admin_approve_orders.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approve Orders</title>
    <!-- Example CSS Styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #333;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            background-color: #fff;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        button {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 3px;
        }

        button:hover {
            background-color: #2980b9;
        }

        a {
            color: #3498db;
            text-decoration: none;
            margin-right: 10px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Pending Orders</h2>

    <?php if (!empty($pendingOrders)) : ?>
        <ul>
            <?php foreach ($pendingOrders as $order) : ?>
                <li>
                    Order ID: <?php echo $order['order_id']; ?><br>
                    Total Amount: $<?php echo $order['total_amount']; ?><br>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <button type="submit" name="approve_order">Approve Order</button>
                    </form>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <button type="submit" name="deliver_order">Mark as Delivered</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No pending orders.</p>
    <?php endif; ?>

    <p><a href="admin_dashboard.php">Back to Dashboard</a></p>
    <p><a href="logout.php">Logout</a></p>

    <!-- Example JavaScript -->
    <script>
        // Simple confirmation for order approval
        function confirmApprove() {
            return confirm("Are you sure you want to approve this order?");
        }

        // Simple confirmation for order delivery
        function confirmDeliver() {
            return confirm("Are you sure you want to mark this order as delivered?");
        }
    </script>
</body>
</html>

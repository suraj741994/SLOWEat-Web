<?php
session_start();

// Check if the user is logged in as a customer
if (!(isset($_SESSION["user_id"]) && isset($_SESSION["username"]) && $_SESSION["user_type"] == "customer")) {
    header("Location: login.php"); // Redirect to login page if not logged in as a customer
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(45deg, #ff8a00, #e52e71);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .dashboard-container {
            text-align: center;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #333;
        }

        p {
            color: #555;
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            margin: 10px;
            padding: 15px;
            background: #4caf50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease-in-out;
        }

        a:hover {
            background: #45a049;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
        <p>Customer Dashboard</p>
        <a href="customer_select_restaurant.php">Select a Restaurant</a>
        <a href="customer_cart.php">View Shopping Cart</a>
        <a href="customer_orders.php">Order History</a>
        <a href="logout.php">Logout</a>
    </div>
</body>

</html>

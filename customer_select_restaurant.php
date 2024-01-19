<?php
session_start();

// Include your database connection
include('db_connection.php');

// Check if the user is logged in as a customer
if (!(isset($_SESSION["user_id"]) && isset($_SESSION["username"]) && $_SESSION["user_type"] == "customer")) {
    header("Location: login.php"); // Redirect to login page if not logged in as a customer
    exit();
}

// Fetch restaurants from the database
$sql = "SELECT * FROM restaurants WHERE approved = 1"; // Assuming 'approved' column indicates the restaurant is approved
$result = $conn->query($sql);

$restaurants = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $restaurants[] = $row;
    }
}

// Fetch menu items for the selected restaurant
if (isset($_GET['restaurant_id'])) {
    $restaurantId = $_GET['restaurant_id'];
    $_SESSION['selected_restaurant'] = $restaurantId; // Store selected restaurant in session
    $sql = "SELECT * FROM menu WHERE restaurant_id = $restaurantId";
    $result = $conn->query($sql);

    $menuItems = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $menuItems[] = $row;
        }
    }
}

// Add items to cart and proceed to the next page
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        // Initialize cart if not already set
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Get selected item IDs
        $selectedItems = isset($_POST['selected_items']) ? $_POST['selected_items'] : [];

        // Fetch item details from the database
        $sql = "SELECT * FROM menu WHERE menu_id IN (" . implode(',', $selectedItems) . ")";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Add items to the cart array
                $_SESSION['cart'][] = [
                    'menu_id' => $row['menu_id'],
                    'item_name' => $row['item_name'],
                    'price' => $row['price'],
                ];
            }
        }

        // Redirect to the cart page
        header("Location: customer_cart.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select a Restaurant</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(45deg, #3498db, #e74c3c);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        h2 {
            color: #2c3e50;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin: 10px;
        }

        a {
            text-decoration: none;
            color: #fff;
            background-color: #3498db;
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
            transition: background 0.3s ease-in-out;
        }

        a:hover {
            background: #2980b9;
        }

        form {
            margin-top: 20px;
        }

        button {
            padding: 10px;
            background: #2ecc71;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
        }

        button:hover {
            background: #27ae60;
        }
    </style>
</head>

<body>
    <h2>Select a Restaurant</h2>

    <!-- Display list of restaurants -->
    <ul>
        <?php foreach ($restaurants as $restaurant) : ?>
            <li>
                <a href="customer_select_restaurant.php?restaurant_id=<?php echo $restaurant['restaurant_id']; ?>">
                    <?php echo $restaurant['name']; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Display menu items for the selected restaurant -->
    <?php if (isset($menuItems)) : ?>
        <h3>Menu Items</h3>
        <form method="post">
            <ul>
                <?php foreach ($menuItems as $menuItem) : ?>
                    <li>
                        <?php echo $menuItem['item_name']; ?> - $<?php echo $menuItem['price']; ?>
                        <input type="checkbox" name="selected_items[]" value="<?php echo $menuItem['menu_id']; ?>">
                    </li>
                <?php endforeach; ?>
            </ul>
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>
    <?php endif; ?>

    <a href="customer_dashboard.php">Back to Dashboard</a>
</body>

</html>

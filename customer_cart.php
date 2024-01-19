<?php
session_start();

// Include your database connection
include('db_connection.php');

// Display selected items in the cart
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Handle order placement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    // Check if a restaurant is selected
    if (!isset($_SESSION['selected_restaurant']) || empty($_SESSION['selected_restaurant'])) {
        echo "Please select a restaurant before placing an order.";
        exit();
    }

    // Insert order into the database
    $userId = $_SESSION["user_id"];
    $restaurantId = $_SESSION['selected_restaurant'];

    if (!empty($cartItems)) {
        $totalAmount = array_sum(array_column($cartItems, 'price'));

        $sql = "INSERT INTO orders (user_id, restaurant_id, total_amount) VALUES ($userId, $restaurantId, $totalAmount)";
        if ($conn->query($sql) === TRUE) {
            $orderId = $conn->insert_id;

            // Insert order items into the database
            foreach ($cartItems as $cartItem) {
                $menuId = $cartItem['menu_id'];
                $quantity = 1; // Assuming each selected item has a quantity of 1 in this example

                $sql = "INSERT INTO order_items (order_id, menu_id, quantity) VALUES ($orderId, $menuId, $quantity)";
                $conn->query($sql);
            }

            // Clear the cart after placing the order
            unset($_SESSION['cart']);

            // Redirect to a confirmation page or any other desired destination
            header("Location: order_confirmation.php?order_id=$orderId");
            exit();
        } else {
            echo "Error inserting order: " . $conn->error;
        }
    }
}

// Handle item deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_item'])) {
    $deleteIndex = $_POST['delete_item'];

    if (isset($cartItems[$deleteIndex])) {
        unset($cartItems[$deleteIndex]);
        $_SESSION['cart'] = array_values($cartItems); // Reset array keys
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        /* Add your CSS styles here */
    </style>
</head>

<body>
    <h2>Shopping Cart</h2>

    <?php if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && !empty($_SESSION['cart'])) : ?>
        <ul>
            <?php foreach ($_SESSION['cart'] as $index => $cartItem) : ?>
                <li>
                    <?php
                    // Ensure $cartItem is an array before accessing its elements
                    if (is_array($cartItem) && isset($cartItem['item_name']) && isset($cartItem['price'])) {
                        echo $cartItem['item_name'] . ' - $' . $cartItem['price'];
                    }
                    ?>
                    <!-- Delete Button -->
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="delete_item" value="<?php echo $index; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Checkout Form -->
        <form method="post">
            <button type="submit" name="place_order">Place Order</button>
        </form>
    <?php else : ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <a href="customer_select_restaurant.php">Back to Restaurant Selection</a>
</body>

</html>

<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['action']) && isset($_GET['menu_id'])) {
    $action = $_GET['action'];
    $menuId = $_GET['menu_id'];

    if ($action == 'add') {
        // Add item to the cart
        $_SESSION['cart'][] = $menuId;
        header('Location: menus.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        .cartItem {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <h2>Shopping Cart</h2>

    <?php if (!empty($_SESSION['cart'])): ?>
        <?php foreach ($_SESSION['cart'] as $menuItem): ?>
            <div class="cartItem">
                <p><strong>Menu Item ID:</strong> <?php echo $menuItem; ?></p>
                <!-- Add more details based on your menu data -->
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

</body>
</html>

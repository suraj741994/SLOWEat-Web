<?php
include('db_connection.php');

if (isset($_GET['restaurant_id'])) {
    $restaurantId = $_GET['restaurant_id'];

    $sql = "SELECT * FROM menu WHERE restaurant_id = $restaurantId";
    $result = $conn->query($sql);

    $menuItems = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $menuItems[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        .menuItem {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
        }
        .addToCartBtn {
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <?php if (isset($menuItems)): ?>
        <h2>Menu</h2>

        <?php foreach ($menuItems as $item): ?>
            <div class="menuItem">
                <h3><?php echo $item['item_name']; ?></h3>
                <p><strong>Price:</strong> $<?php echo $item['price']; ?></p>
                <!-- Add to Cart Button -->
                <a class="addToCartBtn" href="cart.php?action=add&menu_id=<?php echo $item['menu_id']; ?>">Add to Cart</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No menu available for the selected restaurant.</p>
    <?php endif; ?>

</body>
</html>

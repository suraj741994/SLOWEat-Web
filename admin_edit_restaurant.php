<?php
include('db_connection.php');

// Check if the user is logged in as an admin
session_start();
if (!(isset($_SESSION["user_id"]) && isset($_SESSION["username"]) && $_SESSION["user_type"] == "admin")) {
    header("Location: login.php"); // Redirect to login page if not logged in as admin
    exit();
}

// Function to get restaurant data by restaurant_id
function getRestaurantData($conn, $restaurantId) {
    $sql = "SELECT * FROM restaurants WHERE restaurant_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $restaurantId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $restaurantData = $result->fetch_assoc();
        $stmt->close();
        return $restaurantData;
    } else {
        $stmt->close();
        return [];
    }
}

// Function to get menus by restaurant_id
function getMenusByRestaurant($conn, $restaurantId) {
    $sql = "SELECT * FROM menu WHERE restaurant_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $restaurantId);
    $stmt->execute();
    $result = $stmt->get_result();

    $menus = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $menus[] = $row;
        }
    }

    return $menus;
}

// Function to update restaurant details
function updateRestaurantDetails($conn, $restaurantId, $name, $description, $category, $rating) {
    $sql = "UPDATE restaurants SET name = ?, description = ?, category = ?, rating = ? WHERE restaurant_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $name, $description, $category, $rating, $restaurantId);
    $stmt->execute();
    $stmt->close();
}

// Function to update menu item details
function updateMenuItem($conn, $menuId, $itemName, $price) {
    $sql = "UPDATE menu SET item_name = ?, price = ? WHERE menu_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdi", $itemName, $price, $menuId);
    $stmt->execute();
    $stmt->close();
}

// Fetch restaurant data
$restaurantId = isset($_GET['restaurant_id']) ? $_GET['restaurant_id'] : null;
$restaurantData = getRestaurantData($conn, $restaurantId);

// Fetch menus
$menusData = getMenusByRestaurant($conn, $restaurantId);

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update restaurant details
    updateRestaurantDetails($conn, $restaurantId, $_POST['name'], $_POST['description'], $_POST['category'], $_POST['rating']);

    // Update each menu item
    foreach ($_POST['menu'] as $menuId => $menuItem) {
        updateMenuItem($conn, $menuId, $menuItem['item_name'], $menuItem['price']);
    }

    // Reload the page after updating
    header("Location: admin_edit_restaurant.php?restaurant_id=$restaurantId");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Restaurant</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        h1, h2 {
            color: #007BFF;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input,
        textarea,
        button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        p {
            margin-top: 20px;
        }

        /* Dark Mode Styling */
        body.dark-mode {
            background-color: #333;
            color: #fff;
        }

        /* Add your additional styling here */
    </style>
</head>
<body>
    <h1>Edit Restaurant</h1>

    <?php if (!empty($restaurantData)): ?>
        <form method="post">
            <h2>Restaurant Details</h2>
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo $restaurantData['name']; ?>" required>
            <label for="description">Description:</label>
            <textarea name="description"><?php echo $restaurantData['description']; ?></textarea>
            <label for="category">Category:</label>
            <input type="text" name="category" value="<?php echo $restaurantData['category']; ?>" required>
            <label for="rating">Rating:</label>
            <input type="number" name="rating" value="<?php echo $restaurantData['rating']; ?>" required>

            <h2>Menus</h2>
            <?php foreach ($menusData as $menu): ?>
                <label for="menu[<?php echo $menu['menu_id']; ?>][item_name]">Item Name:</label>
                <input type="text" name="menu[<?php echo $menu['menu_id']; ?>][item_name]" value="<?php echo $menu['item_name']; ?>" required>
                <label for="menu[<?php echo $menu['menu_id']; ?>][price]">Price:</label>
                <input type="number" name="menu[<?php echo $menu['menu_id']; ?>][price]" value="<?php echo $menu['price']; ?>" required>
                <hr>
            <?php endforeach; ?>

            <button type="submit">Update</button>
        </form>
    <?php else: ?>
        <p>No restaurant found.</p>
    <?php endif; ?>

    <p><a href="admin_manage_restaurants.php">Back to Manage Restaurants</a></p>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>

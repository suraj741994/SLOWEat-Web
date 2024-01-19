<?php
include('db_connection.php');

// Function to get restaurant data
function getRestaurantsData($conn) {
    $sql = "SELECT * FROM restaurants";
    $result = $conn->query($sql);

    $restaurants = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $restaurants[] = $row;
        }
    }

    return $restaurants;
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

// Fetch all restaurants
$restaurantsData = getRestaurantsData($conn);

// Handle restaurant editing
if (isset($_POST['edit_restaurant'])) {
    $restaurantIdToEdit = $_POST['restaurant_id'];
    // Redirect to the admin_edit_restaurant.php page with the restaurant ID
    header("Location: admin_edit_restaurant.php?restaurant_id=$restaurantIdToEdit");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Restaurants</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(45deg, #3498db, #e74c3c);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        h1 {
            color: #2c3e50;
        }

        table {
            width: 80%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: #fff;
        }

        tr:hover {
            background-color: #ecf0f1;
        }

        button {
            background-color: #2ecc71;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
        }

        button:hover {
            background-color: #27ae60;
        }

        p {
            margin-top: 20px;
        }

        a {
            text-decoration: none;
            color: #fff;
            background-color: #3498db;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s ease-in-out;
        }

        a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <h1>Manage Restaurants</h1>

    <!-- Display restaurants -->
    <?php if (!empty($restaurantsData)): ?>
        <table border="1">
            <tr>
                <th>Restaurant ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Rating</th>
                <th>Action</th>
            </tr>
            <?php foreach ($restaurantsData as $restaurant): ?>
                <tr>
                    <td><?php echo $restaurant['restaurant_id']; ?></td>
                    <td><?php echo $restaurant['name']; ?></td>
                    <td><?php echo $restaurant['description']; ?></td>
                    <td><?php echo $restaurant['category']; ?></td>
                    <td><?php echo $restaurant['rating']; ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="restaurant_id" value="<?php echo $restaurant['restaurant_id']; ?>">
                            <button type="submit" name="edit_restaurant">Edit</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No restaurants found.</p>
    <?php endif; ?>

    <p><a href="admin_dashboard.php">Back to Admin Dashboard</a></p>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>

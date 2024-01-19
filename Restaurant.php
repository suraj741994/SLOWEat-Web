<?php
include('db_connection.php');

function getApprovedRestaurants($conn) {
    $sql = "SELECT * FROM restaurants WHERE approved = 1";
    $result = $conn->query($sql);

    $restaurants = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $restaurants[] = $row;
        }
    }

    return $restaurants;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Page</title>

</head>
<body>

    <h2>Approved Restaurants</h2>

    <?php
    $restaurantsData = getApprovedRestaurants($conn);

    foreach ($restaurantsData as $restaurant) {
        echo '<div class="restaurant" onclick="window.location=\'menus.php?restaurant_id=' . $restaurant['restaurant_id'] . '\'">';
        echo '<h3>' . $restaurant['name'] . '</h3>';
        echo '<p><strong>Description:</strong> ' . $restaurant['description'] . '</p>';
        echo '<p><strong>Category:</strong> ' . $restaurant['category'] . '</p>';
        echo '<p><strong>Rating:</strong> ' . $restaurant['rating'] . '</p>';
        echo '</div>';
    }

    $conn->close();
    ?>

</body>
</html>

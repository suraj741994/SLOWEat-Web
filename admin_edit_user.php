<?php
include('db_connection.php');

// Function to get user data by user_id
function getUserData($conn, $userId) {
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        $stmt->close();
        return $userData;
    } else {
        $stmt->close();
        return [];
    }
}

// Check if the user ID is provided in the URL
if (!isset($_GET['user_id'])) {
    echo "User ID not provided.";
    exit();
}

$userId = $_GET['user_id'];

// Get user data for the provided user ID
$userData = getUserData($conn, $userId);

// Handle updating a user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $updatedUsername = $_POST['edit_username'];
    $updatedPassword = $_POST['edit_password'];
    $updatedUserType = $_POST['edit_user_type'];

    // Perform the update operation using prepared statements
    $sql = "UPDATE users SET username = ?, password = ?, user_type = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $updatedUsername, $updatedPassword, $updatedUserType, $userId);
    $stmt->execute();
    $stmt->close();

    // Redirect to the same page after updating
    header("Location: admin_edit_user.php?user_id=$userId");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit User</title>
</head>
<body>
    <h1>Edit User</h1>

    <?php if (!empty($userData)): ?>
        <!-- Display user details -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?user_id=<?php echo $userId; ?>" method="post">
            <label for="edit_username">Username:</label>
            <input type="text" name="edit_username" value="<?php echo $userData['username']; ?>" required>
            <label for="edit_password">Password:</label>
            <input type="password" name="edit_password" required>
            <label for="edit_user_type">User Type:</label>
            <select name="edit_user_type" required>
                <option value="admin" <?php echo ($userData['user_type'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="customer" <?php echo ($userData['user_type'] == 'customer') ? 'selected' : ''; ?>>Customer</option>
            </select>
            <button type="submit" name="update_user">Update User</button>
        </form>
    <?php else: ?>
        <p>User not found.</p>
    <?php endif; ?>

    <p><a href="admin_manage_users.php">Back to Manage Users</a></p>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>

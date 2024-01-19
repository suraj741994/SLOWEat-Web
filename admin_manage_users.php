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

// Function to fetch all users from the database
function getAllUsers($conn) {
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);

    $users = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    return $users;
}

// Function to delete a user by user_id
function deleteUser($conn, $userId) {
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();
}

// Fetch all users
$usersData = getAllUsers($conn);

// Handle user deletion
if (isset($_POST['delete_user'])) {
    $userIdToDelete = $_POST['user_id'];
    deleteUser($conn, $userIdToDelete);
    // Reload the page after deletion
    header("Location: admin_manage_users.php");
    exit();
}

// Handle user editing
if (isset($_POST['edit_user'])) {
    $userIdToEdit = $_POST['user_id'];
    // Redirect to the edit user page with the user_id
    header("Location: admin_edit_user.php?user_id=$userIdToEdit");
    exit();
}

// Handle adding a new user
if (isset($_POST['add_user'])) {
    $newUsername = $_POST['new_username'];
    $newPassword = $_POST['new_password'];
    $newUserType = $_POST['new_user_type'];

    $sql = "INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $newUsername, $newPassword, $newUserType);
    $stmt->execute();
    $stmt->close();

    // Reload the page after adding a new user
    header("Location: admin_manage_users.php");
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Users</title>
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
    <h1>Manage Users</h1>

    <!-- Display users -->
    <?php if (!empty($usersData)): ?>
        <table border="1">
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>User Type</th>
                <th>Action</th>
            </tr>
            <?php foreach ($usersData as $user): ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['user_type']; ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <button type="submit" name="edit_user">Edit</button>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <button type="submit" name="delete_user">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>

    <!-- Add new user form -->
    <h2>Add New User</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="new_username">Username:</label>
        <input type="text" name="new_username" required>
        <label for="new_password">Password:</label>
        <input type="password" name="new_password" required>
        <label for="new_user_type">User Type:</label>
        <select name="new_user_type" required>
            <option value="admin">Admin</option>
            <option value="customer">Customer</option>
        </select>
        <button type="submit" name="add_user">Add User</button>
    </form>
</body>
</html>

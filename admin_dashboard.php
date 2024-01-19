<?php
session_start();

// Check if the user is logged in as an admin
if (!(isset($_SESSION["user_id"]) && isset($_SESSION["username"]) && $_SESSION["user_type"] == "admin")) {
    header("Location: login.php"); // Redirect to login page if not logged in as admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: #333;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        a {
            text-decoration: none;
            color: #0066cc;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        p {
            margin-top: 20px;
        }

        a.logout-btn {
            color: #cc0000;
            cursor: pointer;
        }

        a.logout-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Welcome to Admin Dashboard, <?php echo $_SESSION["username"]; ?></h1>
    <!-- Add links to various admin sections -->
    <ul>
        <li><a href="admin_manage_users.php">Manage Users</a></li>
        <li><a href="admin_manage_restaurants.php">Manage Restaurants</a></li>
        <li><a href="admin_approve_orders.php">Approve Orders</a></li>
    </ul>
    <p><a href="logout.php" class="logout-btn">Logout</a></p> <!-- Include a logout link -->

    <script>
        // Example JavaScript functionality for dark mode toggle

        // Check if a dark mode preference is stored in localStorage
        const darkModeEnabled = localStorage.getItem('darkMode') === 'enabled';

        // Apply dark mode if the preference is set
        if (darkModeEnabled) {
            document.body.classList.add('dark-mode');
        }

        // Function to toggle dark mode
        const toggleDarkMode = () => {
            document.body.classList.toggle('dark-mode');
            // Update the dark mode preference in localStorage
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
        };

        // Add an event listener to a button or element to trigger dark mode toggle
        // For example, let's use a button with an id "darkModeToggle"
        const darkModeToggleBtn = document.getElementById('darkModeToggle');
        if (darkModeToggleBtn) {
            darkModeToggleBtn.addEventListener('click', toggleDarkMode);
        }
    </script>
</body>
</html>

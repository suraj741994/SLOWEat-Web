<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$db_name = "restaurant_db";

$conn = new mysqli($servername, $username, $password, $db_name, 3306);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate login credentials
function validateLogin($conn, $username, $password, $userType) {
    $stmt = $conn->prepare("SELECT user_id, username, user_type FROM users WHERE username = ? AND password = ? AND user_type = ?");
    $stmt->bind_param("sss", $username, $password, $userType);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $username, $user_type);
        $stmt->fetch();
        $_SESSION["user_id"] = $user_id;  // Corrected this line
        $_SESSION["username"] = $username;
        $_SESSION["user_type"] = $user_type;
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (isset($_POST["admin_login"])) {
        if (validateLogin($conn, $username, $password, "admin")) {
            header("Location: admin_dashboard.php");
            exit();
        }
    }

    // Customer login
    if (isset($_POST["customer_login"])) {
        if (validateLogin($conn, $username, $password, "customer")) {
            header("Location: customer_dashboard.php");
            exit();
        }
    }

    // Invalid login
    $loginError = "Invalid username or password.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>Login</h2>
        <div class="error"><?php echo isset($loginError) ? $loginError : ''; ?></div>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="admin_login">Admin Login</button>
        <button type="submit" name="customer_login">Customer Login</button>
    </form>
</body>

</html>

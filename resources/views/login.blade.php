<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<body>

<?php
session_start();

// Database connection details
$host = "192.168.0.222";
$username = "remote";
$password = "password";
$database = "dev";


// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user input
function sanitize_input($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $username = sanitize_input($_POST["username"]);
    $password = sanitize_input($_POST["password"]);

    // SQL query to check if the provided credentials are valid
    $sql = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    // If a matching record is found, login is successful
    if ($result->num_rows > 0) {
        $_SESSION["username"] = $username;
        header("Location: dashboard.php"); // Redirect to the dashboard page
        exit();
    } else {
        echo "Invalid username or password.";
    }
}

// Close the database connection
$conn->close();
?>

<h2>Admin Login</h2>
<form method="post" action="/assigned-employees">
    @csrf
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br>

    <input type="submit" value="Login">
</form>

</body>
</html>

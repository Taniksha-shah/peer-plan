<?php
// Database connection details
$servername = "localhost";
$dbusername = "root"; // Replace with your database username
$dbpassword = "";     // Replace with your database password
$dbname = "peerplan"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input (optional but recommended)
    if (empty($username) || empty($password)) {
        die("Username and password are required.");
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
        // Registration successful, redirect to dashboard.html
        header("Location: dashboard.php");
        exit(); // Important to exit after a header redirect
    } else {
        // Check for a duplicate username error
        if ($conn->errno == 1062) {
            echo "Error: The username '{$username}' already exists. Please choose a different one.";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
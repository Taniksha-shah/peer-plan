<?php
// Start a new session
session_start();

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
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the hashed password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a session
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Define the log file path
$log_file = 'login_log.txt';

// Get the current date and time
$login_time = date('Y-m-d H:i:s');

// Format the log entry
$log_entry = "[" . $login_time . "] - User: " . $user['username'] . " logged in." . PHP_EOL;

// Open the file in append mode ('a') and write the log entry
file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);

            // Redirect to dashboard page
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>
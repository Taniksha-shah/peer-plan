<?php
// Database connection details
$servername = "localhost";
$dbusername = "root"; // Your MySQL username
$dbpassword = "";     // Your MySQL password
$dbname = "peerplan"; // The name of your database

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}
?>
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(401); // Unauthorized
    exit;
}

// Database connection details
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "peerplan";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    http_response_code(500); // Internal Server Error
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['id'];
    $user_id = $_SESSION['id'];

    // Use a prepared statement to prevent SQL injection
    // The query ensures the user can only delete their own events
    $stmt = $conn->prepare("DELETE FROM calendar_events WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $event_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
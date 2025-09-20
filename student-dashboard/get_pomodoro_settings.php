<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(null);
    exit;
}

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "peerplan";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    echo json_encode(null);
    exit;
}

$user_id = $_SESSION['id'];
$stmt = $conn->prepare("SELECT * FROM pomodoro_settings WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(['session_length' => 25, 'short_break_length' => 5, 'long_break_length' => 15, 'number_of_sessions' => 3]);
}
$stmt->close();
$conn->close();
?>
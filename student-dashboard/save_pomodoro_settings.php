<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(401);
    exit;
}

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "peerplan";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$user_id = $_SESSION['id'];
$session_length = $data['session_length'];
$short_break = $data['short_break'];
$long_break = $data['long_break'];
$number_of_sessions = $data['number_of_sessions']; // New variable

$stmt = $conn->prepare("INSERT INTO pomodoro_settings (user_id, session_length, short_break_length, long_break_length, number_of_sessions) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE session_length=?, short_break_length=?, long_break_length=?, number_of_sessions=?");
$stmt->bind_param("iiiiiiii", $user_id, $session_length, $short_break, $long_break, $number_of_sessions, $session_length, $short_break, $long_break, $number_of_sessions);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}
$stmt->close();
$conn->close();
?>
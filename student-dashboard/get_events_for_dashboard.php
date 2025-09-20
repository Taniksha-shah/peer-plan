<?php
session_start();

// Return an empty array if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode([]);
    exit;
}

// Database connection details
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "peerplan";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$user_id = $_SESSION['id'];
$today_start = date("Y-m-d 00:00:00");
$today_end = date("Y-m-d 23:59:59");

$sql = "SELECT title, start_date, end_date FROM calendar_events WHERE user_id = ? AND start_date BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $user_id, $today_start, $today_end);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

header('Content-Type: application/json');
echo json_encode($events);

$stmt->close();
$conn->close();
?>
<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode([]);
    exit;
}

// Database connection details
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "your_database_name";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$user_id = $_SESSION['id'];
$requested_date = $_GET['date'] ?? date('Y-m-d'); // Default to today

$start_of_day = $requested_date . " 00:00:00";
$end_of_day = $requested_date . " 23:59:59";

$sql = "SELECT title, start_date, end_date FROM calendar_events WHERE user_id = ? AND start_date BETWEEN ? AND ? ORDER BY start_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $user_id, $start_of_day, $end_of_day);
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
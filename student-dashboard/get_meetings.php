<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode([]);
    exit;
}

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "peerplan";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT sm.*, u.username FROM study_meetings sm JOIN users u ON sm.user_id = u.id ORDER BY sm.meet_date, sm.start_time";
$result = $conn->query($sql);

$meetings = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $meetings[] = $row;
    }
}

echo json_encode($meetings);

$conn->close();
?>
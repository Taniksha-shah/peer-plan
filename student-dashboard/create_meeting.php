<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in.']);
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

$user_id = $_SESSION['id'];
$title = $_POST['title'];
$subject = $_POST['subject'];
$date = $_POST['date'];
$time = $_POST['time'];
$description = $_POST['description'];

$stmt = $conn->prepare("INSERT INTO study_meetings (user_id, title, subject, meet_date, start_time, description) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssss", $user_id, $title, $subject, $date, $time, $description);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
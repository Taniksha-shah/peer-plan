<?php
session_start();

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
    http_response_code(500);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task'])) {
    $user_id = $_SESSION['id'];
    $task = $_POST['task'];

    $stmt = $conn->prepare("INSERT INTO todo_items (user_id, task_description) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $task);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'id' => $conn->insert_id, 'task' => htmlspecialchars($task)]);
    } else {
        http_response_code(500);
    }
    $stmt->close();
    $conn->close();
}
?>
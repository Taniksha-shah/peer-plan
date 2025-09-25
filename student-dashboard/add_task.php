<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized.']);
    exit;
}

require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title']) && isset($_POST['list_id'])) {
    $user_id = $_SESSION['id'];
    $list_id = $_POST['list_id'];
    $title = trim($_POST['title']);

    if (empty($title)) {
        echo json_encode(['status' => 'error', 'message' => 'Task title cannot be empty.']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO tasks (user_id, list_id, title) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $list_id, $title);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'id' => $conn->insert_id, 'title' => $title]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to add task.']);
    }

    $stmt->close();
    $conn->close();

} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
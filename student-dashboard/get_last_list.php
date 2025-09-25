<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized.']);
    exit;
}

require 'db_connect.php';

$user_id = $_SESSION['id'];

// Get the last saved list for the user
$stmt = $conn->prepare("SELECT id, title FROM todo_lists WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$list_result = $stmt->get_result();
$list = $list_result->fetch_assoc();
$stmt->close();

if (!$list) {
    // No lists found
    echo json_encode(['status' => 'success', 'list' => null]);
    $conn->close();
    exit;
}

// Get the tasks for that list
$stmt = $conn->prepare("SELECT id, title, is_completed FROM tasks WHERE list_id = ? ORDER BY created_at ASC");
$stmt->bind_param("i", $list['id']);
$stmt->execute();
$tasks_result = $stmt->get_result();

$tasks = [];
while ($row = $tasks_result->fetch_assoc()) {
    $tasks[] = $row;
}
$stmt->close();
$conn->close();

echo json_encode(['status' => 'success', 'list' => [
    'title' => htmlspecialchars($list['title']),
    'tasks' => $tasks
]]);
?>
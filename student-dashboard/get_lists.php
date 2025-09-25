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
$stmt = $conn->prepare("SELECT id, title FROM todo_lists WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$lists = [];
while ($row = $result->fetch_assoc()) {
    $listId = $row['id'];
    $listTitle = htmlspecialchars($row['title']);

    // Fetch tasks for each list
    $tasks_stmt = $conn->prepare("SELECT id, title, is_completed FROM tasks WHERE list_id = ?");
    $tasks_stmt->bind_param("i", $listId);
    $tasks_stmt->execute();
    $tasks_result = $tasks_stmt->get_result();

    $tasks = [];
    while ($task_row = $tasks_result->fetch_assoc()) {
        $tasks[] = $task_row;
    }

    $lists[] = [
        'id' => $listId,
        'title' => $listTitle,
        'tasks' => $tasks
    ];
    $tasks_stmt->close();
}

echo json_encode($lists);

$stmt->close();
$conn->close();
?>
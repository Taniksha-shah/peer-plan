<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized.']);
    exit;
}

require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title']) && isset($_POST['tasks'])) {
    $user_id = $_SESSION['id'];
    $list_title = $_POST['title'];
    $tasks = json_decode($_POST['tasks'], true);

    if (empty($list_title)) {
        echo json_encode(['status' => 'error', 'message' => 'List title cannot be empty.']);
        exit;
    }

    $conn->begin_transaction();

    try {
        // Insert the new list title
        $stmt_list = $conn->prepare("INSERT INTO todo_lists (user_id, title) VALUES (?, ?)");
        $stmt_list->bind_param("is", $user_id, $list_title);
        $stmt_list->execute();
        $list_id = $conn->insert_id;
        $stmt_list->close();

        // Insert each task associated with the new list
        $stmt_tasks = $conn->prepare("INSERT INTO tasks (list_id, title, is_completed) VALUES (?, ?, ?)");
        foreach ($tasks as $task_title) {
            $is_completed = 0; // New tasks are not completed
            $stmt_tasks->bind_param("isi", $list_id, $task_title, $is_completed);
            $stmt_tasks->execute();
        }
        $stmt_tasks->close();

        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'List saved successfully.']);

    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

    $conn->close();

} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
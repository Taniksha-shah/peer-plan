<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized.']);
    exit;
}

require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['id'];
    $fullname = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $bio = trim($_POST['bio']);
    $interests = trim($_POST['interests']);

    // Update the profiles table
    $stmt = $conn->prepare("UPDATE profiles SET fullname = ?, email = ?, bio = ?, interests = ? WHERE user_id = ?");
    $stmt->bind_param("ssssi", $fullname, $email, $bio, $interests, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
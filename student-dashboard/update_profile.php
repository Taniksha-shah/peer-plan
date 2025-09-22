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
    $full_name = $_POST['full_name'] ?? null;
    $bio = $_POST['bio'] ?? null;
    $profile_picture_url = $_POST['profile_picture_url'] ?? null;

    $stmt = $conn->prepare("UPDATE profiles SET full_name = ?, bio = ?, profile_picture_url = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $full_name, $bio, $profile_picture_url, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
}
?>
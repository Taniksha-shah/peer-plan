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

// Check if a profile already exists for the user
$stmt = $conn->prepare("SELECT p.full_name, p.bio, u.username, u.email FROM profiles p JOIN users u ON p.user_id = u.id WHERE p.user_id = ? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

// If no profile exists, create a new one with default values
if (!$profile) {
    $stmt_insert = $conn->prepare("INSERT INTO profiles (user_id) VALUES (?)");
    $stmt_insert->bind_param("i", $user_id);
    $stmt_insert->execute();
    $stmt_insert->close();

    // Now re-fetch the data including the username and email
    $stmt = $conn->prepare("SELECT p.full_name, p.bio, u.username, u.email FROM profiles p JOIN users u ON p.user_id = u.id WHERE p.user_id = ? LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $profile = $result->fetch_assoc();
}

echo json_encode(['status' => 'success', 'profile' => $profile]);

$stmt->close();
$conn->close();
?>
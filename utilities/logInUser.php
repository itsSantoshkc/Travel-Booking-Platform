<?php
header("Content-Type: application/json");
session_start();
include '../model/User.php';
require_once '../conn.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Method Not Allowed"
    ]);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Invalid email address"
    ]);
    exit;
}

$user = new User($conn);

$userData = $user->authenticateUser($email, $password);

if ($userData) {
    $_SESSION["userID"] = $userData['userId'];
    $_SESSION["role"] = $userData['role'];

    echo json_encode([
        "success" => true,
        "role" => $userData['role'],
        "message" => "Login successful"
    ]);
    exit;
}

http_response_code(401);
echo json_encode([
    "success" => false,
    "message" => "Invalid credentials"
]);
exit;

<?php
include '../model/User.php';
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Method Not Allowed"
    ]);
    exit;
}


$firstName = trim($_POST['firstName'] ?? '');
$lastName = trim($_POST['lastName'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$dob = trim($_POST['dob'] ?? '');
$password = $_POST['password'] ?? '';




if (empty($firstName) ||empty($lastName) || empty($email) || empty($phone) || empty($password)) {
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

if (!preg_match('/^\d{10}$/', $phone)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Phone must be 10 digits"
    ]);
    exit;
}

// $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&^#()[\]{}<>~`_+=|\\\\:;\'",.\/?-]).{9,}$/';
// if (!preg_match($passwordRegex, $password)) {
//     http_response_code(400);
//     echo json_encode([
//         "success" => false,
//         "message" => "Password must be 9+ characters with uppercase, lowercase, digit, and special character"
//     ]);
//     exit;
// }


$hash = password_hash($password, PASSWORD_BCRYPT);



$user = new User($conn);
$newUser =  array("firstName" => $firstName, "lastName" => $lastName, "email" => $email, "password" =>  $hash, "dateOfBirth" => $dob, "phone" => $phone, "role" => "user");
$userData = $user->newUser($newUser);


if ($userData['success']) {
    $_SESSION["userID"] = $userData['userId'];
    $_SESSION["role"] = "user";
    $_SESSION["verified"] = false;

    http_response_code(200);
    echo json_encode([
        "success" => true,
        "message" => "Registration successful"
    ]);
    exit;
}
http_response_code(500);
echo json_encode([
    "success" => false,
    "message" => "Internal Server Error"
]);

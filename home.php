<?php

include('conn.php');
include('./model/User.php');
include('./middleware/authMiddleware.php');


if (isLoggedIn()) {
    echo "welcome fellowUser";
}

// $mockUser = [
//     "firstName"   => "Ava",
//     "lastName"    => "Johnson",
//     "email"       => "ava.johnson@example.com",
//     "password"    => password_hash("StrongPass123!", PASSWORD_DEFAULT),
//     "dateOfBirth" => "1995-06-12",
//     "phone"       => "9876543210",
//     "role"        => "traveler"
// ];

// $user = new User($conn);

// $user->newUser($mockUser);

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <script src="https://kit.fontawesome.com/e07ff84049.js" crossorigin="anonymous"></script>

    <title>Travel Booking Platform</title>
</head>

<?php 
include __DIR__ . '/middleware/authMiddleware.php';



if (isLoggedIn() && isAdmin()) {
    header("Location: admin/dashboard.php");
    exit;
}

?>
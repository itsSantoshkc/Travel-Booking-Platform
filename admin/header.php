<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <script src="https://kit.fontawesome.com/e07ff84049.js" crossorigin="anonymous"></script>
</head>
<body>

    <div class="flex items-center justify-center w-screen h-screen">
        <aside class="w-1/4 h-full ">
            <?php
            
include("./component/Sidebar.php");
require_once __DIR__ . '/../middleware/authMiddleware.php';

if(!isLoggedIn()){
    header("Location:" ."/Travel-Booking-Platform/login.php");
    exit;
}

require_once __DIR__ . '/../middleware/authMiddleware.php';


if(isLoggedIn() && !isAdmin()){
    header("Location:" ."/Travel-Booking-Platform/index.php");
    exit;
}



?>
        </aside>

        <main class="flex items-center justify-center w-3/4 h-full mt-15 ">
            

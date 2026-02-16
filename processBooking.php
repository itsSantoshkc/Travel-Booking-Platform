<?php
include("conn.php");
include("model/travel_package.php");
include("model/Booking.php"); 
include("middleware/authMiddleware.php");

if(!isLoggedIn()){
    return [
        "success" => false,
        "message" => "Please login to book"
    ];
}

$package_id = trim($_POST['package_id'] ?? '');
$noOfSlot   = intval($_POST['no-of-slots'] ?? 0);
$userId     = $_SESSION["userID"];

if (empty($package_id) || $noOfSlot <= 0) {
    return [
        "success" => false,
        "message" => "All fields are required and slots must be at least 1"
    ];
}


$activityObj = new Travel($conn);
$bookingObj  = new Booking($conn);

$activity = $activityObj->getTravelPackageById($package_id);
if (!$activity) {
    return [
        "success" => false, 
        "message" => "Package not found"
    ];
}

$maxCapacity = intval($activity['totalSlots']);
$occupied    = $bookingObj->getSlotsOccupied($package_id);
$remaining   = $maxCapacity - $occupied;

if ($noOfSlot > $remaining) {
    return [
        "success" => false, 
        "message" => "Booking failed. Only $remaining slots remaining for this time."
    ];
}

$bookingData = [
    "user_id" => $userId,
    "package_id" => $package_id,
    "slots" => $noOfSlot,
];

$result = $bookingObj->newBooking($bookingData);

if($result['success'] == true){
     header("Location: index.php?status=success");
}else{
    echo "Invalid Server Error";
}
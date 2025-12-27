<?php
include("conn.php");
include("model/Activity.php");
include("model/Booking.php"); 
include("middleware/authMiddleware.php");

// 1. Authentication Check
if(!isLoggedIn()){
    return [
        "success" => false,
        "message" => "Please login to book"
    ];
}

// 2. Data Extraction and Sanitization
$activityId = trim($_POST['activity-id'] ?? '');
$date       = $_POST['date'] ?? '';
$timeSlot   = $_POST['time-slot'] ?? '';
$noOfSlot   = intval($_POST['no-of-slots'] ?? 0);
$userId     = $_SESSION["userID"];

// 3. Mandatory Field Validation
if (empty($activityId) || empty($date) || empty($timeSlot) || $noOfSlot <= 0) {
    return [
        "success" => false,
        "message" => "All fields are required and slots must be at least 1"
    ];
}

// 4. Capacity and Existence Validation
$activityObj = new Activity($conn);
$bookingObj  = new Booking($conn);

// Check if activity exists
$activity = $activityObj->getActivityById($activityId);
if (!$activity) {
    return [
        "success" => false, 
        "message" => "Activity not found"
    ];
}

// Calculate Availability
$maxCapacity = intval($activity['no_of_slots']);
$occupied    = $bookingObj->getSlotsOccupied($activityId, $date, $timeSlot);
$remaining   = $maxCapacity - $occupied;

if ($noOfSlot > $remaining) {
    return [
        "success" => false, 
        "message" => "Booking failed. Only $remaining slots remaining for this time."
    ];
}

// 5. Save to Database
$bookingData = [
    "userId" => $userId,
    "activityId" => $activityId,
    "slots" => $noOfSlot,
    "time" => $timeSlot,
    "bookedFor" => $date
];

$result = $bookingObj->newBooking($bookingData);

// Return the final result array
return $result;
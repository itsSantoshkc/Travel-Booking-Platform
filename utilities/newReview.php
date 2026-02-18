<?php
// Example POST data
// $_POST = array(
//     "package_id" => "PKG-012",
//     "rating" => "3",
//     "review" => "ds"
// );

// Database connection


// Validation
$errors = [];

$package_id = isset($_POST['package_id']) ? trim($_POST['package_id']) : '';
$rating = isset($_POST['rating']) ? trim($_POST['rating']) : '';
$review = isset($_POST['review']) ? trim($_POST['review']) : '';

if (empty($package_id)) {
    $errors[] = "Package ID is required.";
}

if (empty($rating) || ($rating<1 || $rating > 5)) {
    $errors[] = "Rating must be an integer between 1 and 5.";
}

if (!empty($review) && strlen($review) > 500) {
    $errors[] = "Review must be less than 500 characters.";
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
    exit;
}
require_once("../conn.php");
require_once("../model/Review.php");
$reviewObj = new Review($conn);

$reviewData =   ['userID' => $_SESSION['userID'],'package_id' => $package_id, 'rating' => $rating,'review' => $review ];
$result = $reviewObj->newReview($reviewData);

if($result['success']){
    header("Location: ../travelPackage.php?package=$package_id");
}else{
    foreach($errors as $error){
        echo $error;
    }
}


?>

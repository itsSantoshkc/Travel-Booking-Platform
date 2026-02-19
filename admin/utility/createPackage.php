<?php
include("../conn.php");
include('../model/travel_package.php');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
}

$errors        = [];
$uploadedFiles = [];
$totalUploaded = 0;

$uploadDir     = "../uploads/packages/";
$allowedTypes  = ['image/jpeg', 'image/png', 'image/svg'];
$minImages     = 3;
$maxImages     = 4;


if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}


$name        = trim($_POST['name'] ?? '');
$price       = $_POST['price'] ?? '';
$duration = (int)($_POST['duration'] ?? 0);
$description = trim($_POST['description'] ?? '');
$totalSlots       = (int)($_POST['slots'] ?? 0);
$location    = trim($_POST['location'] ?? '');
$arrivalTime   = $_POST['arrival-time'];
$startingDate    = $_POST['starting-date'] ?? date('Y-m-d');

if ($name === '') {
    $errors[] = "Package name is required.";
}

if (!is_numeric($price)) {
    $errors[] = "Price must be a number.";
}


for ($i = 1; $i <= $maxImages; $i++) {
    $key = "image_$i";

    if (empty($_FILES[$key]) || $_FILES[$key]['error'] !== UPLOAD_ERR_OK) {
        continue;
    }

    $tmpPath = $_FILES[$key]['tmp_name'];
    $type    = $_FILES[$key]['type'];
    $nameRaw = basename($_FILES[$key]['name']);

    if (!in_array($type, $allowedTypes, true)) {
        $errors[] = "Image $i must be JPG, PNG, or WebP.";
        continue;
    }

    $newName = uniqid('', true) . '_' . $nameRaw;
    $dest    = $uploadDir . $newName;

    if (!move_uploaded_file($tmpPath, $dest)) {
        $errors[] = "Failed to upload Image $i.";
        continue;
    }

    $uploadedFiles[] = $dest;
    $totalUploaded++;
}


if ($totalUploaded < $minImages) {
    $errors[] = "You must upload at least $minImages images (uploaded: $totalUploaded).";
}


if (!empty($errors)) {
    foreach ($uploadedFiles as $file) {
        if (file_exists($file)) {
            unlink($file);
        }
    }

    echo "<div style='color:red;'><strong>Errors:</strong><ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul></div>";

    return;
}


$packageData = [
    'name'          => $name,
    'price'         => (float)$price,
    'duration'      => $duration,
    'description'   => $description,
    'slots'         => $totalSlots,
    'location'      => $location,
    'arrival_time'  => $arrivalTime,
    'starting_date' => $startingDate,
    'images'        => $uploadedFiles,
];


$packageModel = new Travel($conn);
$response = $packageModel->newPackage($packageData);

if($response['success'] == true){
echo "<h1>Success!</h1>";
echo "<p>Activity '{$name}' created with {$totalUploaded} images.</p>";
}else{
    echo "Error Uploading File";
}


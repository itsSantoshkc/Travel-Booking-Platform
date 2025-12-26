<?php
include('../conn.php');
include("../model/Activity.php");

// 1. The Data
$data = [
    "name"        => "Santosh kc",
    "price"       => 299.00,
    "description" => "sdf",
    "slots"       => 3,
    "location"    => "Kathmandu",
    "eventType"   => "recurring",
    "mainDate"    => "2025-12-27",
    "slots_list"  => ["10:52 - 10:54"],
    "days" => [
        "2",
        "3",
        "4",
        "5",
        "6"
    ],
    "images"      => [
        "uploads/activities/694e7b79117c8_1.jpg",
        "uploads/activities/694e7b7911d3f_2.jpg"
    ]
];



// 2. The Object
$activityObj = new Activity($conn);

// 3. The Call
$result = $activityObj->newActivity($data);

print_r($result);

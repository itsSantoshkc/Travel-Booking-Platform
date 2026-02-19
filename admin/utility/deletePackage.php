<?php
ob_start();
require_once("../conn.php");
require_once("../model/travel_package.php");
$package_id = isset($_GET['id']) ? $_GET['id'] : "";
$travelObj = new Travel($conn);
$travelData = $travelObj->deleteTravelPackage($package_id);
if ($travelData) {
    header("Location: managePackage.php?page=1&status=success");
    exit;
} else {
    header("Location: managePackage.php?page=1&status=fail");
    exit;
}

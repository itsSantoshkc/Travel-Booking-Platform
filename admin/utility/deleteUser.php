<?php
ob_start();
require_once("../conn.php");
require_once("../model/User.php");
$userID = isset($_GET['id']) ? $_GET['id'] : "";
$userObj = new User($conn);
$result = $userObj->deleteUser($userID);
if ($result['success']) {
    header("Location: manageUser.php?status=success");
    exit;
} else {
    header("Location: manageUser.php?status=fail");
    exit;
}

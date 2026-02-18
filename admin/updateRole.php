<?php
include("../conn.php");
include("../model/user.php"); 
include("./header.php");

$errors = [];

$userID = isset($_POST['userID']) ? trim($_POST['userID']) : '';
$role = isset($_POST['role']) ? trim($_POST['role']) : '';

if (empty($userID)) {
    $errors[] = "User ID is required.";
}

if (empty($role)) {
    $errors[] = "Role  is required.";
}



if (!empty($errors)) {
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
    exit;
}

$userObj = new User($conn);
$newUserData = ['role'=> $role,'userID' => $userID];
$result = $userObj->updateUserRole($newUserData);

if($result['success']){
    $_SESSION['role'] = $role;
    header("Location: manageUser.php");
    showToast("Role Updated Successfully",'success');
    exit;

}else{
    foreach($errors as $error){
        echo $error;
    }
}


?>

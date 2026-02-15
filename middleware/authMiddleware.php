<?php
require_once(__DIR__ . '/../conn.php');
function isLoggedIn()
{
    if (isset($_SESSION["userID"]) && !empty($_SESSION["userID"])) {
        return true;
    } else
        return false;
}

function isAdmin()
{
    if (isLoggedIn()) {
        if (isset($_SESSION["role"]) && !empty($_SESSION["role"])) {
            if ($_SESSION["role"] == "admin") {
                return true;
            }
        }
    }
    return false;
}




// if (isLoggedIn()) {
//     $userId = $_SESSION["userID"];
// } else {
//     $user_name = "";
// }

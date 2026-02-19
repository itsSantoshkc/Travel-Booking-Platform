<?php
include("../conn.php");
include("../header.php");

$userId = $_SESSION['userID']; 
$sql = "SELECT * FROM user WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fName       = $_POST['firstName'];
    $lName       = $_POST['lastName'];
    $dob         = $_POST['dateOfBirth'];
    $phone       = $_POST['phone'];
    $newEmail    = trim($_POST['email']);
    $newPassword = $_POST['new_password'];

    $sqlUp = "UPDATE user SET firstName = ?, lastName = ?, dateOfBirth = ?, phone = ?, email = ? WHERE userID = ?";
    $stmtUp = $conn->prepare($sqlUp);
    $stmtUp->bind_param("ssssss", $fName, $lName, $dob, $phone, $newEmail, $userId);
    $stmtUp->execute();

    if (!empty($newPassword)) {
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
        $sqlPwd = "UPDATE user SET password = ? WHERE userID = ?";
        $stmtPwd = $conn->prepare($sqlPwd);
        $stmtPwd->bind_param("ss", $hashed, $userId);
        $stmtPwd->execute();
    }

    echo "<script>alert('Profile Updated Successfully!'); window.location.href='manageProfile.php';</script>";
}
require_once("../components/Navbar.php")
?>
<head>
    <link rel="stylesheet" href="./css/manageProfile.css" />
</head>

<main class="profile-wrapper">
    <div class="profile-card">

        <div class="profile-banner">
            <div class="profile-avatar">
                <?= strtoupper(substr($user['firstName'], 0, 1) . substr($user['lastName'], 0, 1)) ?>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-header">
                <div>
                    <h2 class="profile-name">
                        <?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?>
                    </h2>
                    <p class="profile-meta">
                        <i class="fas fa-envelope"></i>
                        <?= htmlspecialchars($user['email']) ?>
                        <span class="profile-role"><?= $user['role'] ?></span>
                    </p>
                </div>
            </div>

            <form method="POST" class="profile-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>First Name</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input type="text" name="firstName"
                                   value="<?= htmlspecialchars($user['firstName']) ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Last Name</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input type="text" name="lastName"
                                   value="<?= htmlspecialchars($user['lastName']) ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <div class="input-wrapper">
                            <i class="fas fa-phone"></i>
                            <input type="text" name="phone"
                                   value="<?= htmlspecialchars($user['phone']) ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Date of Birth</label>
                        <div class="input-wrapper">
                            <i class="fas fa-calendar"></i>
                            <input type="date" name="dateOfBirth"
                                   value="<?= date('Y-m-d', strtotime($user['dateOfBirth'])) ?>">
                        </div>
                    </div>
                </div>

                <div class="section-divider">
                    <p>Account Credentials</p>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Email Address</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="email"
                                       value="<?= htmlspecialchars($user['email']) ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>New Password</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="new_password"
                                       placeholder="Leave blank to keep current">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</main>

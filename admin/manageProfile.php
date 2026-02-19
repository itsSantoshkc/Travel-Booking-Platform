<?php
include("../conn.php");
include("../middleware/authMiddleware.php");
include("./header.php");

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
    $success = $stmtUp->execute();

    if (!empty($newPassword)) {
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
        $sqlPwd = "UPDATE user SET password = ? WHERE userID = ?";
        $stmtPwd = $conn->prepare($sqlPwd);
        $stmtPwd->bind_param("ss", $hashed, $userId);
        $success = $stmtPwd->execute();
    }

    if ($success) {
        echo "<script>window.location.href='manageProfile.php?status=success';</script>";
    } else {
        echo "<script>window.location.href='manageProfile.php?status=fail';</script>";
    }
    exit;
}
?>

<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    background: #f8fafc;
    font-family: sans-serif;
  }

  /* ── Page wrapper ── */
  .profile-page {
    width: 100%;
    max-width: 768px;
    padding: 40px 16px;
    margin: 0 auto;
  }

  /* ── Card ── */
  .profile-card {
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 24px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.10);
    overflow: hidden;
  }

  /* ── Banner ── */
  .profile-banner {
    position: relative;
    height: 128px;
    background: linear-gradient(to right, #ef4444, #fb923c);
  }

  .profile-avatar-wrapper {
    position: absolute;
    bottom: -48px;
    left: 32px;
  }

  .profile-avatar {
    width: 96px;
    height: 96px;
    background: #fff;
    border: 4px solid #fff;
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    font-weight: 700;
    color: #ef4444;
  }

  /* ── Card body ── */
  .profile-body {
    padding: 64px 32px 32px;
  }

  /* ── User info header ── */
  .profile-info {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 32px;
  }

  .profile-name {
    font-size: 1.4rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 4px;
  }

  .profile-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #64748b;
    font-size: 0.9rem;
  }

  .profile-meta i {
    font-size: 0.75rem;
  }

  .profile-role-badge {
    padding: 2px 8px;
    background: #f1f5f9;
    color: #475569;
    font-size: 10px;
    font-weight: 700;
    border-radius: 999px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  /* ── Form ── */
  .profile-form {
    display: flex;
    flex-direction: column;
    gap: 24px;
  }

  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
  }

  @media (max-width: 640px) {
    .form-grid { grid-template-columns: 1fr; }
    .form-actions { flex-direction: column; }
  }

  .form-field {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .form-label {
    margin-left: 4px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #94a3b8;
    letter-spacing: 0.5px;
  }

  .input-wrapper {
    position: relative;
  }

  .input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.75rem;
    color: #94a3b8;
    pointer-events: none;
  }

  .form-input {
    width: 100%;
    padding: 12px 16px 12px 40px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #f8fafc;
    outline: none;
    font-size: 0.95rem;
    color: #1e293b;
    transition: border-color 0.2s, box-shadow 0.2s;
    font-family: inherit;
  }

  .form-input:focus {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239,68,68,0.12);
    background: #fff;
  }

  /* ── Credentials section ── */
  .credentials-section {
    border-top: 1px solid #f1f5f9;
    padding-top: 16px;
  }

  .credentials-label {
    margin-left: 4px;
    margin-bottom: 16px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #94a3b8;
    letter-spacing: 0.5px;
  }

  /* ── Actions ── */
  .form-actions {
    display: flex;
    flex-direction: row;
    gap: 16px;
    padding-top: 24px;
    border-top: 1px solid #f8fafc;
  }

  .btn-save {
    flex: 1;
    padding: 16px;
    background: #1e293b;
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
    border: none;
    border-radius: 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background 0.2s, transform 0.1s;
    font-family: inherit;
  }

  .btn-save:hover  { background: #0f172a; }
  .btn-save:active { transform: scale(0.98); }
</style>

<main class="profile-page">
    <div class="profile-card">

        <div class="profile-banner">
            <div class="profile-avatar-wrapper">
                <div class="profile-avatar">
                    <?= strtoupper(substr($user['firstName'], 0, 1) . substr($user['lastName'], 0, 1)) ?>
                </div>
            </div>
        </div>

        <div class="profile-body">

            <div class="profile-info">
                <div>
                    <h2 class="profile-name"><?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?></h2>
                    <p class="profile-meta">
                        <i class="fas fa-envelope"></i>
                        <?= htmlspecialchars($user['email']) ?>
                        <span class="profile-role-badge"><?= $user['role'] ?></span>
                    </p>
                </div>
            </div>

            <form action="" method="POST" class="profile-form">

                <div class="form-grid">
                    <div class="form-field">
                        <label class="form-label">First Name</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" name="firstName" class="form-input"
                                   value="<?= htmlspecialchars($user['firstName']) ?>">
                        </div>
                    </div>

                    <div class="form-field">
                        <label class="form-label">Last Name</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" name="lastName" class="form-input"
                                   value="<?= htmlspecialchars($user['lastName']) ?>">
                        </div>
                    </div>

                    <div class="form-field">
                        <label class="form-label">Phone Number</label>
                        <div class="input-wrapper">
                            <i class="fas fa-phone input-icon"></i>
                            <input type="text" name="phone" class="form-input"
                                   value="<?= htmlspecialchars($user['phone']) ?>">
                        </div>
                    </div>

                    <div class="form-field">
                        <label class="form-label">Date of Birth</label>
                        <div class="input-wrapper">
                            <i class="fas fa-calendar input-icon"></i>
                            <input type="date" name="dateOfBirth" class="form-input"
                                   value="<?= date('Y-m-d', strtotime($user['dateOfBirth'])) ?>">
                        </div>
                    </div>
                </div>

                <!-- Account Credentials -->
                <div class="credentials-section">
                    <p class="credentials-label">Account Credentials</p>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Email Address</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" name="email" class="form-input"
                                       value="<?= htmlspecialchars($user['email']) ?>">
                            </div>
                        </div>

                        <div class="form-field">
                            <label class="form-label">New Password</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" name="new_password" class="form-input"
                                       placeholder="Leave blank to keep current">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</main>

<?php
require_once "../components/Notificaton.php";
$status = isset($_GET['status']) ? $_GET['status'] : "";
if (!empty($status)) {
    if ($status == 'success') {
        showToast("Profile updated successfully", 'success');
    } else if ($status == 'fail') {
        showToast("Unable to update profile", 'error');
    }
}
?>
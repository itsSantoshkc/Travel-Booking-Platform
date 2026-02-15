<?php
include('../conn.php');
include('../header.php');
include("../components/Navbar.php");

$userId = $_SESSION['userID']; 
$sql = "SELECT * FROM user WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fName = $_POST['firstName'];
    $lName = $_POST['lastName'];
    $dob   = $_POST['dateOfBirth'];
    $phone = $_POST['phone'];

    $sqlUp = "UPDATE user SET firstName = ?, lastName = ?, dateOfBirth = ?, phone = ? WHERE userID = ?";
    $stmtUp = $conn->prepare($sqlUp);
    $stmtUp->bind_param("sssss", $fName, $lName, $dob, $phone, $userId);
    
    if ($stmtUp->execute()) {
        echo "<script>alert('Profile Updated Successfully!'); window.location.href='manageProfile.php';</script>";
    }
}
?>

<main class="w-full max-w-3xl px-4 py-10 mx-auto">
    <div class="overflow-hidden bg-white border border-gray-100 shadow-xl rounded-3xl">
        
        <div class="relative h-32 bg-gradient-to-r from-red-500 to-orange-400">
            <div class="absolute -bottom-12 left-8">
                <div class="flex items-center justify-center w-24 h-24 text-3xl font-bold text-red-500 bg-white border-4 border-white shadow-lg rounded-2xl">
                    <?= strtoupper(substr($user['firstName'], 0, 1) . substr($user['lastName'], 0, 1)) ?>
                </div>
            </div>
        </div>

        <div class="px-8 pt-16 pb-8">
            <div class="flex items-start justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800"><?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?></h2>
                    <p class="flex items-center gap-2 text-slate-500">
                        <i class="text-xs fas fa-envelope"></i> <?= htmlspecialchars($user['email']) ?>
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-full uppercase tracking-tighter"><?= $user['role'] ?></span>
                    </p>
                </div>
            </div>

            <form action="" method="POST" class="space-y-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-1">
                        <label class="ml-1 text-xs font-bold uppercase text-slate-400">First Name</label>
                        <div class="relative">
                            <i class="absolute text-xs -translate-y-1/2 fas fa-user left-4 top-1/2 text-slate-400"></i>
                            <input type="text" name="firstName" value="<?= htmlspecialchars($user['firstName']) ?>" 
                                   class="w-full py-3 pl-10 pr-4 transition-all border outline-none bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="ml-1 text-xs font-bold uppercase text-slate-400">Last Name</label>
                        <div class="relative">
                            <i class="absolute text-xs -translate-y-1/2 fas fa-user left-4 top-1/2 text-slate-400"></i>
                            <input type="text" name="lastName" value="<?= htmlspecialchars($user['lastName']) ?>" 
                                   class="w-full py-3 pl-10 pr-4 transition-all border outline-none bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="ml-1 text-xs font-bold uppercase text-slate-400">Phone Number</label>
                        <div class="relative">
                            <i class="absolute text-xs -translate-y-1/2 fas fa-phone left-4 top-1/2 text-slate-400"></i>
                            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" 
                                   class="w-full py-3 pl-10 pr-4 transition-all border outline-none bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="ml-1 text-xs font-bold uppercase text-slate-400">Date of Birth</label>
                        <div class="relative">
                            <i class="absolute text-xs -translate-y-1/2 fas fa-calendar left-4 top-1/2 text-slate-400"></i>
                            <input type="date" name="dateOfBirth" value="<?= date('Y-m-d', strtotime($user['dateOfBirth'])) ?>" 
                                   class="w-full py-3 pl-10 pr-4 transition-all border outline-none bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-4 pt-6 border-t border-slate-50 md:flex-row">
                    <button type="submit" class="flex-1 py-4 bg-slate-800 text-white font-bold rounded-2xl hover:bg-slate-900 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        Save Changes
                    </button>
                    <button type="button" onclick="togglePasswordModal()" class="flex items-center justify-center flex-1 gap-2 py-4 font-bold transition-all bg-white border border-slate-200 text-slate-600 rounded-2xl hover:bg-slate-50">
                        <i class="fas fa-lock"></i> Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
<?php
include("../conn.php");
include("../model/user.php");         // adjust to your actual User model
include("../middleware/authMiddleware.php");
include("./header.php");

// Handle role update via POST (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_role') {
    header('Content-Type: application/json');
    $userId = intval($_POST['user_id'] ?? 0);
    $newRole = trim($_POST['role'] ?? '');
    $allowedRoles = ['admin', 'user', 'moderator']; // adjust to your roles

    if ($userId && in_array($newRole, $allowedRoles)) {
        // Direct query — replace with your User model method if available
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE user_id = ?");
        $stmt->bind_param("si", $newRole, $userId);
        echo $stmt->execute()
            ? json_encode(['success' => true])
            : json_encode(['success' => false, 'message' => 'DB error']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
    exit;
}
?>

<head>
    <link rel="stylesheet" href="./css/managebookings.css">
</head>

<section class="min-h-screen p-6 bg-gray-50">

    <header class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manage Users</h2>
    </header>

    <!-- Search Bar -->
    <div class="flex items-center gap-3 mb-4">
        <div class="relative flex-1 max-w-sm">
            <span class="absolute inset-y-0 flex items-center text-gray-400 pointer-events-none left-3">
                <i class="text-sm fas fa-search"></i>
            </span>
            <input
                type="text"
                id="userSearch"
                placeholder="Search by name or email…"
                class="w-full py-2 pr-4 text-sm bg-white border border-gray-200 rounded-lg shadow-sm pl-9 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
        </div>
        <span id="searchCount" class="text-sm text-gray-400"></span>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white border border-gray-200 shadow-sm rounded-xl">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">S.N.</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Name</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Email</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Date of Birth</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Role</th>
                    <th class="px-4 py-3 text-xs font-semibold text-center text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="usersTableBody">
                <?php
                if (isLoggedIn()) {
                    $userObj = new User($conn);
                    $result = $userObj->getAllUser();
                    
                    
                    // Adjust query/model call to match your setup
                  
                    if ($result['success']) {
                        $userData = $result['data'];
                        foreach($userData as $i => $u){
                            $role = htmlspecialchars($u['role'] ?? 'user');
                            ?>
                            <tr class="transition-colors hover:bg-gray-50 user-row">
                                <form action="updateRole.php" method="post">
                                <td class="px-4 py-4 text-sm text-gray-700"><?=  $i + 1?></td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900 searchable">
                                    <?php echo htmlspecialchars($u['firstName']); ?>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600 searchable">
                                    <?php echo htmlspecialchars($u['email']); ?>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    <?php echo htmlspecialchars($u['dateOfBirth']); ?>
                                </td>
                                <input type="text" name="userID" hidden value="<?php echo $u['userID']; ?>">
                                <td class="px-4 py-4 text-sm">
                                    <select
                                        class="px-2 py-1 text-xs bg-white border border-gray-200 rounded-lg cursor-pointer role-select focus:outline-none focus:ring-2 focus:ring-blue-400"
                                        data-user-id="<?php echo $u['userID']; ?>"
                                        data-original="<?php echo $role; ?>"
                                        name="role"
                                    >
                                        <option value="user"      <?php echo $role === 'user'      ? 'selected' : ''; ?>>User</option>
                                        <option value="admin"     <?php echo $role === 'admin'     ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                </td>
                                <td class="px-4 py-4 text-sm text-center">
                                    <div class="flex justify-center gap-3">
                                        <button
                                            type="submit"
                                            class="hidden px-3 py-1 text-xs text-white transition bg-blue-600 rounded-lg save-role-btn hover:bg-blue-700"
                                            data-user-id="<?php echo $u['userID']; ?>"
                                        >Save</button>
                                        <button
                                            type="button"
                                            onclick="confirmDeleteUser(<?php echo $u['userID']; ?>)"
                                            class="text-red-500 transition hover:text-red-700"
                                            title="Delete user"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                                </form>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='6' class='p-6 text-sm text-center text-gray-400'>No users found.</td></tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between p-4 mt-6 bg-white border border-gray-200 rounded-lg shadow-sm">
        <span class="text-sm text-gray-600" id="pageLabel">Page 1 of 1</span>
        <div class="flex space-x-2">
            <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-100 disabled:opacity-50" id="prevPage">
                <i class="text-xs fas fa-chevron-left"></i>
            </button>
            <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-100" id="nextPage">
                <i class="text-xs fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

</section>

<!-- Toast -->
<div id="toastContainer" style="position:fixed;bottom:1.5rem;left:50%;transform:translateX(-50%);z-index:9999;display:flex;flex-direction:column;align-items:center;gap:.5rem;pointer-events:none;"></div>

<script>
// ── Toast ──────────────────────────────────────────────────────────
function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.textContent = msg;
    t.style.cssText = `
        background:${type === 'success' ? '#1a1a1a' : '#e53e3e'};
        color:#fff;padding:.55rem 1.1rem;border-radius:100px;
        font-size:.82rem;box-shadow:0 8px 30px rgba(0,0,0,.18);
        animation:toastIn .3s cubic-bezier(.34,1.56,.64,1) both;
        pointer-events:auto;
    `;
    document.getElementById('toastContainer').appendChild(t);
    setTimeout(() => t.remove(), 3000);
}

// ── Role change ─────────────────────────────────────────────────────
document.querySelectorAll('.role-select').forEach(select => {
    select.addEventListener('change', function () {
        const userId = this.dataset.userId;
        const saveBtn = document.querySelector(`.save-role-btn[data-user-id="${userId}"]`);
        // Show Save button only if value differs from original
        this.value !== this.dataset.original
            ? saveBtn.classList.remove('hidden')
            : saveBtn.classList.add('hidden');
    });
});



// ── Search ──────────────────────────────────────────────────────────
const searchInput  = document.getElementById('userSearch');
const searchCount  = document.getElementById('searchCount');
const rows         = document.querySelectorAll('.user-row');

searchInput.addEventListener('input', function () {
    const q = this.value.trim().toLowerCase();
    let visible = 0;

    rows.forEach(row => {
        const text = [...row.querySelectorAll('.searchable')]
            .map(el => el.textContent.toLowerCase())
            .join(' ');
        const match = !q || text.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    searchCount.textContent = q ? `${visible} result${visible !== 1 ? 's' : ''}` : '';
});

// ── Delete ──────────────────────────────────────────────────────────
function confirmDeleteUser(id) {
    if (confirm('Are you sure you want to delete this user?')) {
        window.location.href = `delete_user.php?id=${id}`;
    }
}
</script>

<style>
@keyframes toastIn {
    from { opacity:0; transform:translateY(10px) scale(.95); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
</style>
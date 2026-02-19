<?php
include("../conn.php");
include("../model/travel_package.php");
include("../middleware/authMiddleware.php");
include("./header.php");






?>
    <style>
        body {
            margin: 0;
            background: #f9fafb;
        }

        .manage-packages {
            min-height: 100vh;
            padding: 24px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
        }

        .btn-primary {
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            background: #2563eb;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.2s ease;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        /* Card */
        .table-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        /* Table */
        table {
            width: 60vw;
            border-collapse: collapse;
        }

        thead {
            background: #f3f4f6;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: #4b5563;
            text-align: left;
        }

        td {
            padding: 16px;
            font-size: 14px;
            color: #374151;
        }

        tbody tr {
            border-top: 1px solid #f1f5f9;
            transition: background 0.2s ease;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .center {
            text-align: center;
        }

        /* Actions */
        .actions {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .actions a {
            color: #4f46e5;
            text-decoration: none;
        }

        .actions a:hover {
            color: #312e81;
        }

        .actions button {
            background: none;
            border: none;
            cursor: pointer;
            color: #ef4444;
        }

        .actions button:hover {
            color: #b91c1c;
        }

        /* Pagination */
        .pagination {
            margin-top: 24px;
            padding: 16px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pagination span {
            font-size: 14px;
            color: #4b5563;
        }

        .pagination-controls {
            display: flex;
            gap: 8px;
        }

        .pagination-controls a {
            padding: 6px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            color: #374151;
            text-decoration: none;
        }

        .pagination-controls a:hover {
            background: #f3f4f6;
        }

        .pagination-controls a.disabled {
            pointer-events: none;
            opacity: 0.5;
        }
    </style>
</head>

<body>

<section class="manage-packages">
    <header class="page-header">
        <h2>Manage Packages</h2>
        <a href="newPackage.php" class="btn-primary">+ New Package</a>
    </header>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>S.N.</th>
                    <th>Package Name</th>
                    <th>Created At</th>
                    <th>Starting Date</th>
                    <th class="center">Booked Slots</th>
                    <th class="center">Total Slots</th>
                    <th class="center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isLoggedIn()) {
                    $travelObj = new Travel($conn);
                    $travelData = $travelObj->getAllAvailableTravelPackages();

                    $totalRecords = count($travelData);
                    $limit = 10;
                    $totalPages = ceil($totalRecords / $limit);

                    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $currentPage = max(1, min($currentPage, $totalPages));

                    $start = ($currentPage - 1) * $limit;
                    $end   = min($start + $limit, $totalRecords);

                    if ($totalRecords > 0) {
                        for ($i = $start; $i < $end; $i++) {
                            ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($travelData[$i]['name']) ?></td>
                                <td><?= htmlspecialchars($travelData[$i]['created_at']) ?></td>
                                <td><?= htmlspecialchars($travelData[$i]['starting_date']) ?></td>
                                <td class="center"><?= htmlspecialchars($travelData[$i]['booked_slots']) ?></td>
                                <td class="center"><?= htmlspecialchars($travelData[$i]['totalSlots']) ?></td>
                                <td class="center">
                                    <div class="actions">
                                        <a href="editPackage.php?id=<?= $travelData[$i]['package_id'] ?>"><i class="fas fa-edit"></i></a>
                                        <button onclick="confirmDelete('<?= $travelData[$i]['package_id'] ?>')"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' class='center'>No packages found.</td></tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalRecords > $limit): ?>
        <div class="pagination">
            <span>Page <?= $currentPage ?> of <?= $totalPages ?></span>

            <div class="pagination-controls">
                <a href="?page=<?= $currentPage - 1 ?>"
                   class="<?= $currentPage == 1 ? 'disabled' : '' ?>"><i class="text-xs fas fa-chevron-left"></i></a>

                <a href="?page=<?= $currentPage + 1 ?>"
                   class="<?= $currentPage == $totalPages ? 'disabled' : '' ?>"><i class="text-xs fas fa-chevron-right"></i></a>
            </div>
        </div>
    <?php endif; ?>

</section>

<script>

function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this package?")) {
        window.location.href = "utility/deletePackage.php?id=" + id;
    }
}
</script>
<?php
require_once   "../components/Notificaton.php";
$status = isset($_GET['status']) ? $_GET['status'] :"";
if(!empty($status)){
    if($status == 'success'){
    showToast("Travel Package deleted successfully",'success');
    }else if($status == 'fail'){
    showToast("Unable to delete travel package",'error');
    }

}
?>

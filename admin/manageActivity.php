<?php
include("../conn.php");
include("../model/Activity.php");
include("../middleware/authMiddleware.php");
include("./header.php");
?>

<head>
    <link rel="stylesheet" href="./css/managebookings.css">
</head>
<section class="min-h-screen p-6 bg-gray-50">
    <header class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manage Activities</h2>
        <a href="newactivity.php" class="px-4 py-2 text-sm font-semibold text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
            + New Activity
        </a>
    </header>

    <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">S.N.</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Activity Name</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Created At</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Started Date</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Event Type</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Total Slots</th>
                    <th class="px-4 py-3 text-xs font-semibold text-center text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
         


<tbody class="divide-y divide-gray-100">
    <?php
    if (isLoggedIn()) {
        $activityObj = new Activity($conn);
        $activity = $activityObj->getAllActivity();
        $count = 0;
        if (!empty($activity)) {
            foreach ($activity as $b) {
                $count++;
                // We close PHP here to write clean HTML
                ?>
                <tr class="transition-colors hover:bg-gray-50">
                    <td class="px-4 py-4 text-sm text-gray-700">
                        <?php echo $count; ?>
                    </td>
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">
                        <?php echo htmlspecialchars($b['name']); ?>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-600">
                        <?php echo htmlspecialchars($b['created_at']); ?>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-600">
                        <?php echo htmlspecialchars($b['starting_date']); ?>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-600">
                        <span class="px-2 py-1 text-xs font-medium text-blue-700 rounded-md bg-blue-50">
                            <?php
                           echo $b['event_type'] == 0? "Recurring" : "One Time";
                              ; ?>
                        </span>
                    </td>
                    <td class="px-4 py-4 text-sm text-center text-gray-600">
                        <?php echo htmlspecialchars($b['no_of_slots']); ?>
                    </td>
                    <td class="px-4 py-4 text-sm text-center">
                        <div class="flex justify-center space-x-3">
                            <a href="editactivity.php?id=<?php echo $b['activity_id']; ?>" class="text-indigo-600 transition hover:text-indigo-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" 
                                    onclick="confirmDelete(<?php echo $b['activity_id']; ?>)" 
                                    class="text-red-500 transition hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php 
                // We reopen PHP just to close the foreach loop
            } 
        } else {
            echo "<tr><td colspan='7' class='p-4 text-center'>No activities found.</td></tr>";
        }
    }
    ?>
</tbody>
        </table>
    </div>

    <div class="flex items-center justify-between p-4 mt-6 bg-white border border-gray-200 rounded-lg shadow-sm">
        <span class="text-sm text-gray-600">Page 1 of 1</span>
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

<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this activity?')) {
        // Redirect to your delete handler script
        window.location.href = `delete_activity.php?id=${id}`;
    }
}
</script>

<script src="./js/managebookings.js"></script>
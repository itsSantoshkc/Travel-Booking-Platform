<?php
include("../conn.php");
include("../model/Activity.php"); 
include("../middleware/authMiddleware.php");
include("./header.php");

$id = isset($_GET['id']) ? $_GET['id'] : null;
$activityObj = new Activity($conn);
$activity = $activityObj->getActivityById($id); 

if (!$activity) {
    die("Activity not found.");
}

$existing_slots = !empty($activity['time_slots']) ?  $activity['time_slots'] : '';
$existing_days = !empty($activity['activity_days']) ? explode('|', $activity['activity_days']) : [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $desc = $_POST['description'];
    $is_one_time = isset($_POST['is_one_time']) ? 1 : 0;
    $s_date = $_POST['starting_date'];
    $price = $_POST['price'];
    $loc = $_POST['location'];
    $slots_count = $_POST['no_of_slots'];
    
  
    $post_days = isset($_POST['days']) ? $_POST['days'] : [];
    $post_times = isset($_POST['time_slots']) ? $_POST['time_slots'] : [];

    if ($activityObj->updateFullActivity($id, $name, $desc, $is_one_time, $s_date, $price, $loc, $slots_count, $post_days, $post_times)) {
        header("Location: manageActivity.php?status=success");
        exit();
    }
}
?>



<main class="w-full max-w-2xl overflow-hidden bg-white border border-gray-100 shadow-xl rounded-3xl">
    <div class="px-8 py-6 border-b border-gray-100 bg-slate-50">
        <h2 class="flex items-center gap-3 text-2xl font-extrabold text-slate-800">
            <div class="p-2 text-sm text-white bg-red-500 rounded-lg">
                <i class="fas fa-pen-to-square"></i>
            </div>
            Edit Activity
        </h2>
        <p class="mt-1 text-sm text-slate-500">Update activity details, schedule, and pricing.</p>
    </div>

    <form action="" method="POST" class="p-8 space-y-8">
        
        <div class="space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-1 h-4 bg-red-500 rounded-full"></span>
                <h3 class="text-sm font-bold tracking-wider uppercase text-slate-400">Basic Information</h3>
            </div>
            
            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2">
                    <label class="block mb-1 ml-1 text-sm font-semibold text-slate-700">Activity Name</label>
                    <div class="relative">
                        <i class="absolute text-xs -translate-y-1/2 fas fa-tag left-4 top-1/2 text-slate-400"></i>
                        <input type="text" name="name" value="<?= htmlspecialchars($activity['name']) ?>" 
                               class="w-full py-3 pl-10 pr-4 transition-all border outline-none bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500" placeholder="e.g. White Water Rafting">
                    </div>
                </div>

                <div class="col-span-2">
                    <label class="block mb-1 ml-1 text-sm font-semibold text-slate-700">Description</label>
                    <textarea name="description" rows="3" 
                              class="w-full px-4 py-3 transition-all border outline-none resize-none bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500" 
                              placeholder="Describe the adventure..."><?= htmlspecialchars($activity['description']) ?></textarea>
                </div>

                <div>
                    <label class="block mb-1 ml-1 text-sm font-semibold text-slate-700">Price (Nrs.)</label>
                    <div class="relative">
                        <span class="absolute text-sm font-bold -translate-y-1/2 left-4 top-1/2 text-slate-400">Nrs.  </span>
                        <input type="number" step="0.01" name="price" value="<?= $activity['price'] ?>" 
                               class="w-full py-3 pr-4 transition-all border outline-none pl-14 bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                    </div>
                </div>

                <div>
                    <label class="block mb-1 ml-1 text-sm font-semibold text-slate-700">Available Slots</label>
                    <div class="relative">
                        <i class="absolute text-xs -translate-y-1/2 fas fa-users left-4 top-1/2 text-slate-400"></i>
                        <input type="number" name="no_of_slots" value="<?= $activity['no_of_slots'] ?>" 
                               class="w-full py-3 pl-10 pr-4 transition-all border outline-none bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-1 h-4 bg-red-500 rounded-full"></span>
                <h3 class="text-sm font-bold tracking-wider uppercase text-slate-400">Schedule & Frequency</h3>
            </div>

            <div class="overflow-hidden border border-slate-100 rounded-2xl">
                <details class="group schedule-dropdown" open>
                    <summary class="flex items-center justify-between p-4 transition-colors cursor-pointer bg-slate-50 hover:bg-slate-100">
                        <span class="flex items-center gap-2 text-sm font-bold text-slate-700">
                            <i class="text-red-500 fas fa-calendar-alt"></i> Configure Availability
                        </span>
                        <i class="text-xs transition-transform fas fa-chevron-down group-open:rotate-180"></i>
                    </summary>
                    
                    <div class="p-6 space-y-6 bg-white">
                        <label class="flex items-center justify-between p-3 transition-all cursor-pointer bg-slate-50 rounded-xl hover:bg-slate-100">
                            <span class="text-sm font-semibold text-slate-700">One Time Event</span>
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="eventType" name="is_one_time" class="sr-only peer" <?= $activity['event_type'] == 1 ? 'checked' : '' ?>>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:width-5 after:transition-all peer-checked:bg-red-500"></div>
                            </div>
                        </label>

                        <div id="dateWrapper">
                            <label class="block mb-2 ml-1 text-xs font-bold uppercase text-slate-500" id="dateLabel">
                                <?= $activity['event_type'] == 1 ? 'Event Date' : 'Starting From Date' ?>
                            </label>
                            <input type="date" name="starting_date" class="w-full px-4 py-3 transition-all bg-white border outline-none border-slate-200 rounded-xl focus:border-red-500" id="mainDate" value="<?= $activity['starting_date'] ?>" />
                        </div>

                        <div id="recurringControls" class="<?= $activity['event_type'] == 1 ? 'hidden' : '' ?> animate-in fade-in duration-300">
                            <label class="block mb-3 ml-1 text-xs font-bold uppercase text-slate-500">Recurring Days</label>
                            <div class="grid grid-cols-7 gap-2">
                                <?php 
                                $days_labels = [1=>'S', 2=>'M', 3=>'T', 4=>'W', 5=>'T', 6=>'F', 7=>'S'];
                                $full_names = [1=>'SUN', 2=>'MON', 3=>'TUE', 4=>'WED', 5=>'THU', 6=>'FRI', 7=>'SAT'];
                                foreach($days_labels as $val => $label): 
                                ?>
                                <label class="relative group">
                                    <input type="checkbox" name="days[]" value="<?= $val ?>" class="sr-only peer" <?= in_array($val, $existing_days) ? 'checked' : '' ?> />
                                    <div class="flex items-center justify-center h-10 text-xs font-bold transition-all border-2 rounded-lg cursor-pointer border-slate-100 text-slate-400 peer-checked:border-red-500 peer-checked:bg-red-500 peer-checked:text-white hover:border-red-200">
                                        <?= $label ?>
                                    </div>
                                    
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-3 ml-1 text-xs font-bold uppercase text-slate-500">Time Slots</label>
                            <div id="timeBox" class="flex flex-wrap gap-2 min-h-[50px] p-3 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 mb-4">
                                <?php if(empty($existing_slots)): ?>
                                    <span id="placeholderText" class="m-auto text-xs italic text-slate-400">No time slots configured</span>
                                <?php else: ?>
                                    <?php foreach($existing_slots as $ts): ?>
                                        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border border-slate-200 shadow-sm text-sm font-medium text-slate-700 animate-in zoom-in duration-200">
                                            <i class="text-xs text-red-500 far fa-clock"></i>
                                            <?= htmlspecialchars($ts) ?>
                                            <input type="hidden" name="time_slots[]" value="<?= htmlspecialchars($ts) ?>">
                                            <button type="button" class="ml-1 transition-colors text-slate-300 hover:text-red-500" onclick="this.parentElement.remove()">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-end gap-3 p-4 bg-white border shadow-inner border-slate-200 rounded-xl">
                                <div class="flex-1 space-y-1">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase ml-1">Start Time</span>
                                    <input type="time" id="fromTime" class="w-full px-2 py-2 text-sm rounded-lg outline-none bg-slate-50 focus:ring-1 focus:ring-red-500" />
                                </div>
                                <div class="flex-1 space-y-1">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase ml-1">End Time</span>
                                    <input type="time" id="toTime" class="w-full px-2 py-2 text-sm rounded-lg outline-none bg-slate-50 focus:ring-1 focus:ring-red-500" />
                                </div>
                                <button type="button" id="addTimeBtn" class="bg-slate-800 text-white px-4 py-2.5 rounded-lg font-bold text-sm hover:bg-slate-700 active:scale-95 disabled:opacity-30 transition-all" disabled>
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </details>
            </div>
        </div>

        <div class="flex items-center gap-4 pt-6 border-t border-slate-100">
            <button type="submit" class="flex-[2] py-4 bg-red-500 text-white font-bold rounded-2xl hover:bg-red-600 hover:shadow-lg hover:shadow-red-200 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <i class="fas fa-check-circle"></i> Update Activity
            </button>
            <a href="manageActivity.php" class="flex-1 py-4 font-bold text-center transition-all bg-slate-100 text-slate-500 rounded-2xl hover:bg-slate-200">
                Cancel
            </a>
        </div>
    </form>
</main>

<script>
    const eventType = document.getElementById("eventType");
    const dateLabel = document.getElementById("dateLabel");
    const recurringControls = document.getElementById("recurringControls");
    const timeBox = document.getElementById("timeBox");
    const fromTime = document.getElementById("fromTime");
    const toTime = document.getElementById("toTime");
    const addTimeBtn = document.getElementById("addTimeBtn");
    const placeholderText = document.getElementById("placeholderText");

    eventType.addEventListener("change", () => {
        if (eventType.checked) {
            dateLabel.innerText = "Event Date";
            recurringControls.classList.add("hidden");
        } else {
            dateLabel.innerText = "Starting From Date";
            recurringControls.classList.remove("hidden");
        }
    });

    const validateTime = () => {
        addTimeBtn.disabled = !(fromTime.value && toTime.value);
    };

    fromTime.addEventListener("change", validateTime);
    toTime.addEventListener("change", validateTime);

    addTimeBtn.addEventListener("click", () => {
        const slotText = `${fromTime.value} - ${toTime.value}`;
        
        if (placeholderText && !placeholderText.classList.contains('hidden')) {
            placeholderText.classList.add("hidden");
        }

        if (eventType.checked) {
            timeBox.innerHTML = ""; 
        }

        const pill = document.createElement("div");
        pill.className = "flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border border-slate-200 shadow-sm text-sm font-medium text-slate-700 animate-in zoom-in duration-200";
        
        pill.innerHTML = `
            <i class="text-xs text-red-500 far fa-clock"></i>
            ${slotText}
            <input type="hidden" name="time_slots[]" value="${slotText}">
            <button type="button" class="ml-1 transition-colors text-slate-300 hover:text-red-500" onclick="this.parentElement.remove()">
                <i class="fas fa-times-circle"></i>
            </button>
        `;

        timeBox.appendChild(pill);

        // Reset inputs
        fromTime.value = "";
        toTime.value = "";
        validateTime();
    });
</script>

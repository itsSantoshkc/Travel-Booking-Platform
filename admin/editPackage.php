<?php
include("../conn.php");
include("../model/travel_package.php"); 
include("../middleware/authMiddleware.php");
include("./header.php");

$id = isset($_GET['id']) ? $_GET['id'] : null;
$activityObj = new Travel($conn);
$activity = $activityObj->getTravelPackageById($id); 

if (!$activity) {
    die("Activity not found.");
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $desc = $_POST['description'];
    $duration = $_POST['duration'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $slots_count = $_POST['totalSlots'];
    $starting_date = $_POST['starting_date'];
    $arrivalTime = $_POST['arrivalTime'];
    

    if ($activityObj->updateFullActivity($id, $name, $desc, $duration,$location,$price,$slots_count,$starting_date,$arrivalTime)) {
        header("Location: managePackage.php?status=success");
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
            Edit Travel Package
        </h2>
        <p class="mt-1 text-sm text-slate-500">Update travel package details.</p>
    </div>

    <form action="" method="POST" class="p-8 space-y-8">
        
        <div class="space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-1 h-4 bg-red-500 rounded-full"></span>
                <h3 class="text-sm font-bold tracking-wider uppercase text-slate-400">Basic Information</h3>
            </div>
            
            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2">
                    <label class="block mb-1 ml-1 text-sm font-semibold text-slate-700">Package Name</label>
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
                    <label class="block mb-1 ml-1 text-sm font-semibold text-slate-700">Duration</label>
                    <div class="relative">
                        <i class="absolute text-xs -translate-y-1/2 fa-regular fa-clock left-4 top-1/2 text-slate-400"></i>
                        <input type="number" step="0.01" name="duration" value="<?= $activity['duration'] ?>" 
                               class="w-full py-3 pr-4 transition-all border outline-none pl-14 bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                    </div>
                </div>

                <div>
                    <label class="block mb-1 ml-1 text-sm font-semibold text-slate-700">Location</label>
                    <div class="relative">
                        <i class="absolute text-xs -translate-y-1/2 fas fa-location-dot left-4 top-1/2 text-slate-400"></i>
                        <input type="text" name="location" value="<?= $activity['location'] ?>" 
                               class="w-full py-3 pl-10 pr-4 transition-all border outline-none bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                    </div>
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
                    <label class="block mb-1 ml-1 text-sm font-semibold text-slate-700">Total Slots</label>
                    <div class="relative">
                        <i class="absolute text-xs -translate-y-1/2 fas fa-users left-4 top-1/2 text-slate-400"></i>
                        <input type="number" name="totalSlots" value="<?= $activity['totalSlots'] ?>" 
                               class="w-full py-3 pl-10 pr-4 transition-all border outline-none bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                    </div>
                </div>
                <div>
                    <label class="block mb-1 ml-1 text-sm font-semibold text-slate-700">Arrival Time</label>
                    <div class="relative">
                        <i class="absolute text-xs -translate-y-1/2 fas fa-users left-4 top-1/2 text-slate-400"></i>
                        <input type="time" name="arrivalTime" value="<?= $activity['arrivalTime'] ?>" 
                               class="w-full py-3 pl-10 pr-4 transition-all border outline-none bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                    </div>
                </div>
                <div>
                    <label class="block mb-1 ml-1 text-sm font-semibold text-slate-700">Starting Date</label>
                    <div class="relative">
                        <i class="absolute text-xs -translate-y-1/2 fas fa-users left-4 top-1/2 text-slate-400"></i>
                        <input type="date" name="starting_date" value="<?= $activity['starting_date'] ?>" 
                               class="w-full py-3 pl-10 pr-4 transition-all border outline-none bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                    </div>
                </div>
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


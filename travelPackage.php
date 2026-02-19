<?php
include("header.php");
require_once("conn.php");
include("model/travel_package.php");
include("model/Review.php");
include("model/Booking.php");
include("./components/Navbar.php");
?>

<head>
    <link rel="stylesheet" href="css/details.css">
    <script defer src="./js/details.js"></script>
</head>


<main class='activity-container'>
    <div class='image-gallery'>

        <div class='image-grid-container' id='imageGridContainer'>
            <?php
            try {
                $id =  $_GET['package'];
                $travelObj = new Travel($conn);
                $packageData = $travelObj->getTravelPackageById($id);
                $availableSlots = $packageData['totalSlots'] -  $packageData['booked_slots'];

                
                // $mainImage = str_replace('../', '', BASE_URL. $packageData['images'][0]);
                $mainImage = str_replace('../', '',  $packageData['images'][0]);

                for ($i = 0; $i < count($packageData['images']); $i++) {
                    $image = str_replace('../', '',  $packageData['images'][$i]);
                    $class = ($i === 0) ? 'main-grid-image' : 'sub-grid-image';
                    echo "<img class='{$class}' src='{$image}' alt='Activity Image' />";
                }
                $avgRating = !empty($packageData['avg_rating']) ? $packageData['avg_rating'] : 0;
                echo "</div></div></div>";
                echo "<div class='details'>
                        <h1 id='activityTitle'>{$packageData['name']}</h1>
                        <p class='location' id='activityLocation'>
                        <span class='location-text'>{$packageData['location']}</span>
                        <!-- <span class='view-location'>View Full Location</span> -->
                        </p>
                        <p class='rating' id='activityRating'>
                            <i class='fas fa-star' style='color: #FFD700; font-size: 13px;'></i> {$avgRating} out of 5
                        </p>
                        <p class='description' id='activityDescription'>{$packageData['description']}</p>
                        <div class='tags' id='activityTags'>
                        <span><b>Nrs : </b>{$packageData['price']}</span>
                        <span><b>{$availableSlots}</b> Slots Remaining</span>
                        <span><b>Date : </b> {$packageData['starting_date']}</span>
                    </div>";
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            if($availableSlots <= 0){
                    echo "
                <div disabled class='check-availability-container'>
                <button class='check-btn-disabled' disabled >
                        Unavailable 
                </button>
                </div>
                ";
            }else{
                echo "
                <div class='check-availability-container'>
                <button class='check-btn' id='openSlots'>
                Book Now!
                </button>
                </div>
                ";
                }
                    ?>
        </div>
    </main>
   


<?php
try {
    $reviewObj = new Review($conn);
    $reviewDatas = $reviewObj->getReviewByPackageID($id);
    
    echo '<div class="reviews">';
    include("review.php");
 
    echo '</div>';
} catch (Exception $e) {
    echo $e->getMessage();
}

?>
<div id="slotOverlay" class="overlay">
    <div class="popup">
        <div class="close" id="closeOverlay" aria-label="Close"></div>
        <h2 class="popup-title">Booking</h2>
        <div id="slotsContainer">
            <div class="max-w-lg p-4 mx-auto bg-white border border-gray-100 shadow-lg booking-container rounded-2xl">
                <form action="processBooking.php" method="POST" id="bookingForm" class="space-y-6">
                    
                        <input type="hidden" name="package_id" value="<?php echo $id; ?>"
                    <?php if($availableSlots > 1){
                        echo '
                          <div id="bookingDetails" class="pt-4 space-y-4 border-t ">
                        <div class="grid gap-4">
                            <label class="block mb-2 text-sm font-semibold text-gray-600">No of Slots</label>
                            <select name="no-of-slots" required class="w-full p-3 border border-gray-300 outline-none no-of-slots bg-gray-50 rounded-xl">
                                <option value="" selected disabled>Select Slots</option>';
                                for ($i = 1; $i <= $availableSlots; $i++) {
                                    echo "<option value='$i' class='py-2 text-gray-700 bg-white'>$i</option>";
                                }
                                echo '</select>';   } 
                                ?>
                  

                            <button type="submit" name="submit_booking"  class="w-full py-4 font-bold text-white transition-all bg-red-500 shadow-lg rounded-xl hover:bg-red-600 active:scale-95">
                                Confirm Booking
                            </button>
                        </div>
                </form>
            </div>

        </div>
    </div>
</div>
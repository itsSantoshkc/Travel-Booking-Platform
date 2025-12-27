<?php
include("header.php");
include("conn.php");
include("model/Activity.php");
include("model/Review.php");
include("model/Booking.php");
include("./components/Navbar.php");
?>

<head>
    <link rel="stylesheet" href="css/details.css">
</head>

<main class='activity-container'>
    <div class='image-gallery'>
    
    <div class='image-grid-container' id='imageGridContainer'>
<?php
try {
    $id =  $_GET['activity'];
    $activityObj = new Activity($conn);
    $activityData = $activityObj->getActivityById($id);
    $mainImage = str_replace('../', '', $baseUrl . $activityData['images'][0]);
    
    for($i = 0;$i< count($activityData['images']);$i++){
        $image = str_replace('../', '', $baseUrl . $activityData['images'][$i]);
        $class = ($i === 0) ? 'main-grid-image' : 'sub-grid-image';
        echo "<img class='{$class}' src='{$image}' alt='Activity Image' />";
    }
    
echo"</div>
</div>
</div>";


    echo "<div class='details'>
        <h1 id='activityTitle'>{$activityData['name']}</h1>
        <p class='location' id='activityLocation'>
          <span class='location-text'>{$activityData['location']}</span>
          <!-- <span class='view-location'>View Full Location</span> -->
        </p>
        <p class='rating' id='activityRating'>
            <i class='fas fa-star' style='color: #FFD700; font-size: 13px;'></i> 5 out of 5
        </p>
        <p class='description' id='activityDescription'>{$activityData['description']}</p>
        <div class='tags' id='activityTags'>
        <span><b>Nrs : </b>{$activityData['price']}</span>
        <span><b>Slots : </b> {$activityData['no_of_slots']}</span>
        <span><b>Date : </b> {$activityData['starting_date']}</span>
        </div>";
      
    // var_dump($activityData);
} catch (Exception $e) {
    echo $e->getMessage();
}
?>

  <div class='check-availability-container'>
          <button class='check-btn' id='openSlots'>
            <span class='check-text'>Check</span>
            <span class='availability-text'>Availability</span>
          </button>
        </div>
      </div>
    </main>

<?php
try{
 $reviewObj = new Review($conn);
    $reviewDatas = $reviewObj->getReviewByActivityId($id);

    echo '<section class="reviews">
    <h2>Ratings & Reviews</h2><div id="reviewsContainer"></div>';
    
    foreach($reviewDatas as $reviewData){
        
        echo"
        <div class='review'>
        <img src ='./public/default-photo.jpg' alt='{$reviewData['firstName']}'>
        
        <div class='review-content'>
        <p class='reviewer-name'>{$reviewData['firstName']}</p>
        <p class='review-rating'>
        <i class='fas fa-star' style='color: #FFD700; font-size: 13px;'></i> {$reviewData['rating']} out of 5
        </p>
        <p class='review-text'>{$reviewData['review']} </p>
        <p class='review-date'>{$reviewData['createdAt']}</p>
        </div>
        </div>";
        }
    echo '</section>';
}catch (Exception $e) {
    echo $e->getMessage();
}

    ?>
<div id="slotOverlay" class="overlay">
    <div class="popup">
        <div class="close" id="closeOverlay" aria-label="Close"></div>
        <h2 class="popup-title">Available Slots</h2>
        <div id="slotsContainer">
<?php
$timeSlots = $activityObj->getTimeSlotsAndDays($id);
$allowedDays = array_column($timeSlots['days'], 'name');
$availableTimes = $timeSlots['slots']; 

$resultsFound = 0;
$maxResults = 14; 
$currentDate = new DateTime(); 
$dates = [];
?>

<div class="max-w-lg p-4 mx-auto bg-white border border-gray-100 shadow-lg booking-container rounded-2xl">
    <form action="processBooking.php" method="POST" id="bookingForm" class="space-y-6">
        <input type="hidden" name="activity-id" value="<?php echo htmlspecialchars($id); ?>">

        <div>
            <label class="block mb-2 text-sm font-semibold text-gray-600">Select a Date</label>
            <select name="date" id="datePicker" required class="w-full p-3 transition-all border border-gray-300 outline-none bg-gray-50 rounded-xl focus:ring-2 focus:ring-red-500">
                <option value="" selected disabled>Choose your preferred day...</option>
                <?php 
                $foundCount = 0;
                while ($foundCount < 5 && $resultsFound < $maxResults): 
                    $dayName = $currentDate->format('D'); 
                    if (in_array($dayName, $allowedDays)): 
                        $dateValue = $currentDate->format('Y-m-d');
                        $displayDate = $currentDate->format('l, M d');
                        ?>
                        <option value="<?php echo $dateValue; ?>"><?php echo $displayDate; ?></option>
                        <?php 
                        $foundCount++;
                        array_push($dates,$dateValue);
                    endif;
                    $currentDate->modify('+1 day');
                    $resultsFound++;
                endwhile; 
                ?>
            </select>
        </div>
       

        <div id="bookingDetails" class="hidden pt-4 space-y-4 border-t">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-600">Time Slot</label>
                    <select name="time-slot" required class="w-full p-3 border border-gray-300 outline-none time-slot bg-gray-50 rounded-xl">
                        <option value="" selected disabled>Time</option>
                        <?php foreach ($availableTimes as $time): 
                            ?>
                            <option value="<?php echo htmlspecialchars($time); ?>"><?php echo htmlspecialchars($time); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
 <?php

        $bookingObj = new Booking($conn);
        $bookingData = $bookingObj->getBookingMap($id,$dates,$availableTimes);
        echo "<script>
    // Store the map in a global JS variable for easy access
    const BOOKING_MAP = " . json_encode($bookingData, JSON_HEX_TAG | JSON_HEX_AMP) . ";
    const totalSlots = {$activityData['no_of_slots']};
</script>";
?>

                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-600">Persons</label>
                    <select name="no-of-slots" required class="w-full p-3 border border-gray-300 outline-none no-of-slots bg-gray-50 rounded-xl">
                        

                    </select>
                </div>
            </div>

            <button type="submit" name="submit_booking" class="w-full py-4 font-bold text-white transition-all bg-red-500 shadow-lg rounded-xl hover:bg-red-600 active:scale-95">
                Confirm Booking
            </button>
        </div>
    </form>
</div>

        </div>
    </div>
</div>
<script defer src="./js/details.js"></script>


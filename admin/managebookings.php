
<head>
  <link rel="stylesheet" href="./css/managebookings.css">
</head>
<?php

include("../conn.php");
include("../model/Booking.php");
include("../middleware/authMiddleware.php");
include("./header.php");
?>

  <section class="main">
    <header class="page-header">
      <h2 class="page-title">Manage Bookings</h2>
    </header>

    <section class="bookings-table-container" aria-labelledby="bookingsHeading">
      <div class="table-header-row" role="presentation">
        <div class="header-cell sn">S.N.</div>
        <div class="header-cell activity">Name</div>
        <div class="header-cell time">Location</div>
        <div class="header-cell date">Date</div>
        <div class="header-cell bookedby">Booked By</div>
        <div class="header-cell slots">No of Slots</div>
      </div>

      <div class="table-body-wrapper">
        <div class="table-body-rows">
          <?php
if(isLoggedIn()){
                    $count = 0;
                    $userID = $_SESSION["userID"];
                    $bookingObj = new Booking($conn);
                    $bookings = $bookingObj->getAllBooking();
                       
foreach ($bookings as $b) {
    $count++;
    echo "<div class='body-row'>
            <div class='body-cell sn'>{$count}</div>
            <div class='body-cell activity'>{$b['name']}</div>
            <div class='body-cell time'>{$b['location']}</div>
            <div class='body-cell date'>{$b['starting_date']}</div>
            <div class='body-cell time'>{$b['firstName']}</div>
            <div class='body-cell slots'>{$b['no_of_slots']}</div>
        </div>";
    
}
                }
            ?>

        </div>
      </div>
    </section>

    <div class="table-pagination" id="pagination">
      <button class="pagination-btn arrow-prev" id="prevPage" aria-label="Previous page">
        <i class="fas fa-chevron-left"></i>
      </button>
      <div class="pagination-page-number" id="currentPage">1</div>
      <button class="pagination-btn arrow-next" id="nextPage" aria-label="Next page">
        <i class="fas fa-chevron-right"></i>
      </button>
    </div>
  </section>


  <script src="./js/managebookings.js"></script>

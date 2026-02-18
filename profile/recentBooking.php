<head>
    <link rel="stylesheet" href="./css/recentbook.css">
</head>
<?php
include("../header.php");
require_once("../conn.php");
include("../model/Booking.php");
include("../components/Navbar.php");
?>

  <!-- MAIN CONTENT -->
  <div class="main-content">

    <!-- Recent Booking Header -->
    <header class="page-header">
      <h2 class="page-title">Recent Booking</h2>
    </header>

    <!-- Activity Table Container -->
    <section class="bookings-table-container" aria-labelledby="recentBookingsHeading">
      <!-- Header bar (rounded) -->
      <div class="table-header-row" role="presentation">
        <div class="header-cell sn">S.N.</div>
        <div class="header-cell activity">Name</div>
        <div class="header-cell date">Booked On</div>
        <div class="header-cell time">Travel Date</div>
        <div class="header-cell slots">No of Slots</div>
      </div>
 <?php
                if(isLoggedIn()){
                    $count = 0;
                    $userID = $_SESSION["userID"];
                    $bookingObj = new Booking($conn);
                    $bookings = $bookingObj->getBookingsByUserId($userID);
                       
foreach ($bookings as $b) {
    $count++;
    echo "<div class='body-row'>
            <div class='body-cell sn'>{$count}</div>
            <div class='body-cell activity'>{$b['package_name']}</div>
            <div class='body-cell time'>{$b['booked_at']}</div>
            <div class='body-cell date'>{$b['starting_date']}</div>
            <div class='body-cell slots'>{$b['no_of_slots']}</div>
        </div>";
    
}
                }
            ?>
      <!-- Table body using same grid structure -->
      <div class="table-body-wrapper">
        <div class="table-body-rows" id="table-body">
           
          <!-- JS will populate rows here -->
        </div>
      </div>
    </section>

    <!-- Pagination - Only shows when more than 10 items -->
    <div class="table-pagination" id="pagination" >
        <?php 
        $isLeftDisabed = $count > 0  ? ' ':'disabled';
        $isNextDisabed = $count < 11 ? 'disabled':'';
        echo "
      <button class='pagination-btn arrow-prev' disabled id='prevPage' aria-label='Previous page'>
        <i class='fas fa-chevron-left'></i>
      </button>
      <div class='pagination-page-number' id='currentPage'>1</div>
      <button class='pagination-btn arrow-next' {$isNextDisabed} id='nextPage' aria-label='Next page'>
        <i class='fas fa-chevron-right'></i>
      </button>";
      ?>
    </div>

  </div>


  <!-- <script src="./js/recentbook.js"></script> -->
</body>

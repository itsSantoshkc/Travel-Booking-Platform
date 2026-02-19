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







<style>
  .main-content {
    margin-top: 48px;
    /* Reduced from 60px to 48px - less space below navbar */
    display: flex;
    flex-direction: column;
    align-items: center;

    justify-content: center;
    padding: 0.5rem 2rem;
    /* Reduced from 1rem to 0.5rem */
    width: 100%;
    box-sizing: border-box;
  }

  .manage-booking-body {
    min-height: 100vh;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 60px 20px 80px;
    color: #1e2a4a;
  }




  h1 {
    font-size: 2.4rem;
    font-weight: 900;
    color: #1e2a4a;
    margin-bottom: 40px;
    letter-spacing: -0.5px;
  }

  .table-wrapper {
    width: 100%;
    max-width: 1000px;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(30, 42, 74, 0.10);
    border: 1px solid #dde6f5;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  thead tr {
    background: #1e2a4a;
  }

  thead th {
    color: #fff;
    font-size: 0.92rem;
    font-weight: 800;
    padding: 18px 16px;
    text-align: center;
    letter-spacing: 0.3px;
  }

  tbody tr {
    border-bottom: 1px solid #d6e2f0;
    transition: background 0.15s;
  }

  tbody tr:last-child {
    border-bottom: none;
  }



  tbody td {
    padding: 18px 16px;
    text-align: center;
    font-size: 0.95rem;
    font-weight: 600;
    color: #2c3e6b;
  }

  /* Pagination */
  .pagination {
    max-width: 1000px;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 28px;
    width: 100%;
    justify-content: flex-end;
  }

  .pagination button {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 800;
    font-size: 1rem;
    transition: all 0.15s;
  }

  .pagination .page-num {
    background: #1e2a4a;
    color: #fff;
  }

  .pagination .page-nav {
    background: #1e2a4a;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .pagination .page-nav:hover,
  .pagination .page-num:hover {
    background: #2e3f6e;
  }

  .pagination .page-nav svg {
    width: 14px;
    height: 14px;
    stroke: #fff;
    stroke-width: 2.5;
    fill: none;
  }
</style>

<main class="manage-booking-body">

  <h1>Recent Bookings</h1>

  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>S.N.</th>
          <th>Name</th>
          <th>No of Slots</th>
          <th>Travel Date</th>
          <th>Booked On</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (isLoggedIn()) {
          $count = 0;
          $userID = $_SESSION["userID"];
          $bookingObj = new Booking($conn);
          $bookings = $bookingObj->getBookingsByUserId($userID);
          foreach ($bookings as $b) {
            $count++;
            $date = date("Y-m-d", strtotime($b['booked_at']));
            echo " <tr>
             <td>{$count}</td>
        <td>{$b['package_name']}</td>
        <td>{$b['no_of_slots']}</td>
        <td>{$b['starting_date']}</td>
        <td>{$date}</td>
          </tr>";
          }
        }
        ?>
        <?php
        if (isLoggedIn()) {
          $count = 0;
          $userID = $_SESSION["userID"];
          $bookingObj = new Booking($conn);
          $bookings = $bookingObj->getAllBooking();
        }
        ?>


      </tbody>
    </table>

  </div>
  <?php
  if (count($bookings) > 10) {
    echo '<div class="pagination">
      <button class="page-nav">
        <svg viewBox="0 0 24 24">
          <polyline points="15 18 9 12 15 6" />
        </svg>
      </button>
      <button class="page-num">1</button>
      <button class="page-nav">
        <svg viewBox="0 0 24 24">
          <polyline points="9 18 15 12 9 6" />
        </svg>
      </button>
    </div>';
  }
  ?>
</main>
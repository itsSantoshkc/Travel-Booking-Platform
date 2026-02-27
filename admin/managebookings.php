<?php
include("../conn.php");
include("../model/Booking.php");
include("../middleware/authMiddleware.php");
include("./header.php");

// Handle Status Updates (Accept/Reject logic)
if (isset($_GET['action']) && isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $action = $_GET['action'];
  $newStatus = ($action === 'accept') ? 'accepted' : 'rejected';

  // Basic SQL update - ideally, this should be a method inside your Booking class
  $updateQuery = "UPDATE bookings SET status = '$newStatus' WHERE id = $id";
  if (mysqli_query($conn, $updateQuery)) {
    echo "<script>alert('Booking $newStatus successfully!'); window.location.href='managebookings.php';</script>";
  }
}
?>

<head>

  <link rel="stylesheet" href="./css/managebookings.css">

</head>
<style>
  h1 {

    font-size: 2.4rem;

    font-weight: 900;

    color: #1e2a4a;

    margin-bottom: 40px;

    letter-spacing: -0.5px;

  }

  .manage-booking-body {
    min-height: 100vh;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 60px 20px 80px;
    color: #1e2a4a;
    font-family: sans-serif;
  }

  .table-wrapper {
    width: 100%;
    max-width: 1100px;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(30, 42, 74, 0.10);
    border: 1px solid #dde6f5;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    background: white;
  }

  thead tr {
    background: #1e2a4a;
  }

  thead th {
    color: #fff;
    font-size: 0.92rem;
    padding: 18px 10px;
    text-align: center;
  }

  tbody td {
    padding: 16px 10px;
    text-align: center;
    border-bottom: 1px solid #eee;
    font-weight: 600;
    color: #2c3e6b;
  }

  /* Status Badges */
  .badge {
    padding: 6px 12px;
    border-radius: 50px;
    font-size: 0.75rem;
    text-transform: uppercase;
    font-weight: 700;
  }

  .status-pending {
    background: #fff3cd;
    color: #856404;
  }

  .status-approved {
    background: #d4edda;
    color: #155724;
  }

  .status-rejected {
    background: #f8d7da;
    color: #721c24;
  }

  /* Action Buttons */
  .btn {
    padding: 6px 12px;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.8rem;
    margin: 0 2px;
    display: inline-block;
    transition: 0.2s;
  }

  .btn-accept {
    background: #28a745;
    color: #fff;
  }

  .btn-reject {
    background: #dc3545;
    color: #fff;
  }

  .btn:hover {
    opacity: 0.8;
  }

  .pagination {
    display: flex;
    margin-top: 25px;
    justify-content: flex-end;
    width: 100%;
    max-width: 1100px;
  }
</style>

<main class="manage-booking-body">
  <h1>Manage Bookings</h1>

  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>S.N.</th>
          <th>Name</th>
          <th>Location</th>
          <th>Date</th>
          <th>Booked By</th>
          <th>Slots</th>
          <th>Status / Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (isLoggedIn()) {
          $count = 0;
          $bookingObj = new Booking($conn);
          $bookings = $bookingObj->getAllBooking();

          foreach ($bookings as $b) {
            $count++;
            // Use 'pending' as default if status is empty
            $status = !empty($b['status']) ? strtolower($b['status']) : 'pending';

            echo "<tr>";
            echo "<td>{$count}</td>";
            echo "<td>" . htmlspecialchars($b['name']) . "</td>";
            echo "<td>" . htmlspecialchars($b['location']) . "</td>";
            echo "<td>{$b['starting_date']}</td>";
            echo "<td>" . htmlspecialchars($b['firstName']) . "</td>";
            echo "<td>{$b['no_of_slots']}</td>";
            echo "<td>";

            if ($status === 'pending') {
              echo "<a href='?id={$b['booking_id']}&action=accept' class='btn btn-accept' onclick='return confirm(\"Accept this booking?\")'><i class='fa-solid fa-check'></i></a>";
              echo "<a href='?id={$b['booking_id']}&action=reject' class='btn btn-reject' onclick='return confirm(\"Reject this booking?\")'><i class='fa-solid fa-x'></i></a>";
            } else {
              echo "<span class='badge status-{$status}'>" . ucfirst($status) . "</span>";
            }

            echo "</td>";
            echo "</tr>";
          }
        }
        ?>
      </tbody>
    </table>
  </div>

  <div class="pagination">
    <button style="background:#1e2a4a; color:white; border:none; padding:10px; border-radius:5px; cursor:pointer;">1</button>
  </div>
</main>
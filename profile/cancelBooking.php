<?php
require_once("../conn.php");
include("../model/Booking.php");
require_once("../components/Navbar.php");
require_once("../header.php");


// 1. Fetch Booking Details
$bookingData = null;
if (isset($_GET['id'])) {
    $bookingID = intval($_GET['id']);
    // Assuming your Booking model has a method to get a single booking by ID
    // If not, a quick query:
    $query = "SELECT * FROM booking as b INNER JOIN travelpackages as t ON t.package_id = b.package_id WHERE b.booking_id = $bookingID";
    $result = mysqli_query($conn, $query);
    $bookingData = mysqli_fetch_assoc($result);
}

// 2. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_cancel'])) {
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $id = intval($_POST['booking_id']);

    // Update status and store reason (Ensure your DB has a 'cancel_reason' column)
    $updateSql = "UPDATE bookings SET status = 'rejected', cancel_reason = '$reason' WHERE booking_id = $id";
    
    if (mysqli_query($conn, $updateSql)) {
        echo "<script>alert('Booking cancelled successfully.'); window.location.href='managebookings.php';</script>";
    }
}
?>

<style>
    .cancel-container {
        min-height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .cancel-card {
        background: white;
        width: 100%;
        max-width: 500px;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(30, 42, 74, 0.1);
        border: 1px solid #dde6f5;
    }

    .booking-header {
        border-bottom: 2px solid #f0f4f8;
        margin-bottom: 25px;
        padding-bottom: 15px;
    }

    .booking-header h2 {
        color: #1e2a4a;
        font-size: 1.5rem;
        margin-bottom: 10px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        color: #5c6784;
        font-size: 0.9rem;
    }

    .info-item span {
        display: block;
        font-weight: 800;
        color: #1e2a4a;
    }

    .form-group {
        margin-top: 25px;
    }

    label {
        display: block;
        font-weight: 700;
        margin-bottom: 8px;
        color: #1e2a4a;
    }

    textarea {
        width: 100%;
        padding: 15px;
        border: 2px solid #dde6f5;
        border-radius: 8px;
        resize: vertical;
        font-family: inherit;
        min-height: 120px;
        transition: border-color 0.3s;
    }

    textarea:focus {
        outline: none;
        border-color: #1e2a4a;
    }

    .btn-group {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .btn-cancel-submit {
        flex: 2;
        background: #dc3545;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-back {
        flex: 1;
        background: #f8f9fa;
        color: #1e2a4a;
        text-decoration: none;
        text-align: center;
        padding: 12px;
        border-radius: 8px;
        font-weight: 700;
        border: 1px solid #dde6f5;
    }

    .btn-cancel-submit:hover { background: #c82333; }
</style>

<main class="cancel-container">
    <div class="cancel-card">
        <?php if ($bookingData): ?>
            <div class="booking-header">
                <p style="color: #dc3545; font-weight: 800; text-transform: uppercase; font-size: 0.8rem;">Cancel Request</p>
                <h2><?php echo htmlspecialchars($bookingData['name']); ?></h2>
                
                <div class="info-grid">
                    <div class="info-item">
                        Date <span><?php echo $bookingData['starting_date']; ?></span>
                    </div>
                    <div class="info-item">
                        Slots Booked <span><?php echo $bookingData['no_of_slots']; ?> Slots</span>
                    </div>
                </div>
            </div>

            <form action="" method="POST">
                <input type="hidden" name="booking_id" value="<?php echo $bookingData['booking_id']; ?>">
                
                <div class="form-group">
                    <label for="reason">Reason for Rejection/Cancellation</label>
                    <textarea name="reason" id="reason" placeholder="Please explain why the booking is being rejected..." required></textarea>
                </div>

                <div class="btn-group">
                    <a href="managebookings.php" class="btn-back">Go Back</a>
                    <button type="submit" name="confirm_cancel" class="btn-cancel-submit">Confirm Rejection</button>
                </div>
            </form>
        <?php else: ?>
            <p>Booking not found. <a href="managebookings.php">Return to list</a></p>
        <?php endif; ?>
    </div>
</main>
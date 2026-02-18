<?php
require_once("../model/User.php");
require_once("../model/stats.php");
require_once("../model/booking.php");
require_once("../model/Review.php");
require_once("../utilities/renderStar.php");
include("header.php");


$userId = $_SESSION['userID'];
// No noeed to access the db just get the data from the session

$userObj = new User($conn);
$statsObj = new Stats($conn);
$bookingObj = new Booking($conn);
$ratingObj = new Review($conn);


$userData = $userObj->getUserWithId($userId)['data'];
$bookingData = $bookingObj->getRecentBookings();
$recent_review = $ratingObj->getAllReview();

$dashboardStats = $statsObj->getDashboardStats();
if ($dashboardStats['success'] == true) {
    $totalBookings = $dashboardStats['data']['total_bookings'];
    $avgRating = $dashboardStats['data']['avg_rating'];
    $totalUsers = $dashboardStats['data']['total_users'];
}
?>



<head>
    <style>
        :root {
            --primary: #1a2332;
            --primary-light: #2a3b52;
            --accent: gold;
            --accent-dark: #b88f5f;
            --bg-main: #f8f6f3;
            --bg-card: #ffffff;
            --text-primary: #1a2332;
            --text-secondary: #6b7280;
            --border: #e5e3df;
            --success: #10b981;
            --shadow-sm: 0 2px 8px rgba(26, 35, 50, 0.06);
            --shadow-md: 0 4px 20px rgba(26, 35, 50, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* CONSTRAINT: 100vh height and centered layout */
        body {
            color: var(--text-primary);
            height: 100vh;
            width: 95vw;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            /* Prevent body scroll */
        }

        .booking-avatar-text {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
            /* Light gray background */
            border-radius: 50%;
            /* Keeps it circular like an avatar */
            font-weight: bold;
            color: #333;
            font-size: 14px;
            text-transform: capitalize;
        }

        /* CONSTRAINT: 80vw width */
        .container {
            width: 80vw;
            height: 95vh;
            /* Slight padding from top/bottom */
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            position: relative;
            z-index: 1;
        }

        /* Header - Fixed Height */
        .header {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            padding: 1rem 2rem;
            background: var(--bg-card);
            border-radius: 20px;
            box-shadow: var(--shadow-md);
            animation: fadeInDown 0.6s ease forwards;
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .profile-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid var(--accent);
            object-fit: cover;
        }

        .profile-info h1 {
            font-size: 1.2rem;
        }

        .profile-info p {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        /* Stats Grid - Fixed Height */
        .stats-grid {
            flex-shrink: 0;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .stat-card {
            background: var(--bg-card);
            height: 300px;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1.2rem;
            border-radius: 16px;
            color: var(--primary);
            box-shadow: var(--shadow-md);
            animation: fadeInUp 0.6s ease forwards;
        }

        .stat-label {
            font-size: 1.75rem;
            font-weight: bold;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
        }

        /* Bottom Grid - FLEX GROW to fill remaining space */
        .bottom-grid {
            flex-grow: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            min-height: 0;
            /* Important for nested scrolling */
        }

        .section {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            display: flex;
            flex-direction: column;
            min-height: 0;
            /* Allows children to scroll */
        }

        .section-title {
            font-size: 1.7rem;
            margin-bottom: 1rem;
            font-weight: bold;
            flex-shrink: 0;
        }

        /* Internal Scrollable Areas */
        .scrollable-content {
            flex-grow: 1;
            overflow-y: auto;
            padding-right: 8px;
        }

        /* Custom Scrollbar */
        .scrollable-content::-webkit-scrollbar {
            width: 5px;
        }

        .scrollable-content::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollable-content::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 10px;
        }

        /* Items Styling */
        .booking-item,
        .review-item {
            display: flex;
            align-items: center;
            padding: 0.8rem;
            margin-bottom: 0.8rem;
            background: #fbfbfb;
            border-radius: 12px;
            border: 1px solid var(--border);
            transition: all 0.2s;
            cursor: pointer;
        }

        .booking-item:hover,
        .review-item:hover {
            border-color: var(--primary);
        }

        .booking-avatar,
        .review-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 12px;
        }
        .booking-details{
            margin-left: 1rem;
        }
        .booking-details h3,
        .review-info h4 {
            font-size: 1rem;
            font-weight: 500;
            padding-left: 10px;
        }

        .stars {
            color: var(--accent);
            font-size: 1rem;
            padding-left: 10px;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1100px) {
            .container {
                width: 95vw;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .bottom-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            body {
                overflow-y: auto;
                height: auto;
                padding: 20px 0;
            }

            .container {
                height: auto;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

    <?php  ?>
    <div class="container">
        <header class="header">
            <div class="profile-section">
                <span class="booking-avatar-text">
                                <?= htmlspecialchars($userData['firstName'][0]  . $userData['lastName'][0]); ?>
                            </span>
                <div class="profile-info">
                    <h1>Welcome, <?= htmlspecialchars($userData['firstName'] . " " . $userData['lastName']); ?></h1>
                    <p><?= htmlspecialchars($userData['email']); ?></p>
                </div>
            </div>
        </header>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Bookings</div>
                <div class="stat-value"><?= number_format($totalBookings); ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Avg Rating</div>
                <div class="stat-value"><?= $avgRating ?> ★</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total User Count</div>
                <div class="stat-value"><?= $totalUsers; ?></div>
            </div>
        </div>

        <div class="bottom-grid">
            <div class="section">
                <h2 class="section-title">Recent Bookings</h2>
                <div class="scrollable-content">
                    <?php foreach ($bookingData as $booking): ?>
                        <div class="booking-item">
                            <span class="booking-avatar-text">
                                <?= htmlspecialchars($booking['firstName'][0] . $booking['lastName'][0]); ?>
                            </span>
                            <div class="booking-details">
                                <h3><?= htmlspecialchars($booking['firstName'] . " " . $booking['lastName']); ?></h3>
                                <p style="font-size:1rem; color: var(--text-secondary);">
                                    <?= $booking['package_name']; ?> • <?= $booking['booked_at']; ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">Recent Reviews</h2>
                <div class="scrollable-content">
                    <?php
                    
                    foreach ($recent_review as $review): ?>
                        <div class="review-item">
                            <span class="booking-avatar-text">
                                <?= htmlspecialchars($review['firstName'][0] . $review['lastName'][0]); ?>
                            </span>
                            <div class="review-info">
                                <h4><?= htmlspecialchars($review['firstName'] ." ". $review['lastName']); ?> :  <?= htmlspecialchars($review['review']) ?></h4>
                                <div class="stars"><?= renderStars($review['rating']); ?></div>
                                <p style="font-size:0.75rem; padding-left:10px; color: var(--text-secondary);">
                                    <?= $review['name']; ?> • <?= $review['createdAt']; ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

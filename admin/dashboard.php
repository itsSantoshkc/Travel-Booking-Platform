<?php
include("../middleware/authMiddleware.php"); 
require_once("../model/User.php");



$admin_profile = [
    'name' => 'Jesse James',
    'email' => 'jesse@gmail.com',
    'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&h=200&fit=crop'
];


$stats = [
    'total_bookings' => 1247,
    'revenue' => 89200,
    'avg_rating' => 4.5,
    'active_tours' => 24
];

// Recent bookings
$recent_bookings = [
    [
        'id' => 1,
        'customer_name' => 'Tyril R',
        'customer_image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop',
        'tour' => 'Everest Helicopter ride',
        'date' => '2025/12/25',
        'time' => '10:00 AM',
        'persons' => 4
    ],
    [
        'id' => 2,
        'customer_name' => 'Maya R',
        'customer_image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop',
        'tour' => 'Everest Helicopter ride',
        'date' => '2025/12/25',
        'time' => '2:00 PM',
        'persons' => 4
    ],
    [
        'id' => 3,
        'customer_name' => 'Arjun S',
        'customer_image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop',
        'tour' => 'Everest Helicopter ride',
        'date' => '2025/12/25',
        'time' => '4:00 PM',
        'persons' => 4
    ],
    [
        'id' => 4,
        'customer_name' => 'Rihana K',
        'customer_image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop',
        'tour' => 'Everest Helicopter ride',
        'date' => '2025/12/25',
        'time' => '6:00 PM',
        'persons' => 4
    ],
      [
        'id' => 1,
        'customer_name' => 'Tyril R',
        'customer_image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop',
        'tour' => 'Everest Helicopter ride',
        'date' => '2025/12/25',
        'time' => '10:00 AM',
        'persons' => 4
    ],
    [
        'id' => 2,
        'customer_name' => 'Maya R',
        'customer_image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop',
        'tour' => 'Everest Helicopter ride',
        'date' => '2025/12/25',
        'time' => '2:00 PM',
        'persons' => 4
    ],
    [
        'id' => 3,
        'customer_name' => 'Arjun S',
        'customer_image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop',
        'tour' => 'Everest Helicopter ride',
        'date' => '2025/12/25',
        'time' => '4:00 PM',
        'persons' => 4
    ],
    [
        'id' => 4,
        'customer_name' => 'Rihana K',
        'customer_image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop',
        'tour' => 'Everest Helicopter ride',
        'date' => '2025/12/25',
        'time' => '6:00 PM',
        'persons' => 4
    ]
];

// Recent reviews
$recent_reviews = [
    [
        'id' => 1,
        'customer_name' => 'Maya R',
        'customer_image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop',
        'rating' => 4.5,
        'has_review' => false
    ],
    [
        'id' => 2,
        'customer_name' => 'James',
        'customer_image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop',
        'rating' => 4.5,
        'has_review' => true
    ],
    [
        'id' => 3,
        'customer_name' => 'Rihana',
        'customer_image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop',
        'rating' => 4.5,
        'has_review' => true
    ],
    [
        'id' => 1,
        'customer_name' => 'Maya R',
        'customer_image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop',
        'rating' => 4.5,
        'has_review' => false
    ],
    [
        'id' => 2,
        'customer_name' => 'James',
        'customer_image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop',
        'rating' => 4.5,
        'has_review' => true
    ],
    [
        'id' => 3,
        'customer_name' => 'Rihana',
        'customer_image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop',
        'rating' => 4.5,
        'has_review' => true
    ]
];


function renderStars($rating) {
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
    
    $stars = str_repeat('★ ', $fullStars);
    if ($halfStar) $stars .= '⯨ ';
    $stars .= str_repeat('☆ ', $emptyStars);
    
    return trim($stars);
}

$userId = $_SESSION['userID']; 

$userObj = new User($conn);
$userData = $userObj->getUserWithId($userId)['data'];

// public function getDashboardStats() {
//     $sql = "SELECT 
//                 (SELECT COUNT(*) FROM Booking) AS total_bookings,
//                 (SELECT ROUND(AVG(rating), 1) FROM Reviews) AS avg_rating,
//                 (SELECT COUNT(*) FROM User) AS total_users";
    
//     $result = $this->conn->query($sql);
//     return $result->fetch_assoc();
// }
?>



<head>
    <title>Admin Dashboard</title>
    <style>
        :root {
            --primary: #1a2332;
            --primary-light: #2a3b52;
            --accent: #d4a574;
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
            overflow: hidden; /* Prevent body scroll */
        }

        /* CONSTRAINT: 80vw width */
        .container {
            width: 80vw;
            height: 95vh; /* Slight padding from top/bottom */
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

        .profile-section { display: flex; align-items: center; gap: 1rem; }
        .profile-image { width: 50px; height: 50px; border-radius: 50%; border: 2px solid var(--accent); object-fit: cover; }
        .profile-info h1 {  font-size: 1.2rem; }
        .profile-info p { font-size: 0.8rem; color: var(--text-secondary); }

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

        .stat-label { font-size: 1.75rem;
        font-weight: bold;
        }
        .stat-value {  font-size: 1.8rem; font-weight: 700; }

        /* Bottom Grid - FLEX GROW to fill remaining space */
        .bottom-grid {
            flex-grow: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            min-height: 0; /* Important for nested scrolling */
        }

        .section {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            display: flex;
            flex-direction: column;
            min-height: 0; /* Allows children to scroll */
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
        .scrollable-content::-webkit-scrollbar { width: 5px; }
        .scrollable-content::-webkit-scrollbar-track { background: transparent; }
        .scrollable-content::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }

        /* Items Styling */
        .booking-item, .review-item {
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

        .booking-item:hover, .review-item:hover {
            border-color: var(--primary);
        }

        .booking-avatar, .review-avatar { width: 40px; height: 40px; border-radius: 50%; margin-right: 12px; }
        .booking-details h3, .review-info h4 { font-size: 1.5rem; font-weight: 500; }
        .stars { color: var(--accent); font-size: 1rem; }

        /* Responsive Breakpoints */
        @media (max-width: 1100px) {
            .container { width: 95vw; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .bottom-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            body { overflow-y: auto; height: auto; padding: 20px 0; }
            .container { height: auto; }
        }

        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <?php include("header.php");?>
    <div class="container">
        <header class="header">
            <div class="profile-section">
                <img src="<?= htmlspecialchars($admin_profile['image']); ?>" class="profile-image">
                <div class="profile-info">
                    <h1>Welcome, <?= htmlspecialchars($userData['firstName']. " " .$userData['lastName'] ); ?></h1>
                    <p><?= htmlspecialchars($userData['email']); ?></p>
                </div>
            </div>
        </header>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Bookings</div>
                <div class="stat-value"><?= number_format($stats['total_bookings']); ?></div>
            </div>
          
            <div class="stat-card">
                <div class="stat-label">Avg Rating</div>
                <div class="stat-value"><?= $stats['avg_rating']; ?> ★</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Active Tours</div>
                <div class="stat-value"><?= $stats['active_tours']; ?></div>
            </div>
        </div>

        <div class="bottom-grid">
            <div class="section">
                <h2 class="section-title">Recent Bookings</h2>
                <div class="scrollable-content">
                    <?php foreach ($recent_bookings as $booking): ?>
                    <div class="booking-item">
                        <img src="<?= $booking['customer_image']; ?>" class="booking-avatar">
                        <div class="booking-details">
                            <h3><?= htmlspecialchars($booking['customer_name']); ?></h3>
                            <p style="font-size:1rem; color: var(--text-secondary);">
                                <?= $booking['tour']; ?> • <?= $booking['time']; ?>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">Recent Reviews</h2>
                <div class="scrollable-content">
                    <?php foreach ($recent_reviews as $review): ?>
                    <div class="review-item">
                        <img src="<?= $review['customer_image']; ?>" class="review-avatar">
                        <div class="review-info">
                            <h4><?= htmlspecialchars($review['customer_name']); ?></h4>
                            <div class="stars"><?= renderStars($review['rating']); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
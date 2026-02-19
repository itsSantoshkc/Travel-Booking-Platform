<?php
include("header.php");
require_once("conn.php");
include("model/travel_package.php");

$location = $_GET['location'] ?? '';
$date = $_GET['date'] ?? '';

$travelObj = new Travel($conn);
$results = $travelObj->searchPackage($location, $date);
?>

<style>



  /* ── Page header ── */
  .page-header {
    padding: 40px 0;
    text-align: center;
  }

  .page-header h1 {
    font-size: 2.2rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 6px;
  }

  .page-header p {
    color: #6b7280;
    font-size: 1rem;
  }

  /* ── Cards wrapper ── */
  .cards-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    min-height: 100vh;
    padding-bottom: 60px;
  }

  .cards-grid {
    display: grid;
    width: 80%;
    grid-template-columns: repeat(3, 1fr);
    column-gap: 96px;
    row-gap: 48px;
    place-items: center;
  }

  /* ── Empty state ── */
  .empty-state {
    grid-column: span 3;
    text-align: center;
    padding: 80px 0;
  }

  .empty-state h2 {
    font-size: 1.4rem;
    color: #9ca3af;
    margin-bottom: 12px;
  }

  .empty-state a {
    color: #ef4444;
    text-decoration: underline;
    font-size: 1rem;
  }

  /* ── Single card ── */
  .card {
    width: 100%;
    margin-bottom: 32px;
    text-decoration: none;
    display: block;
    color: inherit;
  }

  .card-img-wrapper {
    position: relative;
    width: 100%;
    max-height: 90%;
  }

  .card-img {
    display: block;
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 24px;
    z-index: 0;
  }

  .card-badge {
    position: absolute;
    bottom: 8px;
    right: 8px;
    z-index: 10;
    padding: 6px 10px;
    font-weight: 600;
    color: #111;
    background: #fff;
    border-radius: 16px;
    font-size: 0.9rem;
  }

  .card-info {
    padding: 10px 0;
  }

  .card-name {
    font-size: 1.6rem;
    font-weight: 700;
    color: #4b5563;
    margin-bottom: 4px;
  }

  .card-location {
    font-size: 1.1rem;
    font-weight: 600;
    color: #4b5563;
    margin-bottom: 4px;
  }

  .card-price {
    font-size: 1.3rem;
    font-weight: 700;
    color: #16a34a;
  }
</style>

<body>
  <?php include("./components/Navbar.php"); ?>

  <div class="page-header">
    <h1>Search Results</h1>
    <p>Showing results for "<?= htmlspecialchars($location) ?>"</p>
  </div>

  <div class="cards-wrapper">
    <div class="cards-grid" id="content">

      <?php if (empty($results)): ?>
        <div class="empty-state">
          <h2>No Travel package found matching your criteria.</h2>
          <a href="index.php">Clear Filters</a>
        </div>

      <?php else: ?>
        <?php foreach ($results as $data): ?>
          <?php
            $imagePath = !empty($data['images']) ? str_replace('../', '', $data['images'][0]) : 'uploads/placeholder.jpg';
            $avgRating = !empty($data['avg_rating']) ? $data['avg_rating'] : 0;
          ?>

          <a class="card" href="travelPackage.php?package=<?= $data['package_id'] ?>">
            <div class="card-img-wrapper">
              <img class="card-img"
                   src="<?= $imagePath ?>"
                   alt="<?= htmlspecialchars($data['name']) ?>"/>
              <div class="card-badge">
                ⭐ <?= $avgRating ?> Out of 5
              </div>
            </div>
            <div class="card-info">
              <h3 class="card-name"><?= htmlspecialchars($data['name']) ?></h3>
              <p class="card-location"><?= htmlspecialchars($data['location']) ?></p>
              <p class="card-price">Rs. <?= number_format($data['price']) ?></p>
            </div>
          </a>

        <?php endforeach; ?>
      <?php endif; ?>

    </div>
  </div>

</body>
</html>
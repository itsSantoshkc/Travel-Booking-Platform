<?php
include("header.php");
require_once("conn.php");
include("model/travel_package.php");
?>

<style>
  /* ── Layout ── */
  .search-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    margin: 80px 0;
  }

  .search-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 33%;
    height: 64px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    border-radius: 999px;
    background: #fff;
    overflow: hidden;
  }

  .search-form {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
  }

  .search-inputs {
    width: 90%;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .search-inputs input {
    height: 64px;
    font-size: 1.2rem;
    border: none;
    outline: none;
    padding: 4px 10px;
    background: transparent;
  }

  .search-inputs input:focus {
    background: #f1f5f9;
  }

  .search-inputs input[name="location"] {
    width: 50%;
    border-radius: 999px 0 0 999px;
    padding-left: 20px;
  }

  .search-inputs input[name="date"] {
    width: 50%;
  }

  .search-divider {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 4px;
    color: #9ca3af;
    font-size: 1.2rem;
    user-select: none;
  }

  .search-btn-wrapper {
    width: 10%;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    height: 64px;
    border-radius: 0 999px 999px 0;
  }

  .search-btn-inner {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
  }

  .search-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    font-size: 1.2rem;
    color: #fff;
    background-color: #ef4444;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    transition: background-color 0.2s;
  }

  .search-btn:hover {
    background-color: #dc2626;
  }

  /* ── Cards grid ── */
  .cards-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    min-height: 100vh;
  }

  .cards-grid {
    display: grid;
    width: 80%;
    grid-template-columns: repeat(3, 1fr);
    column-gap: 96px;
    row-gap: 48px;
    place-items: center;
  }

  /* ── Single card ── */
  .card-link {
    display: block;
    text-decoration: none;
    margin-bottom: 32px;
    width: 100%;
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
    margin: 0 0 4px;
  }

  .card-location {
    font-size: 1.1rem;
    font-weight: 600;
    color: #4b5563;
    margin: 0 0 4px;
  }

  .card-price {
    font-size: 1.3rem;
    font-weight: 700;
    color: #16a34a;
    margin: 0;
  }
</style>

<body>
  <?php include("./components/Navbar.php"); ?>

  <div class="search-wrapper">
    <div class="search-box">
      <form method="get" action="searchActivity.php" class="search-form">

        <div class="search-inputs">
          <input type="text" name="location" id="location" placeholder="Destination">
          <span class="search-divider">|</span>
          <input type="date" name="date" id="date" placeholder="Date">
        </div>

        <div class="search-btn-wrapper">
          <div class="search-btn-inner">
            <button type="submit" class="search-btn">
              <i class="fa fa-search" aria-hidden="true"></i>
            </button>
          </div>
        </div>

      </form>
    </div>
  </div>

  <div class="cards-wrapper">
    <div class="cards-grid" id="content">
      <?php
      try {
        $travelPackageObj = new Travel($conn);
        $activityData = $travelPackageObj->getAllAvailableTravelPackages();

        foreach ($activityData as $data) {
          if (!empty($data['images'])) {
            $imagePath = str_replace('../', '', $data['images'][0]);
          }
          echo "
          <a class='card-link' href='travelPackage.php?package={$data['package_id']}'>
            <div class='card-img-wrapper'>
              <img
                class='card-img'
                src='{$imagePath}'
                alt='{$data['name']}'
              />
              <div class='card-badge'>
                ⭐ {$data['avg_rating']} Out of 5
              </div>
            </div>
            <div class='card-info'>
              <h3 class='card-name'>{$data['name']}</h3>
              <p class='card-location'>{$data['location']}</p>
              <p class='card-price'>Rs. {$data['price']}</p>
            </div>
          </a>";
        }
      } catch (Exception $e) {
        echo $e->getMessage();
      }
      ?>
    </div>
  </div>

</body>
</html>
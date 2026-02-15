<?php
include("header.php");
include("conn.php");
include("model/travel-package.php");

// 1. Capture the GET data
$location = $_GET['location'] ?? '';
$date = $_GET['date'] ?? '';
$people = (int)($_GET['noOfPeople'] ?? 0);

$travelObj = new Travel($conn);
$results = $travelObj->searchActivities($location, $date, $people);
?>

<body class="bg-gray-50">
  <?php include("./components/Navbar.php") ?>

  <div class="py-10 text-center">
    <h1 class="text-4xl font-bold text-gray-800">Search Results</h1>
    <p class="text-gray-500">Showing results for "<?= htmlspecialchars($location) ?>"</p>
  </div>

  <div class="flex items-center justify-center w-full min-h-screen">
    <div class="grid w-4/5 grid-cols-3 gap-x-24 gap-y-12 place-items-center" id="content">
      
      <?php if (empty($results)): ?>
        <div class="col-span-3 py-20 text-center">
            <h2 class="text-2xl text-gray-400">No activities found matching your criteria.</h2>
            <a href="index.php" class="text-red-500 underline">Clear Filters</a>
        </div>
      <?php else: ?>
        <?php foreach ($results as $data): ?>
          <?php 
            // Reuse your clean path logic
            $imagePath = !empty($data['images']) ? str_replace('../', '', $data['images'][0]) : 'uploads/placeholder.jpg';
          ?>
          
          <div class='w-full mb-8'>
            <div class='max-h-[90%] w-full relative'>
              <img class='z-0 object-cover w-full h-[300px] rounded-3xl' 
                   src='<?= $imagePath ?>' alt='<?= htmlspecialchars($data['name']) ?>' />
              <div class='absolute z-10 p-2 font-semibold text-black bg-white rounded-2xl bottom-2 right-2'>
                ⭐ 5 Out of 5
              </div>
            </div>
            <div class='py-2'>
              <h3 class='text-3xl font-bold text-gray-600'><?= htmlspecialchars($data['name']) ?></h3>
              <p class='text-xl font-semibold text-gray-600'><?= htmlspecialchars($data['location']) ?></p>
              <p class='text-2xl font-bold text-green-600'>Rs. <?= number_format($data['price']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>
  </div>
</body>
</html>
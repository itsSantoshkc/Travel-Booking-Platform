<?php
include("header.php");
require_once("conn.php");
include("model/travel_package.php");
?>

<body>
  <?php
  include("./components/Navbar.php")
  ?>

  <div class="flex items-center justify-center w-full my-20 border-gray-600">
  <div class="flex items-center justify-between w-3/6 h-16 shadow-lg rounded-3xl">
    <form method="get" action="searchActivity.php" class="flex items-center justify-center w-full">
      
      <div class="w-[90%] *:h-16 *:focus:outline-none *:focus:bg-slate-100 *:text-xl flex justify-center items-center">
        <input type="text" name="location" id="location" placeholder="Destination" class="w-2/5 px-6 py-1 placeholder:px-2 rounded-l-3xl">
        
        <span class="flex items-center justify-center mx-1">|</span>
        
        <input type="date" name="date" id="date" placeholder="Date" class="w-2/5 px-2 py-1 placeholder:px-2">
        
        <span class="flex items-center justify-center mx-1">|</span>

        <select name="noOfPeople" id="noOfPeople" class="w-1/5 px-2 py-1 placeholder:px-2">
          <option value='0'>No of People</option>
          <?php
          for ($i = 1; $i < 12; $i++) {
            echo "<option value='$i'>$i</option>";
          }
          ?>
        </select>
      </div>

      <div class="w-[10%] flex justify-start items-center h-16 rounded-r-3xl">
        <div class="flex items-center justify-center w-full h-full text-2xl text-white">
          <button type="submit" class="flex items-center justify-center w-12 h-12 text-center transition-colors bg-red-500 rounded-full cursor-pointer hover:bg-red-600">
            <i class="fa fa-search" aria-hidden="true"></i>
          </button>
        </div>
      </div>

    </form>
  </div>
</div>



  <div class="flex items-center justify-center w-full min-h-screen">
    <div
      class="grid w-4/5 grid-cols-3 gap-x-24 gap-y-12 place-items-center"
      id="content">
      <?php
    try {
  $travelPackageObj = new Travel($conn);
  $activityData = $travelPackageObj->getAllAvailableTravelPackages();

  foreach ($activityData as $data) {
    if (!empty($data['images'])) {
        // $imagePath = str_replace('../', '', $baseUrl . $data['images'][0]);
                $imagePath = str_replace('../', '',  $data['images'][0]);
    }
    echo "
    <a class='mb-8' href='travelPackage.php?package={$data['package_id']}'>
    
      <div class='max-h-[90%] w-full relative'>
        <img
          class='z-0 object-cover w-full h-[300px] rounded-3xl'
          src='{$imagePath}'
          alt='{$data['name']}'
        />
        <div class='absolute z-10 p-2 font-semibold text-black bg-white rounded-2xl bottom-2 right-2'>
         ⭐ 5 Out of 5
        </div>
      </div>
      <div class='py-2'>
        <h3 class='text-3xl font-bold text-gray-600'>{$data['name']}</h3>
        <p class='text-xl font-semibold text-gray-600'>{$data['location']}</p>
        <p class='text-2xl font-bold text-green-600'>Rs. {$data['price']}</p>
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
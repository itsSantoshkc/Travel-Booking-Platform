<?php
include("header.php");
?>

<body>
  <?php
  include("./components/Navbar.php")
  ?>

  <div class="flex items-center justify-center w-full my-20 border-gray-600">
    <div class="flex items-center justify-between w-3/6 h-16 shadow-lg rounded-3xl">
      <form class="w-[90%] *:h-16  *:focus:outline-none *:focus:bg-slate-100 *:text-xl flex justify-center items-center">
        <input type="text" placeholder="Destination" class="w-2/5 px-6 py-1  placeholder:px-2 rounded-l-3xl">
        <span class="flex items-center justify-center mx-1">|</span>
        <input type="datetime-local" placeholder="Date" class="w-2/5 px-2 py-1  placeholder:px-2">
        <span class="flex items-center justify-center mx-1">|</span>

        <select placeholder="No of People" class="w-1/5 px-2 py-1  placeholder:px-2 placeholder:text-wrap">
          <option value='0'>No of People</option>
          <?php
          for ($i = 1; $i < 25; $i++) {
            echo "<option value='$i'>$i</option>";
          }
          ?>
        </select>
      </form>
      <div class="w-[10%]  flex justify-start items-center h-16 rounded-r-3xl">
        <div class="flex items-center justify-center w-full h-full text-2xl text-white ">
          <div class="flex items-center justify-center w-12 h-12 text-center bg-red-500 rounded-full cursor-pointer">
            <i class="fa fa-search " aria-hidden="true"></i>
          </div>
        </div>

      </div>
    </div>
  </div>



  <div class="flex items-center justify-center w-full min-h-screen">
    <div
      class="grid w-4/5 grid-cols-3 gap-x-24 gap-y-12 place-items-center"
      id="content"></div>
  </div>
</body>
<script type="module">
  import {
    faker
  } from "https://esm.sh/@faker-js/faker";
  const content = document.getElementById("content");
  for (let i = 0; i < 6; i++) {
    content.innerHTML += ` <div>
          <div class="max-h-[90%] w-full  relative">
            <img
              class="z-0 object-contain w-full h-full rounded-3xl "
              src=${faker.image.urlLoremFlickr({ width: 1280, height: 960 })}
              alt="Rafting Image"
            />
            <div
              class="absolute z-10 p-2 font-semibold text-black bg-white rounded-2xl bottom-2 right-2"
            >
              ⭐ ${faker.number.float({
                min: 0,
                max: 5,
                multipleOf: 0.25,
              })} Out of 5
            </div>
          </div>
          <div class="py-2">
            <h3 class="text-3xl font-bold text-gray-600">${faker.commerce.product()}</h3>
            <p class="text-xl font-semibold text-gray-600">${faker.location.city()}, ${faker.location.county()}</p>
          </div>
        </div>`;
  }
</script>

</html>
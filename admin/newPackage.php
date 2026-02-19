<head>
    <link rel="stylesheet" href="css/newactivity.css" />
</head>
<?php
require_once("./header.php");
?>
<div class="h-screen pt-20 ">
<form  action="createPackage.php" enctype="multipart/form-data" method="post" onsubmit="return handleSubmit(event)">
    <div class="flex justify-center w-[500px]">
        <div class="grid grid-cols-3 gap-5 place-content-center  *:rounded-xl ">

            <label for="img-1"
                class="col-span-3 flex justify-center items-center w-[500px] h-64 bg-slate-200 cursor-pointer">
                <input onchange="handleImageChange(event)" type="file" id="img-1" name="img-1" class="hidden">
                <img class="hidden object-cover w-full h-full rounded-xl" src="" alt="">
                <i class="text-6xl fa-solid fa-camera"></i>
            </label>

            <label for="img-2"
                class="flex items-center justify-center h-32 col-span-1 cursor-pointer bg-slate-300">
                <input onchange="handleImageChange(event)" type="file" id="img-2" name="img-2" class="hidden">
                <img class="hidden object-cover w-full h-full rounded-xl" src="" alt="">
                <i class="text-xl fa-solid fa-camera"></i>
            </label>

            <label for="img-3"
                class="flex items-center justify-center h-32 col-span-1 cursor-pointer bg-slate-300">
                <input onchange="handleImageChange(event)" type="file" id="img-3" class="hidden">
                <img class="hidden object-cover w-full h-full rounded-xl" src="" alt="">
                <i class="text-xl fa-solid fa-camera"></i>
            </label>

            <label for="img-4"
                class="flex items-center justify-center h-32 col-span-1 cursor-pointer bg-slate-300">
                <input onchange="handleImageChange(event)" type="file" id="img-4" class="hidden">
                <img class="hidden object-cover w-full h-full rounded-xl" src="" alt="">
                <i class="text-xl fa-solid fa-camera"></i>
            </label>

        </div>
    </div>



    <div class="activity-form" id="activityForm">

        <div class="field">
            <label>Name</label>
            <input type="text" id="name" name="name" placeholder="Name">
        </div>

        <div class="field">
            <label>Price</label>
            <input type="number" id="price" name="price" placeholder="Price">
        </div>

         <div class="field">
            <label>Duration</label>
            <input type="number" id="duration" name="duration" placeholder="Duration">
        </div>

        <div class="field">
            <label>Description</label>
            <textarea id="description" name="description" placeholder="Description"></textarea>
        </div>

        <div class="field">
            <label>Number of Slots</label>
            <input type="number" id="slots" name="slots" placeholder="Number of Slots">
        </div>

        <div class="field">
            <label for="arrival-time">Arrival Time</label>
            <input type="time" id="arrival-time" name="arrival-time" placeholder="Arrival Time">
        </div>

          <div class="field">
            <label for="starting-date">Starting Date</label>
            <input type="date" id="starting-date" name="starting-date" placeholder="Starting Date">
        </div>






        <div class="field">
            <label>Location</label>
            <input type="text" id="location" name="location" placeholder="Location">
        </div>

        <button type="submit" class="create-btn">Create</button>
    </div>
</form>
</div>
<script src="./js/newactivity.js"></script>
</body>

</html>
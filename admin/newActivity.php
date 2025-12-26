<head>
    <link rel="stylesheet" href="css/newactivity.css" />
</head>
<?php
include("./component/Sidebar.php");
include("../header.php");
?>
<form class="main" action="createActivity.php" enctype="multipart/form-data" method="post" onsubmit="return handleSubmit(event)">
    <div class="flex justify-center w-[500px]">
        <div class="grid grid-cols-3 gap-5 place-content-center  *:rounded-xl ">

            <label for="img-1"
                class="col-span-3 flex justify-center items-center w-[500px] h-64 bg-slate-200 cursor-pointer">
                <input onchange="handleImageChange(event)" type="file" id="img-1" class="hidden">
                <img class="hidden object-cover w-full h-full rounded-xl" src="" alt="">
                <i class="text-6xl fa-solid fa-camera"></i>
            </label>

            <label for="img-2"
                class="flex items-center justify-center h-32 col-span-1 cursor-pointer bg-slate-300">
                <input onchange="handleImageChange(event)" type="file" id="img-2" class="hidden">
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
            <input type="text" id="name" placeholder="Enter the name of the activity">
        </div>

        <div class="field">
            <label>Price</label>
            <input type="number" id="price" placeholder="Enter the price">
        </div>

        <div class="field">
            <label>Description</label>
            <textarea id="description" placeholder="Enter the description"></textarea>
        </div>

        <div class="field">
            <label>Number of Slots</label>
            <input type="number" id="slots" placeholder="Enter the number of slots available per activity">
        </div>





        <?php
        include("component/configueActivity.php");
        ?>

        <div class="field">
            <label>Location</label>
            <input type="text" id="location" placeholder="Enter the location">
        </div>

        <button type="submit" class="create-btn">Create</button>
    </div>
</form>

<script src="./js/newactivity.js"></script>
</body>

</html>
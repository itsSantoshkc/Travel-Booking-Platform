<head>
    <link rel="stylesheet" href="css/newactivity.css" />
</head>
<?php
include("./component/Sidebar.php");
include("../header.php");
?>
<form class="main" onsubmit="handleSubmit(event)">
    <!-- <div class="image-section">
          <div class="image-large" id="imgPreviewMain"><i class="fa-solid fa-camera"><input type="file" id="imageInput" accept="image/*" style="display: none;"></i></div>

          <div class="image-row">
              <input type="file" id="imageInput" accept="image/*" hidden></div>
              <input type="file" id="imageInput" accept="image/*" hidden></div>
              <input type="file" id="imageInput" accept="image/*" hidden></div>
          </div> -->
    <div class="flex justify-center w-[500px]">
        <div class="grid grid-cols-3 gap-5 place-content-center  *:rounded-xl ">

            <label for="img-1"
                class="col-span-3 flex justify-center items-center w-[500px] h-64 bg-slate-200 cursor-pointer">
                <input onchange="handleImageChange(event)" type="file" id="img-1" class="hidden">
                <img class="w-full h-full object-cover rounded-xl hidden" src="" alt="">
                <i class="fa-solid fa-camera text-6xl"></i>
            </label>

            <label for="img-2"
                class="col-span-1 flex justify-center items-center h-32 bg-slate-300 cursor-pointer">
                <input onchange="handleImageChange(event)" type="file" id="img-2" class="hidden">
                <img class="w-full h-full object-cover rounded-xl hidden" src="" alt="">
                <i class="fa-solid fa-camera text-xl"></i>
            </label>

            <label for="img-3"
                class="col-span-1 flex justify-center items-center h-32 bg-slate-300 cursor-pointer">
                <input onchange="handleImageChange(event)" type="file" id="img-3" class="hidden">
                <img class="w-full h-full object-cover rounded-xl hidden" src="" alt="">
                <i class="fa-solid fa-camera text-xl"></i>
            </label>

            <label for="img-4"
                class="col-span-1 flex justify-center items-center h-32 bg-slate-300 cursor-pointer">
                <input onchange="handleImageChange(event)" type="file" id="img-4" class="hidden">
                <img class="w-full h-full object-cover rounded-xl hidden" src="" alt="">
                <i class="fa-solid fa-camera text-xl"></i>
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

        <div class="field">
            <label>From</label>
            <input type="text" id="duration" placeholder="Enter the duration">
        </div>
        <style>
            .modal-content {
                transition: transform 0.3s ease-out, opacity 0.3s ease-out;
            }
        </style>

        <button id="openModalBtn" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150">
            Open Modal
        </button>

        <div id="myModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">

            <div class="modal-content bg-white rounded-lg shadow-xl w-11/12 md:max-w-md mx-auto p-6 transform scale-95 opacity-0">

                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-xl font-semibold text-gray-800">Modal Title</h3>
                    <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                </div>

                <div class="mt-4 mb-6">
                    <p class="text-gray-700">This is the content of the modal. It's built with HTML, styled with **Tailwind CSS**, and made interactive with JavaScript.</p>
                </div>

                <div class="flex justify-end pt-3 border-t">
                    <button id="confirmBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-150">
                        Confirm
                    </button>
                    <button id="cancelBtn" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition duration-150">
                        Cancel
                    </button>
                </div>

            </div>

        </div>

        <!-- <div class="field">
            Configure Time and Slots
        </div> -->

        <!-- <div class="">
            <label><input type="checkbox" name="" id="eventType"> One Time Event</label>
        </div>

        <div class="w-full">
            Select Days for the event
        </div>
        <div class="w-full flex items-center justify-between *:px-2">
            <label>
                Sun
                <input type="checkbox" name="days[]" value="Sunday">
            </label>

            <label>
                Mon
                <input type="checkbox" name="days[]" value="Monday">
            </label>

            <label>
                Tue
                <input type="checkbox" name="days[]" value="Tuesday">
            </label>

            <label>
                Wed
                <input type="checkbox" name="days[]" value="Wednesday">
            </label>

            <label>
                Thu
                <input type="checkbox" name="days[]" value="Thursday">
            </label>

            <label>
                Fri
                <input type="checkbox" name="days[]" value="Friday">
            </label>

            <label>
                Sat
                <input type="checkbox" name="days[]" value="Saturday">
            </label>
        </div> -->



        <!-- <div class="min-h-32 timeBox bg-white  *:m-2 flex flex-col justify-around field">

            <div class="w-full">
                Choose Time
            </div>
            <div
                id="timeContainer"
                class="h-2/3 grid grid-cols-3 grid-rows-5    py-2 rounded-xl overflow-hidden " style="box-shadow: 0px 0px 4px #00000040;"></div>
            <div class="grid grid-cols-3 gap-4 content-center">
                <div class="flex flex-col">
                    <label for="fromTime" class="text-sm">From</label>
                    <input
                        id="fromTime"
                        type="time"
                        placeholder="Starting Time"
                        class="border rounded-lg px-2 text-sm" />
                </div>
                <div class="flex flex-col" class="text-sm">
                    <label for="toTime">To</label>
                    <input
                        type="time"
                        id="toTime"
                        class="border rounded-lg px-2 text-sm" />
                </div>

                <div class="flex w-full h-full justify-center items-end">
                    <button
                        id="addButton"
                        class="p-2 disabled:cursor-no-drop bg-[#ef233c]   px-4 w-full h-2/3 flex justify-center items-center text-sm text-center disabled:bg-red-400 text-white rounded-xl cursor-pointer button-disabled"
                        disabled>
                        Add Time
                    </button>
                </div>
            </div>
        </div> -->

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
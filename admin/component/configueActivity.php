  <?php
// include("./component/Sidebar.php");
include("../header.php");
?>
  <div class="field">
            Configure Time and Slots
        </div>

       <div class="one-time-event ">
            <label><input type="checkbox" name="" id="eventType"> One Time Event</label>
        </div> 
 
        <div class="w-full ">
            <label class="">Select Days for the event</label>
        </div>
   <select
   style=" padding: 12px 16px;"
  class="w-64 p-10 text-gray-700 transition duration-200 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
>
<option value="">Select days</option>
    <option value="sunday">Sunday</option>
    <option value="monday">Monday</option>
    <option value="tuesday">Tuesday</option>
    <option value="wednesday">Wednesday</option>
    <option value="thursday">Thursday</option>
    <option value="friday">Friday</option>
    <option value="saturday">Saturday</option>
    <option value="everyday">Everyday</option>
    <option value="mon-fri">Mon – Fri</option>
    <option value="custom">Custom</option>
</select>

        <!-- <div class="w-full grid grid-cols-4  *:px-2">
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



        <div class="min-h-32 timeBox bg-white  *:m-2 flex flex-col justify-around field">

            <div class="w-full">
                Choose Time
            </div>
            <div
                id="timeContainer"
                class="grid grid-cols-3 grid-rows-5 py-2 overflow-hidden h-2/3 rounded-xl " style="box-shadow: 0px 0px 4px #00000040;"></div>
            <div class="grid content-center grid-cols-3 gap-4">
                <div class="flex flex-col">
                    <label for="fromTime" class="text-sm">From</label>
                    <input
                        id="fromTime"
                        type="time"
                        placeholder="Starting Time"
                        class="px-2 text-sm border rounded-lg" />
                </div>
                <div class="flex flex-col" class="text-sm">
                    <label for="toTime">To</label>
                    <input
                        type="time"
                        id="toTime"
                        class="px-2 text-sm border rounded-lg" />
                </div>

                <div class="flex items-end justify-center w-full h-full">
                    <button
                        id="addButton"
                        class="p-2 disabled:cursor-no-drop bg-[#ef233c]   px-4 w-full h-2/3 flex justify-center items-center text-sm text-center disabled:bg-red-400 text-white rounded-xl cursor-pointer button-disabled"
                        disabled>
                        Add Time
                    </button>
                </div>
            </div>
        </div>
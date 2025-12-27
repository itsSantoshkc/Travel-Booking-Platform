   <script src="https://kit.fontawesome.com/e07ff84049.js" crossorigin="anonymous"></script>
   <nav
       class="w-screen h-16  bg-white flex justify-between items-center *:mx-28  shadow-md ">
       <a class="text-2xl font-bold cursor-pointer" href="index.php"><i class="fa-solid fa-plane-departure"></i> Activity Booking</a>
       <div class="relative flex flex-col items-center">
           <?php
            include("./middleware/authMiddleware.php");
            if (isLoggedIn()) {
                // Todo: Use avatar icon instead of the user profie picture
                echo '<div>
                
                <img
                class="bg-white rounded-full cursor-pointer h-9 w-9 avatar"
                src="https://png.pngtree.com/png-vector/20231019/ourmid/pngtree-user-profile-avatar-png-image_10211467.png" />
                </div>';
                //Todo: Add Hover 
            } else {
                echo '<div class="flex items-center justify-center w-64 ">
               <a href="signup.php" class="px-3 py-2 mx-2 font-semibold text-white bg-red-500 rounded-lg">Sign Up</a>
               <a href="login.php" class="px-3 py-2 mx-2 font-semibold text-red-500 border-2 border-red-500 rounded-lg">Log In</a>
           </div>';
            }
            ?>

           <div
               class="*:cursor-pointer 
                 *:font-semibold *:text-lg *:transition-all *:duration-500   absolute shadow-md   avatar-menu p-2 bg-white opacity-0 -translate-y-48   rounded-b-xl transition-all font-semibold duration-700 w-44 h-48 hidden flex-col items-center justify-around">
               <div
                   class="hover:bg-[#2B2D42] rounded-xl hover:text-white w-[90%] py-2 text-center">
                   Manage profile
               </div>
               <div
                   class="hover:bg-[#2B2D42] rounded-xl hover:text-white w-[90%] py-2 text-center">
                   View Booking
               </div>



               <a href="logout.php"
                   class="bg-[#EF233C] log-out text-center
                     hover:bg-[#A41728] text-white w-3/4 rounded-2xl py-2">
                   Log Out
               </a>
           </div>
       </div>
   </nav>
   <script>
       const avatar = document.querySelector(".avatar");



       const avatarMenu = document.querySelector(".avatar-menu");
       if (avatarMenu && avatar) {
           avatar.addEventListener("click", () => {
               //When the menu is above
               if (!avatarMenu.classList.contains("translate-y-14")) {
                   avatarMenu.classList.toggle("hidden");
                   avatarMenu.classList.toggle("flex");
                   setTimeout(() => {
                       avatarMenu.classList.toggle("translate-y-14");
                       avatarMenu.classList.toggle("-translate-y-48");
                   }, 0)
               } else {

                   avatarMenu.classList.toggle("translate-y-14");
                   avatarMenu.classList.toggle("-translate-y-48");
                   setTimeout(() => {
                       avatarMenu.classList.toggle("hidden");
                       avatarMenu.classList.toggle("flex");
                   }, 500)
               }

               avatarMenu.classList.toggle("opacity-0");

           });
       }
   </script>
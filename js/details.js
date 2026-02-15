

function setupBookingHandlers(travelData) {
    document.querySelectorAll(".book-now-btn").forEach((btn) => {
        btn.onclick = (e) => {
            const row = e.target.closest(".slot");
            
            // 1. Grab values from the specific row
            const selectedTime = row.querySelector(".time-select").value;
            const selectedDate = e.target.getAttribute("data-date");
            const fullDayName = e.target.getAttribute("data-day");

            // 2. Validation: Ensure user picked a time
            if (selectedTime === "Select Time" || !selectedTime) {
                alert("Please select a time slot first!");
                return;
            }

            // 3. Switch Modals
            document.getElementById("slotOverlay").classList.remove("show");
            document.getElementById("bookSlotOverlay").classList.add("show");

            // 4. Update the Confirmation Text
            document.getElementById("selectedTime").innerHTML = `
                <span class="block font-bold text-red-500">${fullDayName}, ${selectedDate}</span>
                <span class="text-gray-600">Time: ${selectedTime}</span>
            `;
            
            // We use the activity's main slot count for validation
            const maxSlots = travelData.no_of_slots || 10; 
            document.getElementById("slotsRemainingText").textContent = `* ${maxSlots} Slots Available`;

            // 5. Setup the Final Confirmation Click
            document.getElementById("confirmBook").onclick = () => {
                const count = parseInt(document.getElementById("slotInput").value);
                const extra = document.getElementById("extraInfo").value;

                if (!count || count <= 0 || count > maxSlots) {
                    alert(`Please enter a valid number of slots (1-${maxSlots})`);
                    return;
                }

                // Final Alert with Date AND Time
                alert(`✅ Booking Request Sent!\n` +
                      `Activity: ${travelData.name}\n` +
                      `Date: ${selectedDate}\n` +
                      `Time: ${selectedTime}\n` +
                      `Seats: ${count}`);

                document.getElementById("bookSlotOverlay").classList.remove("show");
                
                // NEXT STEP: Use fetch() here to send this to a PHP booking script
            };

            document.getElementById("cancelBook").onclick = () =>
                document.getElementById("bookSlotOverlay").classList.remove("show");
        };
    });
}
  
// }

document.addEventListener("DOMContentLoaded", () => {
    // 1. Element Selectors
    const openBtn = document.getElementById("openSlots");
    const slotOverlay = document.getElementById("slotOverlay");
    const closeBtn = document.getElementById("closeOverlay");
    
    const datePicker = document.getElementById("datePicker");
    const bookingDetails = document.getElementById("bookingDetails");
    const bookingForm = document.getElementById("bookingForm");

    // 2. Open Overlay Logic
    if (openBtn && slotOverlay) {
        openBtn.addEventListener('click', () => {
            slotOverlay.classList.add("show");
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        });
    }

    // 3. Close Overlay Logic (X Button)
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            closePopup();
        });
    }

    // 4. Close on Outside Click
    if (slotOverlay) {
        slotOverlay.addEventListener('click', (e) => {
            if (e.target === slotOverlay) {
                closePopup();
            }
        });
    }

 

    // 6. Helper function to close and reset
    function closePopup() {
        slotOverlay.classList.remove("show");
        document.body.style.overflow = ''; // Restore scrolling
        
        // Reset the form so it's fresh for the next click
        if (bookingForm) {
            bookingForm.reset();
        }
     
    }
});





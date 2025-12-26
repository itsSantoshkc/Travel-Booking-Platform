<style>
    :root {
        --slate-100: #f1f5f9;
        --slate-300: #cbd5e1;
        --slate-700: #334155;
        --border: #e2e8f0;
        --color-primary: #ef233c;
    }

    /* Dropdown Container */
    .schedule-dropdown {
        width: 100%;
        max-width: 650px;
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
        font-family: sans-serif;
    }

    /* The Clickable Header */
    summary {
        list-style: none;
        padding: 20px 30px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        transition: background 0.2s;
    }

    summary:hover {
        background: var(--slate-100);
    }

    /* Add a chevron icon using CSS */
    summary::after {
        content: '▼';
        font-size: 0.8rem;
        color: var(--slate-700);
        transition: transform 0.3s;
    }

    details[open] summary::after {
        transform: rotate(180deg);
    }

    /* Content Area */
    .card-content {
        padding: 0 30px 30px 30px;
        border-top: 2px solid var(--slate-100);
    }

    .section-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--slate-300);
        margin: 20px 0 10px 0;
        letter-spacing: 0.5px;
    }

    .toggle-box {
        background: var(--slate-100);
        padding: 15px;
        border-radius: 12px;
        margin-top: 20px;
        display: flex;
        font-size: 12px;
        align-items: center;
        cursor: pointer;
        font-weight: 600;
        gap: 12px;
    }

    /* Standard Inputs */
    .input-field {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 1rem;
        margin-bottom: 10px;
        box-sizing: border-box;
    }

    .days-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
    }

    @media (min-width: 500px) {
        .days-grid {
            grid-template-columns: repeat(7, 1fr);
        }
    }

    .day-option input {
        display: none;
    }

    .day-box {
        padding: 12px 0;
        text-align: center;
        border: 2px solid var(--slate-100);
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 800;
        cursor: pointer;
    }

    .day-option input:checked+.day-box {
        background: var(--color-primary);
        border-color: var(--color-primary);
        color: white;
    }

    .time-box {
        min-height: 60px;
        border: 2px dashed var(--slate-100);
        border-radius: 12px;
        padding: 10px;
        margin-bottom: 15px;
        display: flex;
        flex-wrap: wrap;
    }

    .time-slot-pill {
        display: inline-flex;
        align-items: center;
        background: var(--slate-100);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin: 4px;
    }

    .remove-slot {
        margin-left: 8px;
        color: var(--color-primary);
        cursor: pointer;
        border: none;
        background: none;
        font-weight: bold;
    }

    .time-controls {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 10px;
        align-items: flex-end;
    }

    .add-btn {
        background: var(--color-primary);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
    }

    .add-btn:disabled {
        opacity: 0.4;
    }

    .hidden {
        display: none !important;
    }
</style>

<details class="schedule-dropdown">
    <summary>Configure Time and Slots</summary>

    <div class="card-content">
        <label class="toggle-box">
            <input type="checkbox" id="eventType" /> One Time Event
        </label>

        <div id="dateWrapper">
            <label class="section-label" id="dateLabel">Starting From Date</label>
            <input type="date" class="input-field" id="mainDate" />
        </div>

        <div id="recurringControls">
            <label class="section-label">Select Recurrence</label>
            <select id="dayDropdown" class="input-field">
                <option value="">Select days</option>
                <option value="everyday">Everyday</option>
                <option value="mon-fri">Mon – Fri</option>
                <option value="custom">Custom</option>
            </select>

            <div id="customDays" class="hidden days-grid">
                <label class="day-option"><input type="checkbox" name="days[]" value="1" />
                    <div class="day-box">SUN</div>
                </label>
                <label class="day-option"><input type="checkbox" name="days[]" value="2" />
                    <div class="day-box">MON</div>
                </label>
                <label class="day-option"><input type="checkbox" name="days[]" value="3" />
                    <div class="day-box">TUE</div>
                </label>
                <label class="day-option"><input type="checkbox" name="days[]" value="4" />
                    <div class="day-box">WED</div>
                </label>
                <label class="day-option"><input type="checkbox" name="days[]" value="5" />
                    <div class="day-box">THU</div>
                </label>
                <label class="day-option"><input type="checkbox" name="days[]" value="6" />
                    <div class="day-box">FRI</div>
                </label>
                <label class="day-option"><input type="checkbox" name="days[]" value="7" />
                    <div class="day-box">SAT</div>
                </label>
            </div>
        </div>

        <label class="section-label">Configure Time Slots</label>
        <div id="timeBox" class="time-box">
            <span id="placeholderText" style="color: #cbd5e1; font-size: 0.8rem; margin: auto">No time slots added yet</span>
        </div>

        <div class="time-controls">
            <div style="display: flex; flex-direction: column">
                <label style="font-size: 0.7rem; font-weight: 700">From</label>
                <input type="time" id="fromTime" class="input-field" style="margin-bottom: 0" />
            </div>
            <div style="display: flex; flex-direction: column">
                <label style="font-size: 0.7rem; font-weight: 700">To</label>
                <input type="time" id="toTime" class="input-field" style="margin-bottom: 0" />
            </div>
            <button id="addTimeBtn" class="add-btn" disabled>Add</button>
        </div>
    </div>
</details>

<script>
    // All original logic remains the same
    const eventType = document.getElementById("eventType");
    const dateLabel = document.getElementById("dateLabel");
    const recurringControls = document.getElementById("recurringControls");
    const dayDropdown = document.getElementById("dayDropdown");
    const customDays = document.getElementById("customDays");
    const timeBox = document.getElementById("timeBox");
    const fromTime = document.getElementById("fromTime");
    const toTime = document.getElementById("toTime");
    const addTimeBtn = document.getElementById("addTimeBtn");
    const placeholderText = document.getElementById("placeholderText");

    eventType.addEventListener("change", () => {
        if (eventType.checked) {
            dateLabel.innerText = "Event Date";
            recurringControls.classList.add("hidden");
            dayDropdown.value = "";
            customDays.classList.add("hidden");
        } else {
            dateLabel.innerText = "Starting From Date";
            recurringControls.classList.remove("hidden");
        }
    });

    dayDropdown.addEventListener("change", () => {
        if (dayDropdown.value === "custom") {
            customDays.classList.remove("hidden");
        } else {
            customDays.classList.add("hidden");
        }
    });

    const validateTime = () => {
        addTimeBtn.disabled = !(fromTime.value && toTime.value);
    };

    fromTime.addEventListener("change", validateTime);
    toTime.addEventListener("change", validateTime);

    addTimeBtn.addEventListener("click", () => {
        const slotText = `${fromTime.value} - ${toTime.value}`;
        if (eventType.checked) {
            timeBox.innerHTML = "";
        } else {
            placeholderText.classList.add("hidden");
        }
        const pill = document.createElement("div");
        pill.className = "time-slot-pill";
        pill.innerHTML = `${slotText} <button class="remove-slot" onclick="this.parentElement.remove()">×</button>`;
        timeBox.appendChild(pill);
        fromTime.value = "";
        toTime.value = "";
        validateTime();
    });
</script>
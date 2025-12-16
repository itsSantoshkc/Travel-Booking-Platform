function handleSubmit(event) {
  event.preventDefault();
  // console.log(event);
}

function handleImageChange(event) {
  const label = event.target.parentElement;
  const imgElement = label.children[1];
  const imgFile = event.target.files[0];
  const imgIcon = label.children[2];
  if (!imgFile) {
    return;
  }
  imgElement.src = URL.createObjectURL(imgFile);
  if (imgElement.classList.contains("hidden")) {
    imgElement.classList.toggle("hidden");
    imgIcon.classList.toggle("fa-solid");
    imgIcon.classList.toggle("hidden");
    imgIcon.classList.toggle("fa-camera");
  }
}

const timeContainer = document.getElementById("timeContainer");
const timeBox = document.querySelector(".timeBox");
const fromTime = document.getElementById("fromTime");
const toTime = document.getElementById("toTime");
const addButton = document.getElementById("addButton");
const eventType = document.getElementById("eventType");

eventType.addEventListener("change", () => {
  console.log(timeBox);
  if (eventType.checked) {
    if (timeBox.classList.contains("flex")) {
      timeBox.classList.toggle("flex");
      timeBox.classList.toggle("field");
      timeBox.classList.toggle("hidden");
    }
  } else {
    timeBox.classList.toggle("hidden");
    timeBox.classList.toggle("flex");
    timeBox.classList.toggle("field");
  }
});

const removeTime = (event) => {
  const timeSlotSpan = event.target.parentElement;
  timeContainer.removeChild(timeSlotSpan);
};

const updateButtonState = () => {
  if (fromTime.value && toTime.value) {
    addButton.disabled = false;
    addButton.classList.remove("button-disabled");
  } else {
    addButton.disabled = true;
    addButton.classList.add("button-disabled");
  }
};

const addNewTime = () => {
  const fromVal = fromTime.value;
  const toVal = toTime.value;

  if (toVal <= fromVal) {
    alert("Error: 'To Time' must be later than 'From Time'.");
    return;
  }

  const span = document.createElement("span");
  const timeValue = document.createElement("span");
  const crossBtn = document.createElement("span");

  crossBtn.textContent = "X";
  crossBtn.classList = ["cursor-pointer"];
  span.classList = [
    "bg-slate-300 p-1 flex items-center justify-around row-span-1 m-1 rounded-lg text-slate-600 text-xs",
  ];

  timeValue.textContent = `${fromVal} - ${toVal}`;
  crossBtn.addEventListener("click", (e) => removeTime(e));

  span.appendChild(timeValue);
  span.appendChild(crossBtn);
  timeContainer.appendChild(span);

  fromTime.value = "";
  toTime.value = "";
  updateButtonState(); // Update state after clearing inputs
};

fromTime.addEventListener("change", updateButtonState);
toTime.addEventListener("change", updateButtonState);

addButton.addEventListener("click", () => {
  addNewTime();
});

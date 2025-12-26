async function handleSubmit(event) {
  event.preventDefault(); // Stop page reload

  const form = event.target;
  // FormData automatically captures all inputs with a "name" attribute
  const formData = new FormData();
  const eventType = document.getElementById('eventType').checked ? 'one-time' : 'recurring'

  // 1. Manually add the text fields (Ensure IDs match your HTML)
  formData.append('name', document.getElementById('name').value);
  formData.append('price', document.getElementById('price').value);
  formData.append('description', document.getElementById('description').value);
  formData.append('slots', document.getElementById('slots').value);
  formData.append('location', document.getElementById('location').value)
    ;
  // 2. Add Schedule Data
  formData.append('eventType', eventType);
  formData.append('mainDate', document.getElementById('mainDate').value);

  // 3. Add Time Slots (Gathering from the UI pills)
  const slots = Array.from(document.querySelectorAll(".time-slot-pill"))
    .map(p => p.innerText.replace('×', '').trim());

  // We append them individually so PHP sees them as an array slots_list[]
  slots.forEach(slot => {
    formData.append('slots_list[]', slot);
  });

  if (eventType === "recurring") {
    const dayDropdown = document.getElementById("dayDropdown");
    const recurrenceValue = dayDropdown.value;
    if (recurrenceValue === "everyday") {
      // Manually add all days
      const allDays = ["1", "2", "3", "4", "5", "6", "7"];
      allDays.forEach(day => formData.append('days[]', day));
    }
    else if (recurrenceValue === "mon-fri") {
      // Manually add work days
      const workDays = ["2", "3", "4", "5", "6"];
      workDays.forEach(day => formData.append('days[]', day));
    }
    else if (recurrenceValue === "custom") {
      // Get only the checked boxes from the custom grid
      const checkedBoxes = document.querySelectorAll('input[name="days[]"]:checked');
      checkedBoxes.forEach(cb => {
        formData.append('days[]', cb.value);
      });
    }
  }

  // 4. Add Images (Mapping IDs to the names PHP expects)
  for (let i = 1; i <= 4; i++) {
    const fileInput = document.getElementById(`img-${i}`);
    if (fileInput.files[0]) {
      formData.append(`image_${i}`, fileInput.files[0]);
    }
  }

  // 5. Send to PHP via FETCH
  // 5. Send to PHP via XMLHttpRequest
  const xhr = new XMLHttpRequest();

  // Configure the request
  xhr.open('POST', 'createActivity.php', true);

  // Handle the response
  xhr.onload = function () {
    if (xhr.status >= 200 && xhr.status < 300) {
      // Equivalent to await response.text()
      // console.log("Server Response:", xhr.responseText);
      // alert("Check console for server response");
    } else {
      console.error("Server Error:", xhr.statusText);
    }
  };

  // Handle network errors
  xhr.onerror = function () {
    console.error("Request failed");
  };

  // Send the data
  // Note: Like fetch, do NOT set Content-Type header manually when sending FormData
  xhr.send(formData);
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


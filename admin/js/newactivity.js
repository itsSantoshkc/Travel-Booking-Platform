// Validation helper functions
function showError(fieldId, message) {
  const field = document.getElementById(fieldId);
  if (field) {
    field.style.border = '2px solid red';
    // Remove existing error message if any
    const existingError = field.parentElement.querySelector('.error-message');
    if (existingError) {
      existingError.remove();
    }
    // Add new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.color = 'red';
    errorDiv.style.fontSize = '12px';
    errorDiv.style.marginTop = '5px';
    errorDiv.textContent = message;
    field.parentElement.appendChild(errorDiv);
  }
}

function clearError(fieldId) {
  const field = document.getElementById(fieldId);
  if (field) {
    field.style.border = '';
    const errorMessage = field.parentElement.querySelector('.error-message');
    if (errorMessage) {
      errorMessage.remove();
    }
  }
}

function clearAllErrors() {
  const errorMessages = document.querySelectorAll('.error-message');
  errorMessages.forEach(msg => msg.remove());
  
  const fields = ['name', 'price', 'duration', 'description', 'slots', 'arrival-time', 'starting-date', 'location'];
  fields.forEach(fieldId => {
    const field = document.getElementById(fieldId);
    if (field) {
      field.style.border = '';
    }
  });
}

function validateForm() {
  clearAllErrors();
  let isValid = true;

  // Validate Name (required, min 3 characters)
  const name = document.getElementById('name').value.trim();
  if (!name) {
    showError('name', 'Name is required');
    isValid = false;
  } else if (name.length < 3) {
    showError('name', 'Name must be at least 3 characters');
    isValid = false;
  }

  // Validate Price (required, must be positive number)
  const price = document.getElementById('price').value;
  if (!price) {
    showError('price', 'Price is required');
    isValid = false;
  } else if (parseFloat(price) <= 0) {
    showError('price', 'Price must be greater than 0');
    isValid = false;
  }

  // Validate Duration (required, must be positive number)
  const duration = document.getElementById('duration').value;
  if (!duration) {
    showError('duration', 'Duration is required');
    isValid = false;
  } else if (parseInt(duration) <= 0) {
    showError('duration', 'Duration must be greater than 0');
    isValid = false;
  }

  // Validate Description (required, min 10 characters)
  const description = document.getElementById('description').value.trim();
  if (!description) {
    showError('description', 'Description is required');
    isValid = false;
  } else if (description.length < 10) {
    showError('description', 'Description must be at least 10 characters');
    isValid = false;
  }

  // Validate Number of Slots (required, must be positive integer)
  const slots = document.getElementById('slots').value;
  if (!slots) {
    showError('slots', 'Number of slots is required');
    isValid = false;
  } else if (parseInt(slots) <= 0) {
    showError('slots', 'Number of slots must be greater than 0');
    isValid = false;
  }

  // Validate Arrival Time (required)
  const arrivalTime = document.getElementById('arrival-time').value;
  if (!arrivalTime) {
    showError('arrival-time', 'Arrival time is required');
    isValid = false;
  }

  // Validate Starting Date (required, must be today or future date)
  const startingDate = document.getElementById('starting-date').value;
  if (!startingDate) {
    showError('starting-date', 'Starting date is required');
    isValid = false;
  } else {
    const selectedDate = new Date(startingDate);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    if (selectedDate < today) {
      showError('starting-date', 'Starting date cannot be in the past');
      isValid = false;
    }
  }

  // Validate Location (required, min 3 characters)
  const location = document.getElementById('location').value.trim();
  if (!location) {
    showError('location', 'Location is required');
    isValid = false;
  } else if (location.length < 3) {
    showError('location', 'Location must be at least 3 characters');
    isValid = false;
  }

  // Validate at least one image is uploaded
  const img1 = document.getElementById('img-1').files[0];
  if (!img1) {
    alert('Please upload at least one image (main image)');
    isValid = false;
  }

  return isValid;
}

async function handleSubmit(event) {
  event.preventDefault(); // Stop page reload

  // Validate form before submission
  if (!validateForm()) {
    return false;
  }

  const formData = new FormData();

  // Add text fields
  formData.append('name', document.getElementById('name').value.trim());
  formData.append('price', document.getElementById('price').value);
  formData.append('duration', document.getElementById('duration').value);
  formData.append('description', document.getElementById('description').value.trim());
  formData.append('slots', document.getElementById('slots').value);
  formData.append('arrival-time', document.getElementById('arrival-time').value);
  formData.append('starting-date', document.getElementById('starting-date').value);
  formData.append('location', document.getElementById('location').value.trim());

  // Add images (only if files are selected)
  for (let i = 1; i <= 4; i++) {
    const fileInput = document.getElementById(`img-${i}`);
    if (fileInput && fileInput.files[0]) {
      formData.append(`image_${i}`, fileInput.files[0]);
    }
  }

  // Send to PHP via XMLHttpRequest
  const xhr = new XMLHttpRequest();

  // Configure the request
  xhr.open('POST', 'createPackage.php', true);

  // Handle the response
  xhr.onload = function () {
    if (xhr.status >= 200 && xhr.status < 300) {
      console.log("Server Response:", xhr.responseText);
      alert("Package created successfully!");
      // Optionally reset the form or redirect
      // window.location.href = 'packages.php';
    } else {
      console.error("Server Error:", xhr.statusText);
      alert("Error creating package. Please try again.");
    }
  };

  // Handle network errors
  xhr.onerror = function () {
    console.error("Request failed");
    alert("Network error. Please check your connection.");
  };

  // Send the data
  xhr.send(formData);
  
  return false;
}

function handleImageChange(event) {
  const label = event.target.parentElement;
  const imgElement = label.children[1];
  const imgFile = event.target.files[0];
  const imgIcon = label.children[2];
  
  if (!imgFile) {
    return;
  }

  // Validate file type
  const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
  if (!validTypes.includes(imgFile.type)) {
    alert('Please upload a valid image file (JPEG, PNG, GIF, or WebP)');
    event.target.value = '';
    return;
  }

  // Validate file size (max 5MB)
  const maxSize = 5 * 1024 * 1024; // 5MB in bytes
  if (imgFile.size > maxSize) {
    alert('Image size must be less than 5MB');
    event.target.value = '';
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

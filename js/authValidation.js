async function validateSignUpData(event) {
  event.preventDefault();

  const fullName = document.getElementById("fullname");
  const phone = document.getElementById("phone");
  const email = document.getElementById("email");
  const password = document.getElementById("password");
  const confirmpassword = document.getElementById("confirmpassword");

  resetErrorStyle(event);
  if (
    !fullName.value ||
    !phone.value ||
    !email.value ||
    !password.value ||
    !confirmpassword.value
  ) {
    return showError("Fields cannot be empty");
  }

  const emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
  const passwordRegex =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&^#()[\]{}<>~`_+=|\\:;'",./-]).{9,}$/;
  const phoneRegex = /^\d{10}$/;
  if (!emailRegex.test(email.value)) {
    return showError("Invalid Email Address");
  }

  if (!phoneRegex.test(parseInt(phone.value))) {
    return showError("Phone Number should be 10 digits");
  }

  // if (!passwordRegex.test(password.value)) {
  //   password.style.border = "red solid";
  //   return showError(
  //     "Password must contain 1 Uppercae,1 Lower case alphabet,1 Number and ! Special Character"
  //   );
  // }

  if (password.value !== confirmpassword.value) {
    password.className = "error-input";
    confirmpassword.className = "error-input";

    return showError("Password and Confirm password do not match");
  }

  const formData = `name=${encodeURIComponent(
    fullName.value
  )}&email=${encodeURIComponent(email.value)}&password=${encodeURIComponent(
    password.value
  )}&phone=${encodeURIComponent(phone.value)}`;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "./utilities/registerUser.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);

          if (response.success) {
            return (window.location.href = "index.php");
          } else {
            return showError(response.message);
          }
        } catch (error) {
          console.log(error);
          return showError(error);
        }
      } else {
        showError("Internal Server Error");
      }
    }
  };
  xhr.send(formData);
}

async function validateLogInData(event) {
  event.preventDefault();
  const email = document.getElementById("email");
  const password = document.getElementById("password");
  resetErrorStyle(event);
  if (!email.value || !password.value) {
    return showError("Fields cannot be empty");
  }

  const emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;

  if (!emailRegex.test(email.value)) {
    return showError("Invalid Email Address");
  }

  const formData = `email=${encodeURIComponent(
    email.value
  )}&password=${encodeURIComponent(password.value)}`;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "./utilities/logInUser.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);

          if (response.success) {
            if (response.role === "admin") {
              return (window.location.href = "admin/dashboard.php");
            } else {
              return (window.location.href = "index.php");
            }
          } else {
            return showError(response.message);
          }
        } catch (error) {
          return showError(error);
        }
      }
    }
  };
  xhr.send(formData);
}

function resetErrorStyle(event) {
  const childElements = event.target.children;
  const error = document.querySelector(".error-container");
  error.style.display = "none";
  for (element of childElements) {
    if (element.classList.contains("error-input")) {
      element.classList.remove("error-input");
    }
  }
}

function showError(message) {
  const error = document.querySelector(".error-container");
  error.style.display = "flex";
  error.children[0].textContent = "Error! " + message;
}

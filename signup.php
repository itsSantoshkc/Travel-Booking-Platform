<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Trippie - Sign Up</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="./css/signup.css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
  <div class="signup-container">
    <div class="signup-heading">Get Started Now</div>
    <div class="signup-subheading">
      Enter your credentials to create an account
    </div>

    <div class="error-container">
      <p class="error">Hello</p>
    </div>
    <script src="./js/authValidation.js"></script>
    <form
      id="signupForm"
      onsubmit="return validateSignUpData(event)">
      <label for="fullname">Full Name</label>
      <input
        type="text"
        id="fullname"
        placeholder="Enter your Full Name"
        required />

      <label for="phone">Phone Number</label>
      <input
        type="text"
        id="phone"
        placeholder="Enter your phone number"
        required />

      <label for="email">Email Address</label>
      <input
        type="email"
        id="email"
        placeholder="Enter your email"
        required />

      <label for="password">Password</label>
      <input
        type="password"
        id="password"
        placeholder="Enter your password"
        required />

      <label for="confirmpassword">Confirm Password</label>
      <input
        type="password"
        id="confirmpassword"
        placeholder="Enter your password again"
        required />

      <div class="signup-footer">
        Have an account? <span id="goToLogin">Log In</span>
      </div>

      <button type="submit" class="signup-button">
        Create a new account
      </button>
    </form>
  </div>


</body>

</html>
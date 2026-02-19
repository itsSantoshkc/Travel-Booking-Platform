<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Trippie - Login</title>
  <link rel="stylesheet" href="./css/login.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap"
    rel="stylesheet" />
</head>
<style>

</style>

<body>
  <div class="login-container">
    <div class="login-heading">Welcome Back!</div>
    <div class="login-subheading">
      Enter your email and password to log in
    </div>

    <div class="error-container">
      <p class="error">Phone Number should be 10 digits</p>
    </div>

    <form id="loginForm" method="post" onsubmit="return validateLogInData(event)">
      <div class="form-group">
        <label class="email-label" for="email">Email Address</label>
        <input
          type="email"
          id="email"
          name="email"
          class="email-input"
          placeholder="Enter your email"
          required />
      </div>

      <div class="form-group">
        <label class="password-label" for="password">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          class="password-input"
          placeholder="Enter your password"
          required />
      </div>

      <div class="login-footer">
        <div class="create-account">
          New User? <a id="goToSignup" href="signup.php">Create an account</a>
        </div>
        
      </div>

      <button type="submit" class="login-button">Log In</button>
    </form>
  </div>

  <script src="./js/authValidation.js"></script>
</body>

</html>
  <!-- Sidebar -->

  <style>
      .sidebar {
          width: 297px;
          background: #EDF2F4;
          border-radius: 48px;
          height: 100vh;
          padding: 40px 25px;
          position: fixed;
          margin: 10px 0 10px 30px;
      }

      .logo {
          display: flex;
          align-items: center;
          gap: 10px;
          margin-bottom: 25px;
      }

      .logo-text {
          font-size: 20px;
          font-weight: 700;
          color: #2B2D42;
      }

      .plane {
          font-size: 36px;
      }

      .nav-links {
          list-style: none;
          margin-top: 20px;
      }

      .nav-links li {
          margin-bottom: 20px;
      }

      .nav-links a {
          text-decoration: none;
          font-size: 20px;
          font-weight: 700;
          color: #2B2D42;
          padding: 8px 14px;
          display: block;
          border-radius: 20px;
          transition: 0.3s;
      }

      .nav-links li.active a,
      .nav-links a:hover {
          background: #2B2D42;
          color: #FFFFFF;
      }

      .logout-btn {
          background: #EF233C;
          color: #fff;
          font-size: 20px;
          font-weight: 700;
          border: none;
          padding: 12px 46px;
          border-radius: 16px;
          position: absolute;
          bottom: 45px;
          left: 48px;
          cursor: pointer;
      }
  </style>
  <nav class="sidebar">
      <div class="logo">
          <span class="plane">✈️</span>
          <span class="logo-text">Travel Booking</span>
      </div>
      <hr>

      <?php
        $currentPath = $_SERVER['REQUEST_URI'];
        $slug = basename($currentPath);

        ?>
      <ul class="nav-links">
          <li class="<?= ($slug == 'dashboard.php') ? 'active' : '' ?>">
              <a href="dashboard.php">Dashboard</a>
          </li>

          <li class="<?= ($slug == 'managebookings.php') ? 'active' : '' ?>">
              <a href="managebookings.php">Manage Bookings</a>
          </li>

          <li class="<?= ($slug == 'managePackage.php') ? 'active' : '' ?>">
              <a href="managePackage.php">Manage Travel</a>
          </li>

          <li class="<?= ($slug == 'newPackage.php') ? 'active' : '' ?>">
              <a href="newPackage.php">New Package</a>
          </li>

          <li class="<?= ($slug == 'manageProfile.php') ? 'active' : '' ?>">
              <a href="manageProfile.php">Manage Profile</a>
          </li>
      </ul>
      <button id="logoutBtn" class="logout-btn">Log Out</button>
  </nav>
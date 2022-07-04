<?php
require_once "./constants.php";
require_once "./customFunctions.php";
require_once "./api/lib/jwtUtils.php";
if ((!$_SESSION['PHP_PACKAGE_ADMIN']) && (!$_SESSION['PHP_PACKAGE_TOKEN'])) {
    redirectPage("login");
} else {
    $bearerToken = trim($_SESSION['PHP_PACKAGE_TOKEN']);
    $validToken = jwtValiditatonCheck($bearerToken, JWT_SECRET_KEY);
    if ($validToken) {
        $headerParams = array("Authorization: Bearer " . $_SESSION['PHP_PACKAGE_TOKEN']);
    } else {
        redirectPage("logout.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>PHP package :: <?php echo $pageName; ?></title>
  <link href="https://fonts.gstatic.com" rel="preconnect" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" />
  <link href="<?php echo getHostLink(SITE_NAME);?>/assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?php echo getHostLink(SITE_NAME);?>/assets/css/bootstrap-icons.css" rel="stylesheet" />
  <link href="<?php echo getHostLink(SITE_NAME);?>/assets/css/boxicons.min.css" rel="stylesheet" />
  <link href="<?php echo getHostLink(SITE_NAME);?>/assets/css/style.css" rel="stylesheet" />
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="<?php echo getHostLink(SITE_NAME);?>/home" class="logo d-flex align-items-center">
        <img src="<?php echo getHostLink(SITE_NAME);?>/assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">NiceAdmin</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="<?php echo getHostLink(SITE_NAME);?>/assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">
              <?php echo $_SESSION['PHP_PACKAGE_ADMIN']; ?>
            </span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $_SESSION['PHP_PACKAGE_ADMIN']; ?></h6>
              <span>Web Designer</span>
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?php echo getHostLink(SITE_NAME);?>/logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="<?php echo getHostLink(SITE_NAME);?>/home">
          <i class="bi bi-gear"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#students-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-people-fill"></i><span>Students</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="students-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?php echo getHostLink(SITE_NAME);?>/addStudents">
              <i class="bi bi-circle"></i><span>Add</span>
            </a>
          </li>
          <li>
            <a href="<?php echo getHostLink(SITE_NAME);?>/viewStudents">
              <i class="bi bi-circle"></i><span>Manage</span>
            </a>
          </li>
        </ul>
      </li><!-- End Components Nav -->

      <li class="nav-item">
        <a class="nav-link " href="<?php echo getHostLink(SITE_NAME);?>/rowcolumn">
          <i class="bi bi-grid-3x3"></i>
          <span>Dynamic Row and column</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link " href="<?php echo getHostLink(SITE_NAME);?>/figureWords">
          <i class="bi bi-grid"></i>
          <span>Figure to Words</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link " href="<?php echo getHostLink(SITE_NAME);?>/payments">
          <i class="bi bi-credit-card"></i>
          <span>Payment</span>
        </a>
      </li><!-- End Dashboard Nav -->
    </ul>

  </aside><!-- End Sidebar-->
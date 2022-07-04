<?php
require_once "./constants.php";
require_once "./customFunctions.php";
$pageName = "Login";

if (isset($_REQUEST['login_btn'])) {
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];

    $apiMethod = "POST";
    $apiURL = getHostLink("/".SITE_NAME."/api/user-login");
    $loginCred = array("Username" => $username, "Password" => $password);

    $loginData = json_decode(callAPI($apiMethod, $apiURL, $loginCred, false, null));

    $statusCode = $loginData->statusCode;

    if ($statusCode != 200) {
      $error = $loginData->error;
        $userNameErr = $error->Username;
        $passwordErr = $error->Password;
        $loginErr = $error->Login;
    } else {
        $data = $loginData->data;
        $_SESSION['PHP_PACKAGE_ADMIN'] = $data->Username;
        $_SESSION['PHP_PACKAGE_TOKEN'] = $data->Token;
        redirectPage("home");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

  <head>
  <title>PHP package :: <?php echo $pageName; ?></title>
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
      href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
      rel="stylesheet">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
  </head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block">PHP package</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                    <p class="text-center small">Username : Admin and Password : admin</p>
                  </div>

                  <form class="row g-3" id="login-form" method="POST">

                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="text" name="username" class="form-control" id="username">
                       </div>
                       <b class="text-danger"><?php echo $userNameErr; ?></b>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="password">
                    </div>
                    <b class="text-danger"><?php echo $passwordErr; ?></b>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit" name="login_btn">Login</button>
                    </div>
                  </form>
                  <div class="text-danger" style="font-weight: bold;">
                    <?php echo $loginErr; ?>
                  </div>
                </div>
              </div>

              <div class="credits">
                Designed by <a href="https://arweb.in">ArWeb</a>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

</body>

</html>
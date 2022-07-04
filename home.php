<?php
$pageName = "Dashboard";
require_once "./top.php";
?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./home">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
              <div class="card-body">
                <h3 class="card-title">Database configurations</h3>
                <ul>
                  <li><b>Database </b> : MySQL</li>
                  <li><b>Database name</b> : basic_ex</li>
                  <li><b>Connection type</b> : PDO (PHP Data Objects)</li>
                  <li><a href="exportDB.php" style="font-weight: bolder;">Export</a> the database</li>
                </ul>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <h3 class="card-title">Extra assets</h3>
                <ul>
                  <li>Email sending with / without attachment</li>
                  <li>Figure to words</li>
                  <li>Dynamic rows and columns</li>
                  <li>Token Authentication - Bearer token</li>
                  <li>JWT token</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
              <div class="card-body">
                <h3 class="card-title">CRUD application using CURL and REST API</h3>
                <ul>
                  <li>HTACCESS</li>
                  <li>Image size compressor</li>
                  <li>Generate QR code </li>
                  <li>Generate PDF </li>
                  <li>Generate dynamic image using in-built GD library</li>
                </ul>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <h3 class="card-title">Payment Gateway Integration</h3>
                <ul>
                  <li>Razorpay</li>                  
                </ul>
              </div>
            </div>
          </div>
        </div>
    </section>

</main><!-- End #main -->

<?php
require_once "./bottom.php";
?>
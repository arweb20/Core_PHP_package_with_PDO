<?php
require_once "./customFunctions.php";
$pageName = "Payments";
require_once "./top.php";
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1><?php echo $pageName; ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home">Home</a></li>
                <li class="breadcrumb-item"><?php echo $pageName; ?></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Payment</h5>
                        <b class="text-success" id="successs_msg"></b>
                        <b class="text-danger" id="error_msg"></b>

                        <form name="entry_form" id="entry_form">
                            <div class="row mb-3">
                                <label for="inputName" class="col-sm-2 col-form-label">Name <span class="text-danger"> *</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="reg_name" class="form-control" id="reg_name" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPrice" class="col-sm-2 col-form-label">Price <span class="text-danger"> *</span></label>
                                <div class="col-sm-10">
                                <input type="text" name="price" class="form-control" id="price" />
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                  <input type="button" name="razorpay_btn" id="razorpay_btn" class="btn btn-primary" value="Pay now with Razorpay" onclick="razorpay_payment()" />
                                </div>
                            </div>                      
                    </div>
                    <!-- /.row -->
                    </form>

                </div>
            </div>
        </div>
        </div>
    </section>

</main><!-- End #main -->

<?php
require_once "./bottom.php";
?>
<!-- Razorpay JavaScript -->
<script src="https://checkout.razorpay.com/v1/checkout.js?v=<?php echo time(); ?>"></script>

<script>
    function razorpay_payment() {
        var amount = $("#price").val();
        var options = {
            "key": "rzp_test_MIvWD607abvHjc",
            "amount": amount * 100,
            "currency": "INR",
            "name": "ArWeb",
            "description": "IT service company",
            "image": "https://arweb.in/assets/img/logo.png",
            "prefill": {'contact': '+91-9612603587', 'email': 'arwebcs@gmail.com'},
            "readonly": {'contact': true, 'email': true},
            "handler": function (response) {
                $("#successs_msg").text("Successfully paid. Payment Id : " + response.razorpay_payment_id);
            }
        };
        new Razorpay(options).open();
    }
</script>
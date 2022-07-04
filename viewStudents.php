<?php
$pageName = "View Data";
require_once "./top.php";
?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1><?php echo $pageName; ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active"><?php echo $pageName; ?></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $pageName; ?></h5>
                        <!-- Table with stripped rows -->

                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Course name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">About</th>
                                    <th scope="col">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
$apiMethod = "GET";
$apiURL = getHostLink("/" . SITE_NAME . "/api/users");
$regStatus = json_decode(callAPI($apiMethod, $apiURL, null, false, $headerParams));
$statusCode = $regStatus->statusCode;
if ($statusCode != 200) {
    ?>
                                    <tr>
                                        <td colspan="5" style="text-align:center;">
                                            No records found
                                        </td>
                                    </tr>
                                    <?php
} else {
    $details = $regStatus->data;
    foreach ($details as $detailsVal) {
        ?>
                                        <tr>
                                            <td><?php echo $detailsVal->full_name; ?></td>
                                            <td><?php echo $detailsVal->course_name; ?></td>
                                            <td><?php echo $detailsVal->email; ?></td>
                                            <td><?php echo nl2br($detailsVal->about); ?></td>
                                            <td>
                                                <a href="./student/<?php echo $detailsVal->user_id; ?>/edit">
                                                    <button class="btn btn-warning btn-xs">
                                                        EDIT
                                                    </button></a>
                                                <button type="button" class="btn btn-danger btn-xs" onclick="delete_record('<?php echo $detailsVal->user_id; ?>');">
                                                    DELETE</button>
                                                <a href="./idcard/<?php echo $detailsVal->user_id; ?>">
                                                    <button type="button" class="btn btn-info btn-xs">
                                                        ID</button></a>
                                                        <a href="./certificate/<?php echo $detailsVal->user_id; ?>">
                                                    <button type="button" class="btn btn-primary btn-xs">
                                                        CERTIFICATE</button></a>
                                            </td>
                                        </tr>
                                        <?php
}
}
?>
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->


                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- End #main -->

<?php
require_once "./bottom.php";
?>
<script type="text/javascript">
    function delete_record(record_id) {
        var token = "Bearer " + "<?php echo $_SESSION['PHP_PACKAGE_TOKEN']; ?>";
        var apiURL = "<?php echo getHostLink("/" . SITE_NAME . "/api/users"); ?>/" + record_id;

        /*var values = {record_id: record_id};
         var arr = {};
         arr["data_cred"] = JSON.stringify(values);*/

        $.ajax({
            type: "DELETE",
            url: apiURL,
            dataType: "json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', token)
                xhr.setRequestHeader('Accept', "application/json")
            },
            data: {},
            success: function (RetVal) {
                if (RetVal.statusCode == "200") {
                    window.location.href = "./viewStudents";
                } else {
                    alert(RetVal.message);
                }
            },
            error: function (jhr, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }
</script>
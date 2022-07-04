<?php
$pageName = "Dynamic row and column";
require_once "./top.php";
?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1><?php echo $pageName; ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./home">Home</a></li>
                <li class="breadcrumb-item active"><?php echo $pageName; ?></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="card-title"><b>ROWS AND COLUMNS</b></div>
                <?php
                $i = 0;
                $values = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");
                while ($i < count($values)) {
                    if ($i == 0 || $i % 3 == 0) {
                        ?>
                        <div class="row">
                            <?php
                        }
                        ?>
                        <div class="col-lg-4">
                            <?php
                            echo $values[$i];
                            ?>
                        </div><!--end of col-lg-4-->
                        <?php
                        $i++;
                        if (($i % 3 == 0) || ($i == count($values))) {
                            ?>
                        </div><!--end of row-->
                        <?php
                    }
                }
                ?>
                <div class="card-title"><b>TABLES</b></div>
                <table class="table table-bordered table-hover">
                    <?php
                    $j = 0;
                    $value = array("11", "12", "13", "14", "15", "16", "17", "18", "19", "20");
                    while ($j < count($value)) {
                        if ($j == 0 || $j % 3 == 0) {
                            ?>
                            <tr>
                                <?php
                            }
                            ?>
                            <td>
                                <?php
                                echo $value[$j];
                                ?>
                            </td><!--end of table cell-->
                            <?php
                            $j++;
                            if (($j % 3 == 0) || ($j == count($value))) {
                                ?>
                            </tr><!--end of table row-->
                            <?php
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
    </section>

</main><!-- End #main -->

<?php
require_once "./bottom.php";
?>
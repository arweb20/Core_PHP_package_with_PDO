<?php
require_once "./customFunctions.php";
$pageName = "Student Update";
require_once "./top.php";

if (isset($_REQUEST['studentid'])) {
    $studentID = $_REQUEST['studentid'];
    $statusCode = 201;
    if (isset($_REQUEST['save_btn'])) {
        $fullName = $_REQUEST['reg_name'];
        $courseName = $_REQUEST['course_name'];
        $email = $_REQUEST['reg_email'];
        $hemail = $_REQUEST['reg_hemail'];
        $website = $_REQUEST['reg_url'];
        $mobile = $_REQUEST['reg_mobile'];
        $hmobile = $_REQUEST['reg_hmobile'];
        $gender = $_REQUEST['reg_gender'];
        $about = $_REQUEST['reg_about'];
        $languages = $_REQUEST["languages"];

        if ($_FILES["reg_propic"]["size"] > 0) {
            $cfile = new CurlFile($_FILES["reg_propic"]["tmp_name"], $_FILES["reg_propic"]["type"], $_FILES["reg_propic"]["name"]);
        } else {
            $cfile = "";
        }

        $dataCred = array("RegName" => $fullName, "CourseName" => $courseName, "Gender" => $gender, "Email" => $email,
            "Mobile" => $mobile, "Website" => $website, "About" => $about, "Languages" => $languages,
             "HEmail"=> $hemail, "HMobile"=> $hmobile);

        $apiMethod = "POST";
        $apiURL = getHostLink("/" . SITE_NAME . "/api/users/".$studentID);
        $regCred = array("requestData" => json_encode($dataCred), "profileImage" => $cfile);

        $regStatus = json_decode(callAPI($apiMethod, $apiURL, $regCred, true, $headerParams));

        $statusCode = $regStatus->statusCode;

        if ($statusCode != 201) {
            if ($statusCode == 500) {
                $errorMessage = $regStatus->error;
            } else {
                $errors = $regStatus->errors;
                $fullNameErr = $errors->RegName;
                $courseNameErr = $errors->CourseName;
                $emailErr = $errors->Email;
                $websiteErr = $errors->Website;
                $mobileErr = $errors->Mobile;
                $genderErr = $errors->Gender;
                $aboutErr = $errors->About;
                $profileImageErr = $errors->ProfilePic;
                $errorMessage = $errors->Message;
            }
        } else {
            $successMessage = $regStatus->success;
        }
    }

    $rapiMethod = "GET";
    $rapiURL = getHostLink("/" . SITE_NAME . "/api/users/".$studentID);
    $returnData = json_decode(callAPI($rapiMethod, $rapiURL,null,false, $headerParams));
    $rstatusCode = $returnData->statusCode;
    
    if ($rstatusCode != 200) {
        $errorMeassage =  $returnData->error;
    } else {
        $infoDetails = $returnData->data;
        foreach ($infoDetails as $infoDetailsVal) {
            $fFullName = $infoDetailsVal->full_name;
            $fgender = $infoDetailsVal->gender;
            $femail = $infoDetailsVal->email;
            $flanguages = array_map('trim', explode(", ", $infoDetailsVal->languages));
            $fmobile = $infoDetailsVal->mobile;
            $fwebsite = $infoDetailsVal->website;
            $fabout = $infoDetailsVal->about;
            $fcourseName = $infoDetailsVal->course_name;
        }
    }

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
                            <h5 class="card-title">Registration</h5>
                            <span class="card-title text-success">
                                <?php echo $successMessage; ?>
                            </span>
                            <span class="card-title text-danger">
                                <?php echo $errorMessage; ?>
                            </span>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <label for="inputName" class="col-sm-2 col-form-label">Name <span class="text-danger"> *</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="reg_name" name="reg_name"
                                               value="<?php
                                               if ($statusCode == 201) {
                                                echo $fFullName;
                                            } else {
                                                echo $fullName;
                                            }
                                               ?>"/>
                                        <b class="text-danger"><?php echo $fullNameErr; ?></b>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCourseName" class="col-sm-2 col-form-label">Course name <span class="text-danger"> *</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="course_name" name="course_name"
                                               value="<?php
                                               if ($statusCode == 201) {
                                                   echo $fcourseName;
                                               } else {
                                                   echo $courseName;
                                               }
                                               ?>"/>
                                        <b class="text-danger"><?php echo $courseNameErr; ?></b>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Email <span class="text-danger"> *</span></label>
                                    <div class="col-sm-10">
                                    <input type="hidden" name="reg_hemail" class="form-control" id="reg_hemail" value="<?php echo $femail; ?>" />
                                        <input type="text" class="form-control" id="reg_email" name="reg_email"
                                               value="<?php
                                               if ($statusCode == 201) {
                                                   echo $femail;
                                               } else {
                                                   echo $email;
                                               }
                                               ?>"/>
                                        <b class="text-danger"><?php echo $emailErr; ?></b>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputUrl" class="col-sm-2 col-form-label">Website </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="reg_url" name="reg_url"
                                               value="<?php
                                               if ($statusCode == 201) {
                                                   echo $fwebsite;
                                               } else {
                                                   echo $website;
                                               }
                                               ?>"/>
                                        <b class="text-danger"><?php echo $websiteErr; ?></b>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputMobile" class="col-sm-2 col-form-label">Mobile <span class="text-danger"> *</span></label>
                                    <div class="col-sm-10">
                                    <input type="hidden" name="reg_hmobile" class="form-control" id="reg_hmobile" value="<?php echo $fmobile; ?>" />
                                        <input type="text" class="form-control" id="reg_mobile" name="reg_mobile"
                                               value="<?php
                                               if ($statusCode == 201) {
                                                   echo $fmobile;
                                               } else {
                                                   echo $mobile;
                                               }
                                               ?>"/>
                                        <b class="text-danger"><?php echo $mobileErr; ?></b>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputGender" class="col-sm-2 col-form-label">Gender <span class="text-danger"> *</span></label>
                                    <div class="col-sm-10">
                                        <select name="reg_gender" class="form-control">
                                            <option value="">Select Gender</option>
                                            <?php
                                            $genderArr = array("Male", "Female");
                                            foreach ($genderArr as $genderArrVal) {
                                                ?>
                                                <option value="<?php echo $genderArrVal; ?>"
                                                <?php
                                                if ($statusCode == 201) {
                                                    if (isset($fgender) && $fgender == $genderArrVal) {
                                                        echo "selected=selected";
                                                    }
                                                } else {
                                                    if (isset($gender) && $gender == $genderArrVal) {
                                                        echo "selected=selected";
                                                    }
                                                }
                                                ?>>
                                                            <?php echo $genderArrVal; ?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <b class="text-danger"><?php echo $genderErr; ?></b>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputLanguages" class="col-sm-2 col-form-label">Languages</label>
                                    <div class="col-sm-10">
                                        <select name="languages[]" id="languages" class="form-control" multiple>
                                            <option value="">Select Languages</option>
                                            <?php
                                            $langArr = array("Bengali", "English", "Hindi");
                                            foreach ($langArr as $langArrVal) {
                                                ?>
                                                <option value="<?php echo $langArrVal; ?>"
                                                <?php
                                                if ($statusCode == 201) {
                                                    if (isset($flanguages) && is_array($flanguages) && in_array($langArrVal, $flanguages)) {
                                                        echo 'selected="selected"';
                                                    }
                                                } else {
                                                    if (isset($languages) && is_array($languages) && in_array($langArrVal, $languages)) {
                                                        echo 'selected="selected"';
                                                    }
                                                }
                                                ?>>
                                                            <?php echo $langArrVal; ?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputAbout" class="col-sm-2 col-form-label">About <span class="text-danger"> *</span></label>
                                    <div class="col-sm-10">
                                        <textarea name="reg_about" class="form-control" id="about" cols="30" rows="10">
                                            <?php
                                            if ($statusCode == 201) {
                                                echo $fabout;
                                            } else {
                                                echo $about;
                                            }
                                            ?>
                                        </textarea>
                                        <b class="text-danger"><?php echo $aboutErr; ?></b>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPic" class="col-sm-2 col-form-label">Profile picture <span class="text-danger"> </span></label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control" id="reg_propic" name="reg_propic" />
                                        <b class="text-danger"><?php echo $profileImageErr; ?></b>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary" id="save_btn" name="save_btn">
                                            Submit </button>
                                    </div>
                                </div>

                            </form><!-- End General Form Elements -->

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->
    <?php
}
require_once "./bottom.php";
?>
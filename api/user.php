<?php

require_once "../vendor/autoload.php";
require_once '../constants.php';
require_once '../customFunctions.php';
require_once "./lib/jwtUtils.php";
require_once './config/header.php';

use Api\Models\User as UserModels;

$userObj = new UserModels();

if ((!empty($bearerToken)) && ($bearerToken != null) && ($bearerToken != "")) {
    if ($validToken) {
        $filesAllowed = array('jpg', 'jpeg', 'png', 'JPEG', 'JPG', 'PNG');

        if (!is_dir("files")) {
            mkdir("files", 0777);
        }

        $mediaPath = "./files/";

        if (array_key_exists("userid", $_GET)) {

            $userID = $_GET['userid'];
            $requestParams = array("UserID" => $userID);
            $details = $userObj->getSelectedUser(json_encode($requestParams));
            $getCountRecord = $details['Record'];
            $getDetails = $details['Data'];

            if ($_SERVER['REQUEST_METHOD'] === "GET") {
                if ($getCountRecord > 0) {
                    $response['statusCode'] = 200;
                    $response['message'] = "Ok";
                    $response['data'] = $getDetails;
                } elseif ($getCountRecord == 0) {
                    $response['statusCode'] = 205;
                    $response['message'] = "No Content";
                    $response['error'] = "No record found";
                } else {
                    $response['statusCode'] = 500;
                    $response['message'] = "Internal Server Error";
                    $response['error'] = "Server error";
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === "DELETE") {
                if ($getCountRecord > 0) {
                    $deleteStatus = $userObj->deleteUser(json_encode($requestParams));
                    if ($deleteStatus > 0) {
                        $response['statusCode'] = 200;
                        $response['message'] = "Ok";
                        $response['success'] = "Successfully deleted";
                    } else {
                        $response['statusCode'] = 500;
                        $response['message'] = "Internal Server Error";
                        $response['error'] = "Server error";
                    }
                } elseif ($getCountRecord == 0) {
                    $response['statusCode'] = 205;
                    $response['message'] = "No Content";
                    $response['error'] = "No record found to delete";
                } else {
                    $response['statusCode'] = 500;
                    $response['message'] = "Internal Server Error";
                    $response['error'] = "Server error";
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === "POST") {
                if ($getCountRecord > 0) {
                    $requestData = json_decode(trim($_REQUEST['requestData']));
                    $files = $_FILES['profileImage'];

                    $regName = removeHTMLEntities(trim($requestData->RegName));
                    $courseName = removeHTMLEntities(trim($requestData->CourseName));
                    $gender = removeHTMLEntities(trim($requestData->Gender));
                    $email = removeHTMLEntities(trim($requestData->Email));
                    $hemail = removeHTMLEntities(trim($requestData->HEmail));
                    $mobile = removeHTMLEntities(trim($requestData->Mobile));
                    $hmobile = removeHTMLEntities(trim($requestData->HMobile));
                    $website = removeHTMLEntities(trim($requestData->Website));
                    $about = removeHTMLEntities(trim($requestData->About));
                    $languages = $requestData->Languages;

                    if (empty($regName)) {
                        $regNameErr = "Required";
                    } else {
                        if (!textPatternValidation($regName, "a-zA-Z ")) {
                            $regNameErr = "Only letters and white space allowed";
                        }
                    }
                    if (empty($courseName)) {
                        $courseNameErr = "Required";
                    } else {
                        if (!textPatternValidation($courseName, "a-zA-Z ")) {
                            $courseNameErr = "Only letters and white space allowed";
                        }
                    }
                    if ($languages == null) {
                        $languages = "";
                    } else {
                        $languages = implode(", ", $languages);
                    }
                    if (empty($about)) {
                        $aboutErr = "Required";
                    }
                    if (empty($mobile)) {
                        $mobileErr = "Required";
                    } else {
                        if (!textPatternValidation($mobile, "0-9")) {
                            $mobileErr = "Only numeric allowed";
                        } else {
                            if (strlen($mobile) != 10) {
                                $mobileErr = "Mobile no. must be 10 digits";
                            } else {
                                $mobileCred = array("mobile_no" => $mobile, "hmobile_no" => $hmobile);
                                $retVal = $userObj->getDupMobile(json_encode($mobileCred));
                                $getCountRecord = $retVal['Record'];
                                if ($getCountRecord > 0) {
                                    $mobileErr = "Duplicate number. Try again";
                                }
                            }
                        }
                    }
                    if (empty($email)) {
                        $email = "";
                    } else {
                        if (!emailValidation($email)) {
                            $emailErr = "Invalid Email";
                        } else {
                            $emailCred = array("email_id" => $email, "hemail_id" => $hemail);
                            $retVal = $userObj->getDupEmail(json_encode($emailCred));
                            $getCountRecord = $retVal['Record'];
                            if ($getCountRecord > 0) {
                                $emailErr = "Duplicate email. Try again";
                            }
                        }
                    }
                    if (empty($website)) {
                        $website = "";
                    } else {
                        if (!urlValidation($website)) {
                            $websiteErr = "Invalid URL";
                        }
                    }

                    if ($files['size'] > 0) {
                        $fileName = basename($files['name']);
                        $fileSize = $files['size']; // File size in "BYTES"
                        $fileType = $files['type'];
                        $fileTmpName = $files['tmp_name'];
                        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        if (!(in_array($fileExtension, $filesAllowed))) {
                            $profileImageErr = "Upload JPEG,JPG,PNG files";
                        } else {
                            if ($fileSize > MAX_SIZE) {
                                $profileImageErr = "Upload less than or equal to 1 MB";
                            } else {
                                $filename = $userID . "." . $fileExtension;
                                $sourceProperties = getimagesize($fileTmpName);
                                $sourceImageWidth = $sourceProperties[0];
                                $sourceImageHeight = $sourceProperties[1];
                                $fileLocation = $mediaPath . $filename;
                                if (($sourceImageWidth > 149) && ($sourceImageHeight > 149)) {
                                    $src = imagecreatefromjpeg($fileTmpName);
                                    $imageLayer = resizeImage($src, $sourceImageWidth, $sourceImageHeight, 150, 150);
                                    if (imagejpeg($imageLayer, $fileLocation)) {
                                        $profileImage = base64_encode(file_get_contents($fileLocation));
                                    } else {
                                        unlink($fileLocation);
                                        $profileImageErr = "File cannot be inserted into folder";
                                    }
                                } else {
                                    $profileImageErr = "File size must be greater than or equal to 150*150";
                                }
                            }
                        }
                    } else {
                        foreach ($getDetails as $getDetailsVal) {
                            $profileImage = $getDetailsVal['profile_pic'];
                            $fileType = $getDetailsVal['profile_pic_type'];
                        }
                    }

                   /*                 * *************************** QR GENERATOR **************************** */

                $qrText = "User ID : $userID, Email ID : $email";
                $qrCodeImage = $mediaPath . rand(0, 999999) . ".png";
                $ecc = "L";
                $pixelSize = 5;
                $frameSize = 5;
                QRcode::png($qrText, $qrCodeImage, $ecc, $pixelSize, $frameSize);

                /*                 * *************************** QR GENERATOR **************************** */

                /*                 * *************************** PDF GENERATOR **************************** */
                $output = "";
                $output .= "<div style='width:200px;height:250px;'>";
                $output .= "<div style='width:100%;height:50px;background-color:#1468FA;border-radius:5px 5px 0px 0px;padding:5px;'>";
                $output .= "<center><img src='data:$fileType;base64,$profileImage' width='50' height='50' style='border-radius:100%;margin-top:25px;' /></center></div>";
                $output .= "<div style='width:100%;height:200px;background-color:#F6EDD9;border-radius:0px 0px 5px 5px;padding:5px;padding-top:25px;'>";
                $output .= "<center><b>$regName</b><img src='$qrCodeImage' width='100' height='100' /><br/><br/><b>Mobile</b>: $mobile<br/>";
                $output .= "</center></div></div>";
                $mpdf = new \Mpdf\Mpdf();
                $mpdf->WriteHTML($output);
                $pdfFileName = $userID . '.pdf';
                $mpdf->Output($mediaPath . $pdfFileName);
                $profileIDCard = base64_encode(file_get_contents($mediaPath . $pdfFileName));

                /*                 * *************************** PDF GENERATOR **************************** */

                /*                 * *************************** DYNAMIC CERTIFICATE GENERATOR **************************** */

                $font = dirname(__FILE__) . "/lib/fonts/times_new_roman_bold.ttf";
                $certificateImagePath = "./lib/certificate.jpg";
                $certificateImage = imagecreatefromjpeg($certificateImagePath);
                $color = imagecolorallocate($certificateImage, 19, 21, 22);
                imagettftext($certificateImage, 40, 0, 500, 490, $color, $font, $regName);
                imagettftext($certificateImage, 40, 0, 400, 600, $color, $font, $courseName);
                $certificateFileName = $userID . "_certificate.jpg";
                imagejpeg($certificateImage, $mediaPath . $certificateFileName);
                imagedestroy($certificateImage);
                /*$courseCertificate = base64_encode(file_get_contents($mediaPath . $cerificateFileName));
                $certificateFileType = mime_content_type($mediaPath . $cerificateFileName);*/

                /*                 * *************************** DYNAMIC CERTIFICATE GENERATOR **************************** */

                /*                 * *************************** ADD IMAGE **************************** */
                $qr = imagecreatefrompng($qrCodeImage);
                $certificate = imagecreatefromjpeg($mediaPath . $certificateFileName);
                imagecopy($certificate, $qr, 0,0,0,0,200,200);
                $certificateFileNameWater = $userID . "_certificate_water.jpg";
                imagejpeg($certificate, $mediaPath . $certificateFileNameWater);
                $courseCertificate = base64_encode(file_get_contents($mediaPath . $certificateFileNameWater));
                $certificateFileType = mime_content_type($mediaPath . $certificateFileNameWater);

                /*                 * *************************** ADD IMAGE **************************** */

                    emptyDirectory($mediaPath);

                    if (($regNameErr == "") && ($courseNameErr == "") && ($genderErr == "") && ($aboutErr == "") && ($mobileErr == "") && ($emailErr == "") && ($websiteErr == "") && ($profileImageErr == "")) {
                        $updateCredentials = array("UserID" => $userID, "CourseName" => $courseName, "RegName" => $regName, "Gender" => $gender, "Email" => $email,
                            "Mobile" => $mobile, "Website" => $website, "About" => $about, "Languages" => $languages, "ProfileImage" => $profileImage,
                            "ProfileImageType" => $fileType, "ProfileID" => $profileIDCard, "CertificateImageType" => $certificateFileType, "CertificateImage" => $courseCertificate);
                        $updateStatus = $userObj->updateUser(json_encode($updateCredentials));

                        if ($updateStatus > 0) {
                            $response['statusCode'] = 201;
                            $response['message'] = "Created";
                            $response['success'] = "Successfully updated";
                        } else {
                            $response['statusCode'] = 500;
                            $response['message'] = "Internal Server Error";
                            $response['error'] = "Server error";
                        }
                    } else {
                        $dataErrs = array("RegName" => $regNameErr, "CourseName" => $courseNameErr, "Gender" => $genderErr, "Email" => $emailErr,
                            "ProfilePic" => $profileImageErr, "Mobile" => $mobileErr, "Website" => $websiteErr,
                            "About" => $aboutErr, "Message" => "Recorrect errors");
                        $response['statusCode'] = 400;
                        $response['message'] = "Bad Request";
                        $response['errors'] = $dataErrs;
                    }
                } elseif ($getCountRecord == 0) {
                    $response['statusCode'] = 205;
                    $response['message'] = "No Content";
                    $response['error'] = "No record found to update";
                } else {
                    $response['statusCode'] = 500;
                    $response['message'] = "Internal Server Error";
                    $response['error'] = "Server error";
                }
            } else {
                $response['statusCode'] = 405;
                $response['message'] = "Method not allowed";
                $response['error'] = "HTTP method not allowed";
            }
        } elseif (array_key_exists("limit", $_GET)) {
            if ($_SERVER['REQUEST_METHOD'] === "POST") {
                $requestData = json_decode(file_get_contents("php://input"));

                $limit = $requestData->Limit;
                if ($limit == null) {
                    $limitErr = "Required";
                } else {
                    $limitFrom = trim($limit[0]);
                    $limitTo = trim($limit[1]);
                    if ($limitFrom != "" && $limitTo == "") {
                        $limitTo = $limitFrom;
                    } else {
                        if ($limitFrom > $limitTo) {
                            $limitErr = "FROM must be less than or equals to TO";
                        }
                    }
                }
                if ($limitErr == "") {
                    $credential = array("StartIndex" => $limitFrom, "RecordsToBeShown" => $limitTo);
                    $details = $userObj->getAllUsers(json_encode($credential));
                    $getCountRecord = $details['Record'];
                    $getDetails = $details['Data'];
                    if ($getCountRecord > 0) {
                        $response['statusCode'] = 200;
                        $response['message'] = "Ok";
                        $response['records'] = $getCountRecord;
                        $response['data'] = $getDetails;
                    } elseif ($getCountRecord == 0) {
                        $response['statusCode'] = 205;
                        $response['message'] = "No Content";
                        $response['error'] = "No record found";
                    } else {
                        $response['statusCode'] = 500;
                        $response['message'] = "Internal Server Error";
                        $response['error'] = "Server error";
                    }
                } else {
                    $dataErrs = array("Limit" => $limitErr, "Message" => "Recorrect errors");
                    $response['statusCode'] = 400;
                    $response['message'] = "Bad Request";
                    $response['errors'] = $dataErrs;
                }
            } else {
                $response['statusCode'] = 405;
                $response['message'] = "Method not allowed";
                $response['error'] = "HTTP method not allowed";
            }
        } elseif (empty($_GET)) {
            if ($_SERVER['REQUEST_METHOD'] === "GET") {
                $details = $userObj->getAllUsers();
                $getCountRecord = $details['Record'];
                $getDetails = $details['Data'];
                if ($getCountRecord > 0) {
                    $response['statusCode'] = 200;
                    $response['message'] = "Ok";
                    $response['records'] = $getCountRecord;
                    $response['data'] = $getDetails;
                } elseif ($getCountRecord == 0) {
                    $response['statusCode'] = 205;
                    $response['message'] = "No Content";
                    $response['error'] = "No record found";
                } else {
                    $response['statusCode'] = 500;
                    $response['message'] = "Internal Server Error";
                    $response['error'] = "Server error";
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === "POST") {
                $requestData = json_decode(trim($_REQUEST['requestData']));
                $files = $_FILES['profileImage'];

                $regName = removeHTMLEntities(trim($requestData->RegName));
                $courseName = removeHTMLEntities(trim($requestData->CourseName));
                $gender = removeHTMLEntities(trim($requestData->Gender));
                $email = removeHTMLEntities(trim($requestData->Email));
                $mobile = removeHTMLEntities(trim($requestData->Mobile));
                $website = removeHTMLEntities(trim($requestData->Website));
                $about = removeHTMLEntities(trim($requestData->About));
                $languages = $requestData->Languages;

                $userID = date("YmdHis");

                if (empty($regName)) {
                    $regNameErr = "Required";
                } else {
                    if (!textPatternValidation($regName, "a-zA-Z ")) {
                        $regNameErr = "Only letters and white space allowed";
                    }
                }
                if (empty($courseName)) {
                    $courseNameErr = "Required";
                } else {
                    if (!textPatternValidation($courseName, "a-zA-Z ")) {
                        $courseNameErr = "Only letters and white space allowed";
                    }
                }
                if ($languages == null) {
                    $languages = "";
                } else {
                    $languages = implode(", ", $languages);
                }
                if (empty($gender)) {
                    $genderErr = "Required";
                }
                if (empty($about)) {
                    $aboutErr = "Required";
                }
                if (empty($mobile)) {
                    $mobileErr = "Required";
                } else {
                    if (!textPatternValidation($mobile, "0-9")) {
                        $mobileErr = "Only numeric allowed";
                    } else {
                        if (strlen($mobile) != 10) {
                            $mobileErr = "Mobile no. must be 10 digits";
                        } else {
                            $mobileCred = array("mobile_no" => $mobile, "hmobile_no" => "");
                            $retVal = $userObj->getDupMobile(json_encode($mobileCred));
                            $getCountRecord = $retVal['Record'];
                            if ($getCountRecord > 0) {
                                $mobileErr = "Duplicate number. Try again";
                            }
                        }
                    }
                }
                if (empty($email)) {
                    $emailErr = "Required";
                } else {
                    if (!emailValidation($email)) {
                        $emailErr = "Invalid Email";
                    } else {
                        $emailCred = array("email_id" => $email, "hemail_id" => "");
                        $retVal = $userObj->getDupEmail(json_encode($emailCred));
                        $getCountRecord = $retVal['Record'];
                        if ($getCountRecord > 0) {
                            $emailErr = "Duplicate email. Try again";
                        }
                    }
                }
                if (empty($website)) {
                    $website = "";
                } else {
                    if (!urlValidation($website)) {
                        $websiteErr = "Invalid URL";
                    }
                }

                if ($files['size'] > 0) {
                    $fileName = basename($files['name']);
                    $fileSize = $files['size']; // File size in "BYTES"
                    $fileType = $files['type'];
                    $fileTmpName = $files['tmp_name'];
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    if (!(in_array($fileExtension, $filesAllowed))) {
                        $profileImageErr = "Upload JPEG,JPG,PNG files";
                    } else {
                        if ($fileSize > MAX_SIZE) {
                            $profileImageErr = "Upload less than or equal to 1 MB";
                        } else {
                            $filename = $userID . "." . $fileExtension;
                            $sourceProperties = getimagesize($fileTmpName);
                            $sourceImageWidth = $sourceProperties[0];
                            $sourceImageHeight = $sourceProperties[1];
                            $fileLocation = $mediaPath . $filename;
                            if (($sourceImageWidth > 149) && ($sourceImageHeight > 149)) {
                                $src = imagecreatefromjpeg($fileTmpName);
                                $imageLayer = resizeImage($src, $sourceImageWidth, $sourceImageHeight, 150, 150);
                                if (imagejpeg($imageLayer, $fileLocation)) {
                                    $profileImage = base64_encode(file_get_contents($fileLocation));
                                } else {
                                    unlink($fileLocation);
                                    $profileImageErr = "File cannot be inserted into folder";
                                }
                            } else {
                                $profileImageErr = "File size must be greater than or equal to 150*150";
                            }
                        }
                    }
                } else {
                    $profileImageErr = "Insert file";
                }

                /*                 * *************************** QR GENERATOR **************************** */

                $qrText = "User ID : $userID, Email ID : $email";
                $qrCodeImage = $mediaPath . rand(0, 999999) . ".png";
                $ecc = "L";
                $pixelSize = 5;
                $frameSize = 5;
                QRcode::png($qrText, $qrCodeImage, $ecc, $pixelSize, $frameSize);

                /*                 * *************************** QR GENERATOR **************************** */

                /*                 * *************************** PDF GENERATOR **************************** */
                $output = "";
                $output .= "<div style='width:200px;height:250px;'>";
                $output .= "<div style='width:100%;height:50px;background-color:#1468FA;border-radius:5px 5px 0px 0px;padding:5px;'>";
                $output .= "<center><img src='data:$fileType;base64,$profileImage' width='50' height='50' style='border-radius:100%;margin-top:25px;' /></center></div>";
                $output .= "<div style='width:100%;height:200px;background-color:#F6EDD9;border-radius:0px 0px 5px 5px;padding:5px;padding-top:25px;'>";
                $output .= "<center><b>$regName</b><img src='$qrCodeImage' width='100' height='100' /><br/><br/><b>Mobile</b>: $mobile<br/>";
                $output .= "</center></div></div>";
                $mpdf = new \Mpdf\Mpdf();
                $mpdf->WriteHTML($output);
                $pdfFileName = $userID . '.pdf';
                $mpdf->Output($mediaPath . $pdfFileName);
                $profileIDCard = base64_encode(file_get_contents($mediaPath . $pdfFileName));

                /*                 * *************************** PDF GENERATOR **************************** */

                /*                 * *************************** DYNAMIC CERTIFICATE GENERATOR **************************** */

                $font = dirname(__FILE__) . "/lib/fonts/times_new_roman_bold.ttf";
                $certificateImagePath = "./lib/certificate.jpg";
                $certificateImage = imagecreatefromjpeg($certificateImagePath);
                $color = imagecolorallocate($certificateImage, 19, 21, 22);
                imagettftext($certificateImage, 40, 0, 500, 490, $color, $font, $regName);
                imagettftext($certificateImage, 40, 0, 400, 600, $color, $font, $courseName);
                $certificateFileName = $userID . "_certificate.jpg";
                imagejpeg($certificateImage, $mediaPath . $certificateFileName);
                imagedestroy($certificateImage);
                /*$courseCertificate = base64_encode(file_get_contents($mediaPath . $cerificateFileName));
                $certificateFileType = mime_content_type($mediaPath . $cerificateFileName);*/

                /*                 * *************************** DYNAMIC CERTIFICATE GENERATOR **************************** */

                /*                 * *************************** ADD IMAGE **************************** */
                $qr = imagecreatefrompng($qrCodeImage);
                $certificate = imagecreatefromjpeg($mediaPath . $certificateFileName);
                imagecopy($certificate, $qr, 0,0,0,0,200,200);
                $certificateFileNameWater = $userID . "_certificate_water.jpg";
                imagejpeg($certificate, $mediaPath . $certificateFileNameWater);
                $courseCertificate = base64_encode(file_get_contents($mediaPath . $certificateFileNameWater));
                $certificateFileType = mime_content_type($mediaPath . $certificateFileNameWater);

                /*                 * *************************** ADD IMAGE **************************** */
                emptyDirectory($mediaPath);

                if (($regNameErr == "") && ($courseNameErr == "") && ($genderErr == "") && ($aboutErr == "") && ($mobileErr == "") && ($emailErr == "") && ($websiteErr == "") && ($profileImageErr == "")) {
                    $insertCredentials = array("UserID" => $userID, "CourseName" => $courseName, "RegName" => $regName, "Gender" => $gender, "Email" => $email,
                        "Mobile" => $mobile, "Website" => $website, "About" => $about, "Languages" => $languages, "ProfileImage" => $profileImage,
                        "ProfileImageType" => $fileType, "ProfileID" => $profileIDCard, "CertificateImageType" => $certificateFileType, "CertificateImage" => $courseCertificate);
                    $insertStatus = $userObj->createUser(json_encode($insertCredentials));

                    if ($insertStatus > 0) {
                        $emailMsg = "Please find the certificate";
                        $subject = "Certificate : " . $courseName;
                        $certificateAttach = array("EncodedString" => $courseCertificate, "FileName" => $cerificateFileName);
                        //emailSending("ArWeb", "tutorcode992@gmail.com", $email, $subject, $emailMsg, $certificateAttach);
                        $response['statusCode'] = 201;
                        $response['message'] = "Created";
                        $response['success'] = "Successfully inserted";
                    } else {
                        $response['statusCode'] = 500;
                        $response['message'] = "Server error";
                        $response['error'] = "Failed to insert";
                    }
                } else {
                    $dataErrs = array("RegName" => $regNameErr, "CourseName" => $courseNameErr, "Gender" => $genderErr, "Email" => $emailErr,
                        "ProfilePic" => $profileImageErr, "Mobile" => $mobileErr, "Website" => $websiteErr,
                        "About" => $aboutErr, "Message" => "Recorrect errors");
                    $response['statusCode'] = 400;
                    $response['message'] = "Bad Request";
                    $response['errors'] = $dataErrs;
                }
            } else {
                $response['statusCode'] = 405;
                $response['message'] = "Method not allowed";
                $response['error'] = "HTTP method not allowed";
            }
        } else {
            $response['statusCode'] = 403;
            $response['message'] = "Forbidden";
            $response['error'] = "Missing URL";
        }
    } else {
        $response['statusCode'] = 401;
        $response['message'] = "Unauthorized";
        $response['error'] = "Access denied";
    }
} else {
    $response['statusCode'] = 422;
    $response['message'] = "Unprocessable entity";
    $response['error'] = "Please provide bearer token";
}

echo json_encode($response, JSON_PRETTY_PRINT);

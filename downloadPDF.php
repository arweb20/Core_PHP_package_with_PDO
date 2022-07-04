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

if (isset($_REQUEST['studentid'])) {
    $studentID = $_REQUEST['studentid'];
    $apiMethod = "GET";
    $apiURL = getHostLink("/" . SITE_NAME . "/api/users/" . $studentID);
    $regStatus = json_decode(callAPI($apiMethod, $apiURL, null, false, $headerParams));

    $statusCode = $regStatus->statusCode;
    
    if ($statusCode != 200) {
        $errorMessage = $regStatus->error;
    } else {
        $infoDetail = $regStatus->data;
        foreach ($infoDetail as $infoDetailVal) {
            $icard_pdf = $infoDetailVal->id_card;
        }
       $decoded = base64_decode($icard_pdf);
        $file = $studentID . '.pdf';
        file_put_contents($file, $decoded);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        unlink($file);
        exit;
    }
}

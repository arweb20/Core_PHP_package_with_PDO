<?php
error_reporting(1);
session_start();
ob_start();
date_default_timezone_set("Asia/Kolkata");

/* *************************************** NORMAL FUNCTIONS *************************************** */

/* *************************************** GET SITE URL *************************************** */

function getHostLink($folderPath = "")
{
    $link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/" . ltrim($folderPath, "/");
    return rtrim($link, "/");
}

/* *************************************** GET SITE URL *************************************** */

/* *************************************** REDIRECT PAGE *************************************** */
function redirectPage($pageName)
{
    $redirectScript = "";
    $redirectScript .= "<script>";
    $redirectScript .= 'window.location.href = \''.$pageName.'\'';
    $redirectScript .= "</script>";
    echo $redirectScript;
}

/* *************************************** REDIRECT PAGE *************************************** */

/* *************************************** CALL API *************************************** */

function callAPI($apiMethod = "GET", $apiUrl = "", $data = null, $files = true, $headers = null)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $apiUrl);
    if($headers == null){
        curl_setopt($curl, CURLOPT_HTTPHEADER, false);
    }else{
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0");
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    switch ($apiMethod) {
        case "GET":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            break;
        case "POST":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POST, true);
            if ($files == true) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            } else {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case "DELETE":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
    }
    $curl_response = curl_exec($curl);
    curl_close($curl);
    return $curl_response;
}

/* *************************************** CALL API *************************************** */

/* ********************************** IMAGE RESIZE ********************************** */

function resizeImage($resourceType = null, $image_width = "", $image_height = "", $resizeWidth = "", $resizeHeight = "")
{
    $imageLayer = imagecreatetruecolor($resizeWidth, $resizeHeight);
    imagecopyresampled($imageLayer, $resourceType, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $image_width, $image_height);
    return $imageLayer;
}

/* ********************************** IMAGE RESIZE ********************************** */

/* ***************************** ENCRYPTION AND DECRYPTION ***************************** */

function encryptDecrypt($action = "", $string = "", $secretKey = "", $secretIV = "")
{
    $output = false;
    $encryptMethod = "AES-256-CBC";
    $key = hash('sha256', $secretKey);
    $iv = substr(hash('sha256', $secretIV), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encryptMethod, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encryptMethod, $key, 0, $iv);
    } else {
        $output = false;
    }
    return $output;
}

/* ***************************** ENCRYPTION AND DECRYPTION ***************************** */

/* ***************************** EMAIL SENDING ***************************** */

function emailSending($senderName = "", $senderEmail = "", $recipientEmail = "", $subject = "", $msgContent = "", $attachment = null)
{
    $separator = md5(time());
    $eol = "\r\n";
    $headers = "From: " . $senderName . " <" . $senderEmail . ">" . $eol;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
    $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
    $headers .= "This is a MIME encoded message." . $eol;
    $body = "--" . $separator . $eol;
    $body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
    $body .= "Content-Transfer-Encoding: 8bit" . $eol;
    $body .= $msgContent . $eol;
    $body .= "--" . $separator . $eol;
    if ($attachment != null) {
        $encodedString = $attachment['EncodedString'];
        $filename = $attachment['FileName'];
        $content = $encodedString;
        $body .= "Content-Type:application/octet-stream; name=\"" . $filename . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol;
        $body .= $content . $eol;
        $body .= "--" . $separator . "--";
    }
    if (mail($recipientEmail, $subject, $body, $headers)) {
        $sendMail = true;
    } else {
        $sendMail = false;
    }
    return $sendMail;
}

/* ***************************** EMAIL SENDING ***************************** */

/* ***************************** GET IP ADDRESS ***************************** */

function getClientIP()
{
    $localIP = getHostByName(getHostName());
    return $localIP;
}

/* ***************************** GET IP ADDRESS ***************************** */

/* ***************************** DELETE A FILE ***************************** */

function deleteFile($filePath = "")
{
    $fileDeleted = "";
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            $fileDeleted = true;
        } else {
            $fileDeleted = -1;
        }
    } else {
        $fileDeleted = false;
    }
    return $fileDeleted;
}

/* ***************************** DELETE A FILE ***************************** */

/* ***************************** EMPTY A DIRECTORY ***************************** */

function emptyDirectory($directoryPath = "")
{
    $emptyDirectory = false;
    if (array_map('unlink', glob("$directoryPath/*.*"))) {
        $emptyDirectory = true;
    } else {
        $emptyDirectory = false;
    }
    return $emptyDirectory;
}

/* ***************************** EMPTY A DIRECTORY ***************************** */

/* ***************************** REMOVE HTML ENTITIES ***************************** */

function removeHTMLEntities($input = "")
{
    $output = htmlspecialchars($input);
    $output = htmlentities($output);
    return $output;
}

/* ***************************** REMOVE HTML ENTITIES ***************************** */

/* *************************************** NORMAL FUNCTIONS ********************************* */

/* *********************************** VALIDATION FUNCTIONS *********************************** */

/* *********************************** EMAIL VALIDATION *********************************** */

function emailValidation($email = "")
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $output = true;
    } else {
        $output = false;
    }
    return $output;
}

/* *********************************** EMAIL VALIDATION *********************************** */

/* *********************************** URL VALIDATION *********************************** */

function urlValidation($website = "")
{
    if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $website)) {
        $output = true;
    } else {
        $output = false;
    }
    return $output;
}

/* *********************************** URL VALIDATION *********************************** */

/* ******************************** TEXT PATTERN VALIDATION ******************************** */

function textPatternValidation($text = "", $pattern = "a-zA-Z0-9")
{
    if (preg_match("/^[$pattern]*$/", $text)) {
        $output = true;
    } else {
        $output = false;
    }
    return $output;
}

/* ******************************** TEXT PATTERN VALIDATION ******************************** */

/* *********************************** VALIDATION FUNCTIONS *********************************** */
ob_flush();
?>
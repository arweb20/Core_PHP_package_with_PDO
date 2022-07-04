<?php
require_once '../constants.php';
require_once '../customFunctions.php';
require_once "./lib/jwtUtils.php";
require_once './config/header.php';

if (empty($_GET)) {
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $dataCred = json_decode(file_get_contents("php://input"));

        $userName = trim(removeHTMLEntities($dataCred->Username));
        $password = trim(removeHTMLEntities($dataCred->Password));

        if (empty($userName)) {
            $userNameErr = "Required";
        }
        if (empty($password)) {
            $passwordErr = "Required";
        } else {
            if (($userName == "Admin") && ($password == "admin")) {
            } else {
                $loginErr = "Wrong username and password";
            }
        }

        if (($userNameErr == "") && ($passwordErr == "") && ($loginErr == "")) {
            $headers = array('alg' => 'HS256', 'typ' => 'JWT');
            $payload = array('username' => $username, 'exp' => (time() + JWT_TOKEN_TIME));

            $token = generateJWTToken($headers, $payload, JWT_SECRET_KEY);
            $successData = array("Username" => $userName, "Token" => $token);
            $response['statusCode'] = 200;
            $response['message'] = "Records found";
            $response['data'] = $successData;
        } else {
            $dataErrs = array("Username" => $userNameErr, "Password" => $passwordErr,
                "Login" => $loginErr);
            $response['statusCode'] = 400;
            $response['message'] = "Bad request";
            $response['error'] = $dataErrs;
        }
    } else {
        $response['statusCode'] = 405;
        $response['message'] = "Method not allowed";
        $response['error'] = "Request method not allowed";
    }
} else {
    $response['statusCode'] = 403;
    $response['message'] = "Forbidden";
    $response['error'] = "URL link error";
}

echo json_encode($response, JSON_PRETTY_PRINT);

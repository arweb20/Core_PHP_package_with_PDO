<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

$headers = getallheaders();
$bearerToken = trim(substr($headers['Authorization'], 6));
$validToken = jwtValiditatonCheck($bearerToken, JWT_SECRET_KEY);

if(isset($_SERVER["REQUEST_METHOD"]) && strtoupper($_SERVER["REQUEST_METHOD"]) == "OPTIONS"){
    exit(0);
}


?>
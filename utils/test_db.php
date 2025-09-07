<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost";
$user = "diconto";
$pass = "DicontoPswd!123";
$dbname = "diconto";
$port = 3306;


$conn = new mysqli($host, $user, $pass, $dbname, $port);

echo "Database connection successful!";

?>
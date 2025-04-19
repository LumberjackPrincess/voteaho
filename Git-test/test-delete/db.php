<?php
$host = "host = 127.0.0.1";
$dbname = "dbname = tribal_voting";
$port        = "port = 5432";
//$user = "jimbo"; 
//$password = "BlackSheep9";
$credentials = "user = jimbo password=BlackSheep9";

$conn = pg_connect("$host $dbname $port $credentials");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}
?>

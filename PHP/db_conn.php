<?php

$sname = "localhost";
$uname = "phpmyadmin";
$password = "1234";

$db_name = "basic";

$conn = mysqli_connect($sname, $uname, $password, $db_name,3307);

if (!$conn) {
    echo "Connection Failed!";
    exit();
}

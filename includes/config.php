<?php

// user pass database
$db_host = 'localhost';
$db_user ='root';
$db_pass = '';
$db_name = 'database_perpus';

// mematikan error report
// mysql_report(MYSQLI_REPORT_OFF);

// membuat koneksi
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

//error handling
if ($mysqli->connect_error) {
    die("Koneksi gagal:" . $mysqli->connect_error);
}
?>
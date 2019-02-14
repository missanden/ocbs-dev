<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "hra_db";

// Koneksi dan memilih database di server
mysql_connect($server,$username,$password) or die("Koneksi gagal");
mysql_select_db($database) or die("Database tidak bisa dibuka");


/*
$server = "localhost";
$username = "sandenco";
$password = "r00tadminsanden";
$database = "sandenco_hradb";

// Koneksi dan memilih database di server
mysql_connect($server,$username,$password) or die("Koneksi gagal");
mysql_select_db($database) or die("Database tidak bisa dibuka");

*/

?>

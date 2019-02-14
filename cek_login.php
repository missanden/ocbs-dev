<?php

session_start();
include "config/koneksi.php";
include "config/datediff.php";

$no_employee = $_POST['no_employee'];
$pass = $_POST['password'];

$login = mysql_query("SELECT * FROM master_employee 
    INNER JOIN section ON master_employee.kode_section=section.kode_section
    INNER JOIN department ON department.kode_department=section.kode_department
    WHERE master_employee.no_employee='$no_employee' AND master_employee.passwordd='$pass'");
$ketemu = mysql_num_rows($login);
$r = mysql_fetch_array($login);

// Apabila no_employee dan password ditemukan
if ($ketemu > 0) {

    $now = date('Y-m-d');
    $joindate = $r['join_date_employee'];
    $lamabekerja = dateDiff("$now", "$joindate");

    $_SESSION['s_no_employee'] = $r['no_employee'];
    $_SESSION['s_nama_employee'] = $r['nama_employee'];
    $_SESSION['s_position'] = $r['position_user'];
    $_SESSION['s_department'] = $r['nama_department'];
    $_SESSION['s_email'] = $r['personal_email_employee'];
    $_SESSION['s_grade_employee'] = $r['grade_employee'];

    if ($_SESSION['s_position'] == 'HOD') {
        $query = "SELECT * FROM booking_mobil
            INNER JOIN booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
            INNER JOIN bonceng ON booking_mobil.kode_booking=bonceng.kode_booking
            INNER JOIN master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
            INNER JOIN master_employee ON booking_mobil.no_employee=master_employee.no_employee
            INNER JOIN section ON master_employee.kode_section=section.kode_section
            INNER JOIN department ON department.kode_department=section.kode_department
            WHERE booking_mobil.approved='0' AND department.nama_department='$_SESSION[s_department]' AND booking_mobil.status_booking='booked'
                ORDER BY booking_mobil.jam_start ASC";
        $query = mysql_query($query);
        $row = mysql_num_rows($query);

        if ($row > 0) {
            // header('location:aplikasi/index.php?page=schedule&act=tampilschedule');
            echo "<script>window.alert('Data Approval yang harus anda approve $row Data'); 
	window.location=('aplikasi/index.php?page=booking&act=approvalbooking')</script>";
        } else {
            header('location:aplikasi/index.php?page=schedule&act=tampilschedule');
        }
    } else {
        header('location:aplikasi/index.php?page=schedule&act=tampilschedule');
    }
} else {
    echo "<script>window.alert('NIK atau Password Anda tidak benar.'); window.location=('index.php')</script>";
}
?>

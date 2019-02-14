<?php


function tglindo1($date) { // fungsi atau method untuk mengubah tanggal ke format indonesia
    // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
    $BulanIndo = array("Jan", "Feb", "Mar",
        "Apr", "Mei", "Jun",
        "Jul", "Agu", "Sep",
        "Okt", "Nov", "Des");
	
	  $HariIndo = array("Senin", "Selasa", "Rabu",
        "Kamis", "Jum'at", "Sabtu","Minggu");	
		
	
    $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
    $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
    $tgl = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
    $hari = substr($date, 11, 2); // memisahkan format hari menggunakan substring

    $result =$HariIndo[(int) $hari- 1].", ". $tgl . " " . $BulanIndo[(int) $bulan - 1] . " " . $tahun;
    return($result);
}



function tglindo($date) { // fungsi atau method untuk mengubah tanggal ke format indonesia
    // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
    $BulanIndo = array("Jan", "Feb", "Mar",
        "Apr", "Mei", "Jun",
        "Jul", "Agu", "Sep",
        "Okt", "Nov", "Des");
	
	  $HariIndo = array("Senin", "Selasa", "Rabu",
        "Kamis", "Jum'at", "Sabtu","Minggu");	
		
	
    $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
    $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
    $tgl = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
    $hari = substr($date, 11, 2); // memisahkan format hari menggunakan substring

    $result = $tgl . " " . $BulanIndo[(int) $bulan - 1] . " " . $tahun;
    return($result);
}

function menubutton() {

	if ($_SESSION['s_position']=='admin')
	{
    echo" 
    <div class = \"btn-group btn-group-justified\">
	 <a href = \"?page=mobil&act=tampilmobil\" class = \"btn btn-primary col-sm-3\">
    Mobil
    </a>
    <a href = \"?page=schedule&act=tampilschedule\" class = \"btn btn-primary col-sm-3\">
    Jadwal Mobil
    </a>
    
	 <a href = \"?page=booking&act=bookingrental\" class = \"btn btn-primary col-sm-3\">
    Booking Mobil Rental
    </a>
	 <a href = \"?page=booking&act=riwayatbooking\" class = \"btn btn-primary col-sm-3\">
    Riwayat Booking
    </a>
	 <a href = \"?page=laporan&act=menulaporan\" class = \"btn btn-primary col-sm-3\">
   Laporan
    </a>
    </div>
    ";
	}
	
	
	else if ($_SESSION['s_position']=='HOD'){ echo" 
    <div class = \"btn-group btn-group-justified\">
    <a href = \"?page=schedule&act=tampilschedule\" class = \"btn btn-primary col-sm-3\">
    Jadwal Mobil
    </a>
    
    <a href = \"?page=booking&act=riwayatbooking\" class = \"btn btn-primary col-sm-3\">
    Riwayat Booking
    </a>
	
    </div>
    ";}
    
    
    else if ($_SESSION['s_no_employee']=='1303-295' OR $_SESSION['s_no_employee']=='1308-500' OR $_SESSION['s_no_employee']=='1310-554' OR 
    $_SESSION['s_no_employee']=='1204-143' OR $_SESSION['s_no_employee']=='1304-329' OR $_SESSION['s_no_employee']=='1305-351') { echo" 
    <div class = \"btn-group btn-group-justified\">
    <a href = \"?page=schedule&act=tampilschedule\" class = \"btn btn-primary col-sm-3\">
    Jadwal Mobil Operasional
    </a>
    
     <a href = \"?page=schedule&act=tampilschedulewarehouse\" class = \"btn btn-primary col-sm-3\">
    Jadwal Mobil Warehouse
    </a>
 
    <a href = \"?page=booking&act=riwayatbooking\" class = \"btn btn-primary col-sm-3\">
    Riwayat Booking
    </a>
    </div>
    ";}
    
	
	
	else { echo" 
    <div class = \"btn-group btn-group-justified\">
    <a href = \"?page=schedule&act=tampilschedule\" class = \"btn btn-primary col-sm-3\">
    Jadwal Mobil
    </a>
 
    <a href = \"?page=booking&act=riwayatbooking\" class = \"btn btn-primary col-sm-3\">
    Riwayat Booking
    </a>
    </div>
    ";}
}

?>
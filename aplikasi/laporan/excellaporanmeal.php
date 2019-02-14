<?php
session_start();
include "../../config/koneksi.php";


$tanggalan=date('d-m-Y');

$filename="excellaporanmeal-".$tanggalan.".xls";
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=$filename");
		header("Pragma: no-cache");
		header("Expires: 0");



		 $tampil=mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
			WHERE booking_mobil.tanggal_pemakaian BETWEEN '2017-07-21' AND '2017-08-20' AND
			booking_mobil.report_makan='ya' AND booking_mobil.status_booking NOT IN ('remove') 
			ORDER BY booking_mobil.tanggal_pemakaian ASC");
		
				
			
	  echo "
		<table border='1' width='800'>
          <tr>
		  <th>No.</th>
		  <th>No. Polisi</th>
		  <th>Driver</th>
		  <th>Penumpang 1</th>
		  <th>Penumpang 2</th>
		  <th>Tanggal Booking</th>
		  <th>Jam Mulai</th>
		  <th>Jam Selesai</th>
		  <th>Tujuan</th>
		  <th>Area</th>
		  <th>Aktifitas</th>
		  <th>Meal</th>
		  </tr>";		

$no=1;
    while($r=mysql_fetch_array($tampil)){
	    
	    $jam_selesai=$r['jam_selesai'];
	
	$query2=mysql_query("SELECT * FROM  jam_booking
					WHERE  jam_booking.jam_start_value LIKE '%$jam_selesai%'"); 
	$dat2=mysql_fetch_array($query2);
	
	$kode_jam=$dat2['kode_jam']+1;
	
	$query3=mysql_query("SELECT * FROM  jam_booking
					WHERE  jam_booking.kode_jam LIKE '%$kode_jam%'"); 
	$dat3=mysql_fetch_array($query3);  
	    
	    
      echo "<tr valign='top'>
				<td>$no</td>
				<td>$r[no_polisi]</td>
				<td>$r[nama_driver]</td>
                <td>$r[nama_employee]</td>
                <td>$r[nama_penumpang]</td>
                <td>$r[tanggal_pemakaian]</td>
                <td>$r[jam_start]</td>
                <td>$dat3[jam_start_value]</td>
                <td>$r[area]</td>
                <td>$r[instansi]</td>
                <td>$r[keperluan]</td>
                <td>Meal</td>
				 </tr>";
      $no++;
    }
    echo "</table>";


?>
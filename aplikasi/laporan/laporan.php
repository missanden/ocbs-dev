<?php
include '../config/koneksi.php';
$aksi="aplikasi/booking/aksi_booking.php";
switch($_GET['act']){	
 case "menulaporan":
 
  echo "<div class='panel-heading'><h4>Laporan</h4></div>
  <br><center><table  cellspacing='0' cellpadding='0' border='1' >
		<tr bgcolor='#dadcdf'> 
			<td colspan='10'><center><font size='+2'><b>Laporan</b></font></td>
		</tr>
		<tr bgcolor='#e7edf5'>";
	
		
			echo "<td><a href='?page=laporan&act=laporanhistory'><img src='images_car/report.jpg' width='200px' height='170px'></a><br>
					
					<br>
					
					<b>&nbsp;Laporan History Mobil</b><br>
			</td>
			
			<td><a href='?page=laporan&act=laporanmakan'><img src='images_car/report.jpg' width='200px' height='170px'></a><br>
					
					<br>
					
					<b>&nbsp;Laporan Meal</b><br>
			</td>
			
			
			<td><a href='?page=laporan&act=laporanbooking'><img src='images_car/report.jpg' width='200px' height='170px'></a><br>
					
					<br>
					
					<b>&nbsp;Laporan % Load Driver</b><br>
			</td>
			
			
			";
			
		
  echo "</tr></table><br><br><br><br>";
break;



case "laporanbooking":

// action='laporan/excellaporanbooking.php'

?>

<script type="text/javascript" src="../config/jquery.js"></script>
<script type="text/javascript" src="../config/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="config/flora.datepicker.css" />
		<script type="text/javascript">
		$(function(){
		$("#tanggal1").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal2").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal3").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal4").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal5").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal6").datepicker({ currentText: 'Skrg' })
		$("#tanggal7").datepicker({ prevText: '|<', nextText: '>|', currentText: 'Skrg', closeAtTop: false })	
		})	
</script>
<?php
echo "<div class='panel-heading'><h4>Laporan Booking</h4></div> <div class='control-group'>";

	echo "<div class='form-style-10'>
	<form method='POST' action='?page=laporan&act=grafiklaporanbooking' onSubmit='return validasi(this)' enctype='multipart/form-data'>
	<table cellpadding='2' cellspacing='0' border='0'>
	<tr>
	<td valign='top'>
    <div class='section'>Laporan Booking</div>
    <div class='inner-wrap'>
        <label>Dari Tanggal <input type='text'  size='35' name='tgl1'  id='tanggal1' size=10/></label>
        <label>Sampai Tanggal <input type='text'  size='35' name='tgl2'  id='tanggal2' size=10/></label>
        			
    </div>
	</td>
		
	</table>
	
    <div class='button-section'>
	<input type='submit' name='submit' id='submit' value='Tampil Laporan'></form>	
     </span>
    </div></div></div>";


	

break;




case "grafiklaporanbooking":

// action='laporan/excellaporanbooking.php'


?>

<script type="text/javascript" src="../config/jquery.js"></script>
<script type="text/javascript" src="../config/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="config/flora.datepicker.css" />
		<script type="text/javascript">
		$(function(){
		$("#tanggal1").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal2").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal3").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal4").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal5").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal6").datepicker({ currentText: 'Skrg' })
		$("#tanggal7").datepicker({ prevText: '|<', nextText: '>|', currentText: 'Skrg', closeAtTop: false })	
		})	
</script>
<?php
echo "<div class='panel-heading'><h4>Laporan Booking</h4></div> <div class='control-group'>";

	echo "<div class='form-style-10'>
	<form method='POST' action='?page=laporan&act=grafiklaporanbooking' onSubmit='return validasi(this)' enctype='multipart/form-data'>
	<table cellpadding='2' cellspacing='0' border='0'>
	<tr>
	<td valign='top'>
    <div class='section'>Laporan Booking</div>
    <div class='inner-wrap'>
        <label>Dari Tanggal <input type='text'  size='35' name='tgl1'  id='tanggal1' size=10/></label>
        <label>Sampai Tanggal <input type='text'  size='35' name='tgl2'  id='tanggal2' size=10/></label>
        			
    </div>
	</td>
		
	</table>
	
    <div class='button-section'>
	<input type='submit' name='submit' id='submit' value='Tampil Laporan'></form>	
     </span>
    </div></div></div>";

	
	$max=mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
						
					WHERE  booking_mobil.tanggal_pemakaian
								BETWEEN '$_POST[tgl1]' AND '$_POST[tgl2]' 
						AND booking_mobil.status_booking NOT IN ('remove') "); 
		$max=mysql_num_rows($max);

		
		$max1=$max1+$max;


	
?>
<center>
<b><font size="+2" color="#FF0000">GRAFIK<?echo $tahun; ?></font>
			<form action="laporan/excellaporanbooking.php" method="POST">
				<input type="hidden" size="25" name="tgl1" value="<?echo $_POST['tgl1'];?>">
				<input type="hidden" size="25" name="tgl2" value="<?echo $_POST['tgl2'];?>">
					<input type="submit" value="Convert Excel"></form></b><br>
<table border=0 cellspacing=5 cellpadding=0 width="800" align="center" style="background-color:#ABCDEF;border:1px solid blue;">
	<tr>

<?

$query=mysql_query("SELECT * FROM master_mobil 
		WHERE  master_mobil.kepemilikan_mobil='OPERASIONAL' GROUP BY master_mobil.nama_driver ASC");



		
 while($r=mysql_fetch_array($query)){



			$jml_bulan=mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
					
						
					WHERE  booking_mobil.tanggal_pemakaian
					BETWEEN  '$_POST[tgl1]' AND '$_POST[tgl2]' 
					AND master_mobil.nama_driver='$r[nama_driver]' AND booking_mobil.status_booking NOT IN ('remove')"); 
					
		$jml_bulan=mysql_num_rows($jml_bulan);

		
		$jml_bulan1=$jml_bulan1+$jml_bulan;
	
	$graphLineValue = round(($jml_bulan * 100) / $max1)*3;
	$percent        = round(($jml_bulan  / $max1)* 100);

	?>
			<td valign="bottom" title="<? echo $r['nama_driver']; ?>">
<table border=0 cellpadding=0 cellspacing=2 width="100%">
	 <tr align=center valign=bottom>
	 	<td style="background-color:;">
<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr align=center>
		<td height=102 valign=bottom nowrap style="color:black;font-family:Arial, Helvetica;font-size:12px;"><b><? echo $percent; ?> %</b>
	</td>
	</tr>
	<tr align=center valign=bottom>
		<td>
<table border=0 cellspacing=0 cellpadding=0 width="70%">
	<tr>
		<td style="border:2px outset white;" bgcolor="#E0F0FF" title="<? echo $jumlah_type; ?>"><div style="width:20px; height:<? echo $graphLineValue; ?>px; line-height:1px; font-size:1px;"></div>
		</td>
	</tr>
</table>
		</td>
	</tr>
</table>
		</td>
	</tr>
	<tr align=center>
	  <td style="color:#000000;background-color:#FFFFFF;border:2px groove white;font-family:Arial, Helvetica;font-size:12px;" nowrap>&nbsp;<? echo "".number_format($jml_bulan,0,"",".")."".""; ?> &nbsp;
	  </td>
	</tr>
	<tr>
		<td bgcolor="#C0E0FF" style="color:#000000;background-color:#C0E0FF;border:2px groove white;font-family:Arial, Helvetica;font-size:12px;text-align:center;"><b>&nbsp;<? echo $r['nama_driver']; ?>&nbsp;</b>
		</td>
	</tr>
	</table></td>
	<?
}
?>
</tr></table>
</tr></table>

<div style="font-family:'Courier New'; font-size:24px; font-weight:bold; padding-top:20px; text-align:center;">
<?php  
	

break;


case "laporanmakan":
//action='laporan/excellaporanmeal.php' 
?>

<script type="text/javascript" src="../config/jquery.js"></script>
<script type="text/javascript" src="../config/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="config/flora.datepicker.css" />
		<script type="text/javascript">
		$(function(){
		$("#tanggal1").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal2").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal3").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal4").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal5").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal6").datepicker({ currentText: 'Skrg' })
		$("#tanggal7").datepicker({ prevText: '|<', nextText: '>|', currentText: 'Skrg', closeAtTop: false })	
		})	
</script>
<?php
echo "<div class='panel-heading'><h4>Laporan Meal</h4></div> <div class='control-group'>";

	echo "<div class='form-style-10'>
	<form method='POST' action='?page=laporan&act=laporanmakanresult' onSubmit='return validasi(this)' enctype='multipart/form-data'>
	<table cellpadding='2' cellspacing='0' border='0'>
	<tr>
	<td valign='top'>
    <div class='section'>Laporan Meal</div>
    <div class='inner-wrap'>
        <label>Dari Tanggal <input type='text'  size='35' name='tgl1'  id='tanggal1' size=10/></label>
        <label>Sampai Tanggal <input type='text'  size='35' name='tgl2'  id='tanggal2' size=10/></label>
        			
    </div>
	</td>
		
	</table>
	
    <div class='button-section'>
	<input type='submit' name='submit' id='submit' value='Tampil Laporan'></form>	
     </span>
    </div></div></div>";


	

break;




case "laporanmakanresult":
//action='laporan/excellaporanmeal.php' 
?>

<script type="text/javascript" src="../config/jquery.js"></script>
<script type="text/javascript" src="../config/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="config/flora.datepicker.css" />
		<script type="text/javascript">
		$(function(){
		$("#tanggal1").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal2").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal3").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal4").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal5").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal6").datepicker({ currentText: 'Skrg' })
		$("#tanggal7").datepicker({ prevText: '|<', nextText: '>|', currentText: 'Skrg', closeAtTop: false })	
		})	
</script>
<?php
echo "<div class='panel-heading'><h4>Laporan Meal</h4></div> <div class='control-group'>";

	echo "<div class='form-style-10'>
	<form method='POST' action='?page=laporan&act=laporanmakanresult' onSubmit='return validasi(this)' enctype='multipart/form-data'>
	<table cellpadding='2' cellspacing='0' border='0'>
	<tr>
	<td valign='top'>
    <div class='section'>Laporan Meal</div>
    <div class='inner-wrap'>
        <label>Dari Tanggal <input type='text'  size='35' name='tgl1'  id='tanggal1' size=10/></label>
        <label>Sampai Tanggal <input type='text'  size='35' name='tgl2'  id='tanggal2' size=10/></label>
    </div>
	</td>
	</table>
    <div class='button-section'>
	<input type='submit' name='submit' id='submit' value='Tampil Laporan'></form>	
     </span>
    </div></div></div>";
?>
    <form action="laporan/excellaporanmeal.php" method="POST">
				<input type="hidden" size="25" name="tgl1" value="<?echo $_POST['tgl1'];?>">
				<input type="hidden" size="25" name="tgl2" value="<?echo $_POST['tgl2'];?>">
					<input type="submit" value="Convert Excel"></form></b><br>
<?php	
      $tampil=mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
			WHERE booking_mobil.tanggal_pemakaian BETWEEN '$_POST[tgl1]' AND '$_POST[tgl2]' AND
			booking_mobil.report_makan='ya'
			ORDER BY booking_mobil.tanggal_pemakaian ASC");
				
			
	  echo "
		<table border='1' width='90%'>
          <tr bgcolor='#4673b7'>
		  <th>No.</th>
		  <th>No. Polisi</th>
		  <th>Driver</th>
		  <th>Nama Karyawan</th>
		  <th>Nama Penumpang</th>
		  <th>Tanggal Booking</th>
		  <th>Jam Mulai</th>
		  <th>Jam Selesai</th>
		  <th>Tujuan</th>
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
	    
	    if ($no % 2) $background='#ffffff'; else $background='#CCE9FD';
	    
      echo "<tr valign='top' bgcolor='$background'>
				<td>$no</td>
				<td>$r[no_polisi]</td>
				<td>$r[nama_driver]</td>
                <td>$r[nama_employee]</td>
                <td>$r[nama_penumpang]</td>
                <td>$r[tanggal_pemakaian]</td>
                <td>$r[jam_start]</td>
                <td>$dat3[jam_start_value]</td>
                <td>$r[instansi]</td>
                <td>Meal</td>
				 </tr>";
      $no++;
    }
    echo "</table>";
	

break;

case "laporanhistory":
?>

<script type="text/javascript" src="../config/jquery.js"></script>
<script type="text/javascript" src="../config/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="config/flora.datepicker.css" />
		<script type="text/javascript">
		$(function(){
		$("#tanggal1").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal2").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal3").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal4").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal5").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal6").datepicker({ currentText: 'Skrg' })
		$("#tanggal7").datepicker({ prevText: '|<', nextText: '>|', currentText: 'Skrg', closeAtTop: false })	
		})	
</script>
<?php

echo "<div class='panel-heading'><h4>Laporan History</h4></div> <div class='control-group'>";

	echo "<div class='form-style-10'>
	<form method='POST'  action='?page=laporan&act=laporanhistoryresult' onSubmit='return validasi(this)' enctype='multipart/form-data'>
	<table cellpadding='2' cellspacing='0' border='0'>
	<tr>
	<td valign='top'>
    <div class='section'>Laporan History</div>
    <div class='inner-wrap'>
        <label>Dari Tanggal <input type='text'  size='35' name='tgl1'  id='tanggal1' size=10/></label>
        <label>Sampai Tanggal <input type='text'  size='35' name='tgl2'  id='tanggal2' size=10/></label>
        			
    </div>
	</td>
		
	</table>
	
    <div class='button-section'>
	<input type='submit' name='submit' id='submit' value='Tampil Laporan'></form>	
     </span>
    </div></div></div>";


	

break;




case "laporanhistoryresult":
?>

<script type="text/javascript" src="../config/jquery.js"></script>
<script type="text/javascript" src="../config/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="config/flora.datepicker.css" />
		<script type="text/javascript">
		$(function(){
		$("#tanggal1").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal2").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal3").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal4").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal5").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#tanggal6").datepicker({ currentText: 'Skrg' })
		$("#tanggal7").datepicker({ prevText: '|<', nextText: '>|', currentText: 'Skrg', closeAtTop: false })	
		})	
</script>
<?php

echo "<div class='panel-heading'><h4>Laporan History</h4></div> <div class='control-group'>";

	echo "<div class='form-style-10'>
	<form method='POST'  action='?page=laporan&act=laporanhistoryresult' onSubmit='return validasi(this)' enctype='multipart/form-data'>
	<table cellpadding='2' cellspacing='0' border='0'>
	<tr>
	<td valign='top'>
    <div class='section'>Laporan History</div>
    <div class='inner-wrap'>
        <label>Dari Tanggal <input type='text'  size='35' name='tgl1'  id='tanggal1' size=10/></label>
        <label>Sampai Tanggal <input type='text'  size='35' name='tgl2'  id='tanggal2' size=10/></label>
        			
    </div>
	</td>
		
	</table>
	
    <div class='button-section'>
	<input type='submit' name='submit' id='submit' value='Tampil Laporan'></form>	
     </span>
    </div></div></div>";


   ?>
    <form action="laporan/excellaporanhistory.php" method="POST">
				<input type="hidden" size="25" name="tgl1" value="<?php echo $_POST['tgl1'];?>">
				<input type="hidden" size="25" name="tgl2" value="<?php echo $_POST['tgl2'];?>">
				<input type="submit" value="Convert Excel"></form></b><br>

<?php 
    
	
      $tampil=mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
					INNER JOIN
						history_mobil ON booking_mobil.kode_booking=history_mobil.kode_booking	
			WHERE booking_mobil.tanggal_pemakaian BETWEEN '$_POST[tgl1]' AND '$_POST[tgl2]' 
			AND booking_mobil.status_booking NOT IN ('remove')
			ORDER BY booking_mobil.tanggal_pemakaian ASC");
				
			
	  echo "<table border='1' width='90%'>
          <tr bgcolor='#4673b7'>
		  <th>No.</th>
		  <th>No. Polisi</th>
		  <th>Driver</th>
		  <th>Nama Karyawan</th>
		  <th>Nama Penumpang</th>
		  <th>Tanggal Booking</th>
		  <th>Jam Mulai</th>
		  <th>Jam Selesai</th>
		  <th>Tujuan</th>
		  <th>KM Perjalanan</th>
		  <th>KM Mobil</th>
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
	    
	   if ($no % 2) $background='#ffffff'; else $background='#CCE9FD'; 
	    
	   $a=$r['km_masuk'] - $r['km_keluar'];
	    
	    
      echo "<tr valign='top' bgcolor='$background'>
		<td>$no</td>
		<td>$r[no_polisi]</td>
		<td>$r[nama_driver]</td>
                <td>$r[nama_employee]</td>
                <td>$r[nama_penumpang]</td>
                <td>$r[tanggal_pemakaian]</td>
                <td>$r[jam_start]</td>
                <td>$dat3[jam_start_value]</td>
                <td>$r[instansi]</td>
                <td> $a</td>
                <td>$r[km_masuk]</td>
				 </tr>";
      $no++;
    }
    echo "</table>";
	

break;

}
?>
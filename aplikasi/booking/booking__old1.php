<?php
include '../config/koneksi.php';
$aksi="aplikasi/booking/aksi_booking.php";
switch($_GET['act']){	

 case "menubooking":
 
  echo "<div class='panel-heading'><h4>Booking Mobil</h4></div>
  <br><center><table  cellspacing='0' cellpadding='0' border='1' >
		<tr bgcolor='#dadcdf'> 
			<td colspan='10'><center><font size='+2'><b>MOBIL OPERASIONAL</b></font></td>
		</tr>
		<tr bgcolor='#e7edf5'>";
		
	if ($_SESSION['s_position']=='HOD'){		
	  $sql = "select * from master_mobil where kepemilikan_mobil IN ('operasional','$_SESSION[s_no_employee]')
	  AND aktif_mobil IN ('aktif') ORDER BY kepemilikan_mobil DESC";
        }
		
	else {
$sql = "select * from master_mobil where kepemilikan_mobil IN ('operasional') AND aktif_mobil IN ('aktif')";
      
		}
		$query = mysql_query($sql);
        $no = 0;
        while ($d = mysql_fetch_array($query)) {
		if($d['status_mobil']=='Available')
				{$color="green";}
			else {$color="red";}
		
			echo "<td><a href='?page=booking&act=bookingmobil&nopolisi=$d[no_polisi]'><img src='images_car/".$d['foto_mobil']."' width='200px' height='120px'></a><br>
					
					<br>
					
					<b>&nbsp;No. Polisi</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='$color'><b> $d[no_polisi]</font><br>
					<b>&nbsp;Jenis & Merk Mobil</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='$color'> $d[type_mobil]</font><br>
					<b>&nbsp;Nama Driver</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='$color'> $d[nama_driver]</font><br>
					<b>&nbsp;Ketersediaan Mobil Saat Ini</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='$color'> $d[status_mobil]</font><br><br>
					
				
			</td>";
			}
		
  echo "</tr></table><br><br><br><br>";
break;


case "bookingmobil":
if (isset($_POST['submit'])) {

	$now=date('Y-m-d');
	
	$queryvalid="SELECT * FROM booking_mobil INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
	WHERE 	booking_mobil.no_polisi='$_POST[no_polisi]' AND
			booking_mobil.tanggal_pemakaian='$_POST[tanggal_pemakaian]' AND
			booking_mobil.jam_start BETWEEN '$_POST[jam_start]' AND '$_POST[jam_selesai]' AND
			booking_selesai.jam_selesai BETWEEN '$_POST[jam_selesai]' AND '$_POST[jam_selesai]'
			";
	$queryvalid=mysql_query($queryvalid);		
	$row = mysql_num_rows($queryvalid);
	
IF($row=='0'){
	$query="SELECT * FROM booking_mobil WHERE tgl_booking='$now'";
	$query=mysql_query($query);		
	$no1 = mysql_num_rows($query);

$nobooking=$no1+1;
$length=strlen($nobooking);

$tahun1  = date('Y');
$bulan1  = date('m');
$date1  = date('d');



if ($length=='1')
		{
		$kode_booking="$tahun1"."$bulan1"."$date1"."-000"."$nobooking";
		}	
elseif ($length=='2')
		{
		   $kode_booking="$tahun1"."$bulan1"."$date1"."-00"."$nobooking";
		}			
elseif ($length=='3')
		{
		  $kode_booking="$tahun1"."$bulan1"."$date1"."-0"."$nobooking";
		}	
elseif ($length=='4')
		{
		  $kode_booking="$tahun1"."$bulan1"."$date1"."-"."$nobooking";
		}
		
$tgl_booking=date('Y-m-d');
$jam_booking=date('h:i:s');

$jammulai=$_POST['jam_start'];
$jamselesai=$_POST['jam_selesai'];

$jammulai=str_replace(":","",$jammulai);	
$jamselesai=str_replace(":","",$jamselesai);	


	
IF($jammulai<120000 AND $jamselesai>120000)
	
{$report_makan="ya";}
ELSE{$report_makan="no";}
	if ($_SESSION['s_position']=='HOD')
	{$sql = "INSERT INTO booking_mobil(kode_booking,
										tgl_booking,
										jam_booking,
										tanggal_pemakaian,
										jam_start,
										instansi,
										area,
										pic_dituju,
										keperluan,
										no_employee,
										report_makan,
										no_polisi,
										approved,
										status_booking) 
		VALUES ('$kode_booking',
				'$tgl_booking',
				'$jam_booking',
				'$_POST[tanggal_pemakaian]',
				'$_POST[jam_start]',
				'$_POST[instansi]',
				'$_POST[area]',
				'$_POST[pic_dituju]',
				'$_POST[keperluan]',
				'$_SESSION[s_no_employee]',
				'$report_makan',
				'$_POST[no_polisi]',
				'1',
				'booked')";
				
	

 
	
	$tanggalpakai=$_POST['tanggal_pemakaian'];
	$jam_start=$_POST['jam_start'];
	$jam_selesai=$_POST['jam_selesai'];
	
	$awal=substr($jam_start,0,2);
	$akhir=substr($jam_selesai,0,2);
	
	
	for ($awal; $awal<=$akhir; $awal++) {
	
	$length=strlen($awal);
	if($length==1){$awal="0".$awal;}
	
	$tgl_tanpastrip=str_replace("-","",$tanggalpakai);
	$kodeunik=$tgl_tanpastrip."_".$d['no_polisi']."-".$awal;
	

	
	$sql = "INSERT INTO schedule_booking(kodeunik,kode_booking) 
		VALUES ('$kodeunik',
			'$_POST[kode_booking]')";
	 $query = mysql_query($sql);	
	
	}
 
 	}
	else{
	$sql = "INSERT INTO booking_mobil(kode_booking,
										tgl_booking,
										jam_booking,
										tanggal_pemakaian,
										jam_start,
										instansi,
										area,
										pic_dituju,
										keperluan,
										no_employee,
										report_makan,
										no_polisi,
										approved,
										status_booking) 
		VALUES ('$kode_booking',
				'$tgl_booking',
				'$jam_booking',
				'$_POST[tanggal_pemakaian]',
				'$_POST[jam_start]',
				'$_POST[instansi]',
				'$_POST[area]',
				'$_POST[pic_dituju]',
				'$_POST[keperluan]',
				'$_SESSION[s_no_employee]',
				'$report_makan',
				'$_POST[no_polisi]',
				'0',
				'booked')";
				
				}
        $query = mysql_query($sql);
		
		
		$sql2 = "INSERT INTO booking_selesai(kode_booking,
										jam_selesai) 
		VALUES ('$kode_booking',
				'$_POST[jam_selesai]'
				)";
        $query = mysql_query($sql2);
        
	
	$sql3 = "INSERT INTO bonceng(kode_booking,
									nama_penumpang,
										tujuan) 
		VALUES ('$kode_booking',
				'$_POST[nama_penumpang]',
				'$_POST[tujuan]'
				
				)";
		$query3 = mysql_query($sql3);		
if ($_SESSION['s_position']=='HOD'){



//================================================mail for hod=========================================				
		$view = mysql_query("SELECT * FROM master_employee INNER JOIN section
							ON master_employee.kode_section=section.kode_section
								INNER JOIN department
							ON department.kode_department=section.kode_department
			WHERE department.nama_department LIKE '%$_SESSION[s_department]%' AND master_employee.position_user LIKE '%presdir%'");		
	$r    = mysql_fetch_array($view);
	$spvmail = $r['personal_email_employee'];	
	
		
$booking = mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
					INNER JOIN section
							ON master_employee.kode_section=section.kode_section
					INNER JOIN department
							ON department.kode_department=section.kode_department
			WHERE booking_mobil.kode_booking='$kode_booking' ORDER BY booking_mobil.jam_start ASC");
$booking   = mysql_fetch_array($booking);

$tgl_booking = $booking['tgl_booking'];
$tanggal_pemakaian = $booking['tanggal_pemakaian'];
$jam_start = $booking['jam_start'];
$jam_selesai = $booking['jam_selesai'];
$instansi = $booking['instansi'];
$pic_dituju = $booking['pic_dituju'];
$nama_penumpang = $booking['nama_penumpang'];
				
	
$mailperson=$_SESSION['s_email'];


$to = "$spvmail";
$subject = "[Pengajuan Tugas Luar]($_SESSION[s_no_employee] | $_SESSION[s_nama_employee])";

$message = "
<html>
<head>
</head>
<body>
<p>Pengajuan Tugas Luar</p>
<table width='100%' cellspacing='0' celpadding='0' border='1'>
<tr bgcolor='#60f860'>
<th>No.</th>
<th>NIK</th>
<th>Nama</th>
<th>Tanggal Booking</th>
<th>Jam Mulai</th>
<th>Jam Selesai</th>
<th>Nama Penumpang</th>
<th>Instansi Dituju</th>
</tr>


<tr>
<td>1.</td>
<td>$_SESSION[s_no_employee]</td>
<td>$_SESSION[s_nama_employee]</td>
<td>".tglindo($tanggal_pemakaian)."</td>
<td>".substr("$jam_start",0,5)."</td>
<td>".substr("$jam_selesai",0,5)."</td>
<td>$nama_penumpang</td>
<td>$instansi<br>$pic_dituju</td>
</tr>
</table>
</body>
</html>

<br><a href='http://192.168.0.111/ocbs/'>Klik Link Berikut Untuk Akses Aplikasi OCBS</a><br>
<i><font size='-1'>*Mohon untuk tidak dibalas email ini.</font></i>
<p>Terima Kasih</p><br>
<p>Admin E-Leave</p>";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";



// More headers
$headers .= 'From: <adminocbs@sanden.co.id>' . "\r\n";
$headers .= 'Cc: $mailperson' . "\r\n";
$headers .= 'Cc: dodi.kesumayadi@sanden.co.id' . "\r\n";
$headers .= 'bcc: mis@sanden.co.id' . "\r\n";


mail($to,$subject,$message,$headers);	
	
	
}
else{
//================================================mail for hod=========================================				
		$view = mysql_query("SELECT * FROM master_employee INNER JOIN section
							ON master_employee.kode_section=section.kode_section
								INNER JOIN department
							ON department.kode_department=section.kode_department
			WHERE department.nama_department LIKE '%$_SESSION[s_department]%' AND master_employee.position_user LIKE '%HOD%'");		
	$r    = mysql_fetch_array($view);
	

IF($_SESSION['s_department']=='Quality'){
	$spvmail = "dhian@sanden.co.id";	
}


ELSE IF($_SESSION['s_department']=='Engineering & PE'){
	$spvmail = "tommy@sanden.co.id";	
}

else{
	
	$spvmail = $r['personal_email_employee'];	
}
		
$booking = mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
					INNER JOIN section
							ON master_employee.kode_section=section.kode_section
					INNER JOIN department
							ON department.kode_department=section.kode_department
			WHERE booking_mobil.kode_booking='$kode_booking' ORDER BY booking_mobil.jam_start ASC");
$booking   = mysql_fetch_array($booking);

$tgl_booking = $booking['tgl_booking'];
$tanggal_pemakaian = $booking['tanggal_pemakaian'];
$jam_start = $booking['jam_start'];
$jam_selesai = $booking['jam_selesai'];
$instansi = $booking['instansi'];
$pic_dituju = $booking['pic_dituju'];
$nama_penumpang = $booking['nama_penumpang'];
				
	
$mailperson=$_SESSION['s_email'];

/*
$to = "$spvmail";
$subject = "[Pengajuan Tugas Luar]($_SESSION[s_no_employee] | $_SESSION[s_nama_employee])";

$message = "
<html>
<head>
</head>
<body>
<p>Pengajuan Tugas Luar</p>
<table width='100%' cellspacing='0' celpadding='0' border='1'>
<tr bgcolor='#60f860'>
<th>No.</th>
<th>NIK</th>
<th>Nama</th>
<th>Tanggal Booking</th>
<th>Jam Mulai</th>
<th>Jam Selesai</th>
<th>Nama Penumpang</th>
<th>Instansi Dituju</th>
</tr>


<tr>
<td>1.</td>
<td>$_SESSION[s_no_employee]</td>
<td>$_SESSION[s_nama_employee]</td>
<td>".tglindo($tanggal_pemakaian)."</td>
<td>".substr("$jam_start",0,5)."</td>
<td>".substr("$jam_selesai",0,5)."</td>
<td>$nama_penumpang</td>
<td>$instansi<br>$pic_dituju</td>
</tr>
</table>
</body>
</html>

<br><a href='http://192.168.0.111/ocbs/'>Klik Link Berikut Untuk Akses Aplikasi OCBS</a><br>
<i><font size='-1'>*Mohon untuk tidak dibalas email ini.</font></i>
<p>Terima Kasih</p><br>
<p>Admin E-Leave</p>";

// Always set content-type when sending HTML email
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";


// More headers
$headers .= 'From: <adminocbs@sanden.co.id>' . "\r\n";
$headers .= 'Cc: $mailperson' . "\r\n";
$headers .= 'Cc: dodi.kesumayadi@sanden.co.id' . "\r\n";
$headers .= 'Cc: mis@sanden.co.id' . "\r\n";
*/


//====================================================================mail test===================
$to = '$spvmail';
 
$subject = '[Pengajuan Tugas Luar]($_SESSION[s_no_employee] | $_SESSION[s_nama_employee])';
$message ="
<html>
<head>
</head>
<body>
<p>Pengajuan Tugas Luar</p>
<table width='100%' cellspacing='0' celpadding='0' border='1'>
<tr bgcolor='#60f860'>
<th>No.</th>
<th>NIK</th>
<th>Nama</th>
<th>Tanggal Booking</th>
<th>Jam Mulai</th>
<th>Jam Selesai</th>
<th>Nama Penumpang</th>
<th>Instansi Dituju</th>
</tr>


<tr>
<td>1.</td>
<td>$_SESSION[s_no_employee]</td>
<td>$_SESSION[s_nama_employee]</td>
<td>".tglindo($tanggal_pemakaian)."</td>
<td>".substr("$jam_start",0,5)."</td>
<td>".substr("$jam_selesai",0,5)."</td>
<td>$nama_penumpang</td>
<td>$instansi<br>$pic_dituju</td>
</tr>
</table>
</body>
</html>

<br><a href='http://192.168.0.111/ocbs/'>Klik Link Berikut Untuk Akses Aplikasi OCBS</a><br>
<i><font size='-1'>*Mohon untuk tidak dibalas email ini.</font></i>
<p>Terima Kasih</p><br>
<p>Admin E-Leave</p>";




// More headers

 
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$headers .= 'To: '.$to. "\r\n";
$headers .= 'Cc: '.$mailperson. "\r\n";
$headers .= 'Bcc: mis@sanden.co.id' . "\r\n";
$headers .= 'From: PSI-OCBS Admin <ocbs@sanden.co.id>' . "\r\n";
 
$sendtomail = mail($to, $subject, $message, $headers);
if( $sendtomail ) echo 'Success';
else echo 'Failed';



}
//===============================================================================	


		
	
 if ($query) {
            echo"<script language='javascript'>alert ('input data berhasil'); </script>
	<script language='javascript'>
	document.location.href='?page=schedule&act=tampilschedule'</script>";
        } 	
}
else{
echo "<script language='javascript'>alert ('Untuk Tanggal & Jam Tersebut Sudah di Booking'); document.location.href='?page=booking&act=menubooking'</script>";
}

}

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
echo "<div class='panel-heading'><h4>Booking Mobil</h4></div> <div class='control-group'>";

$query = mysql_query("SELECT * FROM master_mobil
			WHERE no_polisi='$_GET[nopolisi]'");
	
	while ($d = mysql_fetch_array($query)) {
	echo "<div class='form-style-10'>
	<form method='POST' action='?page=booking&act=bookingmobil' onSubmit='return validasi(this)' enctype='multipart/form-data'>
	<table cellpadding='2' cellspacing='0' border='0'>
	<tr>
	<td valign='top'>
	<div class='section'>Data kendaraan</div>
    <div class='inner-wrap'>
        <label>No. Polisi <input type='text' disabled readonly='' size='25' name='no_polisi1' value='$d[no_polisi]' size=10>
							<input type='hidden' readonly='' size='25' name='no_polisi' value='$d[no_polisi]' size=10></label>
        <label>Driver <input type='text' size='40' disabled readonly='' name='nama_driver' value='$d[nama_driver]' size=10></label>
        <label>Jenis/Type Kendaraan <input type='text' size='50' disabled readonly='' name='typemobil' value='$d[type_mobil]' size=10></label>
    </div>
	</td><td valign='top'>
    <div class='section'>Detail Booking</div>
    <div class='inner-wrap'>
        <label>Tanggal Booking <input type='text'  size='35' name='tanggal_pemakaian'  id='tanggal1' size=10/></label>
        <label>Jam Mulai <select name='jam_start' id='jammulai' width='20'>
		 		<option value=''>Jam Mulai</option>";
		 	 $tampil=mysql_query("SELECT * FROM jam_booking ORDER BY jam_start ASC");
            while($w=mysql_fetch_array($tampil)){
              echo "<option value=$w[jam_start_value]>$w[jam_start]</option>";
            }
			
			
									echo "</select></label>
			 <label>Jam Selesai <select name='jam_selesai' id='jam_selesai'>
		 		<option value=''>Jam Selesai</option>";
		 	 $tampil=mysql_query("SELECT * FROM jam_booking ORDER BY jam_start ASC");
            while($w=mysql_fetch_array($tampil)){
              echo "<option value=$w[jam_finish_value]>$w[jam_finish]</option>";
            }
			//jam selesai
			
									echo "</select></label>						
    </div>
	</td>
	</tr>
	<tr>
	<td valign='top'>
	<div class='section'>Detail Keperluan</div>
    <div class='inner-wrap'>
        <label>Instansi/Individu <input type='text' size='35' name='instansi' value='$d[instansi]' size=10></label>
        <label>Area/Daerah <input type='text' size='50'  name='area' value='$d[area]' size=10></label>
        <label>Bertemu Dengan <input type='text' size='35' name='pic_dituju' value='$d[pic_dituju]' size=10></label>
        <label>Keperluan <textarea name='keperluan' cols='50' rows='3'>$d[keperluan]</textarea></label>
       </div>
	</td>
	
	<td valign='top'>
	<div class='section'>Data Penumpang</div>
    <div class='inner-wrap'>
        <label>Nama Penumpang<select name='nama_penumpang'>
		 		<option value=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama Penumpang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
		 	 
		
		
		$tampil2=mysql_query("SELECT * FROM master_employee WHERE no_employee NOT IN('$_SESSION[s_no_employee]') ORDER BY nama_employee ASC");
            while($w=mysql_fetch_array($tampil2)){
              echo "<option value='$w[nama_employee]'>$w[nama_employee]</option>";
            }
				
			echo "</select></label>
         <label>Tujuan & Keperluan Penumpang <textarea name='tujuan' cols='50' rows='3'>$d[tujuan]</textarea></label>
       </div>
	</td>
	</tr>
	
	</table>
	
    <div class='button-section'>
	<input type='submit' name='submit' id='submit' value='Save'></form>	
     </span>
    </div></div></div>";


}			
	
break;

case "bookingmobil2":

if (isset($_POST['submit'])) {

	$now=date('Y-m-d');
	
	$queryjamstart="SELECT * FROM jam_booking 
		WHERE 	jam_booking.kode_jam='$_POST[jam_start]'";
	$queryjamstart1=mysql_query($queryjamstart);		
	
while ($j = mysql_fetch_array($queryjamstart1)) {
	$jam_start=$j['jam_start_value'];
	$kode_jam1=$j['kode_jam'];
	$jam_delete=$j['jam_delete']; 
	}
	

$jamselesaii=$_POST['jam_selesai']-1;
	
$queryjamselesai="SELECT * FROM jam_booking WHERE kode_jam='$jamselesaii'";
    $queryjamselesai1 = mysql_query($queryjamselesai);
	
        while ($d = mysql_fetch_array($queryjamselesai1)) {
		$jam_selesai=$d['jam_start_value'];
		$kode_jam2=$d['kode_jam'];
	}


	
	
	
	$queryvalid="SELECT * FROM booking_mobil INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
	WHERE 	booking_mobil.no_polisi='$_POST[no_polisi]' AND
			booking_mobil.tanggal_pemakaian='$_POST[tanggal_pemakaian]' AND
			booking_mobil.jam_start BETWEEN '$jam_start' AND '$jam_selesai' AND
			booking_mobil.status_booking NOT IN ('remove') AND
			booking_selesai.jam_selesai BETWEEN '$jam_start' AND '$jam_selesai'
			";
	$queryvalid=mysql_query($queryvalid);		
	$row = mysql_num_rows($queryvalid);
	
IF($row=='0'){
	$query="SELECT * FROM booking_mobil WHERE tgl_booking='$now'";
	$query=mysql_query($query);		
	$no1 = mysql_num_rows($query);

$nobooking=$no1+1;
$length=strlen($nobooking);

$tahun1  = date('Y');
$bulan1  = date('m');
$date1  = date('d');



if ($length=='1')
		{
		$kode_booking="$tahun1"."$bulan1"."$date1"."-000"."$nobooking";
		}	
elseif ($length=='2')
		{
		   $kode_booking="$tahun1"."$bulan1"."$date1"."-00"."$nobooking";
		}			
elseif ($length=='3')
		{
		  $kode_booking="$tahun1"."$bulan1"."$date1"."-0"."$nobooking";
		}	
elseif ($length=='4')
		{
		  $kode_booking="$tahun1"."$bulan1"."$date1"."-"."$nobooking";
		}
		
$tgl_booking=date('Y-m-d');
$jam_booking=date('h:i:s');

		
$tgl_booking=date('Y-m-d');
$jam_booking=date('h:i:s');

$jammulai=$jam_start;
$jamselesai=$jam_selesai;

$jammulai=str_replace(":","",$jammulai);	
$jamselesai=str_replace(":","",$jamselesai);	



	
IF($jammulai<120000 AND $jamselesai>120000)
{$report_makan="ya";}
ELSE{$report_makan="no";}

	if ($_SESSION['s_position']=='HOD'){ $approved="1"; } else { $approved="1"; }
	
	
	
	$sql = "INSERT INTO booking_mobil(kode_booking,
										tgl_booking,
										jam_booking,
										tanggal_pemakaian,
										jam_start,
										instansi,
										area,
										pic_dituju,
										keperluan,
										no_employee,
										report_makan,
										no_polisi,
										approved,
										status_booking,
										jam_delete,
										catatan) 
		VALUES ('$kode_booking',
				'$tgl_booking',
				'$jam_booking',
				'$_POST[tanggal_pemakaian]',
				'$jam_start',
				'$_POST[instansi]',
				'$_POST[area]',
				'$_POST[pic_dituju]',
				'$_POST[keperluan]',
				'$_SESSION[s_no_employee]',
				'$report_makan',
				'$_POST[no_polisi]',
				'$approved',
				'booked',
				'$jam_delete',
				'$_POST[catatan]')";
				
		$query = mysql_query($sql);	
		
	 $query = mysql_query($sql);	
		
	
	$tanggalpakai=$_POST['tanggal_pemakaian'];
	
	
	
	
	for ($kode_jam1; $kode_jam1<=$kode_jam2; $kode_jam1++) {
	
	
	
	$tgl_tanpastrip=str_replace("-","",$tanggalpakai);
	$kodeunik=$tgl_tanpastrip."_".$_POST['no_polisi']."-".$kode_jam1;
	

	
	$sql2 = "INSERT INTO schedule_booking(kodeunik,kode_booking) 
		VALUES ('$kodeunik',
			'$kode_booking')";
	 $query2 = mysql_query($sql2);	
	
	}	
				
       
		
		
		$sql2 = "INSERT INTO booking_selesai(kode_booking,jam_selesai) 
		VALUES ('$kode_booking',
				'$jam_selesai'
				)";
        $query = mysql_query($sql2);
        
	
	$sql3 = "INSERT INTO bonceng(kode_booking,
									nama_penumpang,
										tujuan) 
		VALUES ('$kode_booking',
				'$_POST[nama_penumpang]',
				'$_POST[tujuan]'
				
				)";
		$query3 = mysql_query($sql3);

		
if ($_SESSION['s_position']=='HOD'){

		
$booking = mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
					INNER JOIN section
							ON master_employee.kode_section=section.kode_section
					INNER JOIN department
							ON department.kode_department=section.kode_department
			WHERE booking_mobil.kode_booking='$kode_booking' ORDER BY booking_mobil.jam_start ASC");
$booking   = mysql_fetch_array($booking);

$tgl_booking = $booking['tgl_booking'];
$no_polisi = $booking['no_polisi'];
$nama_driver = $booking['nama_driver'];
$tanggal_pemakaian = $booking['tanggal_pemakaian'];
$type_mobil = $booking['type_mobil'];
$jam_start = $booking['jam_start'];
$instansi = $booking['instansi'];
$pic_dituju = $booking['pic_dituju'];
$nama_penumpang = $booking['nama_penumpang'];
$tujuan = $booking['tujuan'];
$area = $booking['area'];
$pic_dituju = $booking['pic_dituju'];
$keperluan = $booking['keperluan'];
	


	
$mailperson=$_SESSION['s_email'];

IF($_SESSION['s_nama_employee'] =='Tommy Hermawanto'){
	$mailperson = "anriga@sanden.co.id";	
}

ELSE IF($_SESSION['s_nama_employee'] =='R. Dhian Kusuma'){
	$mailperson = "anriga@sanden.co.id";	
}

ELSE IF($_SESSION['s_nama_employee'] =='Yasan'){
	$mailperson = "anriga@sanden.co.id";	
}

else {
	$mailperson=$_SESSION['s_email'];
}



if($_POST['catatan']=='1'){
	
	$catatan="<br>(Hanya Driver yang pergi)";
}
else{
	$catatan="";
}


$to = "".$mailperson."";


$subject = "[Pengajuan Tugas Luar]  ($_SESSION[s_no_employee] | $_SESSION[s_nama_employee])";

$message = "
<html>
<head>
</head>
<body>
<p><b>Pengajuan Tugas Luar / Penggunaan Mobil Operasional</b><i>(Working Out of Office / Car Operasional Usage)</i></p>
<table width='100%' cellspacing='0' celpadding='0' border='1'>
<tr>
<td bgcolor='#289928' colspan='2'><b>Data Pemesan <i>(Booking Data)</i></b></td><td Colspan='2' bgcolor='#289928'><b>Data Kendaraan <i>(Vehicles Data)</i></b></td>
</tr>
<tr>
<td bgcolor='#60f860' width='25%'>NIK <br><font size='-1'><i>(Employee ID)</font></i></td><td width='25%'>$_SESSION[s_no_employee]</td>
<td bgcolor='#60f860' width='25%'>No. Polisi <br><font size='-1'><i>(Plat No.)</i></font></td><td width='25%'>$no_polisi</td>
</tr>
<tr>
<td bgcolor='#60f860' width='25%'>Nama <br><font size='-1'><i>(Employee Name)</i></font></td><td width='25%'>$_SESSION[s_nama_employee]<br>$catatan</td>
<td bgcolor='#60f860' width='25%'>Pemilik Kendaraan <br><font size='-1'><i>(Owner Vehicle)</i></font></td><td width='25%'>$nama_driver</td>
</tr>
<tr>
<td bgcolor='#60f860' width='25%'>Departemen <br><font size='-1'><i>(Department)</i></font></td><td width='25%'>$_SESSION[s_department]</td>
<td bgcolor='#60f860' width='25%'>Type Mobil <br><font size='-1'><i>(Car Type)</i></font></td><td width='25%'>$type_mobil</td>
</tr>
</table>
<br>
<table width='100%' cellspacing='0' celpadding='0' border='1'>
<tr>
<td bgcolor='#289928' colspan='2'><b>Detail Keperluan <i>(Booking Detail)</i></b></td><td Colspan='2' bgcolor='#289928'><b>Data Penumpang Tambahan <i>(Additional Passenger Data)</i></b></td>
</tr>
<tr>
<td bgcolor='#60f860' width='25%'>Tanggal & Jam <br><font size='-1'><i>(Date & Time)</i></font></td><td width='25%'>".tglindo($tanggal_pemakaian)."<br> ".substr("$jam_start",0,5)." s/d ".substr("$jam_selesai",0,5)."</td>
<td bgcolor='#60f860' width='25%'>Penumpang Tambahan <br><font size='-1'><i>(Additional Passenger)</i></font> </td><td width='25%'>$nama_penumpang</td>
</tr>
<tr>
<td bgcolor='#60f860' width='25%'>Instansi / Individu <br><font size='-1'><i>(Company)</i></font></td><td width='25%'>$instansi</td>
<td bgcolor='#60f860' width='25%'>Tujuan Penumpang Tambahan <br><font size='-1'><i>(Second Passenger Destination)</i></font></td><td width='25%'>$tujuan</td>
</tr>
<tr>
<td bgcolor='#60f860' width='25%'>Area / Daerah <br><font size='-1'><i>(Destination Area)</i></font></td><td width='25%'>$area</td>
<td bgcolor='#60f860' width='50%' colspan='2'></td>
</tr>
<tr>
<td bgcolor='#60f860' width='25%'>Bertemu Dengan <br><font size='-1'><i>(Whom to Meet)</i></font></td><td width='25%'>$pic_dituju</td>
<td bgcolor='#60f860' width='50%' colspan='2'></td>
</tr>
<tr>
<td bgcolor='#60f860' width='25%'>Aktifitas <br><font size='-1'><i>(Activities)</i></font></td><td width='25%'>$keperluan</td>
<td bgcolor='#60f860' width='50%' colspan='2'></td>
</tr>
</table>
</body>
</html>

<br><a href='http://192.168.0.111/ocbs/'>Klik Link Berikut Untuk Akses Aplikasi OCBS <i>(Click this link to view OCBS application)</i></a><br>
<i><font size='-1'>*Mohon untuk tidak dibalas email ini. (Please do not reply this mail)</font></i><br>

<table width='100%' border='0'>
<tr>
<td bgcolor='#cdcdcd'>
<p>
Catatan untuk Pemesan <i>(Notes for Bookers)</i>:<br>
1.	Bila Dalam 30 menit setelah jam pemesanan, mobil operasional <b>BELUM</b> meninggalkan area PT Sanden Indonesia, data pemesanan dianggap batal dan akan terhapus otomatis oleh system.
 <br><font size='-1'><i>(If Within 30 Minutes after booking time, operational car has not leaving PT. Sanden Indonesia, Booking Data considered cancel and will be automatically deleted by system)</i></font><br>
</p>
</td>
</tr>
</table>
<br>
<p>Terima Kasih <i>(Thank You)</i></p><br>
<p>Admin OCBS</p>";


// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";



// More headers
$headers .= 'From: PSI-OCBS Admin <admincobs@sanden.co.id>' . "\r\n";
$headers .= 'Bcc: mis@sanden.co.id' . "\r\n";
$headers .= 'Bcc: dodi.kesumayadi@sanden.co.id' . "\r\n";
$headers .= 'Bcc: zaini1@sanden.co.id' . "\r\n";

mail($to,$subject,$message,$headers);


	
	
}
else{
	
//================================================mail for hod=========================================				
		$view = mysql_query("SELECT * FROM master_employee INNER JOIN section
							ON master_employee.kode_section=section.kode_section
								INNER JOIN department
							ON department.kode_department=section.kode_department
			WHERE department.nama_department LIKE '%$_SESSION[s_department]%' AND master_employee.position_user LIKE '%HOD%'");		
	$r    = mysql_fetch_array($view);
	
	
	
		

IF($_SESSION['s_department']=='Quality Assurance'){
	$spvmail = "dhian@sanden.co.id";
	$ccmail="anriga@sanden.co.id";
}


ELSE IF($_SESSION['s_department']=='Engineering'){
	$spvmail = "tommy@sanden.co.id";
		$ccmail="anriga@sanden.co.id";
}

else{
	
	$spvmail = $r['personal_email_employee'];	
}
		
$booking = mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
					INNER JOIN section
							ON master_employee.kode_section=section.kode_section
					INNER JOIN department
							ON department.kode_department=section.kode_department
			WHERE booking_mobil.kode_booking='$kode_booking' ORDER BY booking_mobil.jam_start ASC");
$booking   = mysql_fetch_array($booking);


$tgl_booking = $booking['tgl_booking'];
$no_polisi = $booking['no_polisi'];
$nama_driver = $booking['nama_driver'];
$tanggal_pemakaian = $booking['tanggal_pemakaian'];
$kepemilikan_mobil = $booking['kepemilikan_mobil'];
$type_mobil = $booking['type_mobil'];
$jam_start = $booking['jam_start'];
$instansi = $booking['instansi'];
$pic_dituju = $booking['pic_dituju'];
$nama_penumpang = $booking['nama_penumpang'];
$tujuan = $booking['tujuan'];
$area = $booking['area'];
$pic_dituju = $booking['pic_dituju'];
$keperluan = $booking['keperluan'];
	



if($_POST['catatan']=='1'){
	
	$catatan="<br>(Hanya Driver yang pergi)";
}
else{
	$catatan="";
}	
	


	
$mailperson=$_SESSION['s_email'];


$to = "$spvmail";
$subject = "[Pengajuan Tugas Luar]($_SESSION[s_no_employee] | $_SESSION[s_nama_employee])";

$message = "
<html>
<head>
</head>
<body>
<p><b>Pengajuan Tugas Luar / Penggunaan Mobil Operasional</b><i>(Working Out of Office / Car Operasional Usage)</i></p>
<table width='100%' cellspacing='0' celpadding='0' border='1'>
<tr>
<td bgcolor='#289928' colspan='2'><b>Data Pemesan <i>(Booking Data)</i></b></td><td Colspan='2' bgcolor='#289928'><b>Data Kendaraan <i>(Vehicles Data)</i></b></td>
</tr>
<tr>
<td bgcolor='#60f860' width='25%'>NIK <br><font size='-1'><i>(Employee ID)</font></i></td><td width='25%'>$_SESSION[s_no_employee]</td>
<td bgcolor='#60f860' width='25%'>No. Polisi <br><font size='-1'><i>(Plat No.)</i></font></td><td width='25%'>$no_polisi</td>
</tr>
<tr>
<td bgcolor='#60f860' width='25%'>Nama <br><font size='-1'><i>(Employee Name)</i></font></td><td width='25%'>$_SESSION[s_nama_employee] $catatan</td>
<td bgcolor='#60f860' width='25%'>Nama Driver <br><font size='-1'><i>(Driver Name)</i></font></td><td width='25%'>$nama_driver</td>
</tr>
<tr>
<td bgcolor='#60f860' width='25%'>Departemen <br><font size='-1'><i>(Department)</i></font></td><td width='25%'>$_SESSION[s_department]</td>
<td bgcolor='#60f860' width='25%'>Type Mobil <br><font size='-1'><i>(Car Type)</i></font></td><td width='25%'>$type_mobil</td>
</tr>
</table>
<br>
<table width='100%' cellspacing='0' celpadding='0' border='1'>
<tr>
<td bgcolor='#289928' colspan='2'><b>Detail Keperluan <i>(Booking Detail)</i></b></td><td Colspan='2' bgcolor='#289928'><b>Data Penumpang Tambahan <i>(Additional Passenger Data)</i></b></td>
</tr>
<tr>
<td bgcolor='#60f860' width='25%'>Tanggal & Jam <br><font size='-1'><i>(Date & Time)</i></font></td><td width='25%'>".tglindo($tanggal_pemakaian)."<br> ".substr("$jam_start",0,5)." s/d ".substr("$jam_selesai",0,5)."</td>
<td bgcolor='#60f860' width='25%'>Penumpang Tambahan <br><font size='-1'><i>(Additional Passenger)</i></font> </td><td width='25%'>$nama_penumpang</td>
</tr>
<tr>
<td bgcolor='#60f860' width='25%'>Instansi / Individu <br><font size='-1'><i>(Company)</i></font></td><td width='25%'>$instansi</td>
<td bgcolor='#60f860' width='25%'>Tujuan Penumpang Tambahan <br><font size='-1'><i>(Second Passenger Destination)</i></font></td><td width='25%'>$tujuan</td>
</tr>
<tr>
<td bgcolor='#60f860' width='25%'>Area / Daerah <br><font size='-1'><i>(Destination Area)</i></font></td><td width='25%'>$area</td>
<td bgcolor='#60f860' width='50%' colspan='2'></td>
</tr>
<tr>
<td bgcolor='#60f860' width='25%'>Bertemu Dengan <br><font size='-1'><i>(Whom to Meet)</i></font></td><td width='25%'>$pic_dituju</td>
<td bgcolor='#60f860' width='50%' colspan='2'></td>
</tr>
<tr>
<td bgcolor='#60f860' width='25%'>Aktifitas <br><font size='-1'><i>(Activities)</i></font></td><td width='25%'>$keperluan</td>
<td bgcolor='#60f860' width='50%' colspan='2'></td>
</tr>
</table>
</body>
</html>

<br><a href='http://192.168.0.111/ocbs/'>Klik Link Berikut Untuk Akses Aplikasi OCBS <i>(Click this link to view OCBS application)</i></a><br>
<i><font size='-1'>*Mohon untuk tidak dibalas email ini. (Please do not reply this mail)</font></i><br>

<table width='100%' border='0'>
<tr>
<td bgcolor='#cdcdcd'>
<p>
Catatan untuk Pemesan <i>(Notes for Bookers)</i>:<br>
1.	Bila Dalam 30 menit setelah jam pemesanan, mobil operasional <b>BELUM</b> meninggalkan area PT Sanden Indonesia, data pemesanan dianggap batal dan akan terhapus otomatis oleh system.
 <br><font size='-1'><i>(If Within 30 Minutes after booking time, operational car has not leaving PT. Sanden Indonesia, Booking Data considered cancel and will be automatically deleted by system)</i></font><br>
</p>
</td>
</tr>
</table>
<br>
<p>Terima Kasih <i>(Thank You)</i></p><br>
<p>Admin OCBS</p>";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";



// More headers
$headers .= 'From: PSI-OCBS Admin <admincobs@sanden.co.id>' . "\r\n";

if($kepemilikan_mobil=='Delivery'){
	$headers .= 'Cc: nia.astria@sanden.co.id' . "\r\n";
}


	

IF($_SESSION['s_department']=='Quality Assurance'){
	$headers .= 'Cc: anriga@sanden.co.id' . "\r\n";	
}


ELSE IF($_SESSION['s_department']=='Engineering'){
	$headers .= 'Cc: anriga@sanden.co.id' . "\r\n";		
}

ELSE IF($_SESSION['s_department']=='HRA & CA'){
	$headers .= 'Cc: herriyanto@sanden.co.id' . "\r\n";		
}



$headers .= 'Cc: '.$mailperson. "\r\n";
$headers .= 'Bcc: mis@sanden.co.id' . "\r\n";
$headers .= 'Bcc: dodi.kesumayadi@sanden.co.id' . "\r\n";

mail($to,$subject,$message,$headers);


}
//===============================================================================	


		
	
 if ($query) {
            echo"<script language='javascript'>alert ('input data berhasil'); </script>
	<script language='javascript'>
	document.location.href='?page=schedule&act=tampilschedule'</script>";
        } 	
}
else{
echo "<script language='javascript'>alert ('Untuk Tanggal & Jam Tersebut Sudah di Booking'); document.location.href='?page=schedule&act=tampilschedule'</script>";
}

}

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



function validasi(form){
  if (form.area.value == ""){
    alert("Anda belum mengisikan Area.");
    form.area.focus();
    return (false);
  }
     
  if (form.instansi.value == ""){
    alert("Anda belum mengisikan Instansi/Individu.");
    form.instansi.focus();
    return (false);
  }
  
  
    if (form.jam_selesai.value == ""){
    alert("Anda belum memilih jam selesai");
    form.jam_selesai.focus();
    return (false);
  }
  
  
    if (form.pic_dituju.value == ""){
    alert("Anda belum mengisikan PIC yg anda tuju.");
    form.pic_dituju.focus();
    return (false);
  }
  
  
      if (form.keperluan.value == ""){
    alert("Anda belum mengisikan Keperluan Tugas Luar Anda.");
    form.keperluan.focus();
    return (false);
  }
  return (true);
}	
		
</script>
<?php
echo "<div class='panel-heading'><h4>Booking Mobil</h4></div> <div class='control-group'>";



$query = mysql_query("SELECT * FROM master_mobil
			WHERE no_polisi='$_POST[no_polisi]'");
	
	while ($d = mysql_fetch_array($query)) {
	echo "<div class='form-style-10'>
	<form method='POST' action='?page=booking&act=bookingmobil2' onSubmit='return validasi(this)' enctype='multipart/form-data'>
	<table cellpadding='2' cellspacing='0' border='0'>
	<tr>
	<td valign='top'>
	<div class='section'>Data kendaraan</div>
    <div class='inner-wrap'>
        <label>No. Polisi <input type='text' disabled readonly='' size='25' name='no_polisi1' value='$d[no_polisi]' size=10>
			
				<input type='hidden' readonly='' size='25' name='no_polisi' value='$d[no_polisi]' size=10></label>
        <label>Driver <input type='text' size='40' disabled readonly='' name='nama_driver' value='$d[nama_driver]' size=10></label>
        <label>Jenis/Type Kendaraan <input type='text' size='50' disabled readonly='' name='typemobil' value='$d[type_mobil]' size=10></label>
    </div>
	</td><td valign='top'>
    <div class='section'>Detail Booking</div>
    <div class='inner-wrap'>
        <label>Tanggal Booking <input type='text'  size='35' name='tanggal_pemakaian' value='$_POST[tanggal]'  id='tanggal1' size=10/></label>
        <label>Jam Mulai <select name='jam_start' id='jammulai' width='20'>
		 		<option value=''>Jam Mulai</option>";
			
				if ($_SESSION['s_position']=='admin'){
				$tampil=mysql_query("SELECT * FROM jam_booking ORDER BY jam_start ASC");}
			else {$tampil=mysql_query("SELECT * FROM jam_booking WHERE kode_jam BETWEEN '15' AND '34' ORDER BY jam_start ASC");}
				   
				   
          if ($_POST['kode_jam']==0){
            echo "<option value=0 selected>- Pilih Jam -</option>";
          }   

          while($w=mysql_fetch_array($tampil)){
            if ($_POST['kode_jam']==$w['kode_jam']){
              echo "<option value=$w[kode_jam] selected>$w[jam_start]</option>";
            }
            else{
              echo "<option value=$w[kode_jam]>$w[jam_start]</option>";
            }
          }
				
	
			
									echo "</select>
			</label>
			 <label>Jam Selesai <select name='jam_selesai' id='jam_selesai'>
		 		<option value='' selected>Jam Selesai</option>";
			
	
			if ($_SESSION['s_position']=='admin'){
				$tampil=mysql_query("SELECT * FROM jam_booking ORDER BY jam_start ASC");}
			else {$tampil=mysql_query("SELECT * FROM jam_booking WHERE kode_jam BETWEEN '15' AND '34' ORDER BY jam_start ASC");}
				   
         
          while($w=mysql_fetch_array($tampil)){
            if ($kodejam==$w['kode_jam']){
              echo "<option value=$w[kode_jam]>$w[jam_start]</option>";
            }
            else{
              echo "<option value=$w[kode_jam]> $w[jam_start]</option>";
            }
          }
			
									echo "</select></label>						
    </div>
	</td>
	</tr>
	<tr>
	<td valign='top'>
	<div class='section'>Detail Keperluan</div>
    <div class='inner-wrap'>
       
        <label>Nama Pemesan</label>
		
		<label><input type='checkbox' name='catatan' value='1'>Hanya Driver yg Pergi</label>
		<input type='text' size='35' name='employee_name' readonly='' value='$_SESSION[s_nama_employee]' size=10>
        <label>Instansi/Individu <input type='text' size='35' name='instansi' value='$d[instansi]' size=10></label>
        <label>Area/Daerah <select name='area'>
		 		<option value='Jakarta'>Jakarta</option>
		 		<option value='Karawang'>Karawang</option>
		 		<option value='Bekasi'>Bekasi</option>
		 		<option value='Bogor'>Bogor</option>
		 		<option value='Depok'>Depok</option>
		 		<option value='Tangerang'>Tangerang</option>
		 		<option value='Purwakarta'>Purwakarta</option>
		 		<option value='Cikarang'>Cikarang</option>
		 		<option value='Others'>Others</option>
				</select>
	</label>
        <label>Bertemu Dengan <input type='text' size='35' name='pic_dituju' value='$d[pic_dituju]' size=10></label>
        <label>Keperluan <textarea name='keperluan' cols='50' rows='3'>$d[keperluan]</textarea></label>
       </div>
	</td>
	
	<td valign='top'>
	<div class='section'>Data Penumpang</div>
    <div class='inner-wrap'>
        <label>Nama Penumpang<select name='nama_penumpang'>
		 		<option value=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama Penumpang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
		 	 
		
		
		$tampil2=mysql_query("SELECT * FROM master_employee WHERE no_employee NOT IN('$_SESSION[s_no_employee]') ORDER BY nama_employee ASC");
            while($w=mysql_fetch_array($tampil2)){
              echo "<option value='$w[nama_employee]'>$w[nama_employee]</option>";
            }
				
			echo "</select></label>
         <label>Tujuan & Keperluan Penumpang <textarea name='tujuan' cols='50' rows='3'>$d[tujuan]</textarea></label>
       </div>
	</td>
	</tr>
	
	</table>
	
    <div class='button-section'>
	<input type='submit' name='submit' id='submit' value='Simpan'></form>	
     </span>
    </div></div></div>";


}	

break;

case "bookingrental":


if (isset($_POST['submit'])) {

	$now=date('Y-m-d');
	
	$queryvalid="SELECT * FROM booking_mobil INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
	WHERE 	booking_mobil.no_polisi='$_POST[no_polisi]' AND
			booking_mobil.tanggal_pemakaian='$_POST[tanggal_pemakaian]' AND
			booking_mobil.jam_start BETWEEN '$_POST[jam_start]' AND '$_POST[jam_selesai]' AND
			booking_selesai.jam_selesai BETWEEN '$_POST[jam_selesai]' AND '$_POST[jam_selesai]'
			";
	$queryvalid=mysql_query($queryvalid);		
	$row = mysql_num_rows($queryvalid);
	
IF($row=='0'){
	$query="SELECT * FROM booking_mobil WHERE tgl_booking='$now'";
	$query=mysql_query($query);		
	$no1 = mysql_num_rows($query);

$nobooking=$no1+1;
$length=strlen($nobooking);

$tahun1  = date('Y');
$bulan1  = date('m');
$date1  = date('d');



if ($length=='1')
		{
		$kode_booking="$tahun1"."$bulan1"."$date1"."-000"."$nobooking";
		}	
elseif ($length=='2')
		{
		   $kode_booking="$tahun1"."$bulan1"."$date1"."-00"."$nobooking";
		}			
elseif ($length=='3')
		{
		  $kode_booking="$tahun1"."$bulan1"."$date1"."-0"."$nobooking";
		}	
elseif ($length=='4')
		{
		  $kode_booking="$tahun1"."$bulan1"."$date1"."-"."$nobooking";
		}
		
$tgl_booking=date('Y-m-d');
$jam_booking=date('h:i:s');
		
IF($_POST['jam_start']<12 AND $_POST['jam_selesai']>12)
{$report_makan="ya";}
ELSE{$report_makan="no";}
	if ($_SESSION['s_position']=='HOD')
	{$sql = "INSERT INTO booking_mobil(kode_booking,
										tgl_booking,
										jam_booking,
										tanggal_pemakaian,
										jam_start,
										instansi,
										area,
										pic_dituju,
										keperluan,
										no_employee,
										report_makan,
										no_polisi,
										approved,
										status_booking) 
		VALUES ('$kode_booking',
				'$tgl_booking',
				'$jam_booking',
				'$_POST[tanggal_pemakaian]',
				'$_POST[jam_start]',
				'$_POST[instansi]',
				'$_POST[area]',
				'$_POST[pic_dituju]',
				'$_POST[keperluan]',
				'$_POST[no_employee]',
				'$report_makan',
				'$_POST[no_polisi]',
				'1',
				'booked')";}
	else{
	$sql = "INSERT INTO booking_mobil(kode_booking,
										tgl_booking,
										jam_booking,
										tanggal_pemakaian,
										jam_start,
										instansi,
										area,
										pic_dituju,
										keperluan,
										no_employee,
										report_makan,
										no_polisi,
										approved,
										status_booking) 
		VALUES ('$kode_booking',
				'$tgl_booking',
				'$jam_booking',
				'$_POST[tanggal_pemakaian]',
				'$_POST[jam_start]',
				'$_POST[instansi]',
				'$_POST[area]',
				'$_POST[pic_dituju]',
				'$_POST[keperluan]',
				'$_POST[no_employee]',
				'$report_makan',
				'$_POST[no_polisi]',
				'0',
				'booked')";
				
				}
        $query = mysql_query($sql);
		
		
		$sql2 = "INSERT INTO booking_selesai(kode_booking,
										jam_selesai) 
		VALUES ('$kode_booking',
				'$_POST[jam_selesai]'
				)";
        $query = mysql_query($sql2);
        
	
	$sql3 = "INSERT INTO bonceng(kode_booking,nama_penumpang,
										tujuan) 
		VALUES ('$kode_booking',
				'$_POST[nama_penumpang]',
				'$_POST[tujuan]'
				
				)";
		$query3 = mysql_query($sql3);		
if ($_SESSION['s_position']=='HOD'){}
else{
//================================================mail for hod=========================================		
$view5 = mysql_query("SELECT * FROM master_employee INNER JOIN section
							ON master_employee.kode_section=section.kode_section
								INNER JOIN department
							ON department.kode_department=section.kode_department
			WHERE department.nama_department LIKE '%$_POST[no_employee]%'");		
$r2    = mysql_fetch_array($view5);
$namadepartment = $r2['nama_department'];	
		
		
		$view = mysql_query("SELECT * FROM master_employee INNER JOIN section
							ON master_employee.kode_section=section.kode_section
								INNER JOIN department
							ON department.kode_department=section.kode_department
			WHERE department.nama_department LIKE '%$namadepartment%' AND master_employee.position_user LIKE '%HOD%'");		
	$r    = mysql_fetch_array($view);
	$spvmail = $r['personal_email_employee'];	
	
		
$booking = mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
					INNER JOIN section
							ON master_employee.kode_section=section.kode_section
					INNER JOIN department
							ON department.kode_department=section.kode_department
			WHERE booking_mobil.kode_booking='$kode_booking' ORDER BY booking_mobil.jam_start ASC");
$booking   = mysql_fetch_array($booking);

$tgl_booking = $booking['tgl_booking'];
$tanggal_pemakaian = $booking['tanggal_pemakaian'];
$jam_start = $booking['jam_start'];
$jam_selesai = $booking['jam_selesai'];
$instansi = $booking['instansi'];
$pic_dituju = $booking['pic_dituju'];
$nama_penumpang = $booking['nama_penumpang'];
				
	
$mailperson=$_SESSION['s_email'];


$to = "$spvmail";
$subject = "[Pengajuan Tugas Luar]($_SESSION[s_no_employee] | $_SESSION[s_nama_employee])";

$message = "
<html>
<head>
</head>
<body>
<p>Pengajuan Tugas Luar</p>
<table width='100%' cellspacing='0' celpadding='0' border='1'>
<tr bgcolor='#60f860'>
<th>No.</th>
<th>NIK</th>
<th>Nama</th>
<th>Tanggal Booking</th>
<th>Jam Mulai</th>
<th>Jam Selesai</th>
<th>Nama Penumpang</th>
<th>Instansi Dituju</th>
</tr>


<tr>
<td>1.</td>
<td>$_SESSION[s_no_employee]</td>
<td>$_SESSION[s_nama_employee]</td>
<td>".tglindo($tanggal_pemakaian)."</td>
<td>".substr("$jam_start",0,5)."</td>
<td>".substr("$jam_selesai",0,5)."</td>
<td>$nama_penumpang</td>
<td>$instansi<br>$pic_dituju</td>
</tr>
</table>
</body>
</html>

<br><a href='http://192.168.0.111/ocbs/'>Klik Link Berikut Untuk Akses Aplikasi OCBS</a><br>
<i><font size='-1'>*Mohon untuk tidak dibalas email ini.</font></i>
<p>Terima Kasih</p><br>
<p>Admin E-Leave</p>";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";



// More headers
$headers .= 'From: <admincobs@sanden.co.id>' . "\r\n";
$headers .= 'Cc: $mailperson' . "\r\n";

mail($to,$subject,$message,$headers);}
//===============================================================================	


		
	
 if ($query) {
            echo"<script language='javascript'>alert ('input data berhasil'); </script>
	<script language='javascript'>
	document.location.href='?page=schedule&act=tampilschedule'</script>";
        } 	
}
else{
echo "<script language='javascript'>alert ('Untuk Tanggal & Jam Tersebut Sudah di Booking'); document.location.href='?page=booking&act=menubooking'</script>";
}

}

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
echo "<div class='panel-heading'><h4>Booking Mobil Rental</h4></div> <div class='control-group'>";

	echo "<div class='form-style-10'>
	<form method='POST' action='?page=booking&act=bookingrental' onSubmit='return validasi(this)' enctype='multipart/form-data'>
	<table cellpadding='2' cellspacing='0' border='0'>
	<tr>
	<td valign='top'>
	<div class='section'>Data kendaraan</div>
    <div class='inner-wrap'>
        <label>No. Polisi <select name='no_polisi' id='no_polisi'>
		 	<option value=''>No Polisi - Driver - Type Mobil</option>";
		 	 $tampil1=mysql_query("SELECT * FROM master_mobil WHERE kepemilikan_mobil IN( 'Rental') ORDER BY no_polisi ASC");
            while($s=mysql_fetch_array($tampil1)){
              echo "<option value='$s[no_polisi]'>$s[no_polisi] - $s[nama_driver] - $s[type_mobil] </option>";
            }
									echo "</select></label>
       </div>
	</td><td valign='top'>
    <div class='section'>Detail Booking</div>
    <div class='inner-wrap'> 
	<label>Nama Pembooking<select name='no_employee'>
		 		<option value=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama Pembooking&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
		 	 
		
		
		$tampil2=mysql_query("SELECT * FROM master_employee  ORDER BY nama_employee ASC");
            while($w=mysql_fetch_array($tampil2)){
              echo "<option value='$w[no_employee]'>$w[nama_employee]</option>";
            }
				
			echo "</select></label>
        <label>Tanggal Booking <input type='text'  size='35' name='tanggal_pemakaian'  id='tanggal1' size=10/></label>
        <label>Jam Mulai <select name='jam_start' id='jammulai' width='20'>
		 		<option value=''>Jam Mulai</option>";
		 	 $tampil=mysql_query("SELECT * FROM jam_booking ORDER BY jam_start ASC");
            while($w=mysql_fetch_array($tampil)){
              echo "<option value=$w[jam_start_value]>$w[jam_start]</option>";
            }
			
			
									echo "</select></label>
			 <label>Jam Selesai <select name='jam_selesai' id='jam_selesai'>
		 		<option value=''>Jam Selesai</option>";
		 	 $tampil=mysql_query("SELECT * FROM jam_booking ORDER BY jam_start ASC");
            while($w=mysql_fetch_array($tampil)){
              echo "<option value=$w[jam_finish_value]>$w[jam_finish]</option>";
            }
			//jam selesai
			
									echo "</select></label>						
    </div>
	</td>
	</tr>
	<tr>
	<td valign='top'>
	<div class='section'>Detail Keperluan</div>
    <div class='inner-wrap'>
        <label>Instansi/Individu <input type='text' size='35' name='instansi' value='$d[instansi]' size=10></label>
        <label>Area/Daerah <input type='text' size='50'  name='area' value='$d[area]' size=10></label>
        <label>Bertemu Dengan <input type='text' size='35' name='pic_dituju' value='$d[pic_dituju]' size=10></label>
        <label>Keperluan <textarea name='keperluan' cols='50' rows='3'>$d[keperluan]</textarea></label>
       </div>
	</td>
	
	<td valign='top'>
	<div class='section'>Data Penumpang</div>
    <div class='inner-wrap'>
        <label>Nama Penumpang<select name='nama_penumpang'>
		 		<option value=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama Penumpang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
		 	 
		
		
		$tampil2=mysql_query("SELECT * FROM master_employee  ORDER BY nama_employee ASC");
            while($w=mysql_fetch_array($tampil2)){
              echo "<option value='$w[nama_employee]'>$w[nama_employee]</option>";
            }
				
			echo "</select></label>
         <label>Tujuan & Keperluan Penumpang <textarea name='tujuan' cols='50' rows='3'>$d[tujuan]</textarea></label>
       </div>
	</td>
	</tr>
	
	</table>
	
    <div class='button-section'>
	<input type='submit' name='submit' id='submit' value='Save'></form>	
     </span>
    </div></div></div>";


	

break;

case "bookingtgl":

if (isset($_POST['submit'])) {

	$now=date('Y-m-d');
	
	$queryvalid="SELECT * FROM booking_mobil INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
	WHERE 	booking_mobil.no_polisi='$_POST[no_polisi]' AND
			booking_mobil.tanggal_pemakaian='$_POST[tanggal_pemakaian]' AND
			booking_mobil.jam_start BETWEEN '$_POST[jam_start]' AND '$_POST[jam_selesai]' AND
			booking_selesai.jam_selesai BETWEEN '$_POST[jam_selesai]' AND '$_POST[jam_selesai]'
			";
	$queryvalid=mysql_query($queryvalid);		
	$row = mysql_num_rows($queryvalid);
	
IF($row=='0'){
	$query="SELECT * FROM booking_mobil WHERE tgl_booking='$now'";
	$query=mysql_query($query);		
	$no1 = mysql_num_rows($query);

$nobooking=$no1+1;
$length=strlen($nobooking);

$tahun1  = date('Y');
$bulan1  = date('m');
$date1  = date('d');



if ($length=='1')
		{
		$kode_booking="$tahun1"."$bulan1"."$date1"."-000"."$nobooking";
		}	
elseif ($length=='2')
		{
		   $kode_booking="$tahun1"."$bulan1"."$date1"."-00"."$nobooking";
		}			
elseif ($length=='3')
		{
		  $kode_booking="$tahun1"."$bulan1"."$date1"."-0"."$nobooking";
		}	
elseif ($length=='4')
		{
		  $kode_booking="$tahun1"."$bulan1"."$date1"."-"."$nobooking";
		}
		
$tgl_booking=date('Y-m-d');
$jam_booking=date('h:i:s');
		
IF($_POST['jam_start']<12 AND $_POST['jam_selesai']>12)
{$report_makan="ya";}
ELSE{$report_makan="no";}
	if ($_SESSION['s_position']=='HOD')
	{$sql = "INSERT INTO booking_mobil(kode_booking,
										tgl_booking,
										jam_booking,
										tanggal_pemakaian,
										jam_start,
										instansi,
										area,
										pic_dituju,
										keperluan,
										no_employee,
										report_makan,
										no_polisi,
										approved,
										status_booking) 
		VALUES ('$kode_booking',
				'$tgl_booking',
				'$jam_booking',
				'$_POST[tanggal_pemakaian]',
				'$_POST[jam_start]',
				'$_POST[instansi]',
				'$_POST[area]',
				'$_POST[pic_dituju]',
				'$_POST[keperluan]',
				'$_SESSION[s_no_employee]',
				'$report_makan',
				'$_POST[no_polisi]',
				'1',
				'booked')";}
	else{
	$sql = "INSERT INTO booking_mobil(kode_booking,
										tgl_booking,
										jam_booking,
										tanggal_pemakaian,
										jam_start,
										instansi,
										area,
										pic_dituju,
										keperluan,
										no_employee,
										report_makan,
										no_polisi,
										approved,
										status_booking) 
		VALUES ('$kode_booking',
				'$tgl_booking',
				'$jam_booking',
				'$_POST[tanggal_pemakaian]',
				'$_POST[jam_start]',
				'$_POST[instansi]',
				'$_POST[area]',
				'$_POST[pic_dituju]',
				'$_POST[keperluan]',
				'$_SESSION[s_no_employee]',
				'$report_makan',
				'$_POST[no_polisi]',
				'0',
				'booked')";
				
				}
        $query = mysql_query($sql);
		
		
		$sql2 = "INSERT INTO booking_selesai(kode_booking,
										jam_selesai) 
		VALUES ('$kode_booking',
				'$_POST[jam_selesai]'
				)";
        $query = mysql_query($sql2);
        
	
	$sql3 = "INSERT INTO bonceng(kode_booking,
									nama_penumpang,
										tujuan) 
		VALUES ('$kode_booking',
				'$_POST[nama_penumpang]',
				'$_POST[tujuan]'
				
				)";
		$query3 = mysql_query($sql3);		
if ($_SESSION['s_position']=='HOD'){}
else{
//================================================mail for hod=========================================				
		$view = mysql_query("SELECT * FROM master_employee INNER JOIN section
							ON master_employee.kode_section=section.kode_section
								INNER JOIN department
							ON department.kode_department=section.kode_department
			WHERE department.nama_department LIKE '%$_SESSION[s_department]%' AND master_employee.position_user LIKE '%HOD%'");		
	$r    = mysql_fetch_array($view);
	$spvmail = $r['personal_email_employee'];	
	
		
$booking = mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
					INNER JOIN section
							ON master_employee.kode_section=section.kode_section
					INNER JOIN department
							ON department.kode_department=section.kode_department
			WHERE booking_mobil.kode_booking='$kode_booking' ORDER BY booking_mobil.jam_start ASC");
$booking   = mysql_fetch_array($booking);

$tgl_booking = $booking['tgl_booking'];
$tanggal_pemakaian = $booking['tanggal_pemakaian'];
$jam_start = $booking['jam_start'];
$jam_selesai = $booking['jam_selesai'];
$instansi = $booking['instansi'];
$pic_dituju = $booking['pic_dituju'];
$nama_penumpang = $booking['nama_penumpang'];
				
	
$mailperson=$_SESSION['s_email'];


$to = "$spvmail";
$subject = "[Pengajuan Tugas Luar]($_SESSION[s_no_employee] | $_SESSION[s_nama_employee])";

$message = "
<html>
<head>
</head>
<body>
<p>Pengajuan Tugas Luar</p>
<table width='100%' cellspacing='0' celpadding='0' border='1'>
<tr bgcolor='#60f860'>
<th>No.</th>
<th>NIK</th>
<th>Nama</th>
<th>Tanggal Booking</th>
<th>Jam Mulai</th>
<th>Jam Selesai</th>
<th>Nama Penumpang</th>
<th>Instansi Dituju</th>
</tr>


<tr>
<td>1.</td>
<td>$_SESSION[s_no_employee]</td>
<td>$_SESSION[s_nama_employee]</td>
<td>".tglindo($tanggal_pemakaian)."</td>
<td>".substr("$d[jam_start]",0,5)."</td>
<td>".substr("$d[jam_selesai]",0,5)."</td>
<td>$nama_penumpang</td>
<td>$instansi<br>$pic_dituju</td>
</tr>
</table>
</body>
</html>

<br><a href='http://192.168.0.111/ocbs/'>Klik Link Berikut Untuk Akses Aplikasi OCBS</a><br>
<i><font size='-1'>*Mohon untuk tidak dibalas email ini.</font></i>
<p>Terima Kasih</p><br>
<p>Admin E-Leave</p>";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";



// More headers
$headers .= 'From: <admincobs@sanden.co.id>' . "\r\n";
$headers .= 'Cc: $mailperson' . "\r\n";

mail($to,$subject,$message,$headers);}
//===============================================================================	


		
	
 if ($query) {
            echo"<script language='javascript'>alert ('input data berhasil'); </script>
	<script language='javascript'>
	document.location.href='?page=schedule&act=tampilschedule'</script>";
        } 	
}
else{
echo "<script language='javascript'>alert ('Untuk Tanggal & Jam Tersebut Sudah di Booking'); document.location.href='?page=booking&act=menubooking'</script>";
}

}

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
echo "<div class='panel-heading'><h4>Booking Mobil</h4></div> <div class='control-group'>";
	
	echo "<div class='form-style-10'>
	<form method='POST' action='?page=booking&act=bookingtgl' onSubmit='return validasi(this)' enctype='multipart/form-data'>
	<table cellpadding='2' cellspacing='0' border='0'>
	<tr>
	<td valign='top'>
	<div class='section'>Data kendaraan</div>
    <div class='inner-wrap'>
        <label>No. Polisi <select name='no_polisi' id='no_polisi'>
		 	<option value=''>No Polisi - Driver - Type Mobil</option>";
		 	 $tampil1=mysql_query("SELECT * FROM master_mobil WHERE kepemilikan_mobil IN( 'Operasional') ORDER BY no_polisi ASC");
            while($s=mysql_fetch_array($tampil1)){
              echo "<option value='$s[no_polisi]'>$s[no_polisi] - $s[nama_driver] - $s[type_mobil] </option>";
            }
									echo "</select>	</label>
        
    </div>
	</td><td valign='top'>
    <div class='section'>Detail Booking</div>
    <div class='inner-wrap'>
        <label>Tanggal Booking <input type='text'  size='35' name='tanggal_pemakaian' value='$_GET[tgl]'  id='tanggal1' size=10/></label>
        <label>Jam Mulai <select name='jam_start' id='jammulai' width='20'>
		 		<option value=''>Jam Mulai</option>";
		 	 $tampil=mysql_query("SELECT * FROM jam_booking ORDER BY jam_start ASC");
            while($w=mysql_fetch_array($tampil)){
              echo "<option value=$w[jam_start_value]>$w[jam_start]</option>";
            }
			
			
									echo "</select></label>
			 <label>Jam Selesai <select name='jam_selesai' id='jam_selesai'>
		 		<option value=''>Jam Selesai</option>";
		 	 $tampil=mysql_query("SELECT * FROM jam_booking ORDER BY jam_start ASC");
            while($w=mysql_fetch_array($tampil)){
              echo "<option value=$w[jam_finish_value]>$w[jam_finish]</option>";
            }
			//jam selesai
			
									echo "</select></label>						
    </div>
	</td>
	</tr>
	<tr>
	<td valign='top'>
	<div class='section'>Detail Keperluan</div>
    <div class='inner-wrap'>
        <label>Instansi/Individu <input type='text' size='35' name='instansi' value='$d[instansi]' size=10></label>
        <label>Area/Daerah <input type='text' size='50'  name='area' value='$d[area]' size=10></label>
        <label>Bertemu Dengan <input type='text' size='35' name='pic_dituju' value='$d[pic_dituju]' size=10></label>
        <label>Keperluan <textarea name='keperluan' cols='50' rows='3'>$d[keperluan]</textarea></label>
       </div>
	</td>
	
	<td valign='top'>
	<div class='section'>Data Penumpang</div>
    <div class='inner-wrap'>
        <label>Nama Penumpang<select name='nama_penumpang'>
		 		<option value=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama Penumpang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
		 	 
		
		
		$tampil2=mysql_query("SELECT * FROM master_employee WHERE no_employee NOT IN('$_SESSION[s_no_employee]') ORDER BY nama_employee ASC");
            while($w=mysql_fetch_array($tampil2)){
              echo "<option value='$w[nama_employee]'>$w[nama_employee]</option>";
            }
				
			echo "</select></label>
         <label>Tujuan & Keperluan Penumpang <textarea name='tujuan' cols='50' rows='3'>$d[tujuan]</textarea></label>
       </div>
	</td>
	</tr>
	
	</table>
	
    <div class='button-section'>
	<input type='submit' name='submit' id='submit' value='Save'></form>	
     </span>
    </div></div></div>";




//===========================================================================================

		
	
break;



case "penumpang":
if (isset($_POST['submit'])) {

mysql_query("UPDATE bonceng SET kode_booking= '$_POST[kode_booking]',
								nama_penumpang= '$_POST[nama_penumpang]',
								tujuan= '$_POST[tujuan]'
							  WHERE kode_booking ='$_POST[kode_booking]'");

	 echo"<script language='javascript'>alert ('Update data berhasil'); </script>
	<script language='javascript'>
	document.location.href='?page=schedule&act=tampilschedule'</script>";						  
}

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

echo "<div class='panel-heading'><h4>Booking Mobil Sebagai Penumpang</h4></div> <div class='control-group'>";

$query = mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
					
			WHERE booking_mobil.kode_booking='$_POST[kode_booking]'ORDER BY booking_mobil.jam_start ASC");
	
	while ($d = mysql_fetch_array($query)) {
	echo "<div class='form-style-10'>
	<form method='POST' action='?page=booking&act=penumpang' onSubmit='return validasi(this)' enctype='multipart/form-data'>
	<table cellpadding='2' cellspacing='0' border='0'>
	<tr>
	<td valign='top'>
	<div class='section'>Data kendaraan</div>
    <div class='inner-wrap'>
        <label>No. Polisi <input type='text' disabled readonly='' size='25' name='no_polisi1' value='$d[no_polisi]' size=10>
							<input type='text' readonly='' size='25' name='kode_booking' value='$d[kode_booking]' size=10></label>
        <label>Driver <input type='text' size='40' disabled readonly='' name='nama_driver' value='$d[nama_driver]' size=10></label>
        <label>Jenis/Type Kendaraan <input type='text' size='50' disabled readonly='' name='typemobil' value='$d[type_mobil]' size=10></label>
    </div>
	</td><td valign='top'>
    <div class='section'>Detail Booking</div>
    <div class='inner-wrap'>
        <label>Tanggal Booking <input type='text' disabled size='35' value='".tglindo($d['tanggal_pemakaian'])."' name='tanggal_pemakaian'  id='tanggal1' size=10/></label>
        <label>Jam Mulai <input type='text' disabled size='35' value='$d[jam_start]' name='jam_mulai'  id='tanggal1' size=10/></label>
        <label>Jam Selesai <input type='text' disabled size='35' value='$d[jam_selesai]' name='jam_selesai'  id='tanggal1' size=10/></label>
							
    </div>
	</td>
	</tr>
	<tr>
	<td valign='top'>
	<div class='section'>Detail Keperluan</div>
    <div class='inner-wrap'>
        <label>Instansi/Individu <input type='text' size='35' disabled name='instansi' value='$d[instansi]' size=10></label>
        <label>Area/Daerah <input type='text' size='50' disabled name='area' value='$d[area]' size=10></label>
        <label>Bertemu Dengan <input type='text' size='35' disabled name='pic_dituju' value='$d[pic_dituju]' size=10></label>
        <label>Keperluan <textarea name='keperluan' cols='50' rows='3' disabled>$d[keperluan]</textarea></label>
       </div>
	</td>
	
	<td valign='top'>
	<div class='section'>Data Penumpang</div>
    <div class='inner-wrap'>";
	IF ($d['nama_penumpang']=='')
	{
       echo "<label>Nama Penumpang<input type='text' size='35' readonly='' name='nama_penumpang' value='$_SESSION[s_nama_employee]' size=10></label>";
      }
	 else {
		 echo "<label>Nama Penumpang<input type='text' size='35' readonly='' name='nama_penumpang' value='$d[nama_penumpang]' size=10></label>";
      
		 
	 } 
	  echo "  <label>Tujuan & Keperluan Penumpang Tambahan<textarea name='tujuan' cols='50' rows='3'>$d[tujuan]</textarea></label>
       </div>
	</td>
	</tr>";
	}
	ECHO "</table>
	
    <div class='button-section'>
	<input type='submit' name='submit' id='submit' value='Simpan'></form>";	
	
if ($_SESSION['s_position']=='admin'){	
	echo "<br><form action='?page=booking&act=detailbooking' method='post'>
	<input type='hidden' size='35'  name='kode_booking' value='$d[kode_booking]'>
<input type='submit' name='cancel' id='cancel' value='Hapus'></form>";
}
	
    echo "</span>
    </div></div></div>";

		
break;

case "approvalbooking":	
 ?>
 <div class="panel-heading"><h4>Approval Booking</h4></div>
<?php 
if ($_SESSION['s_department']=='Quality' AND $_SESSION['s_position']=='HOD'){
$query="SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
					INNER JOIN section
							ON master_employee.kode_section=section.kode_section
					INNER JOIN department
							ON department.kode_department=section.kode_department
			WHERE booking_mobil.approved='0' AND department.kode_department in ('4','7')
			ORDER BY booking_mobil.jam_start ASC";
}

else if ($_SESSION['s_position']=='HOD'){
$query="SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
					INNER JOIN section
							ON master_employee.kode_section=section.kode_section
					INNER JOIN department
							ON department.kode_department=section.kode_department
							
			WHERE booking_mobil.approved='0' AND department.nama_department='$_SESSION[s_department]' AND booking_mobil.status_booking='booked'
			ORDER BY booking_mobil.jam_start ASC";
}

$query = mysql_query($query);

$row = mysql_num_rows($query);
	if($row=='0'){echo "<br><br><br><br><br><center>NO DATA APPROVAL</center><br><br><br><br><br><br>";}
	else{
		
		  echo "<br><table  border='1' class='CSS_Table_Example'>
          <tr bgcolor='#e1dada'>
		<td width='1%'>No.</td>
	<td width='8%'>No. Polisi</td>
	<td width='8%'>Driver</td>
	<td width='13%'>Penumpang 1</td>
	<td width='15%'>Penumpang 2</td>
	<td width='15%'>Tanggal</td>
	<td width='9%'>Jam Mulai</td>
	<td width='10%'>Jam Selesai</td>
	<td width='18%'>Tujuan</td>
	<td width='20%'>Approval</td></tr>";
		
	$no = 1;
    while($d=mysql_fetch_array($query)){
			
            echo "<tr valign='top'><td>$no.</td>
			<td>$d[no_polisi]</td>
			<td>$d[nama_driver]</td>
			<td>$d[nama_employee]</td>
			<td>$d[nama_penumpang]</td>
			<td>$d[tanggal_pemakaian]</td>
			
			
			<td align='center>'>".substr("$d[jam_start]",0,5)."</td>
			<td>".substr("$d[jam_selesai]",0,5)."</td>
			<td>$d[instansi]</td>";
			
				 echo "<td>
				 <form method='POST' action='?page=booking&act=actionapprovcobs'>
				 <input type='hidden' name='kode_booking' value='$d[kode_booking]' size=15>
				 <input type='submit' value='Disetujui atau Tidak'></form></tr>";
      $no++;
    }
    echo "</table><br><br><br><br>";			
}
			
break;


case "actionapprovcobs":


if (isset($_POST['submit'])) {

if ($_POST['approved']==1){
$query="SELECT * FROM master_employee INNER JOIN 
				booking_mobil ON master_employee.no_employee=booking_mobil.no_employee
			INNER JOIN
				booking_selesai ON booking_selesai.kode_booking=booking_mobil.kode_booking
			INNER JOIN 
				master_mobil ON master_mobil.no_polisi=booking_mobil.no_polisi
			WHERE booking_mobil.kode_booking ='$_POST[kode_booking]'";
$query2 = mysql_query($query);

 while ($d = mysql_fetch_array($query2)) {
	
	$tanggalpakai=$d['tanggal_pemakaian'];
	$jam_start=$d['jam_start'];
	$jam_selesai=$d['jam_selesai'];
	
	$awal=substr($jam_start,0,2);
	$akhir=substr($jam_selesai,0,2);
	
	
	for ($awal; $awal<=$akhir; $awal++) {
	
	$length=strlen($awal);
	if($length==1){$awal="0".$awal;}
	
	$tgl_tanpastrip=str_replace("-","",$tanggalpakai);
	$kodeunik=$tgl_tanpastrip."_".$d['no_polisi']."-".$awal;
	

	
	$sql = "INSERT INTO schedule_booking(kodeunik,kode_booking) 
		VALUES ('$kodeunik',
			'$_POST[kode_booking]')";
	 $query = mysql_query($sql);	
	
	}
 
 } 
}

else{}
//==================================================

mysql_query("UPDATE booking_mobil SET approved= '$_POST[approved]',
								approved_name='$_SESSION[s_nama_employee]'
							  WHERE kode_booking ='$_POST[kode_booking]'");
							  
 echo"<script language='javascript'>alert ('Approval berhasil'); </script>
	<script language='javascript'>
	document.location.href='?page=booking&act=approvalbooking'</script>";
							  
}

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
echo "<div class='panel-heading'><h4>Booking Mobil</h4></div> <div class='control-group'>";

$query = mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee	
			WHERE booking_mobil.kode_booking='$_POST[kode_booking]'ORDER BY booking_mobil.jam_start ASC");
	
	while ($d = mysql_fetch_array($query)) {
	echo "<div class='form-style-10'>
	<form method='POST' action='?page=booking&act=actionapprovcobs' onSubmit='return validasi(this)' enctype='multipart/form-data'>
	<table cellpadding='2' cellspacing='0' border='0'>
	<tr>
	<td valign='top'>
	<div class='section'>Data kendaraan</div>
    <div class='inner-wrap'>
        <label>No. Polisi <input type='text' disabled readonly='' size='25' name='no_polisi1' value='$d[no_polisi]' size=10>
							<input type='hidden' readonly='' size='25' name='kode_booking' value='$d[kode_booking]' size=10></label>
        <label>Driver <input type='text' size='40' disabled readonly='' name='nama_driver' value='$d[nama_driver]' size=10></label>
        <label>Jenis/Type Kendaraan <input type='text' size='50' disabled readonly='' name='typemobil' value='$d[type_mobil]' size=10></label>
    </div>
	</td><td valign='top'>
    <div class='section'>Detail Booking</div>
    <div class='inner-wrap'>
        <label>Tanggal Booking <input type='text' disabled size='35' value='".tglindo($d['tanggal_pemakaian'])."' name='tanggal_pemakaian'  id='tanggal1' size=10/></label>
        <label>Jam Mulai <input type='text' disabled size='35' value='$d[jam_start]' name='jam_mulai'  id='tanggal1' size=10/></label>
        <label>Jam Selesai <input type='text' disabled size='35' value='$d[jam_selesai]' name='jam_selesai'  id='tanggal1' size=10/></label>
							
    </div>
	</td>
	</tr>
	<tr>
	<td valign='top'>
	<div class='section'>Detail Keperluan</div>
    <div class='inner-wrap'>
        <label>Instansi/Individu <input type='text' size='35' disabled name='instansi' value='$d[instansi]' size=10></label>
        <label>Area/Daerah <input type='text' size='50' disabled name='area' value='$d[area]' size=10></label>
        <label>Bertemu Dengan <input type='text' size='35' disabled name='pic_dituju' value='$d[pic_dituju]' size=10></label>
        <label>Keperluan <textarea name='keperluan' cols='50' rows='3' disabled>$d[keperluan]</textarea></label>
       </div>
	</td>
	
	<td valign='top'>
	<div class='section'>Data Penumpang</div>
    <div class='inner-wrap'>
        <label>Nama Penumpang<input type='text' size='35' disabled name='nama_penumpang' value='$d[nama_penumpang]' size=10></label>
         <label>Tujuan & Keperluan Penumpang <textarea name='tujuan' cols='50' rows='3' disabled>$d[tujuan]</textarea></label>
       </div>
	</td>
	</tr>
	
	<tr>
	<td valign='top'>
	<div class='section'>Approval</div>
    <div class='inner-wrap'>
        <label>Approval
		<select name='approved' id='approved' width='20'>
		<option value='1'>Disetujui</option>
		<option value='2'>Tidak Disetujui</option>
		</select>
		</div>
	</td>
	</tr>
	<tr>
	<td><input type='submit'  name='submit' id='submit' value='Simpan'></td>
	</tr>
	
	</table>
	
    <div class='button-section'>
	</form>	
     </span>
    </div></div></div>";


}

break;


case "riwayatbooking":

 ?>
 <div class="panel-heading"><h4>Riwayat Booking</h4></div>
<?php 
  echo "<br>";
  
  
	
	echo "
	&nbsp;&nbsp;&nbsp;&nbsp;<center>
	<table border='1' class='CSS_Table_Example' style='width:95%;height:5px;'>
	<tr bgcolor='#e1dada'>
	<td width='1%'>No.</td>
	<td width='8%'>No. Polisi</td>
	<td width='8%'>Driver</td>
	<td width='13%'>Nama Karyawan</td>
	<td width='15%'>Nama Penumpang</td>
	<td width='13%'>Tanggal Booking</td>
	<td width='9%'>Jam Mulai</td>
	<td width='10%'>Jam Selesai</td>
	<td width='18%'>Tujuan</td>
	</tr>";
	
	if ($_SESSION['s_position']=='HOD'){
	$query="SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
					INNER JOIN section
							ON master_employee.kode_section=section.kode_section
					INNER JOIN department
							ON department.kode_department=section.kode_department
							
			WHERE department.nama_department='$_SESSION[s_department]' AND booking_mobil.status_booking NOT IN ('remove')
			ORDER BY booking_mobil.tanggal_pemakaian DESC";
	}
	
	else {
		
		$query="SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee	
			WHERE booking_mobil.no_employee='$_SESSION[s_no_employee]' AND booking_mobil.status_booking NOT IN ('remove') 
			ORDER BY booking_mobil.tanggal_pemakaian DESC";
		
	}
    $query = mysql_query($query);
	$row = mysql_num_rows($query);
        
	$no = 0;
	IF($row==0)
		{  echo "<tr><td>1.</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			
			</td></tr>";}
        while ($d = mysql_fetch_array($query)) {
            $no++;
            echo "<tr valign='top'><td>$no.</td>
			<td><b><a href='?page=booking&act=detailbooking&kode_booking=$d[kode_booking]'>$d[no_polisi]</a></b></td>
			<td>$d[nama_driver]</td>
			
			<td>$d[nama_employee]</td>";
			if($d['no_employee']==$_SESSION['s_no_employee']){
			echo "<td>$d[nama_penumpang]</td>";
			}
			else{
			if ($_SESSION['s_position']=='HOD'){echo "<td>$d[nama_penumpang]</td>";}
			else{
			if($d['nama_penumpang']=='')
				{
				
				echo "<td><form method='POST' action='?page=booking&act=penumpang'>
				 <input type='hidden' name='kode_booking' value='$d[kode_booking]' size=15>
				 <input type='submit' value='Menumpang' size='100%'></form></td>";}
			else{
			echo "<td>$d[nama_penumpang]</td>";}}}
			
			
			echo "<td>".tglindo (date($d['tanggal_pemakaian']))."</td>
			<td align='center>'>".substr("$d[jam_start]",0,5)."</td>
			<td>".substr("$d[jam_selesai]",0,5)."</td>
			
			<td>$d[instansi]</td>";
			
        }
	
	
	echo "
	</table></center><br>
	";
   

 echo "<br><br><br><br>";

 
 break;
 
 case "detailbooking";
 
 if (isset($_POST['cancel'])) {


 if ($_POST['position']=='HOD'){
	
		
	
		
$booking = mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
					INNER JOIN section
							ON master_employee.kode_section=section.kode_section
					INNER JOIN department
							ON department.kode_department=section.kode_department
			WHERE booking_mobil.kode_booking='$_GET[kode_booking]' ORDER BY booking_mobil.jam_start ASC");
$booking   = mysql_fetch_array($booking);

$tgl_booking = $booking['tgl_booking'];
$no_polisi = $booking['no_polisi'];
$nama_driver = $booking['nama_driver'];
$tanggal_pemakaian = $booking['tanggal_pemakaian'];
$type_mobil = $booking['type_mobil'];
$jam_start = $booking['jam_start'];
$instansi = $booking['instansi'];
$pic_dituju = $booking['pic_dituju'];
$nama_penumpang = $booking['nama_penumpang'];
$tujuan = $booking['tujuan'];
$area = $booking['area'];
$pic_dituju = $booking['pic_dituju'];
$keperluan = $booking['keperluan'];
	

	
$jam_selesai1=$booking['jam_selesai'];
	
	$query2=mysql_query("SELECT * FROM  jam_booking
					WHERE  jam_booking.jam_start_value LIKE '%$jam_selesai1%'"); 
	$dat2=mysql_fetch_array($query2);
	
	$kode_jam=$dat2['kode_jam']+1;
	
	$query3=mysql_query("SELECT * FROM  jam_booking
					WHERE  jam_booking.kode_jam LIKE '%$kode_jam%'"); 
	$dat3=mysql_fetch_array($query3);
	
$jam_selesai=$dat3['jam_selesai'];	

	
$mailperson=$booking['personal_email_employee'];



$to = "$mailperson";
$subject = "[Pembatalan Pengajuan Tugas Luar] | ($booking[no_employee] | $booking[nama_employee])";

$message = "
<html>
<head>
</head>
<body>
<p><b>Pembatalan Pengajuan Tugas Luar / Penggunaan Mobil Operasional</b>
<br><font size='-1'><i>(Cancel Working Out of Office / Cancel Car Operational Usage)</i></font></p>
<table width='100%' cellspacing='0' celpadding='0' border='1'>
<tr>
<td bgcolor='#ff2e2e' colspan='2'><b>Data Pemesan <i>(Booking Data)</i></b></td><td Colspan='2' bgcolor='#ff2e2e'><b>Data Kendaraan <i>(Vehicles Data)</i></b></td>
</tr>
<tr>
<td bgcolor='#e2616e' width='25%'>NIK <br><font size='-1'><i>(Employee ID)</font></i></td><td width='25%'>$booking[no_employee]</td>
<td bgcolor='#e2616e' width='25%'>No. Polisi <br><font size='-1'><i>(Plat No.)</i></font></td><td width='25%'>$no_polisi</td>
</tr>
<tr>
<td bgcolor='#e2616e' width='25%'>Nama <br><font size='-1'><i>(Employee Name)</i></font></td><td width='25%'>$booking[nama_employee]</td>
<td bgcolor='#e2616e' width='25%'>Nama Driver <br><font size='-1'><i>(Driver Name)</i></font></td><td width='25%'>$nama_driver</td>
</tr>
<tr>
<td bgcolor='#e2616e' width='25%'>Department <br><font size='-1'><i>(Department)</i></font></td><td width='25%'>$booking[nama_department]</td>
<td bgcolor='#e2616e' width='25%'>Type Mobil <br><font size='-1'><i>(Car Type)</i></font></td><td width='25%'>$type_mobil</td>
</tr>
</table>
<br>
<table width='100%' cellspacing='0' celpadding='0' border='1'>
<tr>
<td bgcolor='#ff2e2e' colspan='2'><b>Detail Keperluan <i>(Booking Detail)</i></b></td><td Colspan='2' bgcolor='#ff2e2e'><b>Data Penumpang Tambahan <i>(Additional Passenger Data)</i></b></td>
</tr>
<tr>
<td bgcolor='#e2616e' width='25%'>Tanggal & Jam <br><font size='-1'><i>(Date & Time)</i></font></td><td width='25%'>".tglindo($tanggal_pemakaian)."<br> ".substr("$jam_start",0,5)." s/d ".substr("$jam_selesai",0,5)."</td>
<td bgcolor='#e2616e' width='25%'>Penumpang Tambahan <br><font size='-1'><i>(Additional Passenger)</i></font> </td><td width='25%'>$nama_penumpang</td>
</tr>
<tr>
<td bgcolor='#e2616e' width='25%'>Instansi / Individu <br><font size='-1'><i>(Company)</i></font></td><td width='25%'>$instansi</td>
<td bgcolor='#e2616e' width='25%'>Tujuan Penumpang ke 2 <br><font size='-1'><i>(Second Passenger Destination)</i></font></td><td width='25%'>$tujuan</td>
</tr>
<tr>
<td bgcolor='#e2616e' width='25%'>Area / Daerah <br><font size='-1'><i>(Destination Area)</i></font></td><td width='25%'>$area</td>
<td bgcolor='#e2616e' width='50%' colspan='2'></td>
</tr>
<tr>
<td bgcolor='#e2616e' width='25%'>Bertemu Dengan <br><font size='-1'><i>(Whom to Meet)</i></font></td><td width='25%'>$pic_dituju</td>
<td bgcolor='#e2616e' width='50%' colspan='2'></td>
</tr>
<tr>
<td bgcolor='#e2616e' width='25%'>Aktifitas <br><font size='-1'><i>(Activities)</i></font></td><td width='25%'>$keperluan</td>
<td bgcolor='#e2616e' width='50%' colspan='2'></td>
</tr>
</table>
</body>
</html>
<br>
<p>Terima Kasih <i>(Thank You)</i></p><br>
<p>Admin OCBS</p>";


// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";



// More headers
$headers .= 'From: PSI-OCBS Admin <admincobs@sanden.co.id>' . "\r\n";
$headers .= 'Bcc: mis@sanden.co.id' . "\r\n";
$headers .= 'Bcc: dodi.kesumayadi@sanden.co.id' . "\r\n";
$headers .= 'Bcc: zaini1@sanden.co.id' . "\r\n";
	
	
	
	
}
else{
	
//================================================mail for hod=========================================				
		$view = mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
					INNER JOIN section
							ON master_employee.kode_section=section.kode_section
					INNER JOIN department
							ON department.kode_department=section.kode_department
			WHERE department.nama_department LIKE '%$_POST[department]%' AND master_employee.position_user LIKE '%HOD%'");		
	$r    = mysql_fetch_array($view);
	
	

IF($_POST['department']=='Quality Assurance'){
	$spvmail = "dhian@sanden.co.id";	
}


ELSE IF($_POST['department']=='Engineering'){
	$spvmail = "tommy@sanden.co.id";	
}


if($_POST['kepemilikan_mobil']=='Delivery'){
	$headers .= 'Cc: nia.astria@sanden.co.id' . "\r\n";
}

else{
	
	$spvmail = $r['personal_email_employee'];	
}	
	
	
	
		
$booking = mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
					INNER JOIN section
							ON master_employee.kode_section=section.kode_section
					INNER JOIN department
							ON department.kode_department=section.kode_department
			WHERE booking_mobil.kode_booking='$_POST[kode_booking]' ORDER BY booking_mobil.jam_start ASC");
$booking   = mysql_fetch_array($booking);


$tgl_booking = $booking['tgl_booking'];
$no_polisi = $booking['no_polisi'];
$nama_driver = $booking['nama_driver'];
$tanggal_pemakaian = $booking['tanggal_pemakaian'];
$type_mobil = $booking['type_mobil'];
$jam_start = $booking['jam_start'];
$instansi = $booking['instansi'];
$pic_dituju = $booking['pic_dituju'];
$nama_penumpang = $booking['nama_penumpang'];
$tujuan = $booking['tujuan'];
$area = $booking['area'];
$pic_dituju = $booking['pic_dituju'];
$keperluan = $booking['keperluan'];
$mailperson=$booking['personal_email_employee'];
	
$jam_selesai1=$booking['jam_selesai'];
	
	$query2=mysql_query("SELECT * FROM  jam_booking
					WHERE  jam_booking.jam_start_value = '$jam_selesai1'"); 
	$dat2=mysql_fetch_array($query2);
	
	$kode_jam=$dat2['kode_jam']+1;
	
	$query3=mysql_query("SELECT * FROM  jam_booking
					WHERE  jam_booking.kode_jam = '$kode_jam'"); 
	$dat3=mysql_fetch_array($query3);
	
$jam_selesai=$dat3['jam_selesai'];	

	



$to = "$spvmail";
$subject = "[Pembatalan Pengajuan Tugas Luar] | ($booking[no_employee] | $booking[nama_employee])";

$message = "
<html>
<head>
</head>
<body>
<p><b>Pembatalan Pengajuan Tugas Luar / Penggunaan Mobil Operasional</b>
<br><font size='-1'><i>(Cancel Working Out of Office / Cancel Car Operational Usage)</i></font></p>
<table width='100%' cellspacing='0' celpadding='0' border='1'>
<tr>
<td bgcolor='#ff2e2e' colspan='2'><b>Data Pemesan <i>(Booking Data)</i></b></td><td Colspan='2' bgcolor='#ff2e2e'><b>Data Kendaraan <i>(Vehicles Data)</i></b></td>
</tr>
<tr>
<td bgcolor='#e2616e' width='25%'>NIK <br><font size='-1'><i>(Employee ID)</font></i></td><td width='25%'>$booking[no_employee]</td>
<td bgcolor='#e2616e' width='25%'>No. Polisi <br><font size='-1'><i>(Plat No.)</i></font></td><td width='25%'>$no_polisi</td>
</tr>
<tr>
<td bgcolor='#e2616e' width='25%'>Nama <br><font size='-1'><i>(Employee Name)</i></font></td><td width='25%'>$booking[nama_employee]</td>
<td bgcolor='#e2616e' width='25%'>Nama Driver <br><font size='-1'><i>(Driver Name)</i></font></td><td width='25%'>$nama_driver</td>
</tr>
<tr>
<td bgcolor='#e2616e' width='25%'>Department <br><font size='-1'><i>(Department)</i></font></td><td width='25%'>$booking[nama_department]</td>
<td bgcolor='#e2616e' width='25%'>Type Mobil <br><font size='-1'><i>(Car Type)</i></font></td><td width='25%'>$type_mobil</td>
</tr>
</table>
<br>
<table width='100%' cellspacing='0' celpadding='0' border='1'>
<tr>
<td bgcolor='#ff2e2e' colspan='2'><b>Detail Keperluan <i>(Booking Detail)</i></b></td><td Colspan='2' bgcolor='#ff2e2e'><b>Data Penumpang Tambahan <i>(Additional Passenger Data)</i></b></td>
</tr>
<tr>
<td bgcolor='#e2616e' width='25%'>Tanggal & Jam <br><font size='-1'><i>(Date & Time)</i></font></td><td width='25%'>".tglindo($tanggal_pemakaian)."<br> ".substr("$jam_start",0,5)." s/d ".substr("$jam_selesai",0,5)."</td>
<td bgcolor='#e2616e' width='25%'>Penumpang Tambahan <br><font size='-1'><i>(Additional Passenger)</i></font> </td><td width='25%'>$nama_penumpang</td>
</tr>
<tr>
<td bgcolor='#e2616e' width='25%'>Instansi / Individu <br><font size='-1'><i>(Company)</i></font></td><td width='25%'>$instansi</td>
<td bgcolor='#e2616e' width='25%'>Tujuan Penumpang ke 2 <br><font size='-1'><i>(Second Passenger Destination)</i></font></td><td width='25%'>$tujuan</td>
</tr>
<tr>
<td bgcolor='#e2616e' width='25%'>Area / Daerah <br><font size='-1'><i>(Destination Area)</i></font></td><td width='25%'>$area</td>
<td bgcolor='#e2616e' width='50%' colspan='2'></td>
</tr>
<tr>
<td bgcolor='#e2616e' width='25%'>Bertemu Dengan <br><font size='-1'><i>(Whom to Meet)</i></font></td><td width='25%'>$pic_dituju</td>
<td bgcolor='#e2616e' width='50%' colspan='2'></td>
</tr>
<tr>
<td bgcolor='#e2616e' width='25%'>Aktifitas <br><font size='-1'><i>(Activities)</i></font></td><td width='25%'>$keperluan</td>
<td bgcolor='#e2616e' width='50%' colspan='2'></td>
</tr>
</table>
</body>
</html>
<br>
<p>Terima Kasih <i>(Thank You)</i></p><br>
<p>Admin OCBS</p>";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";



IF($_POST['department']=='Quality Assurance'){
	$headers .= 'Cc: anriga@sanden.co.id' . "\r\n";
}


ELSE IF($_POST['department']=='Engineering'){
	$headers .= 'Cc: anriga@sanden.co.id' . "\r\n";	
}


else{
	
	$spvmail = $r['personal_email_employee'];	
}


// More headers
$headers .= 'From: PSI-OCBS Admin <admincobs@sanden.co.id>' . "\r\n";
$headers .= 'Cc: '.$mailperson. "\r\n";
$headers .= 'Bcc: mis@sanden.co.id' . "\r\n";
$headers .= 'Bcc: dodi.kesumayadi@sanden.co.id' . "\r\n";

mail($to,$subject,$message,$headers);


}

 
	 
	 
	 
	 
	 

mysql_query("UPDATE booking_mobil SET status_booking= 'remove'  WHERE kode_booking ='$_POST[kode_booking]'");
 
mysql_query("DELETE FROM schedule_booking  WHERE kode_booking ='$_POST[kode_booking]'"); 
 
 header('location:?page=schedule&act=tampilschedule'); 
 
 
 } 
 
 
 

echo "<div class='panel-heading'><h4>Detail Booking Mobil</h4></div> <div class='control-group'>";

$query = mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
					INNER JOIN section
							ON master_employee.kode_section=section.kode_section
					INNER JOIN department
							ON department.kode_department=section.kode_department	
			WHERE booking_mobil.kode_booking='$_POST[kode_booking]'ORDER BY booking_mobil.jam_start ASC");
	
	while ($d = mysql_fetch_array($query)) {
		
	$jam_selesai=$d['jam_selesai'];
	
	$query2=mysql_query("SELECT * FROM  jam_booking
					WHERE  jam_booking.jam_start_value LIKE '%$jam_selesai%'"); 
	$dat2=mysql_fetch_array($query2);
	
	$kode_jam=$dat2['kode_jam']+1;
	
	$query3=mysql_query("SELECT * FROM  jam_booking
					WHERE  jam_booking.kode_jam LIKE '%$kode_jam%'"); 
	$dat3=mysql_fetch_array($query3);
	
	echo "<div class='form-style-10'>
	<form method='POST' action='#' onSubmit='return validasi(this)' enctype='multipart/form-data'></form>
	<table cellpadding='2' cellspacing='0' border='0'>
	<tr>
	<td valign='top'>
	<div class='section'>Data kendaraan</div>
    <div class='inner-wrap'>
        <label>No. Polisi <input type='text' disabled readonly='' size='25' name='no_polisi1' value='$d[no_polisi]' size=10>
							<input type='text' readonly='' size='25' name='kode_booking' value='$d[kode_booking]' size=10>
							</label>
        <label>Driver <input type='text' size='40' disabled readonly='' name='nama_driver' value='$d[nama_driver]' size=10></label>
        <label>Jenis/Type Kendaraan <input type='text' size='50' disabled readonly='' name='typemobil' value='$d[type_mobil]' size=10></label>
    </div>
	</td><td valign='top'>
    <div class='section'>Detail Booking</div>
    <div class='inner-wrap'>
        <label>Tanggal Booking <input type='text' disabled size='35' value='".tglindo($d['tanggal_pemakaian'])."' name='tanggal_pemakaian'  id='tanggal1' size=10/></label>
        <label>Jam Mulai <input type='text' disabled size='35' value='$d[jam_start]' name='jam_mulai'  id='tanggal1' size=10/></label>
        <label>Jam Selesai <input type='text' disabled size='35' value='$dat3[jam_start_value]' name='jam_selesai'  id='tanggal1' size=10/></label>
							
    </div>
	</td>
	</tr>
	<tr>
	<td valign='top'>
	<div class='section'>Detail Keperluan</div>
	<div class='inner-wrap'>
        <label>Penumpang Pertama<input type='text' size='35' disabled name='nama_employee' value='$d[nama_employee]' size=10></label>
        <label>Instansi/Individu <input type='text' size='35' disabled name='instansi' value='$d[instansi]' size=10></label>
        <label>Area/Daerah <input type='text' size='50' disabled name='area' value='$d[area]' size=10></label>
        <label>Bertemu Dengan <input type='text' size='35' disabled name='pic_dituju' value='$d[pic_dituju]' size=10></label>
        <label>Keperluan <textarea name='keperluan' cols='50' rows='3' disabled>$d[keperluan]</textarea></label>
       </div>
	</td>
	
	<td valign='top'>
	<div class='section'>Data Penumpang</div>
	  
    <div class='inner-wrap'>
        <label>Penumpang Kedua<input type='text' size='35' disabled name='nama_penumpang' value='$d[nama_penumpang]' size=10></label>
         <label>Tujuan, Keperluan, Penumpang Tambahan <textarea name='tujuan' cols='50' rows='3'>$d[tujuan]</textarea></label>
       </div>
	</td>
	</tr>
	
	<tr>
	<td valign='top'>
	<div class='section'>Approval</div>
    <div class='inner-wrap'>
        <label>Approval";
		
			if($d['approved']=='0'){echo "<input type='text' size='35' disabled name='instansi' value='Proses Persetujuan HOD' size=10>";}
			else if($d['approved']=='1'){echo "<input type='text' size='35' disabled name='instansi' value='Disetujui HOD' size=10>";}
			else if($d['approved']=='2'){echo "<input type='text' size='35' disabled name='instansi' value='Tidak Disetujui HOD' size=10>";}
			else{
			echo "<input type='text' size='35' disabled name='instansi' value='Disetujui HOD' size=10>";}
		echo "</div>
	</td>
	</tr>
	
	</table>
	
    <div class='button-section'>
	<form action='?page=booking&act=detailbooking' method='post'>
	<input type='hidden' readonly='' size='25' name='position' value='$d[position_user]' size=10>
	<input type='hidden' readonly='' size='25' name='department' value='$d[nama_department]' size=10>
	<input type='hidden' size='35'  name='kode_booking' value='$d[kode_booking]'>
	<input type='submit' name='cancel' id='cancel' value='Hapus'></form>";
	
	
	
	
    echo "</span>
    </div></div></div>";

	}

 
break;
}
?>
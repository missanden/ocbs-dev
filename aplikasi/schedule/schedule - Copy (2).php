<?php
include '../config/koneksi.php';
switch($_GET['act']){	
 case "tampilschedule":
 
	$datenow=date('Y-m-d');
	$jamnow=date("H:i:s");

	$q="SELECT * FROM booking_mobil	
			INNER JOIN master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
	WHERE 
	booking_mobil.tanggal_pemakaian='$datenow' AND 
	booking_mobil.jam_delete <= '$jamnow' AND
	master_mobil.kepemilikan_mobil = 'Operasional' AND
	master_mobil.aktif_mobil IN ('aktif') AND
	booking_mobil.status_booking='booked'";
	
	$query3 = mysql_query($q);
	$row = mysql_num_rows($query3);
	
	    while ($d = mysql_fetch_array($query3)) {
	mysql_query("DELETE FROM schedule_booking  WHERE kode_booking ='$d[kode_booking]'"); 	    
	mysql_query("UPDATE booking_mobil SET status_booking= 'remove'  WHERE kode_booking ='$d[kode_booking]'");
	
	    
	    
	 
	    }
 
 
 ?>
 <div class="panel-heading"><h4>Jadwal Mobil</h4></div>
 
<?php 
  echo "<br><img src='images_car/legend.jpg' width='55%' height='50'><br><br>";
  
  		

	
if($_SESSION['s_position']=='admin'){$y = 14;}
else {$y = 7;}

  
  for ($x = 0; $x <= $y; $x++) {
	
	$loop  = mktime(0, 0, 0, date('m')  , date('d')+$x, date('Y'));
	
	$tanggal=date('Y-m-d',$loop);
	 $day=date('N',$loop);
	
	if($_SESSION['s_position']=='admin'){
	
	echo "<font color='#428bca'>&nbsp;
	<b>". tglindo1 (date("Y-m-d N",$loop))."</b></font>
	&nbsp;&nbsp;&nbsp;&nbsp;<center>
	<table border='1' width:'95%' >
	<tr bgcolor='#e1dada'>
	<td width='3%' rowspan='2' align='center'>Type Mobil & Driver</td>
	<td colspan='48' align='center'><b>Jam</b></td></tr>
	<tr bgcolor='#e1dada'>";
	
	 for ($i = 0; $i <= 23; $i++) {
	 echo "<td width='1%' colspan='2' align='center'>$i:00</td>";
	 }
	
	echo "</tr>";
	
	if( $_SESSION['s_no_employee']=='0902-085'){
	$qur="SELECT * FROM jam_booking WHERE kode_jam BETWEEN '16' AND '34' ORDER BY jam_start ASC";
		
	$q="SELECT * FROM master_mobil
	WHERE master_mobil.kepemilikan_mobil NOT IN ('Rental') AND master_mobil.aktif_mobil IN ('aktif') ORDER BY master_mobil.kepemilikan_mobil,master_mobil.no_polisi DESC";	
		
	}
	
	else if($_SESSION['s_no_employee']=='0408-047'){
	$qur="SELECT * FROM jam_booking WHERE kode_jam BETWEEN '16' AND '34' ORDER BY jam_start ASC";
		
	$q="SELECT * FROM master_mobil WHERE kepemilikan_mobil='0408-047'";	
		
	}
	
	else if($_SESSION['s_position']=='HOD'){

	$qur="SELECT * FROM jam_booking WHERE kode_jam BETWEEN '16' AND '34' ORDER BY jam_start ASC";
		
	$q="SELECT * FROM master_mobil
	WHERE master_mobil.kepemilikan_mobil IN ('Operasional','$_SESSION[s_no_employee]') AND
	master_mobil.no_polisi NOT IN ('B1390FJD') AND 
	master_mobil.aktif_mobil IN ('aktif') ORDER BY master_mobil.kepemilikan_mobil,master_mobil.no_polisi DESC";	
		
	}
	
	
	else if($_SESSION['s_position']=='admin'){
	$qur="SELECT * FROM jam_booking ORDER BY jam_start ASC";
	
	$q="SELECT * FROM master_mobil
	WHERE master_mobil.kepemilikan_mobil NOT IN ('Rental') 
	AND master_mobil.aktif_mobil IN ('aktif') ORDER BY master_mobil.kepemilikan_mobil,master_mobil.no_polisi DESC";}
	
	else{
	$qur="SELECT * FROM jam_booking WHERE kode_jam BETWEEN '16' AND '34' ORDER BY jam_start ASC";
	
	$q="SELECT * FROM master_mobil
	WHERE master_mobil.kepemilikan_mobil='Operasional' AND master_mobil.aktif_mobil IN ('aktif') AND
	master_mobil.no_polisi NOT IN ('B1390FJD') 
	ORDER BY master_mobil.kepemilikan_mobil,master_mobil.no_polisi DESC";
	
	}
	
	$query3 = mysql_query($q);
	$row = mysql_num_rows($query3);
        $no = 0;
	    while ($d = mysql_fetch_array($query3)) {
            $no++;
	    
	    
	    
            echo "<tr valign='top'>
			<td width='7%'><font size='-2'><label> $d[type_mobil] : $d[nama_driver] </label></td>";
			
	
	
	$query4 = mysql_query($qur);
	$row3 = mysql_num_rows($query4);
      
	    while ($w = mysql_fetch_array($query4)) {
		    
		  $kode_jam=$w['kode_jam'];
		  $jam_start=$w['jam_start'];
		  $kode_jam=$w['kode_jam'];
		  
		  
		  $tgl_tanpastrip=str_replace("-","",$tanggal);
		  $kodeunik=$tgl_tanpastrip."_".$d['no_polisi']."-".$kode_jam;
	    
	
			$query="SELECT * FROM  schedule_booking INNER JOIN booking_mobil
						ON schedule_booking.kode_booking=booking_mobil.kode_booking
						INNER JOIN master_employee
						ON master_employee.no_employee=booking_mobil.no_employee
						INNER JOIN bonceng
						ON bonceng.kode_booking=booking_mobil.kode_booking
			WHERE schedule_booking.kodeunik ='$kodeunik'";
			$query2 = mysql_query($query);
			$row = mysql_num_rows($query2);
			$s = mysql_fetch_array($query2);
			
			
			$kode_booking=$s['kode_booking'];
			
			
			
			
			
			if($s['approved']=='1' and $s['status_booking']=='booked')
			{$color="#eaff00";
			$comandapprove="Mobil Sudah Dipesan";}
			
			else if($s['approved']=='1' and $s['status_booking']=='gone')
			{$color="#00e00b";
			$comandapprove="Mobil Dipakai Pemesan";}
			
			
			else if($s['approved']=='1' and $s['status_booking']=='done')
			{$color="#ff0000";
			$comandapprove="Mobil Sudah Kembali";}

			if ($row==1)
			
			{
				if($_SESSION['s_no_employee']==$s['no_employee']){
				echo "<td width=''  bgcolor='$color'>
				<form class='form-8' action='?page=booking&act=detailbooking' method='POST'>
				<input type='hidden' name='kode_booking' value='$kode_booking'>
				<input type='submit' width='100%' value='' Title='$comandapprove
				\n Telah dibooking oleh \n Penumpang 1 : $s[nama_employee] \n Penumpang 2 : $s[nama_penumpang] \n Tujuan : $s[instansi]'>
				
				</form>
				";
				}
				
				else if($s['nama_penumpang']==''){
				echo "<td width='' bgcolor='$color'>
				<form class='form-8' action='?page=booking&act=penumpang' method='POST'>
				<input type='hidden' name='kode_booking' value='$kode_booking'>
				<input type='submit' width='100%' value=''   Title='$comandapprove
				\n Telah dibooking oleh \n Penumpang 1 : $s[nama_employee] \n Penumpang 2 : $s[nama_penumpang] \n Tujuan : $s[instansi]'>
				</form>
				";
				}
				
			else{
				if($_SESSION['s_position']=='admin'){
				echo "<td width='' bgcolor='$color'>
				<form class='form-8' action='?page=booking&act=detailbooking' method='POST'>
				<input type='hidden' name='kode_booking' value='$kode_booking'>
				<input type='submit' width='100%' value=''  Title='$comandapprove
				\n Telah dibooking oleh \n Penumpang 1 : $s[nama_employee] \n Penumpang 2 : $s[nama_penumpang] \n Tujuan : $s[instansi]'>
				</form>
				";
				}
					else {
			echo "<td width='' bgcolor='$color' Title='$comandapprove
				\n Telah dibooking oleh \n Penumpang 1 : $s[nama_employee] \n Penumpang 2 : $s[nama_penumpang] \n Tujuan : $s[instansi]'>
			<form class='form-8' action='?page=booking&act=penumpang' method='POST'>
				<input type='hidden' name='kode_booking' value='$kode_booking'>
				<input type='submit' width='100%' value=''  Title='$comandapprove
				\n Telah dibooking oleh \n Penumpang 1 : $s[nama_employee] \n Penumpang 2 : $s[nama_penumpang] \n Tujuan : $s[instansi]'>
				</form>
			</td>";
					}
				
					
				}
			}
			
			else{
			
				echo "<td width=''>
				<form class='form-8' action='?page=booking&act=bookingmobil2' method='POST'>
				<input type='hidden' name='no_polisi' value='$d[no_polisi]'>
				<input type='hidden' name='tanggal' value='$tanggal'>
				<input type='hidden' name='kode_jam' value='$kode_jam'>
				<input type='hidden' name='kodeunik' value='$kodeunik'>
				<input type='submit' width='100%' name='hiden' Title='$jam_start' value=''>
				</form></td>";
				}
				}
			
			echo "</tr>";
        }
	
	echo "</table><br>
	</center>";
	
	/*
	$query = mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
	WHERE booking_mobil.tanggal_pemakaian='$tanggal' AND 
			booking_mobil.status_booking NOT IN ('remove') AND
			master_mobil.kepemilikan_mobil='Operasional' AND
			booking_mobil.approved='1' 
				ORDER BY booking_mobil.jam_start ASC");
	$row = mysql_num_rows($query);
	*/
  if ($row>0){
	
	echo "
	<table border='0' width='70%'>
	<tr>
	<th width='3%'>Driver</th>
	<th width='4%'>Penumpang</th>
	<th width='4%'>Tujuan</th>
	<th width='4%'>Penumpang Ke 2</th>
	<th width='4%'>Tujuan</th>
	<th width='4%'>Jam</th>
	</tr>";
	
	while ($d = mysql_fetch_array($query)) {
	echo "<tr>
	<td>$d[nama_driver]</td>
	<td>$d[nama_employee]</td>
	<td>$d[instansi]</td>
	<td>$d[nama_penumpang]</td>
	<td>$d[tujuan]</td>
	<td>$d[jam_start] s/d $d[jam_selesai]</td>
	
	</tr>";	
		
		
  }
  echo "</table>
	<br><br>
	";
   }
	}

else {
	

	if ($tanggal=='2016-12-16')
	{}
	else{
	
	if($day=='6' or $day=='7'){}
	else{
	
	echo "<font color='#428bca'>&nbsp;
	<b>". tglindo1 (date("Y-m-d N",$loop))."</b></font>
	&nbsp;&nbsp;&nbsp;&nbsp;<center>
	<table border='1' width:'95%' >
	<tr bgcolor='#e1dada'>
	<td width='1%' rowspan='2' align='center'>Type Mobil & Driver</td>
	<td colspan='48' align='center'><b>Jam</b></td></tr>
	<tr bgcolor='#e1dada'>";
	
	if( $_SESSION['s_no_employee']=='0902-085'){$i1="0"; $i2="23";}
	else {$i1="7"; $i2="16";}
	
	 for ($i = $i1; $i <= $i2; $i++) {
	 echo "<td width='1%' colspan='2' align='center'>$i:00</td>";
	 }
	
	echo "</tr>";
	
	if( $_SESSION['s_no_employee']=='0902-085'){
	$qur="SELECT * FROM jam_booking ORDER BY jam_start ASC";	
	
	$q="SELECT * FROM master_mobil
	WHERE master_mobil.kepemilikan_mobil NOT IN ('Rental') 
	AND master_mobil.aktif_mobil IN ('aktif') ORDER BY master_mobil.kepemilikan_mobil,master_mobil.no_polisi DESC";	
		
	}
	
	
	////////////////////////////////////////////////////////////////////
	else if($_SESSION['s_no_employee']=='0408-047'){
	$qur="SELECT * FROM jam_booking WHERE kode_jam BETWEEN '16' AND '34' ORDER BY jam_start ASC";
		
	$q="SELECT * FROM master_mobil WHERE kepemilikan_mobil='0408-047'";	
	}	

	else if($_SESSION['s_no_employee']=='0401-001'){
	$qur="SELECT * FROM jam_booking WHERE kode_jam BETWEEN '16' AND '34' ORDER BY jam_start ASC";
		
	$q="SELECT * FROM master_mobil WHERE kepemilikan_mobil='0401-001'";	
	}	
	
	else if($_SESSION['s_no_employee']=='1403-572'){
	$qur="SELECT * FROM jam_booking WHERE kode_jam BETWEEN '16' AND '34' ORDER BY jam_start ASC";
		
	$q="SELECT * FROM master_mobil WHERE kepemilikan_mobil='1403-572'";	
	}	
	
	else if($_SESSION['s_no_employee']=='0809-083'){
	$qur="SELECT * FROM jam_booking WHERE kode_jam BETWEEN '16' AND '34' ORDER BY jam_start ASC";
		
	$q="SELECT * FROM master_mobil WHERE kepemilikan_mobil='0809-083'";	
	}	
	
	else if($_SESSION['s_no_employee']=='1207-163'){
	$qur="SELECT * FROM jam_booking WHERE kode_jam BETWEEN '16' AND '34' ORDER BY jam_start ASC";
		
	$q="SELECT * FROM master_mobil WHERE kepemilikan_mobil='1207-163'";	
	}	
	
	else if($_SESSION['s_position']=='HOD'){
	$qur="SELECT * FROM jam_booking WHERE kode_jam BETWEEN '15' AND '34' ORDER BY jam_start ASC";		
		
	$q="SELECT * FROM master_mobil
	WHERE master_mobil.kepemilikan_mobil IN ('Operasional','$_SESSION[s_no_employee]') AND
	master_mobil.no_polisi NOT IN ('B1390FJD') AND
	master_mobil.aktif_mobil IN ('aktif') ORDER BY master_mobil.kepemilikan_mobil,master_mobil.no_polisi DESC";	
		
	}
	
	
	else if($_SESSION['s_position']=='admin'){
	$qur="SELECT * FROM jam_booking ORDER BY jam_start ASC";
	
	$q="SELECT * FROM master_mobil
	WHERE master_mobil.kepemilikan_mobil NOT IN ('Rental') 
	AND master_mobil.aktif_mobil IN ('aktif') ORDER BY master_mobil.kepemilikan_mobil,master_mobil.no_polisi DESC";}
	
	else{
	$qur="SELECT * FROM jam_booking WHERE kode_jam BETWEEN '15' AND '34' ORDER BY jam_start ASC";
	
	$q="SELECT * FROM master_mobil
	WHERE master_mobil.kepemilikan_mobil='Operasional' AND master_mobil.aktif_mobil IN ('aktif') AND
	master_mobil.no_polisi NOT IN ('B1390FJD') 
	ORDER BY master_mobil.kepemilikan_mobil,master_mobil.no_polisi DESC";}
	
	$query3 = mysql_query($q);
	$row = mysql_num_rows($query3);
        $no = 0;
	    while ($d = mysql_fetch_array($query3)) {
            $no++;
	    
	    
	    
            echo "<tr valign='top'>
			<td width='7%'><font size='-2'><label> $d[type_mobil] : $d[nama_driver] </label></td>";
			
	
	
	$query4 = mysql_query($qur);
	$row3 = mysql_num_rows($query4);
      
	    while ($w = mysql_fetch_array($query4)) {
		    
		  $kode_jam=$w['kode_jam'];
		  $jam_start=$w['jam_start'];
		  $kode_jam=$w['kode_jam'];
		  
		  
		  $tgl_tanpastrip=str_replace("-","",$tanggal);
		  $kodeunik=$tgl_tanpastrip."_".$d['no_polisi']."-".$kode_jam;
	    
	
			/*
			for ($i = 0; $i <= 23; $i++) {
			
			$length=strlen($i);
			if($length==1){$i="0".$i;}
			
			$tgl_tanpastrip=str_replace("-","",$tanggal);
			$kodeunik=$tgl_tanpastrip."_".$d['no_polisi']."-".$i;
			*/
			
			$query="SELECT * FROM  schedule_booking INNER JOIN booking_mobil
						ON schedule_booking.kode_booking=booking_mobil.kode_booking
						INNER JOIN master_employee
						ON master_employee.no_employee=booking_mobil.no_employee
						INNER JOIN bonceng
						ON bonceng.kode_booking=booking_mobil.kode_booking
			WHERE schedule_booking.kodeunik ='$kodeunik'";
			$query2 = mysql_query($query);
			$row = mysql_num_rows($query2);
			$s = mysql_fetch_array($query2);
			
			
			$kode_booking=$s['kode_booking'];
			
			
			
			
			
			if($s['approved']=='1' and $s['status_booking']=='booked')
			{$color="#eaff00";
			$comandapprove="Mobil Sudah Dipesan";}
			
			else if($s['approved']=='1' and $s['status_booking']=='gone')
			{$color="#00e00b";
			$comandapprove="Mobil Dipakai Pemesan";}
			
			
			else if($s['approved']=='1' and $s['status_booking']=='done')
			{$color="#ff0000";
			$comandapprove="Mobil Sudah Kembali";}

			if ($row==1)
			
			{
				if($_SESSION['s_no_employee']==$s['no_employee']){
				echo "<td width=''  bgcolor='$color'>
				<form class='form-8' action='?page=booking&act=detailbooking' method='POST'>
				<input type='hidden' name='kode_booking' value='$kode_booking'>
				<input type='submit' width='100%' value='' Title='$comandapprove
				\n Telah dibooking oleh \n Penumpang 1 : $s[nama_employee] \n Penumpang 2 : $s[nama_penumpang] \n Tujuan : $s[instansi]'>
				
				</form>
				";
				}
				
				else if($s['nama_penumpang']==''){
				echo "<td width='' bgcolor='$color'>
				<form class='form-8' action='?page=booking&act=penumpang' method='POST'>
				<input type='hidden' name='kode_booking' value='$kode_booking'>
				<input type='submit' width='100%' value=''   Title='$comandapprove
				\n Telah dibooking oleh \n Penumpang 1 : $s[nama_employee] \n Penumpang 2 : $s[nama_penumpang] \n Tujuan : $s[instansi]'>
				</form>
				";
				}
				
			else{
				if($_SESSION['s_position']=='admin'){
				echo "<td width='' bgcolor='$color'>
				<form class='form-8' action='?page=booking&act=detailbooking' method='POST'>
				<input type='hidden' name='kode_booking' value='$kode_booking'>
				<input type='submit' width='100%' value=''  Title='$comandapprove
				\n Telah dibooking oleh \n Penumpang 1 : $s[nama_employee] \n Penumpang 2 : $s[nama_penumpang] \n Tujuan : $s[instansi]'>
				</form>
				";
				}
					else {
			echo "<td width='' bgcolor='$color' Title='$comandapprove
				\n Telah dibooking oleh \n Penumpang 1 : $s[nama_employee] \n Penumpang 2 : $s[nama_penumpang] \n Tujuan : $s[instansi]'>
				
				<form class='form-8' action='?page=booking&act=penumpang' method='POST'>
				<input type='hidden' name='kode_booking' value='$kode_booking'>
				<input type='submit' width='100%' value=''   Title='$comandapprove
				\n Telah dibooking oleh \n Penumpang 1 : $s[nama_employee] \n Penumpang 2 : $s[nama_penumpang] \n Tujuan : $s[instansi]'>
				</form>
			</td>";
					}
				
					
				}
			}
			
			else{
			
				echo "<td width=''>
				<form class='form-8' action='?page=booking&act=bookingmobil2' method='POST'>
				<input type='hidden' name='no_polisi' value='$d[no_polisi]'>
				<input type='hidden' name='tanggal' value='$tanggal'>
				<input type='hidden' name='kode_jam' value='$kode_jam'>
				<input type='hidden' name='kodeunik' value='$kodeunik'>
				<input type='submit' width='100%' name='hiden' Title='$jam_start' value=''>
				</form></td>";
				}
				}
			
			echo "</tr>";
        }
	
	echo "</table><br>
	</center>";
	
	/*
	$query = mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
	WHERE booking_mobil.tanggal_pemakaian='$tanggal' AND 
			booking_mobil.status_booking NOT IN ('remove') AND
			master_mobil.kepemilikan_mobil='Operasional' AND
			booking_mobil.approved='1' 
				ORDER BY booking_mobil.jam_start ASC");
	$row = mysql_num_rows($query);
	*/
  if ($row>0){
	/*
	echo "
	<table border='0' width='70%'>
	<tr>
	<th width='3%'>Driver</th>
	<th width='4%'>Penumpang</th>
	<th width='4%'>Tujuan</th>
	<th width='4%'>Penumpang Ke 2</th>
	<th width='4%'>Tujuan</th>
	<th width='4%'>Jam</th>
	</tr>";
	*/
	while ($d = mysql_fetch_array($query2)) {
	echo "<tr>
	<td>$d[nama_driver]</td>
	<td>$d[nama_employee]</td>
	<td>$d[instansi]</td>
	<td>$d[nama_penumpang]</td>
	<td>$d[tujuan]</td>
	<td>$d[jam_start] s/d $d[jam_selesai]</td>
	
	</tr>";	
		
		
  }
  echo "</table>
	<br><br>
	";
	}}}
	
	
	
}		
	
	
	} 
 echo "<br><br><br><br>";
break;


 case "tampilschedulewarehouse":




 
 
 ?>
 <div class="panel-heading"><h4>Jadwal Mobil Delivery</h4></div>
 
<?php 
  echo "<br><img src='images_car/legend.jpg' width='55%' height='50'><br><br>";
  
  		
	

  
  for ($x = 0; $x <= 6; $x++) {
	
	$loop  = mktime(0, 0, 0, date('m')  , date('d')+$x, date('Y'));
	
  
	 $tanggal=date('Y-m-d',$loop);
	
	
	echo "<font color='#428bca'>&nbsp;
	<b>". tglindo1 (date("Y-m-d N",$loop))."</b></font>
	&nbsp;&nbsp;&nbsp;&nbsp;<center>
	<table border='1' width:'95%' >
	<tr bgcolor='#e1dada'>
	<td width='3%' rowspan='2' align='center'>Type Mobil & Driver</td>
	<td colspan='48' align='center'><b>Jam</b></td></tr>
	<tr bgcolor='#e1dada'>";
	
	 for ($i = 0; $i <= 23; $i++) {
	 echo "<td width='1%' colspan='2' align='center'>$i:00</td>";
	 }
	
	echo "</tr>";
	
	
	if($_SESSION['s_position']=='admin'){
	
	$q="SELECT * FROM master_mobil
	WHERE master_mobil.kepemilikan_mobil NOT IN ('Rental') ORDER BY master_mobil.no_polisi ASC";}
	
	else{
	
	$q="SELECT * FROM master_mobil
	WHERE master_mobil.kepemilikan_mobil='Delivery' ORDER BY master_mobil.no_polisi ASC";}
	
	$query3 = mysql_query($q);
	$row = mysql_num_rows($query3);
        $no = 0;
	    while ($d = mysql_fetch_array($query3)) {
            $no++;
	    
	    
	    
            echo "<tr valign='top'>
			<td width='7%'><font size='-2'><label> $d[type_mobil] : $d[nama_driver] </label></td>";
			
	
	$qur="SELECT * FROM jam_booking ORDER BY jam_start ASC";
	$query4 = mysql_query($qur);
	$row3 = mysql_num_rows($query4);
      
	    while ($w = mysql_fetch_array($query4)) {
		    
		  $kode_jam=$w['kode_jam'];
		  $jam_start=$w['jam_start'];
		  $kode_jam=$w['kode_jam'];
		  
		  
		  $tgl_tanpastrip=str_replace("-","",$tanggal);
		  $kodeunik=$tgl_tanpastrip."_".$d['no_polisi']."-".$kode_jam;
	    
	
			/*
			for ($i = 0; $i <= 23; $i++) {
			
			$length=strlen($i);
			if($length==1){$i="0".$i;}
			
			$tgl_tanpastrip=str_replace("-","",$tanggal);
			$kodeunik=$tgl_tanpastrip."_".$d['no_polisi']."-".$i;
			*/
			
			$query="SELECT * FROM  schedule_booking INNER JOIN booking_mobil
						ON schedule_booking.kode_booking=booking_mobil.kode_booking
						INNER JOIN master_employee
						ON master_employee.no_employee=booking_mobil.no_employee
						INNER JOIN bonceng
						ON bonceng.kode_booking=booking_mobil.kode_booking
			WHERE schedule_booking.kodeunik ='$kodeunik'";
			$query2 = mysql_query($query);
			$row = mysql_num_rows($query2);
			$s = mysql_fetch_array($query2);
			
			
			$kode_booking=$s['kode_booking'];
			
			
			
			
			
			if($s['approved']=='1' and $s['status_booking']=='booked')
			{$color="#eaff00";
			$comandapprove="Mobil Sudah Dipesan";}
			
			else if($s['approved']=='1' and $s['status_booking']=='gone')
			{$color="#00e00b";
			$comandapprove="Mobil Dipakai Pemesan";}
			
			
			else if($s['approved']=='1' and $s['status_booking']=='done')
			{$color="#ff0000";
			$comandapprove="Mobil Sudah Kembali";}

			if ($row==1)
			
			{
				if($_SESSION['s_no_employee']==$s['no_employee']){
				echo "<td width=''  bgcolor='$color'>
				<form class='form-8' action='?page=booking&act=detailbooking' method='POST'>
				<input type='hidden' name='kode_booking' value='$kode_booking'>
				<input type='submit' width='100%' value='' Title='$comandapprove
				\n Telah dibooking oleh \n Penumpang 1 : $s[nama_employee] \n Penumpang 2 : $s[nama_penumpang] \n Tujuan : $s[instansi]'>
				
				</form>
				";
				}
				
				else if($s['nama_penumpang']==''){
				echo "<td width='' bgcolor='$color'>
				<form class='form-8' action='?page=booking&act=penumpang' method='POST'>
				<input type='hidden' name='kode_booking' value='$kode_booking'>
				<input type='submit' width='100%' value=''   Title='$comandapprove
				\n Telah dibooking oleh \n Penumpang 1 : $s[nama_employee] \n Penumpang 2 : $s[nama_penumpang] \n Tujuan : $s[instansi]'>
				</form>
				";
				}
				
			else{
				if($_SESSION['s_position']=='admin'){
				echo "<td width='' bgcolor='$color'>
				<form class='form-8' action='?page=booking&act=detailbooking' method='POST'>
				<input type='hidden' name='kode_booking' value='$kode_booking'>
				<input type='submit' width='100%' value=''  Title='$comandapprove
				\n Telah dibooking oleh \n Penumpang 1 : $s[nama_employee] \n Penumpang 2 : $s[nama_penumpang] \n Tujuan : $s[instansi]'>
				</form>
				";
				}
					else {
			echo "<td width='' bgcolor='$color' Title='$comandapprove
				\n Telah dibooking oleh \n Penumpang 1 : $s[nama_employee] \n Penumpang 2 : $s[nama_penumpang] \n Tujuan : $s[instansi]'>
				
			</td>";
					}
				
					
				}
			}
			
			else{
			
				echo "<td width=''>
				<form class='form-8' action='?page=booking&act=bookingmobil2' method='POST'>
				<input type='hidden' name='no_polisi' value='$d[no_polisi]'>
				<input type='hidden' name='tanggal' value='$tanggal'>
				<input type='hidden' name='kode_jam' value='$kode_jam'>
				<input type='hidden' name='kodeunik' value='$kodeunik'>
				<input type='submit' width='100%' name='hiden' Title='$jam_start' value=''>
				</form></td>";
				}
				}
			
			echo "</tr>";
        }
	
	echo "</table><br>
	</center>";
	
	/*
	$query = mysql_query("SELECT * FROM booking_mobil 
					INNER JOIN
						booking_selesai ON booking_mobil.kode_booking=booking_selesai.kode_booking
					INNER JOIN
						bonceng ON booking_mobil.kode_booking=bonceng.kode_booking	
					INNER JOIN
						master_mobil ON booking_mobil.no_polisi=master_mobil.no_polisi
					INNER JOIN
						master_employee ON booking_mobil.no_employee=master_employee.no_employee
	WHERE booking_mobil.tanggal_pemakaian='$tanggal' AND 
			booking_mobil.status_booking NOT IN ('remove') AND
			master_mobil.kepemilikan_mobil='Operasional' AND
			booking_mobil.approved='1' 
				ORDER BY booking_mobil.jam_start ASC");
	$row = mysql_num_rows($query);
	*/
  if ($row>0){
	
	echo "
	<table border='0' width='70%'>
	<tr>
	<th width='3%'>Driver</th>
	<th width='4%'>Penumpang</th>
	<th width='4%'>Tujuan</th>
	<th width='4%'>Penumpang Ke 2</th>
	<th width='4%'>Tujuan</th>
	<th width='4%'>Jam</th>
	</tr>";
	
	while ($d = mysql_fetch_array($query)) {
	echo "<tr>
	<td>$d[nama_driver]</td>
	<td>$d[nama_employee]</td>
	<td>$d[instansi]</td>
	<td>$d[nama_penumpang]</td>
	<td>$d[tujuan]</td>
	<td>$d[jam_start] s/d $d[jam_selesai]</td>
	
	</tr>";	
		
		
  }
  echo "</table>
	<br><br>
	";
   }
} 
 echo "<br><br><br><br>";

}
?>
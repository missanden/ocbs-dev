<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Vertical Graphic for Report Data [dremi.info]</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Trebuchet MS;
	font-size: 11px;
	color: #333333;
}
body {
	
	margin-left: 20px;
	margin-top: 20px;
	margin-right: 0px;
	margin-bottom: 0px;
}
a:link {
	color: #0099FF;
	text-decoration: underline;
}
a:visited {
	text-decoration: underline;
	color: #0099FF;
}
a:hover {
	text-decoration: none;
	color: #009900;
}
a:active {
	text-decoration: underline;
	color: #0099FF;
}
-->
</style>
</head>

<body>

<?php
include "koneksi.php";

$tahun=$_POST['tahun'];





	
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
								BETWEEN '2016-03-01' AND '2016-03-31' 
						AND booking_mobil.status_booking NOT IN ('remove') "); 
		$max=mysql_num_rows($max);

		
		$max1=$max1+$max;


	
?>
<center>
<b><font size="+2" color="#FF0000">GRAFIK<?echo $tahun; ?></font>
			<form action="detail_tahun_inc.php" method="POST">
				<input type="hidden" size="25" name="tahun" value="<?echo $tahun;?>">
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
					BETWEEN '2016-03-01' AND '2016-03-31' 
					AND master_mobil.nama_driver='$r[nama_driver]' AND booking_mobil.status_booking NOT IN ('remove') "); 
					
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
		<td height=102 valign=bottom nowrap style="color:black;font-family:Arial, Helvetica;font-size:12px;"><? echo "".number_format($percent,0,"",".")."".""; ?> %
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
<?
//echo "Total Unit = ".number_format($max1,0,"",".")."-"."";

?>
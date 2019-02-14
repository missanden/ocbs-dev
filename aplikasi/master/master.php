<?php
include '../config/koneksi.php';
switch($_GET['act']){	
 case "tampilmobil":
?>

<div class="panel-heading"><h4>Daftar Mobil</h4></div>
<?php
echo "<br><font color='#428bca' size='+1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<b><a href='?page=mobil&act=tambahmobil'>+Tambah Mobil</a></b></font><br><br>
	
	<font color='#428bca' size='+1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<b><a href='?page=mobil&act=tampilmobil'>Operasional</a></b></font>
	
	<font color='#428bca' size='+1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<b><a href='?page=mobil&act=tampilmobilpribadi'>Pribadi</a></b></font><br>
	
	&nbsp;&nbsp;&nbsp;&nbsp;<center>
	<table border='1' class='CSS_Table_Example' style='width:95%;height:5px;'>
	<tr bgcolor='#e1dada'>
	<td width='1%'>No.</td>
	<td width='8%'>No. Polisi</td>
	<td width='10%'>Driver</td>
	<td width='15%'>Type Mobil</td>
	<td width='15%'>Kepemilikan Mobil</td>
	<td width='10%'>Foto Mobil</td>
	<td width='10%'>Action</td>
	</tr>";
	
	
	$query="select * from master_mobil where kepemilikan_mobil IN ('operasional')";
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
			if ($no % 2) $background='#ffffff'; else $background='#e5e7f3';
			
            echo "<tr valign='top' bgcolor='$background'><td>$no</td>
			<td>$d[no_polisi]</td>
			<td>$d[nama_driver]</td>
			<td>$d[type_mobil]</td>
			<td>$d[kepemilikan_mobil]</td>
			<td align='center'><img src='images_car/".$d['foto_mobil']."' width='150' height='100'></td>
			
			
			<td>
			<form method='POST' action='?page=mobil&act=editmobil'>
				 <input type='hidden' name='no_polisi' value='$d[no_polisi]' size=15>
				 <input type='submit' value='Update'></form>
			</td></tr>";
        }
	
	
	echo "
	</table></center><br>
	";
   

 echo "<br><br><br><br></div>";



break;

//===============================================================================

case "tampilmobilpribadi":
?>

<div class="panel-heading"><h4>Daftar Mobil</h4></div>
<?php
echo "<br><font color='#428bca' size='+1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<b><a href='?page=mobil&act=tambahmobil'>+Tambah Mobil</a></b></font><br><br>
	
	<font color='#428bca' size='+1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<b><a href='?page=mobil&act=tampilmobil'>Operasional</a></b></font>
	
	<font color='#428bca' size='+1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<b><a href='?page=mobil&act=tampilmobilpribadi'>Pribadi</a></b></font><br>
	
	&nbsp;&nbsp;&nbsp;&nbsp;<center>
	<table border='1' class='CSS_Table_Example' style='width:95%;height:5px;'>
	<tr bgcolor='#e1dada'>
	<td width='1%'>No.</td>
	<td width='8%'>No. Polisi</td>
	<td width='10%'>Driver</td>
	<td width='15%'>Type Mobil</td>
	<td width='15%'>Kepemilikan Mobil</td>
	<td width='10%'>Foto Mobil</td>
	<td width='10%'>Action</td>
	</tr>";
	
	
	$query="select * from master_mobil where kepemilikan_mobil NOT IN ('Operasional','Rental')";
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
			if ($no % 2) $background='#ffffff'; else $background='#e5e7f3';
			
            echo "<tr valign='top' bgcolor='$background'><td>$no</td>
			<td>$d[no_polisi]</td>
			<td>$d[nama_driver]</td>
			<td>$d[type_mobil]</td>
			<td>Pribadi</td>
			<td align='center'><img src='images_car/".$d['foto_mobil']."' width='150' height='100'></td>
			
			
			<td>
			<form method='POST' action='?page=mobil&act=editmobil'>
				 <input type='hidden' name='no_polisi' value='$d[no_polisi]' size=15>
				 <input type='submit' value='Update'></form>
			</td></tr>";
        }
	
	
	echo "
	</table></center><br>
	";
   

 echo "<br><br><br><br></div>";



break;

case "editmobil":
//==================================================================================================================================

if (isset($_POST['submit'])) {
    $no_polisi = $_POST['no_polisi'];
    $type_mobil = $_POST['type_mobil'];
    $nama_driver = $_POST['nama_driver'];
    $kepemilikan_mobil = $_POST['kepemilikan_mobil'];
    $kepemilikan_mobil2 = $_POST['kepemilikan_mobil2'];
	
	$lokasi_file=$_FILES['foto_mobil']['tmp_name'];
	$nama_file=$_FILES['foto_mobil']['name'];
	$ukuran_file=$_FILES['foto_mobil']['size'];
	
    // cek validasi data.
    if (empty($no_polisi)) {
        echo "<script language='javascript'>alert ('Field No Polisi kosong'); document.location.href='?page=mobil&act=tampilmobil'</script>";
    } else if (empty($type_mobil)) {
        echo "<script language='javascript'>alert ('Field jenis/type kosong'); document.location.href='?page=mobil&act=tampilmobil'</script>";
    } else if (empty($nama_driver)) {
        echo "<script language='javascript'>alert ('Field Nama Driver kosong'); document.location.href='?page=mobil&act=tampilmobil'</script>";
    } else if (empty($kepemilikan_mobil)) {
        echo "<script language='javascript'>alert ('Field Kepemiikan Mobil kosong'); document.location.href='?page=mobil&act=tampilmobil'</script>";
    }
	else{
	
	if (empty($nama_file)) {
	
	if($kepemilikan_mobil=='Pribadi'){mysql_query("UPDATE master_mobil SET no_polisi= '$_POST[no_polisi1]',
								type_mobil= '$_POST[type_mobil]',
								nama_driver= '$_POST[nama_driver]',
								kepemilikan_mobil= '$_POST[kepemilikan_mobil2]'
							  WHERE no_polisi ='$_POST[no_polisi]'");}
	else{
		mysql_query("UPDATE master_mobil SET no_polisi= '$_POST[no_polisi1]',
								type_mobil= '$_POST[type_mobil]',
								nama_driver= '$_POST[nama_driver]',
								kepemilikan_mobil= '$_POST[kepemilikan_mobil]'
							  WHERE no_polisi ='$_POST[no_polisi]'");	
							  }}
	else {
	$acak=rand(0000,99999);
	$nama_file_unik=$acak.$nama_file;
	$direktori="images_car/$nama_file_unik";


	if(move_uploaded_file($lokasi_file,"$direktori"));
	
	mysql_query("UPDATE master_mobil SET no_polisi= '$_POST[no_polisi1]',
								type_mobil= '$_POST[type_mobil]',
								nama_driver= '$_POST[nama_driver]',
								kepemilikan_mobil= '$_POST[kepemilikan_mobil]',
								foto_mobil='$nama_file_unik'
							  WHERE no_polisi ='$_POST[no_polisi]'");
	
	}			  

	 echo"<script language='javascript'>alert ('Update data berhasil'); </script>
	<script language='javascript'>
	document.location.href='?page=mobil&act=tampilmobil'</script>";
	
	}

}

 $sql = "select * from master_mobil where no_polisi='$_POST[no_polisi]'";
 $query = mysql_query($sql);
  while ($d = mysql_fetch_array($query)) {
?>

<div class="panel-heading">
    <div class="panel-title">
       
        <h4>Update Data Mobil</h4>
    </div>
</div>
<div class="panel-body">
    <div class="btn-group btn-group-justified">
        <a href="?page=mobil&act=tampilmobil" class="btn btn-primary col-sm-3">
            
            Daftar Mobil
        </a>
    </div>

    <form class="form form-vertical" method="post" action="?page=mobil&act=editmobil" enctype="multipart/form-data">

        <div class="control-group">
            <label>Nomor Polisi</label>
            <div class="controls">
                <input type="text" class="form-control" name='no_polisi1' value="<?php echo $d['no_polisi']; ?>" placeholder="Masukkan Nomor Polisi" id='no_polisi'>
                <input type="hidden" class="form-control" name='no_polisi' value="<?php echo $d['no_polisi']; ?>" placeholder="Masukkan Nomor Polisi" id='no_polisi'>
            </div>
            <label>Jenis/merk/type Mobil</label>
            <div class="controls">
                <input type="text" class="form-control" placeholder="Masukkan jenis/merk/type kendaraan" name='type_mobil' value="<?php echo $d['type_mobil']; ?>" id='type_mobil'>
            </div>
            <label>Driver</label>
            <div class="controls">
                <input type="text" class="form-control" placeholder="Masukkan Nama driver" name='nama_driver' value="<?php echo $d['nama_driver']; ?>" id='nama_driver'>
            </div>
			 <label>Foto Mobil</label><br> <font color="red" size="-2"><i>**Bila gambar tidak Diganti kosongkan</i></font>
            <div class="controls">
				<img src="images_car/<?php echo $d['foto_mobil']; ?>" width="320" height="240">
				<input type="file" name="foto_mobil">
             </div>
            <label>Kepemilikan Mobil</label>
            <div class="controls">
                <select class="form-control" name='kepemilikan_mobil' id='kepemilikan_mobil' onChange='javascript:Check();'>
				
					
                    <?php if($d['kepemilikan_mobil']=='Operasional'){?>
					<option value='Operasional' selected>Operasional</option>
                    <option value='Pribadi'>Pribadi</option>
					<option value='Rental'>Rental</option><?php } else{
					echo "<option value='Operasional' >Operasional</option>
                    <option value='Pribadi' selected>Pribadi</option>
					<option value='Rental'>Rental</option>";
					}
					
					if($d['kepemilikan_mobil']=='Operasional'){}
					
					?>
					
					
					
					</select>
            </div>
			
			
			<label>Pemilik Mobil</label>
            <div class="controls">
                <select class="form-control" name='kepemilikan_mobil2' id='pemilikmobil' 
				<?php if($d['kepemilikan_mobil']=='Operasional' OR $d['kepemilikan_mobil']=='Rental'){?> disabled <?php } 
				else{}?>>
				  <?php
				  
				 echo "
		 		<option value=''>Nama Pemilik</option>";
		 	 $tampil2=mysql_query("SELECT * FROM master_employee WHERE position_user='HOD' ORDER BY nama_employee ASC");
            while($w=mysql_fetch_array($tampil2)){
				if ($w['no_employee']==$d['kepemilikan_mobil'])
					{echo "<option value='$d[kepemilikan_mobil]' selected>$w[nama_employee]</option>";}
              echo "<option value='$w[no_employee]'>$w[nama_employee]</option>";
            }
				
			echo "</select>";
				  
				  ?>
					
            </div>
			
        </div> 
        <div class="control-group">
            <label></label>
            <div class="controls">
                <button type="submit" class="btn btn-primary" value ='simpan' name='submit' id='submit'>
                    Simpan
                </button>
            </div>
        </div>   

    </form>


</div><!--/panel content-->

<?php
}
break;

 case "tambahmobil":
//==================================================================================================================================

if (isset($_POST['submit'])) {
    $no_polisi = $_POST['no_polisi'];
    $type_mobil = $_POST['type_mobil'];
    $nama_driver = $_POST['nama_driver'];
    $kepemilikan_mobil = $_POST['kepemilikan_mobil'];
	
	if($kepemilikan_mobil=='Pribadi'){
    $kepemilikan_mobil2 = $_POST['kepemilikan_mobil2'];}
	
	else{}
	
	$lokasi_file=$_FILES['foto_mobil']['tmp_name'];
	$nama_file=$_FILES['foto_mobil']['name'];
	$ukuran_file=$_FILES['foto_mobil']['size'];
	
    // cek validasi data.
    if (empty($no_polisi)) {
        echo "<script language='javascript'>alert ('Field No Polisi kosong'); document.location.href='?page=mobil&act=tambahmobil'</script>";
    } else if (empty($type_mobil)) {
        echo "<script language='javascript'>alert ('Field jenis/type kosong'); document.location.href='?page=mobil&act=tambahmobil'</script>";
    } else if (empty($nama_driver)) {
        echo "<script language='javascript'>alert ('Field Nama Driver kosong'); document.location.href='?page=mobil&act=tambahmobil'</script>";
    } else if (empty($kepemilikan_mobil)) {
        echo "<script language='javascript'>alert ('Field Kepemiikan Mobil kosong'); document.location.href='?page=mobil&act=tambahmobil'</script>";
    } 
	 else if (empty($nama_file)) {
        echo "<script language='javascript'>alert ('Anda Belum Upload Foto Mobil'); document.location.href='?page=mobil&act=tambahmobil'</script>";
    }
	else {
	$acak=rand(0000,99999);
	$nama_file_unik=$acak.$nama_file;
	$direktori="images_car/$nama_file_unik";


	if($kepemilikan_mobil=='Pribadi'){
	
	if(move_uploaded_file($lokasi_file,"$direktori"));
	    $sql = "INSERT INTO master_mobil(no_polisi, type_mobil, nama_driver, status_mobil, km_mobil, aktif_mobil, kepemilikan_mobil,foto_mobil) 
		VALUES ('$no_polisi','$type_mobil','$nama_driver','Available','0','aktif','$kepemilikan_mobil2','$nama_file_unik');";
        $query = mysql_query($sql);
        if ($query) {
            echo"<script language='javascript'>alert ('input data berhasil'); </script>
	<script language='javascript'>
	document.location.href='?page=mobil&act=tampilmobilpribadi'</script>";
        } else {
            echo "<script language='javascript'>alert ('input data gagal'); </script>";
        }
	
	}
	else{
	if(move_uploaded_file($lokasi_file,"$direktori"));
	    $sql = "INSERT INTO master_mobil(no_polisi, type_mobil, nama_driver, status_mobil, km_mobil, aktif_mobil, kepemilikan_mobil,foto_mobil) 
		VALUES ('$no_polisi','$type_mobil','$nama_driver','Available','0','aktif','$kepemilikan_mobil','$nama_file_unik');";
        $query = mysql_query($sql);
        if ($query) {
            echo"<script language='javascript'>alert ('input data berhasil'); </script>
	<script language='javascript'>
	document.location.href='?page=mobil&act=tampilmobil'</script>";
        } else {
            echo "<script language='javascript'>alert ('input data gagal'); </script>";
        }
		
    }}
}
?>

<div class="panel-heading">
    <div class="panel-title">
       
        <h4>Tambah Data Mobil</h4>
    </div>
</div>
<div class="panel-body">
    <div class="btn-group btn-group-justified">
        <a href="?page=mobil&act=tampilmobil" class="btn btn-primary col-sm-3">
            
            Daftar Mobil
        </a>
    </div>

    <form class="form form-vertical" method="post" action="?page=mobil&act=tambahmobil" enctype="multipart/form-data">

        <div class="control-group">
            <label>Nomor Polisi</label>
            <div class="controls">
                <input type="text" class="form-control" name='no_polisi' placeholder="Masukkan Nomor Polisi" id='no_polisi'>
            </div>
            <label>Jenis/merk/type Mobil</label>
            <div class="controls">
                <input type="text" class="form-control" placeholder="Masukkan jenis/merk/type kendaraan" name='type_mobil' id='type_mobil'>
            </div>
            <label>Driver</label>
            <div class="controls">
                <input type="text" class="form-control" placeholder="Masukkan Nama driver" name='nama_driver' id='nama_driver'>
            </div>
			 <label>Foto Mobil</label>
            <div class="controls">
				<input type="file" name="foto_mobil">
             </div>
            <label>Kepemilikan Mobil</label>
            <div class="controls">
                <select class="form-control" name='kepemilikan_mobil' id='kepemilikan_mobil' onChange='javascript:Check();'>
				 <option value=''>Pilih</option>	
				<option value='Operasional'>Operasional</option>
				<option value='Pribadi'>Pribadi</option>
				<option value='Rental'>Rental</option>
				<option value='Delivery'>Delivery</option>
				</select>
            </div>
		
		<script language="javascript">
			function Check(){
						var objkepemilikan_mobil = document.getElementById('kepemilikan_mobil');
						var objpemilikmobil = document.getElementById('pemilikmobil');
						if(objkepemilikan_mobil.value == 'Pribadi'){
								objpemilikmobil.disabled = false;
								
						}else{
								objpemilikmobil.disabled = true;
								}
						}
						</script>
			
			
			<label>Pemilik Mobil</label>
            <div class="controls">
                <select class="form-control" name='kepemilikan_mobil2' id='pemilikmobil' disabled>
				  <?php
				  
				 echo "
		 		<option value=''>Nama Pemilik</option>";
		 	 $tampil2=mysql_query("SELECT * FROM master_employee WHERE position_user='HOD' ORDER BY nama_employee ASC");
            while($w=mysql_fetch_array($tampil2)){
              echo "<option value='$w[no_employee]'>$w[nama_employee]</option>";
            }
				
			echo "</select>";
				  
				  ?>
					
            </div>
        </div> 
        <div class="control-group">
            <label></label>
            <div class="controls">
                <button type="submit" class="btn btn-primary" value ='simpan' name='submit' id='submit'>
                    Simpan
                </button>
            </div>
        </div>   

    </form>


</div><!--/panel content-->


<?php
break;
}
?>
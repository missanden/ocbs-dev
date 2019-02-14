
<div class="panel-heading">
    <div class="panel-title">
        <i class="glyphicon glyphicon-wrench pull-right"></i>
        <h4>Selamat Datang</h4>
    </div>
</div>
<div class="panel-body">


    <form class="form form-vertical" method="post" action="?page=cari">
        <br>
        <div class="control-group">
            <label>Cari Transaksi</label>
            <div class="controls">
                <input type="text"  name='key' id='key' >
                <select name="kriteria" ><option>Pelanggan</option><option>Mobil</option><option>Nota Transaksi</option></select>
            </div>

        </div> 
        <div class="control-group">
            <label></label>
            <div class="controls">
                <button type="submit" class="btn btn-primary" value ='Cari' name='caribtn' id='caribtn'>
                    Cari
                </button>
            </div>
        </div>   

    </form>

    <?php
    require_once '../config/koneksi.php';
// proses pencarian data
    if (isset($_GET['page'])) {
        if ($_GET['page'] == 'cari') {


            $key = $_POST['key'];
            $kriteria = $_POST['kriteria'];
            if (empty($key)) {
                echo "<script language='javascript'>alert ('Masukkan kata kunci ex.a '); document.location.href='index.php'</script>";
            } else {

                if ($kriteria == 'Pelanggan') {
                    $tt = "select count(*) as jumlah from pelanggan where nama_pelanggan = '$key' OR nama_pelanggan LIKE '%$key%'";
                    $qxt = mysql_query($tt);
                    while ($d = mysql_fetch_array($qxt)) {
                        $hasilcari = $d[jumlah];
                        echo "<p>Ditemukan $d[jumlah]  data terkait kata kunci : '" . $key . "'</p>";
                    }
                    if ($hasilcari == 0) {
                        echo "Data tidak ditemukan";
                    } else {

                        $sql = "select * from pelanggan where nama_pelanggan = '$key' OR nama_pelanggan LIKE '%$key%'";
                        $query = mysql_query($sql);
                        echo "<table width='100%' class=\"table table-striped\">
                    <tr>
                    <th>No</th><th>Nama Pelanggan</th><th>Alamat</th><th>Identitas</th><th>No Telp</th>
                    </tr>";

                        $no = 0;

                        while ($d = mysql_fetch_array($query)) {
                            $no++;
                            echo "<tr><td>$no</td><td>$d[nama_pelanggan]</td><td>$d[alamat]</td><td>$d[identitas]</td><td>$d[no_telp]</td>
	</tr>";
                        }
                        echo "</table>";
                    }
                } else if ($kriteria == 'Mobil') {
                    $tt = "select count(*) as jumlah from mobil where jenis = '$key' OR jenis LIKE '%$key%' OR no_polisi LIKE '%$key%'";
                    $qxt = mysql_query($tt);
                    while ($d = mysql_fetch_array($qxt)) {
                        $hasilcari = $d[jumlah];
                        echo "<p>Ditemukan $d[jumlah]  data terkait kata kunci : '" . $key . "'</p>";
                    }
                    if ($hasilcari == 0) {
                        echo "Data tidak ditemukan";
                    } else {

                        $sql = "select * from mobil where jenis = '$key' OR jenis LIKE '%$key%' OR no_polisi LIKE '%$key%'";
                        $query = mysql_query($sql);
                        echo "<table width='100%' class=\"table table-striped\">
                    <tr>
                    <th>No</th><th>Nomor Polisi</th><th>Jenis/type Mobil</th><th>Tarif/hari</th><th>Keterangan</th>
                    </tr>";


                        $no = 0;
                        while ($d = mysql_fetch_array($query)) {
                            $no++;
                            echo "<tr><td>$no</td><td>$d[no_polisi]</td><td>$d[jenis]</td><td>$d[tarif]</td><td>$d[keterangan]</td>
	</tr>";
                        }
                        echo "</table>";
                    }
                }
            }
        }
    }
    ?> 

</div><!--/panel content-->


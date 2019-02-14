<?php
session_start();

if (empty($_SESSION['s_no_employee']) AND empty($_SESSION['s_password'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=index.php><b>LOGIN</b></a></center>";
}
else{
?>

<html>
<head>
<title></title>
<script type="text/javascript" src="../nicEdit.js"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>
</script>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header">
	<div id="menu">
      <ul>
        <?php include "menu.php"; ?>
      
      </ul>
	    <p>&nbsp;</p>
 	</div>

  <div id="content">
		<?php include "content.php"; ?>
  </div>
<?php   
  echo "<br><br><br><br><br><br><p align=right>Login : $hari_ini, ";
  echo tgl_indo(date("Y m d"))."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
?> 
		<div id="footer">
			Copyright &copy; 2015. All rights reserved.
		</div>
</div>
</body>
</html>
<?php
}
?>

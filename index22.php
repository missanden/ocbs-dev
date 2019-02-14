<html>
<head>
<title>Login System COBS</title>
<script language="javascript">
function validasi(form){
  if (form.	no_employee.value == ""){
    alert("Anda belum mengisikan NIK.");
    form.no_employee.focus();
    return (false);
  }
     
  if (form.password.value == ""){
    alert("Anda belum mengisikan Password.");
    form.password.focus();
    return (false);
  }
  return (true);
}
</script>
<br><br><br>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body OnLoad="document.login.username.focus();">
<div id="header">
  <div id="content"><br><br><br>
	
    <img src="images/login-welcome.gif" width="97" height="95" hspace="10" align="left">

<form name="login" action="cek_login.php" method="POST" onSubmit="return validasi(this)">
<table>
<tr><td>NIK</td><td>  <input type="text" name="no_employee"></td></tr>
<tr><td>Password</td><td>  <input type="password" name="password"></td></tr>
<tr><td colspan="2"><input type="submit" value="Login"></td></tr>
</table>
</form><br>
  </div>
	<div id="footer"><center>COBS (CAR OPERATIONAL BOOKING SYSTEM)<br>
			Copyright &copy; 2016. All rights reserved.
	</div>
</div>
</body>
</html>

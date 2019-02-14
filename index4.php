<!DOCTYPE html>
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
<html >
  <head>
    <meta charset="UTF-8">
    <title>Login System COBS</title>
    
    
        <link rel="stylesheet" href="css/style.css">

  </head>

  <body>
<br><br><br><br>
    <div class="login">
  <div class="login-triangle"></div>
  
  <h2 class="login-header">Log in</h2>

  <form name="login" action="cek_login.php" method="POST" onSubmit="return validasi(this)" class="login-container">
    <p><input type="text" name="no_employee" placeholder="NIK"></p>
    <p><input type="password" placeholder="Password" name="password"></p>
    <p><input type="submit" value="Log in"></p>
  </form>
</div>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    
    
    
    
  </body>
</html>

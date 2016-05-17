<?php
include('config.php');
if($_SESSION["logeado"] == "SI"){ 
header ("Location: inicio.php");
}
?>
<?php
if($_POST['button']){
	if($_POST['mail']){
		
		$mail = htmlentities($_POST['mail']);
		
		$link = mysql_connect ($dbhost, $dbusername, $dbuserpass);
        mysql_select_db($dbname,$link);
		
		$queEmp = "SELECT * FROM usuarios WHERE email='$mail'";
		$resEmp = mysql_query($queEmp, $link) or die(mysql_error());
		$totEmp = mysql_num_rows($resEmp);
		if($totEmp == 0){
		echo "El mail ingresado no existe";
		exit();
		}		
		
		$row = mysql_fetch_assoc($resEmp);
		$hash = md5(md5($row['username']).md5($row['password']));

		$headers .= "From:Recuperar password <info@webmaster.com>\r\n";  
		$message = "Para recuperar tu contrasenia dar click en la url de abajo.
		http://www.tuweb.com/pass.php?id=".$hash."&mail=".$mail."";
		
		if (mail($mail,"Recuperar password",$message,$headers)){
		$msg = "Se te envio un link a tu mail para cambiar la password";
		}
	}
}
?>
<em><?=$msg?></em>
<form id="form1" name="form1" method="post" action="recuperar.php">
  Tu email<br />
  <input type="text" name="mail" id="mail" />
  <br />
  <br />
<input type="submit" name="button" id="button" value="Recuperar" />
</form>

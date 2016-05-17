<?php
include('config.php');
if($_SESSION["logeado"] == "SI"){
header ("Location: inicio.php");
}
?>
<?
$id = htmlentities($_GET['id']);
$mail = htmlentities($_GET['mail']);
$pass = md5($_POST['pass']);
if($_POST['button']){
	if(isset($id) && isset($mail)){
		$link = mysqli_connect ($dbhost, $dbusername, $dbuserpass);
        mysqli_select_db($dbname,$link);

		$queEmp = "SELECT * FROM usuarios WHERE email='$mail'";
		$resEmp = mysqli_query($queEmp, $link) or die(mysql_error());
		$totEmp = mysql_num_rows($resEmp);
		if($totEmp == 0){
		echo "El mail ingresado no existe";
		exit();
		}

		$row = mysqli_fetch_assoc($resEmp);
		$hash = md5(md5($row['username']).md5($row['password']));

		if($hash == $id){
		$sql = "UPDATE usuarios SET password='".$pass."' WHERE email='$mail'";
		mysqli_query($sql,$link);
		echo "Contrase&ntilde;a cambiada correctamente";
		exit();
		}
	}
}
?>
<form id="form1" name="form1" method="post" action="pass.php?id=<?=$id?>&mail=<?=$mail?>">
  Nueva contrase&ntilde;a<br />
  <input type="text" name="pass" />
  <br />
  <br />
<input type="submit" name="button" id="button" value="Guardar" />
</form>

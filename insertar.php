<?php
include('config.php');
    // Primero comprobamos que ningún campo esté vacío y que todos los campos existan.
    if(isset($_POST['username']) && !empty($_POST['username']) &&
    isset($_POST['password']) && !empty($_POST['password']) &&
    isset ($_POST['email']) && !empty($_POST['email']) &&
    isset($_POST['permisos']) && ($_POST['permisos'] != "")){
        // Si entramos es que todo se ha realizado correctamente
		$password = md5($_POST['password']);
		$username = htmlentities($_POST['username']);
		$mail = htmlentities($_POST['email']);
		$permisos = $_POST['permisos'];
		
        $link = mysql_connect ($dbhost, $dbusername, $dbuserpass);
        mysql_select_db($dbname,$link);
		
		$queEmp = "SELECT username FROM usuarios WHERE username='$username'";
		$resEmp = mysql_query($queEmp, $link) or die(mysql_error());
		$totEmp = mysql_num_rows($resEmp);
		if($totEmp > 0){
		echo "Nombre de usuario no disponible";
		exit();
		}
		
		$queEmp = "SELECT email FROM usuarios WHERE email='$mail'";
		$resEmp = mysql_query($queEmp, $link) or die(mysql_error());
		$totEmp = mysql_num_rows($resEmp);
		if($totEmp > 0){
		echo "El mail ingresado no esta disponible";
		exit();
		}
		
        // Con esta sentencia SQL insertaremos los datos en la base de datos
        mysql_query("INSERT INTO usuarios (username,password,email,permisos)
        VALUES ('{$username}','{$password}','{$mail}','{$permisos}')",$link);

        // Ahora comprobaremos que todo ha ido correctamente
        $my_error = mysql_error($link);

        if(!empty($my_error)) {

            header ("Location: registrarse.php?errordat");

        } else {

             header ("Location: usuarios.php");

        }

    } else {

         header ("Location: registrarse.php?errordb");

    }

?>
<?php
include('config.php');
    // Primero comprobamos que ning�n campo est� vac�o y que todos los campos existan.
    if(isset($_POST['nombre']) && !empty($_POST['nombre']) &&
    isset($_POST['direccion']) && !empty($_POST['direccion']) &&
    isset ($_POST['email']) && !empty($_POST['email']) &&
    isset($_POST['telefono']) && !empty($_POST['telefono'])){
    // Si entramos es que todo se ha realizado correctamente
		$direccion = $_POST['direccion'];
		$nombre = htmlentities($_POST['nombre']);
		$mail = htmlentities($_POST['email']);
		$telefono = $_POST['telefono'];

        $link = mysql_connect ($dbhost, $dbusername, $dbuserpass);
        mysql_select_db($dbname,$link);

		$queEmp = "SELECT nombre FROM proveedores WHERE nombre='$nombre'";
		$resEmp = mysql_query($queEmp, $link) or die(mysql_error());
		$totEmp = mysql_num_rows($resEmp);
		if($totEmp > 0){
		echo "Nombre de proveedor no disponible";
		exit();
		}

		$queEmp = "SELECT correo FROM proveedores WHERE correo='$mail'";
		$resEmp = mysql_query($queEmp, $link) or die(mysql_error());
		$totEmp = mysql_num_rows($resEmp);
		if($totEmp > 0){
		echo "El mail ingresado no esta disponible";
		exit();
		}

        // Con esta sentencia SQL insertaremos los datos en la base de datos
        mysql_query("INSERT INTO proveedores (nombre,direccion,telefono,correo)
        VALUES ('{$nombre}','{$direccion}','{$telefono}','{$mail}')",$link);

        // Ahora comprobaremos que todo ha ido correctamente
        $my_error = mysql_error($link);

        if(!empty($my_error)) {

            header ("Location: registrar_proveedores.php?errordat");

        } else {

             header ("Location: proveedores.php");

        }

    } else {

         header ("Location: registrar_proveedores.php?errordb");
    }

?>

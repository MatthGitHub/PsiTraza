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

        $link = mysqli_connect($dbhost, $dbusername, $dbuserpass);
        mysqli_select_db($link,$dbname);

		$queEmp = "SELECT nombre FROM clientes WHERE nombre='$nombre'";
		$resEmp = mysqli_query($link,$queEmp) or die(mysql_error());
		$totEmp = mysql_num_rows($resEmp);
		if($totEmp > 0){
		echo "Nombre de cliente no disponible";
		exit();
		}

		$queEmp = "SELECT correo FROM clientes WHERE correo='$mail'";
		$resEmp = mysqli_query($link,$queEmp) or die(mysql_error());
		$totEmp = mysql_num_rows($resEmp);
		if($totEmp > 0){
		echo "El mail ingresado no esta disponible";
		exit();
		}

        // Con esta sentencia SQL insertaremos los datos en la base de datos
        mysqli_query($link,"INSERT INTO clientes (nombre,direccion,telefono,correo)
        VALUES ('{$nombre}','{$direccion}','{$telefono}','{$mail}')");

        // Ahora comprobaremos que todo ha ido correctamente
        $my_error = mysql_error($link);

        if(!empty($my_error)) {

            header ("Location: registrar_clientes.php?errordat");

        } else {

             header ("Location: clientes.php");

        }

    } else {

         header ("Location: registrar_clientes.php?errordb");

    }

?>

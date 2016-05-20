<?php
include('config.php');
	$idLote = $_GET['idLote'];
	$idIngreso = $_GET['idIngreso'];
	$maxStock = $_GET['max'];
    // Primero comprobamos que ning�n campo est� vac�o y que todos los campos existan.
    if(isset($_POST['idTipoProceso']) && !empty($_POST['idTipoProceso']) &&
    isset($_POST['cantidad']) && !empty($_POST['cantidad'])&&
	 isset($_POST['txtFecha']) && !empty($_POST['txtFecha'])&&
		($_POST['cantidad'] <= $maxStock)){
        // Si entramos es que todo se ha realizado correctamente
		$tipo = $_POST['idTipoProceso'];
		$cantidad = $_POST['cantidad'];
		$fecha = $_POST['txtFecha'];
        
		//Coneccion a BD
		$link = mysqli_connect ($dbhost, $dbusername, $dbuserpass);
        mysqli_select_db($link,$dbname);

		// Con esta sentencia SQL insertaremos los datos en la base de datos
        mysqli_query($link,"INSERT INTO procesos (idIngreso,tipoProceso,fecha,cantidad)
		VALUES ('{$idIngreso}','{$tipo}','{$fecha}','{$cantidad}')");
		
		//Busco idProceso que se genero
		$idProceso = mysqli_insert_id($link);
		
		//Seguido insertamos en los datos en la tabla PROCESOS EN ESPERA.
		mysqli_query($link,"INSERT INTO procesoenespera (idProceso,fecha,cantidad)
		VALUES ('{$idProceso}','{$fecha}','{$cantidad}')");
		
		
        
		// Ahora comprobaremos que todo ha ido correctamente
        $my_error = mysql_error($link);

        if(!empty($my_error)) {

            header ("Location: form_proceso.php?errordat&idIngreso={$idIngreso}");

        } else {

             header ("Location: seguimiento_lote.php?idLote={$idLote}");

        }

    } else {

         header ("Location: form_proceso.php?errordb&idIngreso={$idIngreso}&max={$maxStock}");

    }

?>

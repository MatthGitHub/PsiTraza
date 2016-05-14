<?php
include('config.php');
		$idLote = $_GET['idLote'];
    // Primero comprobamos que ning�n campo est� vac�o y que todos los campos existan.
    if(isset($_POST['idTipoProceso']) && !empty($_POST['idTipoProceso']) &&
    isset($_POST['cantidad']) && !empty($_POST['cantidad'])){
        // Si entramos es que todo se ha realizado correctamente
		$tipo = $_POST['idTipoProceso'];
		$cantidad = $_POST['cantidad'];


		// Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
		date_default_timezone_set('UTC');
		//Imprimimos la fecha actual dandole un formato
		$fecha = date("Y-m-d");
		$anio = date("Y")+1;
		$vencimiento = date("$anio-m-d");

        $link = mysql_connect ($dbhost, $dbusername, $dbuserpass);
        mysql_select_db($dbname,$link);


        // Con esta sentencia SQL insertaremos los datos en la base de datos
        mysql_query("INSERT INTO procesos (idLote,tipoProceso,fecha,cantidad) VALUES ('{$idLote}','{$tipo}','{$fecha}','{$cantidad}')",$link);
        // Ahora comprobaremos que todo ha ido correctamente
        $my_error = mysql_error($link);

        if(!empty($my_error)) {

            header ("Location: form_proceso.php?errordat&numLote={$idLote}");

        } else {

             header ("Location: seguimiento_lote.php?idLote={$idLote}");

        }

    } else {

         header ("Location: form_proceso.php?errordb&numLote={$idLote}");

    }

?>

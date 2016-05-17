<<<<<<< HEAD
<?php
include('config.php');
$idLote = $_GET['idLote'];
		// Primero comprobamos que ning�n campo est� vac�o y que todos los campos existan.
	if(isset($_POST['idTipoProceso']) && isset($_POST['clientes']) &&
	isset($_POST['expedicion']) && !empty($_POST['expedicion']) &&
	isset($_POST['cantidad']) && !empty($_POST['cantidad'])){
        // Si entramos es que todo se ha realizado correctamente

		$tipo = $_POST['idTipoProceso'];
		$cantidad = $_POST['cantidad'];
		$expedicion = $_POST['expedicion'];
		$cliente = $_POST['clientes'];


		// Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
		date_default_timezone_set('UTC');
		//Imprimimos la fecha actual dandole un formato
		$fecha = date("Y-m-d");

        $link = mysql_connect ($dbhost, $dbusername, $dbuserpass);
        mysql_select_db($dbname,$link);


        // Con esta sentencia SQL insertaremos los datos en la base de datos
        mysql_query("INSERT INTO entregas (cliente,tipoProceso,fichaExpedicion,idLote,cantidad,fecha) VALUES ('{$cliente}','{$tipo}','{$expedicion}','{$idLote}','{$cantidad}','{$fecha}')",$link);
        // Ahora comprobaremos que todo ha ido correctamente
        $my_error = mysql_error($link);

        if(!empty($my_error)) {

            header ("Location: form_entrega.php?errordat&numLote={$idLote}");

        } else {

             header ("Location: seguimiento_lote.php?idLote={$idLote}");

        }

    } else {

         header ("Location: form_entrega.php?errordb&numLote={$idLote}");

    }

?>
=======
<?php
include('config.php');
$idLote = $_GET['idLote'];
		// Primero comprobamos que ning�n campo est� vac�o y que todos los campos existan.
	if(isset($_POST['idTipoProceso']) && isset($_POST['clientes']) &&
	isset($_POST['expedicion']) && !empty($_POST['expedicion']) &&
	isset($_POST['cantidad']) && !empty($_POST['cantidad'])){
        // Si entramos es que todo se ha realizado correctamente

		$tipo = $_POST['idTipoProceso'];
		$cantidad = $_POST['cantidad'];
		$expedicion = $_POST['expedicion'];
		$cliente = $_POST['clientes'];


		// Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
		date_default_timezone_set('UTC');
		//Imprimimos la fecha actual dandole un formato
		$fecha = date("Y-m-d");

        $link = mysql_connect ($dbhost, $dbusername, $dbuserpass);
        mysql_select_db($dbname,$link);


        // Con esta sentencia SQL insertaremos los datos en la base de datos
        mysql_query("INSERT INTO entregas (cliente,tipoProceso,fichaExpedicion,idLote,cantidad,fecha) VALUES ('{$cliente}','{$tipo}','{$expedicion}','{$idLote}','{$cantidad}','{$fecha}')",$link);
        // Ahora comprobaremos que todo ha ido correctamente
        $my_error = mysql_error($link);

        if(!empty($my_error)) {

            header ("Location: form_entrega.php?errordat&numLote={$idLote}");

        } else {

             header ("Location: seguimiento_lote.php?idLote={$idLote}");

        }

    } else {

         header ("Location: form_entrega.php?errordb&numLote={$idLote}");

    }

?>
>>>>>>> 7368f1fa91c06f74b61fa0d88eff70f899e21054

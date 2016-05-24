<?php
include('config.php');
$idLote = $_GET['idLote'];
$maxStock = $_GET['max'];
$idProcesoDep = $_GET['idProcesoEs'];
$tipo = $_GET['tipo'];
		// Primero comprobamos que ning�n campo est� vac�o y que todos los campos existan.
	if(isset($_POST['clientes']) &&	isset($_POST['expedicion']) &&
	!empty($_POST['expedicion']) &&	isset($_POST['cantidad']) &&
	!empty($_POST['cantidad']) && ($_POST['cantidad'] <= $maxStock)
	&& isset($_POST['txtFecha'])&& !empty($_POST['txtFecha'])){
    // Si entramos es que todo se ha realizado correctamente
		$cantidad = $_POST['cantidad'];
		$expedicion = $_POST['expedicion'];
		$cliente = $_POST['clientes'];
		$fecha = $_POST['txtFecha'];


    $link = mysqli_connect ($dbhost, $dbusername, $dbuserpass);
    mysqli_select_db($link,$dbname);

        // Con esta sentencia SQL insertaremos los datos en la base de datos si viene de proceso
				if($tipo == 'proceso'){
						mysqli_query($link,"INSERT INTO entregas (cliente,fichaExpedicion,idProcesoEnEspera,cantidad,fecha) VALUES ('{$cliente}','{$expedicion}','{$idProcesoDep}','{$cantidad}','{$fecha}')");
				}
				if($tipo == 'desposito'){
						mysqli_query($link,"INSERT INTO entregas (cliente,fichaExpedicion,idDeposito,cantidad,fecha) VALUES ('{$cliente}','{$expedicion}','{$idProcesoDep}','{$cantidad}','{$fecha}')");
				}

				//Comprobamos cantidad sobrante en proceso en espera -----------------------------------------------------------------------------------
				//Buscamos la cantidad del proceso entregado
				if($tipo == 'proceso'){
					$enEspera = mysqli_query($link,"SELECT cantidad FROM procesoenespera WHERE idProcesoEnEspera = '$idProcesoDep'");
					$enEspera = mysqli_fetch_array($enEspera);
					$cantProcEs = $enEspera['cantidad'];

					//Le restamos lo entregado a la cantidad total
					$total = $cantProcEs - $cantidad;

					// Se actualiza el registro con la nueva cantidad sobrante.
					mysqli_query($link,"UPDATE procesoenespera SET cantidad = '{$total}' WHERE idProcesoEnEspera = '$idProcesoDep'");
				}

				//Buscamos la cantidad del deposito entregado
				if($tipo == 'deposito'){
					$depo = mysqli_query($link,"SELECT cantidad FROM depositos WHERE iDeposito = '$idProcesoDep'");
					$depo = mysqli_fetch_array($depo);
					$cantDepo = $depo['cantidad'];

					//Le restamos lo entregado a la cantidad total
					$total = $cantDepo - $cantidad;

					// Se actualiza el registro con la nueva cantidad sobrante.
					mysqli_query($link,"UPDATE depositos SET cantidad = '{$total}' WHERE idProcesoEnEspera = '$idProcesoDep'");
				}



				// Ahora comprobaremos que todo ha ido correctamente
        $my_error = mysql_error($link);

        if(!empty($my_error)) {

            header ("Location: form_entrega.php?errordat&idLote={$idLote}&max={$maxStock}&id={$idProcesoEs}");

        } else {

             header ("Location: seguimiento_lote.php?idLote={$idLote}");

        }

    } else {

         header ("Location: form_entrega.php?errordb&idLote={$idLote}&max={$maxStock}&id={$idProcesoEs}");

    }

?>

<?php
include('config.php');
if($_SESSION["logeado"] != "SI"){
	header ("Location: index.php");
	exit;
}
$tipo =$_GET['tipo'];
$id =$_GET['id'];
$flag = 0;

		$link = mysqli_connect ($dbhost, $dbusername, $dbuserpass);
		mysqli_select_db($link,$dbname);
		$lotes = mysqli_query($link,"SELECT * FROM lotes");
		$idLote = mysqli_fetch_array($lotes);


		//Si el id que obtenemos por post es de clientes
		if($tipo == 'cliente'){
			$queEmp = "DELETE FROM clientes WHERE idCliente= $id";
			$resEmp = mysqli_query($link,$queEmp) or die(mysql_error());
		}

		//Si el id que obtenemos por post es de proveedores
		if($tipo == 'proveedor'){
			$queEmp = "DELETE FROM proveedores WHERE idProveedor='$id'";
			$resEmp = mysql_query($link,$queEmp) or die(mysql_error());
		}

		//Si el id que obtenemos por post es de usuarios
		if($tipo == 'usuario'){
			$queEmp = "DELETE FROM usuarios WHERE ID='$id'";
			$resEmp = mysqli_query($link,$queEmp) or die(mysql_error());
			$totEmp = mysqli_num_rows($resEmp);
		}

		//Si el id que obtenemos por post es de ingreso tambien se borra el Lote
		if($tipo == 'ingreso'){
			//Busco el id lote para la pagina seguimiento de lote
			$ingreso = mysqli_query($link,"SELECT idLote FROM ingresos WHERE idIngreso='$id'") or die(mysql_error());
			$idLote = mysqli_fetch_array($ingreso);
			$idLote = $idLote['idLote'];
			// Busco si el lote tiene procesos, entregas o depositos
			$qexiste = mysqli_query($link,"SELECT id_lote,i.idIngreso,d.iDeposito,idEntrega,p.idProceso
					FROM lotes l
					LEFT JOIN ingresos i ON i.idLote = id_lote
					LEFT JOIN procesos p ON p.idIngreso = i.idIngreso
					LEFT JOIN procesoenespera pe ON p.idProceso = pe.idProceso
					LEFT JOIN entregas e ON e.idProcesoEnEspera = pe.idProcesoEnEspera
					LEFT JOIN depositos d ON d.idProcesoEnEspera = pe.idProcesoEnEspera
					WHERE i.idIngreso = $id AND (d.iDeposito IS NOT NULL OR e.idEntrega IS NOT NULL OR p.idProceso IS NOT NULL)") or die(mysql_error());
			$cantRows = mysqli_num_rows($qexiste);

			//Si el resultado es mayor a 0 significa que tiene procesos o depositos o entregas y no se puede eliminar hasta que no se elminen esos
			if($cantRows == 0){
				//Busco el id lote para la pagina seguimiento de lote
				$ingreso = mysqli_query($link,"SELECT idLote FROM ingresos WHERE idIngreso='$id'") or die(mysql_error());
				$idLote = mysqli_fetch_array($ingreso);
				$idLote = $idLote['idLote'];
				//Borro el ingreso
				$queEmp = "DELETE FROM ingresos WHERE idLote='$idLote'";
				$resEmp = mysqli_query($link,$queEmp) or die(mysql_error());
				//Borro el lote
				$queEmp = "DELETE FROM lotes WHERE id_lote='$idLote'";
				$resEmp = mysqli_query($link,$queEmp) or die(mysql_error());
				//Pongo la bandera en uno para avisar que se borro y vaya a otra pagina
				$flag = 1;
			}

		}

		//Si el id que obtenemos por post es de entrega
		//Si se elimina la entrega hay que devolverle su cantidad original al proceso en espera
		if($tipo == 'entrega'){
			//Buscamos el id Lote para volver al seguimiento de lote con el vigente
			$entrega = mysqli_query($link,"	SELECT idLote
																			FROM entregas e
																			JOIN procesoenespera pe ON e.idProcesoEnEspera = pe.idProcesoEnEspera
																			JOIN procesos p ON p.idProceso = pe.idProceso
																			JOIN ingresos i ON i.idIngreso = p.idIngreso
																			WHERE idEntrega={$id}
																			UNION ALL
																			SELECT idLote
																			FROM entregas e
																			JOIN depositos d ON e.iDeposito = d.iDeposito
																			JOIN procesoenespera pe ON d.idProcesoEnEspera = pe.idProcesoEnEspera
																			JOIN procesos p ON p.idProceso = pe.idProceso
																			JOIN ingresos i ON i.idIngreso = p.idIngreso
																			WHERE idEntrega={$id}") or die(mysql_error());
			$idLote = mysqli_fetch_array($entrega);
			$idLote = $idLote['idLote'];
			//Nos fijamos si la entrega poroviene del proceso en espera o de un deposito	-----------------------------------------------------------------

			//Verificamos si viene de proceso en espera
			$verifProc = mysqli_query($link,"SELECT idProcesoEnEspera FROM entregas WHERE idEntrega = {$id}");
			$verifProc =mysqli_fetch_array($verifProc);
			$verifProc = $verifProc['idProcesoEnEspera'];
			if($verifProc != NULL){
				//Buscamos el id del proceso en espera
				$idProcesoEs = mysqli_query($link,"SELECT idProcesoEnEspera FROM entregas WHERE idEntrega='$id'") or die(mysql_error());
				$idProcesoEs = mysqli_fetch_array($idProcesoEs);
				$idProcesoEs = $idProcesoEs['idProcesoEnEspera'];
				//Buscamos la cantidad a devolverle al proceso en espera
				$cantidad = mysqli_query($link,"SELECT cantidad FROM entregas WHERE idEntrega='$id'") or die(mysql_error());
				$cantidad = mysqli_fetch_array($cantidad);
				$cantidad = $cantidad['cantidad'];
				//Buscamos la cantidad actual del proceso
				$proCant = mysqli_query($link,"SELECT cantidad FROM procesoenespera WHERE idProcesoEnEspera = '$idProcesoEs'");
				$proCant = mysqli_fetch_array($proCant);
				$proCant = $proCant['cantidad'];
				//Sumo las cantidades para volver a la que teniamos
				$cantidad = $cantidad + $proCant;
				//Borramos el registro de entregas
				$queEmp = "DELETE FROM entregas WHERE idEntrega='$id'";
				$resEmp = mysqli_query($link,$queEmp) or die(mysql_error());
				//Hacemos update del registro del proceso en espera al que pertenecia para devolverle su cantidad
				mysqli_query($link,"UPDATE procesoenespera SET cantidad = '{$cantidad}' WHERE idProcesoEnEspera = {$idProcesoEs}");
			}

			// Verificamos si viene de deposito
			$verifDep = mysqli_query($link,"SELECT iDeposito FROM entregas WHERE idEntrega = {$id}");
			$verifDep =mysqli_fetch_array($verifDep);
			$verifDep = $verifDep['iDeposito'];
			if($verifDep != NULL){
				//Buscamos el id del deposito
				$idProcesoDep = mysqli_query($link,"SELECT iDeposito FROM entregas WHERE idEntrega='$id'") or die(mysql_error());
				$idProcesoDep = mysqli_fetch_array($idProcesoDep);
				$idProcesoDep = $idProcesoDep['iDeposito'];
				//Buscamos la cantidad a devolverle al proceso en espera
				$cantidad = mysqli_query($link,"SELECT cantidad FROM entregas WHERE idEntrega='$id'") or die(mysql_error());
				$cantidad = mysqli_fetch_array($cantidad);
				$cantidad = $cantidad['cantidad'];
				//Buscamos la cantidad actual del proceso
				$depCant = mysqli_query($link,"SELECT cantidad FROM depositos WHERE iDeposito = '$idProcesoDep'");
				$depCant = mysqli_fetch_array($depCant);
				$depCant = $depCant['cantidad'];
				//Sumo las cantidades para volver a la que teniamos
				$cantidad = $cantidad + $depCant;
				//Borramos el registro de entregas
				$queEmp = "DELETE FROM entregas WHERE idEntrega='$id'";
				$resEmp = mysqli_query($link,$queEmp) or die(mysql_error());
				//Hacemos update del registro del proceso en espera al que pertenecia para devolverle su cantidad
				mysqli_query($link,"UPDATE depositos SET cantidad = '{$cantidad}' WHERE iDeposito = {$idProcesoDep}");
			}

		}

		//Si el id que obtenemos por post es de deposito
		if($tipo == 'deposito'){
			//Buscamos el id lote para volver al seguimmiento del lote con este lote
			$deposito = mysqli_query($link,"SELECT idLote FROM depositos d JOIN procesoenespera pe ON pe.idProcesoEnEspera = d.idProcesoEnEspera JOIN procesos p ON p.idProceso = pe.idProceso JOIN ingresos i ON i.idIngreso = p.idIngreso WHERE iDeposito='$id'") or die(mysql_error());
			$idLote = mysqli_fetch_array($deposito);
			$idLote = $idLote['idLote'];
			//Buscamos el id del proceso en espera
			$idProcesoEs = mysqli_query($link,"SELECT idProcesoEnEspera FROM depositos WHERE iDeposito='$id'") or die(mysql_error());
			$idProcesoEs = mysqli_fetch_array($idProcesoEs);
			$idProcesoEs = $idProcesoEs['idProcesoEnEspera'];
			//Buscamos la cantidad a devolverle al proceso en espera
			$cantidad = mysqli_query($link,"SELECT cantidad FROM depositos WHERE iDeposito='$id'") or die(mysql_error());
			$cantidad = mysqli_fetch_array($cantidad);
			$cantidad = $cantidad['cantidad'];
			//Buscamos la cantidad actual del proceso
			$proCant = mysqli_query($link,"SELECT cantidad FROM procesoenespera WHERE idProcesoEnEspera = '$idProcesoEs'");
			$proCant = mysqli_fetch_array($proCant);
			$proCant = $proCant['cantidad'];
			//Sumo las cantidades para volver a la que teniamos
			$cantidad = $cantidad + $proCant;
			//Borramos el registro de depositos
			$queEmp = "DELETE FROM depositos WHERE iDeposito='$id'";
			$resEmp = mysqli_query($link,$queEmp) or die(mysql_error());
			//Hacemos update del registro del proceso en espera al que pertenecia para devolverle su cantidad
			mysqli_query($link,"UPDATE procesoenespera SET cantidad = '{$cantidad}' WHERE idProcesoEnEspera = {$idProcesoEs}");
		}

		//Si el id que obtenemos por post es de proceso
		if($tipo == 'proceso'){
			$proceso = mysqli_query($link,"SELECT idLote FROM procesos p JOIN ingresos i ON p.idIngreso = i.idIngreso WHERE idProceso='$id'") or die(mysql_error());
			$idLote = mysqli_fetch_array($proceso);
			$idLote = $idLote['idLote'];

			//Verificar si el proceso ya posee depositos
			$verifDep = mysqli_query($link,"SELECT * FROM procesos p JOIN procesoenespera pe ON p.idProceso = pe.idProceso JOIN depositos d On d.IdProcesoEnEspera = pe.idProcesoEnEspera WHERE p.idProceso = {$id}");
			$verifDep = mysqli_num_rows($verifDep);
			//Verificar si el proceso ya posee entregas
			$verifEnt = mysqli_query($link,"SELECT * FROM procesos p JOIN procesoenespera pe ON p.idProceso = pe.idProceso JOIN entregas e On e.IdProcesoEnEspera = pe.idProcesoEnEspera WHERE p.idProceso = {$id}");
			$verifEnt = mysqli_num_rows($verifEnt);

			if(($verifDep == 0)&&($verifEnt == 0)){
				$queEmp = "DELETE FROM procesos WHERE idProceso='$id'";
				$resEmp = mysqli_query($link,$queEmp) or die(mysql_error());
				$flag = 1;
			}


		}

        // Ahora comprobaremos que todo ha ido correctamente
        $my_error = mysql_error($link);

      if(!empty($my_error)) {
				if($tipo == 'cliente'){
	            	header ("Location: clientes.php?errordat");
				}
				if($tipo == 'proveedor'){
	            	header ("Location: proveedores.php?errordat");
				}
				if($tipo == 'usuario'){
	            	header ("Location: usuarios.php?errordat");
				}
				if($tipo == 'lote'){
	            	header ("Location: lotes.php?errordat");
				}
				if($tipo == 'ingreso'){
	            	header ("Location: inicio.php");
				}
				if($tipo == 'deposito'){
	            	header ("Location: seguimiento_lote.php?idLote={$idLote}");
				}
				if($tipo == 'proceso'){
	            	header ("Location: seguimiento_lote.php?idLote={$idLote}");
				}
				if($tipo == 'entrega'){
	            	header ("Location: seguimiento_lote.php?idLote={$idLote}");
				}
      } else {
				if($tipo == 'cliente'){
	            	header ("Location: clientes.php");
				}
				if($tipo == 'proveedor'){
	            	header ("Location: proveedores.php");
				}
				if($tipo == 'usuario'){
	            	header ("Location: usuarios.php");
				}
				if($tipo == 'lote'){
	            	header ("Location: lotes.php");
				}
				if(($tipo == 'ingreso')&&($flag == 1)){
	            	header ("Location: inicio.php");
				}
				if(($tipo == 'ingreso')&&($flag == 0)){
	            	header ("Location: seguimiento_lote.php?errordat&idLote={$idLote}");
				}
				if($tipo == 'deposito'){
	            	header ("Location: seguimiento_lote.php?idLote={$idLote}");
				}
				if(($tipo == 'proceso')&&($flag == 1)){
	            	header ("Location: seguimiento_lote.php?idLote={$idLote}");
				}
				if(($tipo == 'proceso')&&($flag == 0)){
	            	header ("Location: seguimiento_lote.php?errordat&idLote={$idLote}");
				}
				if($tipo == 'entrega'){
	            	header ("Location: seguimiento_lote.php?idLote={$idLote}");
				}

      }

?>

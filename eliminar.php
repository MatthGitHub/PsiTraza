<?php
include('config.php');
if($_SESSION["logeado"] != "SI"){
	header ("Location: index.php");
	exit;
}
$tipo =$_GET['tipo'];
$id =$_GET['id'];
$flag = 0;

		$link = mysql_connect ($dbhost, $dbusername, $dbuserpass);
    mysql_select_db($dbname,$link);
		$lotes = mysql_query("SELECT * FROM lotes",$link);
		$idLote = mysql_result($lotes,0,'id_lote');
		//Si el id que obtenemos por post es de clientes
		if($tipo == 'cliente'){
			$queEmp = "DELETE FROM clientes WHERE idCliente= $id";
			$resEmp = mysql_query($queEmp, $link) or die(mysql_error());
		}

		//Si el id que obtenemos por post es de proveedores
		if($tipo == 'proveedor'){
			$queEmp = "DELETE FROM proveedores WHERE idProveedor='$id'";
			$resEmp = mysql_query($queEmp, $link) or die(mysql_error());
		}

		//Si el id que obtenemos por post es de usuarios
		if($tipo == 'usuario'){
			$queEmp = "DELETE FROM usuarios WHERE ID='$id'";
			$resEmp = mysql_query($queEmp, $link) or die(mysql_error());
			$totEmp = mysql_num_rows($resEmp);
		}

		//Si el id que obtenemos por post es de ingreso tambien se borra el Lote
		if($tipo == 'ingreso'){
			$qexiste = mysql_query("SELECT id_lote,idIngreso,iDeposito,idEntrega,idProceso
					FROM lotes l
					LEFT JOIN depositos d ON d.idLote = l.id_lote
					LEFT JOIN entregas e ON e.idLote = id_lote
					LEFT JOIN procesos p ON p.idLote = id_lote
					LEFT JOIN ingresos i ON i.idLote = id_lote
					WHERE idIngreso = $id AND (iDeposito IS NOT NULL OR idEntrega IS NOT NULL OR idProceso IS NOT NULL)",$link) or die(mysql_error());
			$existe = mysql_fetch_array($qexiste);

			if(mysql_num_rows($qexiste) == 0){
				$queEmp = "DELETE FROM ingresos WHERE idLote='$idLote'";
				$resEmp = mysql_query($queEmp, $link) or die(mysql_error());
				$queEmp = "DELETE FROM lotes WHERE id_lote='$idLote'";
				$resEmp = mysql_query($queEmp, $link) or die(mysql_error());
				$flag = 1;
			}

		}

		//Si el id que obtenemos por post es de entrega
		if($tipo == 'entrega'){
			$proceso = mysql_query("SELECT * FROM entregas WHERE idEntrega='$id'",$link) or die(mysql_error());
			$idLote = mysql_result($proceso,0,'idLote');
			$queEmp = "DELETE FROM entregas WHERE idEntrega='$id'";
			$resEmp = mysql_query($queEmp, $link) or die(mysql_error());
		}
		//Si el id que obtenemos por post es de deposito
		if($tipo == 'deposito'){
			$deposito = mysql_query("SELECT * FROM depositos WHERE iDeposito='$id'",$link) or die(mysql_error());
			$idLote = mysql_result($deposito,0,'idLote');
			$queEmp = "DELETE FROM depositos WHERE iDeposito='$id'";
			$resEmp = mysql_query($queEmp, $link) or die(mysql_error());
		}
		//Si el id que obtenemos por post es de proceso
		if($tipo == 'proceso'){
			$proceso = mysql_query("SELECT * FROM procesos WHERE idProceso='$id'",$link) or die(mysql_error());
			$idLote = mysql_result($proceso,0,'idLote');
			$queEmp = "DELETE FROM procesos WHERE idProceso='$id'";
			$resEmp = mysql_query($queEmp, $link) or die(mysql_error());
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
	            	header ("Location: seguimiento_lote.php?errordat");
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

      }

?>

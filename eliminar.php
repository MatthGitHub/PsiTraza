<?php
include('config.php');
if($_SESSION["logeado"] != "SI"){
	header ("Location: index.php");
	exit;
}
$tipo =$_GET['tipo'];
$id =$_GET['id'];
		
		$link = mysql_connect ($dbhost, $dbusername, $dbuserpass);
        mysql_select_db($dbname,$link);
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

        }

?>
<?php
include('config.php');
    // Primero comprobamos que ning�n campo est� vac�o y que todos los campos existan.
    if(isset($_POST['idProveedor']) && !empty($_POST['idProveedor']) &&
    isset($_POST['cantidad']) && !empty($_POST['cantidad']) &&
    isset($_POST['nIngreso']) && !empty($_POST['nIngreso']) &&
    isset($_POST['idLote']) && !empty($_POST['idLote'])){
        // Si entramos es que todo se ha realizado correctamente
    $nIngreso = $_POST['nIngreso'];
    $nIngreso = (string) $_POST['nIngreso'];
    $idProveedor = $_POST['idProveedor'];
		$idLote = $_POST['idLote'];
    $anio = (string) substr(date("y"),1);
    $idProveedor = (string)$idProveedor;
    $idLote = (string) $idLote;
    $idLote = $idLote.$anio.$idProveedor.$nIngreso;
    $cantidad = $_POST['cantidad'];
    if(strpos($cantidad,',') > 0){
      $cantidad = number_format($cantidad,0,",",".");
    }

    // Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('UTC');
    //Imprimimos la fecha actual dandole un formato
    $fecha = date("Y-m-d");

        $link = mysql_connect ($dbhost, $dbusername, $dbuserpass);
        mysql_select_db($dbname,$link);

		$queEmp = "SELECT id_lote FROM lotes WHERE id_lote='$idLote'";
		$resEmp = mysql_query($queEmp, $link) or die(mysql_error());
		$totEmp = mysql_num_rows($resEmp);
		if($totEmp > 0){
		echo "Numero de lote existente";
		exit();
		}

        // Con esta sentencia SQL insertaremos los datos en la base de datos
        mysql_query("INSERT INTO lotes (id_lote,proveedor)
        VALUES ('{$idLote}','{$idProveedor}')",$link);
        mysql_query("INSERT INTO ingresos (idLote,fecha,cantidad)
        VALUES ('{$idLote}','{$fecha}',{$cantidad})",$link);
        // Ahora comprobaremos que todo ha ido correctamente
        $my_error = mysql_error($link);

        if(!empty($my_error)) {

            header ("Location: nuevo_lote.php?errordat");

        } else {

             header ("Location: seguimiento_lote.php?idLote={$idLote}");

        }

    } else {

         header ("Location: nuevo_lote.php?errordb");

    }

?>

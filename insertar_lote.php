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
    $fecha = $_POST['txtFecha'];
    $anio = (string) substr($fecha,3,1);
    $idProveedor = (string)$idProveedor;
    $idLote = (string) $idLote;
    $idLote = $idLote.$anio.$idProveedor.$nIngreso;
    $cantidad = $_POST['cantidad'];
    
    if(strpos($cantidad,',') > 0){
      $cantidad = number_format($cantidad,0,",",".");
    }



        $link = mysqli_connect ($dbhost, $dbusername, $dbuserpass);
        mysqli_select_db($link,$dbname);

		$queEmp = "SELECT id_lote FROM lotes WHERE id_lote='$idLote'";
		$resEmp = mysqli_query($link,$queEmp) or die(mysql_error());
		$totEmp = mysqli_num_rows($resEmp);
		if($totEmp > 0){
		echo "Numero de lote existente";
		exit();
		}

        // Con esta sentencia SQL insertaremos los datos en la base de datos
        mysqli_query($link,"INSERT INTO lotes (id_lote,proveedor)
        VALUES ('{$idLote}','{$idProveedor}')");
        mysqli_query($link,"INSERT INTO ingresos (idLote,fecha,cantidad)
        VALUES ('{$idLote}','{$fecha}',{$cantidad})");
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

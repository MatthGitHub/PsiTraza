<?php
include('config.php');
$idLote = $_GET['idLote'];
$maxStock = $_GET['max'];
$idProcesoEs = $_GET['idProcesoEs'];
	// Primero comprobamos que ning�n campo est� vac�o y que todos los campos existan.
  if(!empty($_POST['cantidad']) && ($_POST['cantidad'] <= $maxStock)
	&& isset($_POST['txtFecha'])&& !empty($_POST['txtFecha'])){
    // Si entramos es que todo se ha realizado correctamente
		$fecha = $_POST['txtFecha'];
		$cantidad = $_POST['cantidad'];

    $anio =substr($fecha,0,5);
    $mes =substr($fecha,6,8);
    $dia =substr($fecha,8,10);
    $anio = $anio + 1;

		$vencimiento ="{$anio}-{$mes}-{$dia}";

    $link = mysqli_connect ($dbhost, $dbusername, $dbuserpass);
    mysqli_select_db($link,$dbname);

    // Con esta sentencia SQL insertaremos los datos en la base de datos
    mysqli_query($link,"INSERT INTO depositos (idProcesoEnEspera,fecha,vencimiento,cantidad) VALUES ('{$idProcesoEs}','{$fecha}','{$vencimiento}','{$cantidad}')");
    //Comprobamos cantidad sobrante en proceso en espera -----------------------------------------------------------------------------------
    //Buscamos la cantidad edl proceso entregado
    $enEspera = mysqli_query($link,"SELECT cantidad FROM procesoenespera WHERE idProcesoEnEspera = '$idProcesoEs'");
    $enEspera = mysqli_fetch_array($enEspera);
    $cantProcEs = $enEspera['cantidad'];

    //Le restamos lo entregado a la cantidad total
    $total = $cantProcEs - $cantidad;

    // Se actualiza el registro con la nueva cantidad sobrante.
    mysqli_query($link,"UPDATE procesoenespera SET cantidad = '{$total}' WHERE idProcesoEnEspera = '$idProcesoEs'");



    // Ahora comprobaremos que todo ha ido correctamente
    $my_error = mysql_error($link);

    if(!empty($my_error)) {

        header ("Location: form_deposito.php?errordat&idLote={$idLote}&max={$maxStock}&id={$idProcesoEs}");

    } else {

         header ("Location: seguimiento_lote.php?idLote={$idLote}");

    }

} else {

     header ("Location: form_deposito.php?errordb&idLote={$idLote}&max={$maxStock}&id={$idProcesoEs}");

}

?>

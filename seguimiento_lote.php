<?php
include('config.php');
if($_SESSION["logeado"] != "SI"){
header ("Location: index.php");
exit;
}

$nlote = $_GET['idLote'];

// Conectar a la base de datos ---------------------------------------------------------------------------------------------------------------------------------------------------------
$link = mysqli_connect ($dbhost, $dbusername, $dbuserpass);
mysqli_select_db($link,$dbname) or die('No se puede seleccionar la base de datos');

//Busco el stock disponible para entregar ----------------------------------------------------------------------------------------------------------------------------------------------
//Busco la suma de la cantidad procesada
$cantProcesada = mysqli_query($link,"	SELECT SUM(pe.cantidad) AS cantidad
																			FROM procesoenespera pe
																			JOIN procesos p ON p.idProceso = pe.idProceso
																			JOIN ingresos i ON i.idIngreso = p.idIngreso
																			WHERE idLote = '{$nlote}' ");
$cantProcesada = mysqli_fetch_array($cantProcesada);
//Guardo cantidad procesada en variable
$tock = $cantProcesada['cantidad'];



//Busco stock disponible para procesar -------------------------------------------------------------------------------------------------------------------------------------------------
//Busco la suma de la cantidad ingresada
$cantIngresada = mysqli_query($link,"SELECT cantidad FROM ingresos WHERE idLote = '{$nlote}'");
$cantIngresada = mysqli_fetch_array($cantIngresada);
$cantIngresada = $cantIngresada['cantidad'];
//Busco cantidad total procesada
$tockQuery2 = mysqli_query($link,"SELECT SUM(p.cantidad) AS cantidad FROM ingresos i JOIN procesos p ON i.idIngreso = p.idIngreso WHERE idLote = '{$nlote}'");
$tockProcesa = mysqli_fetch_array($tockQuery2);
$tockProcesa = $tockProcesa['cantidad'];
//Calculo la diferencia restante que me dice cuanto queda para procesar
$tockProcesa = $cantIngresada - $tockProcesa;

// Busco la cantidad total en procesos -------------------------------------------------------------------------------------------
$cantTotalProcesada = mysqli_query($link,"	SELECT SUM(p.cantidad) AS cantidad
																			FROM ingresos i
																			JOIN procesos p ON p.idIngreso = i.idIngreso
																			WHERE idLote = '{$nlote}' ");
if(mysqli_num_rows($cantTotalProcesada) > 0){
	$cantTotalProcesada = mysqli_fetch_array($cantTotalProcesada);
	$cantTotalProcesada = $cantTotalProcesada['cantidad'];
}else{
	$cantTotalProcesada = 0;
}

//Busco la cantidad total entregada -------------------------------------------------------------------------------------------------------
$cantTotalEnt = mysqli_query($link,"	SELECT SUM(e.cantidad) AS cantidad
																			FROM entregas e
																			JOIN procesoenespera pe ON pe.idProcesoEnEspera = e.idProcesoEnEspera
																			JOIN procesos p ON p.idProceso = pe.idProceso
																			JOIN ingresos i ON i.idIngreso= p.idIngreso
																			WHERE idLote = '{$nlote}'");
if(mysqli_num_rows($cantTotalEnt) > 0){
	$cantTotalEnt = mysqli_fetch_array($cantTotalEnt);
	$cantTotalEnt = $cantTotalEnt['cantidad'];
}else{
	$cantTotalEnt = 0;
}

//Busco la cantidad total depositada -------------------------------------------------------------------------------------------------------------
$cantTotalDep = mysqli_query($link,"	SELECT SUM(d.cantidad) AS cantidad
																			FROM depositos d
																			JOIN procesoenespera pe ON d.idProcesoEnEspera = pe.idProcesoEnEspera
																			JOIN procesos p ON p.idProceso = pe.idProceso
																			JOIN ingresos i ON i.idIngreso= p.idIngreso
																			WHERE idLote = '{$nlote}'");
if(mysqli_num_rows($cantTotalDep) > 0){
	$cantTotalDep = mysqli_fetch_array($cantTotalDep);
	$cantTotalDep = $cantTotalDep['cantidad'];
}else{
	$cantTotalDep = 0;
}

//Busco la cantidad total disponible para procesar (deposito + procesado)
$cantTotalSum = $cantTotalDep + $tock;


//Busco todos los ingresos del lote que siempre va a ser uno ---------------------------------------------------------------------------------------------------------------------------
$ingresos = mysqli_query($link,"SELECT * FROM ingresos WHERE idLote = '{$nlote}'") or die(mysql_error());
$ingresos2 = mysqli_query($link,"SELECT * FROM ingresos WHERE idLote = '{$nlote}'") or die(mysql_error());

//Busco el id ingreso ------------------------------------------------------------------------------------------------------------------------------------------------------------------
$idIngreso = mysqli_fetch_array($ingresos2);
$idIngreso = $idIngreso['idIngreso'];


//Con el idIngreso busco todos los procesos que se generaron con ese idIngreso -----------------------------------------------------------------------------------------------------------
$procesos = mysqli_query($link,"SELECT * FROM procesos JOIN tiposprocesos ON tipoProceso = idTipoProceso WHERE idIngreso = '{$idIngreso}'") or die(mysql_error());
$procesos2 = mysqli_query($link,"SELECT * FROM procesos JOIN tiposprocesos ON tipoProceso = idTipoProceso WHERE idIngreso = '{$idIngreso}'") or die(mysql_error());

//Busco el idProceso ---------------------------------------------------------------------------------------------------------------------------------------------------------------------
$idProceso = mysqli_fetch_array($procesos2);
$idProceso = $idProceso['idProceso'];

//Busco todos los procesos en espera ---------------------------------------------------------------------------------------------------------------------------------------------------------
$procesoEnEsperaQuery = mysqli_query($link,"SELECT pe.idProceso,pe.fecha,pe.cantidad,pe.idProcesoEnEspera,tp.descripcion FROM procesoenespera pe JOIN procesos p ON pe.idProceso = p.idProceso JOIN tiposprocesos tp ON p.tipoProceso = tp.idTipoProceso WHERE idIngreso= {$idIngreso}");
//Busco la cantidad total de los procesos en espera y si es 0 que no aparezca la tabla
$procesoEnEsperaCant = mysqli_query($link,"SELECT SUM(pe.cantidad) AS cantidad FROM procesoenespera pe JOIN procesos p ON pe.idProceso = p.idProceso JOIN ingresos i ON i.idIngreso = p.idIngreso WHERE p.idIngreso= {$idIngreso}");
$procesoEnEsperaCant = mysqli_fetch_array($procesoEnEsperaCant);
$procesoEnEsperaCant = $procesoEnEsperaCant['cantidad'];

//Con el id proceso busco depositos que correspondan a ese proceso -----------------------------------------------------------------------------------------------------------------------
$depositos = mysqli_query($link,"SELECT d.iDeposito,d.IdProcesoEnEspera,d.fecha,d.vencimiento,d.cantidad,tp.descripcion,pe.idProceso FROM depositos d JOIN procesoenespera pe ON d.idProcesoEnEspera = pe.idProcesoEnEspera JOIN procesos p ON p.idProceso = pe.idProceso JOIN tiposprocesos tp ON tp.idTipoProceso = p.tipoProceso WHERE idIngreso ={$idIngreso}") or die(mysql_error());
//Para buscar el ID
$depositos2 = mysqli_query($link,"SELECT * FROM depositos d JOIN procesoenespera pe ON d.idProcesoEnEspera = pe.idProcesoEnEspera JOIN procesos p ON p.idProceso = pe.idProceso JOIN tiposprocesos tp ON tp.idTipoProceso = p.tipoProceso WHERE idIngreso ={$idIngreso}") or die(mysql_error());

//Busco el iDeposito ---------------------------------------------------------------------------------------------------------------------------------------------------------------------
$iDeposito = mysqli_fetch_array($depositos2);
$iDeposito = $iDeposito['iDeposito'];

//Busco las entregas que correspondan o al idDeposito o al idProceso ---------------------------------------------------------------------------------------------------------------------
$entregas = mysqli_query($link,"SELECT idEntrega,cliente,fichaExpedicion,e.idProcesoEnEspera,e.iDeposito,e.cantidad,e.fecha,nombre,p.idProceso,descripcion,i.idIngreso
FROM entregas e
JOIN procesoenespera pe ON pe.idProcesoEnEspera = e.idProcesoEnEspera
JOIN procesos p ON p.idProceso = pe.idProceso
JOIN tiposprocesos tp ON tp.idTipoProceso = p.tipoProceso
JOIN clientes c ON c.idCliente = e.cliente
JOIN ingresos i ON i.idIngreso = p.idIngreso
WHERE i.idIngreso = {$idIngreso}
UNION ALL
SELECT idEntrega,cliente,fichaExpedicion,e.idProcesoEnEspera,e.iDeposito,e.cantidad,e.fecha,nombre,p.idProceso,descripcion,i.idIngreso
FROM entregas e
JOIN depositos d ON d.iDeposito = e.iDeposito
JOIN procesoenespera pe ON pe.idProcesoEnEspera = d.idProcesoEnEspera
JOIN procesos p ON p.idProceso = pe.idProceso
JOIN tiposprocesos tp ON tp.idTipoProceso = p.tipoProceso
JOIN clientes c ON c.idCliente = e.cliente
JOIN ingresos i ON i.idIngreso = p.idIngreso
WHERE i.idIngreso = {$idIngreso}") or die(mysql_error());

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema PsiTraza</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <style type="text/css">
    body{background: #000;}
  </style>
  <body>
    <br>
        <div class="container">
		<!-- Static navbar -->
		<div class="navbar navbar-default" role="navigation">
		<div class="container-fluid">
		  <div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			  <span class="sr-only">Toggle navigation</span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">Bienvenido </a>
		  </div>
		  <div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
			  <li class="active"><a href="inicio.php">Inicio</a></li>
			  <li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">Clientes<span class="caret"></span></a>
				  <ul class="dropdown-menu">
					  <li><a href="clientes.php">Listar</a></li>
					  <li><a href="registrar_clientes.php">Nuevo</a></li>
					</ul>
			  </li>
			  <li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">Proveedores<span class="caret"></span></a>
				  <ul class="dropdown-menu">
					  <li><a href="proveedores.php">Listar</a></li>
					  <li><a href="registrar_proveedores.php">Nuevo</a></li>
					</ul>
			  </li>

		<?php
		  if($_SESSION["permiso"] == 1) {
			?> <li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">Usuarios<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="usuarios.php">Listar</a></li>
							  <li><a href="registrarse.php">Nuevo</a></li>
						  </ul>
					  </li><?php
		  }
		?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
		<li><a href="form_cambiar_clave.php"> Cambiar clave </a></li>
			   <li><a href=""> <?php echo $_SESSION["s_username"]; ?> </a></li>
			  <li><a href="">Fecha:
				<?php
				// Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
				date_default_timezone_set('UTC');
				//Imprimimos la fecha actual dandole un formato
				echo date("d / m / Y");
				?></a></li>
			  <li><a href="cerrar.php">Salir</a></li>
			</ul>
		  </div><!--/.nav-collapse -->
		</div><!--/.container-fluid -->
		</div>

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <div class="row">
          <h1> Seguimiento de Lote <?php echo $nlote; ?> </h1>
          <ul class="nav nav-tabs" algin="right">
          </ul>

          <?php
          if(isset($_GET['errordat'])){
          echo "
          <div class='alert alert-warning-alt alert-dismissable'>
                          <span class='glyphicon glyphicon-certificate'></span>
                          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>
                              ×</button>Debe eliminar todos los procesos precedentes antes de eliminar este estado</div>
          ";
          }else{
          echo "";
          }
          ?>
        </div>
      </div>
      <div class="jumbotron">
        <div class="row">
                  <!--  ------------------------- TABLA INGRESOS --------------------- -->
				  <?php if (mysqli_num_rows($ingresos)>0){ ?>
                    <table class="table table-hover">
                      <h2> Ingreso</h2>
					  <div align="center"><h3> Disponible para procesar: <?php echo $tockProcesa; ?> kg</h3></div>
                        <thead>
                          	  <th> Fecha </th>
                              <th> Cantidad </th>
							  <?php if($tockProcesa != 0) {?>
							  <th> Procesar </th>
							  <?php } ?>
							  <?php if($_SESSION["permiso"] == 1) {?> <th> Eliminar </th> <?php }?>
                          </thead>
                          <tbody>
                          	<?php while($arrayIngresos = mysqli_fetch_array($ingresos)){ ?>
                              <tr class="success">
                                  <td> <?php echo $arrayIngresos['fecha']; ?> </td>
                                  <td> <?php echo $arrayIngresos['cantidad']; ?> </td>
								  <?php if($tockProcesa != 0) {?>
								  <td>  <a href="form_proceso.php?idIngreso=<?php echo $arrayIngresos['idIngreso'];?>&max=<?php echo $tockProcesa; ?>" role="button"  class="btn btn-info btn-primary btn-block"> Procesar </a></td>
								  <?php } ?>
								 <?php if($_SESSION["permiso"] == 1) {?>
								  <td>  <a href="eliminar.php?id=<?php echo $arrayIngresos['idIngreso'];?>&tipo=ingreso " role="button"  class="btn btn-danger btn-primary btn-block"> Eliminar </a></td>
								  <?php }?>
                              </tr>
                              <?php } ?>
                          </tbody>
                    <?php } ?>
                  </table>
              </div>
              </div>
			   <!--  ------------------------- TABLA PROCESOS --------------------- -->
			  <?php if (mysqli_num_rows($procesos)>0){ ?>
					<div class="jumbotron">
						<div class="row">
							<table class="table table-hover">
								<h2> Procesos </h2>
								<div align="center"><h3> Total procesado: <?php echo $cantTotalProcesada; ?> kg</h3></div>
								<div align="center"><h3> Total disponible para entregar: <?php echo $cantTotalSum; ?> kg</h3></div>
								<thead>
									<th> Nº Proceso </th>
									<th> Tipo de Proceso </th>
									<th> Fecha </th>
									<th> Cantidad en KG </th>
									<?php if($_SESSION["permiso"] == 1) {?> <th> Eliminar </th> <?php }?>
								</thead>
								<tbody>
									<?php while($arrayProcesos = mysqli_fetch_array($procesos)){ ?>
									<tr class="success">
										<td> <?php echo $arrayProcesos['idProceso']; ?> </td>
										<td> <?php echo $arrayProcesos['descripcion']; ?> </td>
										<td> <?php echo $arrayProcesos['fecha']; ?> </td>
										<td> <?php echo $arrayProcesos['cantidad']; ?> </td>
										<?php if($_SESSION["permiso"] == 1) {?>
										<td>  <a href="eliminar.php?id=<?php echo $arrayProcesos['idProceso'];?>&tipo=proceso " role="button"  class="btn btn-danger btn-primary btn-block"> Eliminar </a></td>
										<?php }?>
									</tr>
									<?php } ?>
								</tbody>
					<?php } ?>
							</table>
						</div>
					</div>

					 <!--  ------------------------- TABLA PROCESOS EN ESPERA --------------------->
					<?php if ((mysqli_num_rows($procesoEnEsperaQuery) > 0)&&($procesoEnEsperaCant > 0)){ ?>
					<div class="jumbotron">
						<div class="row">
							<table class="table table-hover">
								<h2> Procesos en espera </h2>
								<div align="center"><h3> Disponible para entrega o deposito: <?php echo $tock; ?> kg</h3></div>
								<thead>
									<th> Pertence </th>
									<th> Tipo de Proceso </th>
									<th> Fecha </th>
									<th> Cantidad en KG </th>
									<th> Entregar </th>
									<th> Despositar </th>
								</thead>
								<tbody>
									<?php while($arrayProcesosEp = mysqli_fetch_array($procesoEnEsperaQuery)){ ?>
									<tr class="success">
										<td> <?php echo $arrayProcesosEp['idProceso']; ?> </td>
										<td> <?php echo $arrayProcesosEp['descripcion']; ?> </td>
										<td> <?php echo $arrayProcesosEp['fecha']; ?> </td>
										<td> <?php echo $arrayProcesosEp['cantidad']; ?> </td>
										<td>  <a href="form_entrega.php?id=<?php echo $arrayProcesosEp['idProcesoEnEspera'];?>&max=<?php echo $arrayProcesosEp['cantidad'];?>&idLote=<?php echo $nlote;?>&tipo=proceso" role="button"  class="btn btn-info btn-primary btn-block"> Entregar </a></td>
										<td>  <a href="form_deposito.php?id=<?php echo $arrayProcesosEp['idProcesoEnEspera'];?>&max=<?php echo $arrayProcesosEp['cantidad']; ?>&idLote=<?php echo $nlote;?>" role="button"  class="btn btn-info btn-primary btn-block"> Despositar </a></td>
									</tr>
									<?php } ?>
								</tbody>
					<?php } ?>
							</table>
						</div>
					</div>
					<!--  ------------------------- TABLA DEPOSITOS --------------------- -->
				 <?php if (mysqli_num_rows($depositos)>0){ ?>
				 <div class="jumbotron">
					 <div class="row">
						 <table class="table table-hover">
							 <h3> Deposito </h3>
							 <div align="center"><h3> Disponible para entrega: <?php echo $cantTotalDep; ?> kg</h3></div>
							 <thead>
								 <th> N° Deposito </th>
								 <th> Tipo de Proceso </th>
								 <th> Fecha </th>
								 <th> Vencimiento </th>
								 <th> Cantidad en KG </th>
								 <th> Entregar </th>
								 <?php if($_SESSION["permiso"] == 1) {?> <th> Eliminar </th> <?php }?>
							 </thead>
							 <tbody>
								 <?php while($arrayDepositos = mysqli_fetch_array($depositos)){ ?>
								 <tr class="success">
									  <td> <?php echo $arrayDepositos['iDeposito']; ?> </td>
									 <td> <?php echo $arrayDepositos['descripcion']; ?> </td>
									 <td> <?php echo $arrayDepositos['fecha']; ?> </td>
									 <td> <?php echo $arrayDepositos['vencimiento']; ?> </td>
									 <td> <?php echo $arrayDepositos['cantidad']; ?> </td>
									 <td>  <a href="form_entrega.php?id=<?php echo $arrayDepositos['iDeposito'];?>&tipo=deposito&max=<?php echo $arrayDepositos['cantidad'];?>&idLote=<?php echo $nlote;?> " role="button"  class="btn btn-info btn-primary btn-block"> Entregar </a></td>
									 <?php if($_SESSION["permiso"] == 1) {?>
									 <td>  <a href="eliminar.php?id=<?php echo $arrayDepositos['iDeposito'];?>&tipo=deposito " role="button"  class="btn btn-danger btn-primary btn-block"> Eliminar </a></td>
									 <?php }?>
								 </tr>
								 <?php } ?>
							 </tbody>
				 <?php } ?>
						 </table>
					 </div>
				 </div>
					<!--  ------------------------- TABLA ENTREGAS --------------------- -->
				 <?php if (mysqli_num_rows($entregas)>0){ ?>
				 <div class="jumbotron">
					 <div class="row">
						 <table class="table table-hover">
							 <h3> Entrega </h3>
							 	<div align="center"><h3> Total entregado: <?php echo $cantTotalEnt; ?> kg</h3></div>
							 <thead>
								 <td> Num Deposito </td>
								 <td> Num Proceso </td>
								 <th> Tipo de Proceso </th>
								 <th> Cantidad en KG </th>
								 <th> Fecha </th>
								 <th> Ficha Exp </th>
								 <th> Cliente </th>
								 <?php if($_SESSION["permiso"] == 1) {?> <th> Eliminar </th> <?php }?>
							 </thead>
							 <tbody>
								 <?php while($arrayEntregas = mysqli_fetch_array($entregas)){ ?>
								 <tr class="success">
									 <td> <?php if($arrayEntregas['iDeposito'] != NULL){ echo $arrayEntregas['iDeposito'];}else{ echo " --- ";} ?> </td>
									 <td> <?php if($arrayEntregas['idProceso'] != NULL){ echo $arrayEntregas['idProceso'];}else{ echo " --- ";} ?> </td>
									 <td> <?php echo $arrayEntregas['descripcion']; ?> </td>
									 <td> <?php echo $arrayEntregas['cantidad']; ?> </td>
									 <td> <?php echo $arrayEntregas['fecha']; ?> </td>
									 <td> <?php echo $arrayEntregas['fichaExpedicion']; ?> </td>
									 <td> <?php echo $arrayEntregas['nombre']; ?> </td>
									 <?php if($_SESSION["permiso"] == 1) {?>
									 <td>  <a href="eliminar.php?id=<?php echo $arrayEntregas['idEntrega'];?>&tipo=entrega " role="button"  class="btn btn-danger btn-primary btn-block"> Eliminar </a></td>
									 <?php }?>
								 </tr>
								 <?php } ?>
							 </tbody>
				 <?php } ?>
						 </table>
					 </div>
				 </div>

    </div> <!-- /container -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <p align="center"> by M. Benditti. </p>
  </body>
</html>

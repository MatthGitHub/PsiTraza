<?php
include('config.php');
if($_SESSION["logeado"] != "SI"){
header ("Location: index.php");
exit;
}

$nlote = $_GET['idLote'];

// Conectar a la base de datos
$link = mysqli_connect ($dbhost, $dbusername, $dbuserpass);
mysqli_select_db($link,$dbname) or die('No se puede seleccionar la base de datos');

//Busco el stock
$tockQuery = mysqli_query($link,"SELECT (SUM(p.cantidad) - e.cantidad) AS cantidad FROM ingresos i JOIN procesos p ON p.idIngreso = i.idIngreso LEFT JOIN entregas e ON e.idProceso = p.idProceso WHERE idLote = '{$nlote}'")or die(mysql_error());
$tock = mysqli_fetch_array($tockQuery);
$tock = $tock['cantidad'];


//Busco todos los ingresos del lote que siempre va a ser uno
$ingresos = mysqli_query($link,"SELECT * FROM ingresos WHERE idLote = '{$nlote}'") or die(mysql_error());
$ingresos2 = mysqli_query($link,"SELECT * FROM ingresos WHERE idLote = '{$nlote}'") or die(mysql_error());


//Busco el id ingreso
$idIngreso = mysqli_fetch_array($ingresos2);
$idIngreso = $idIngreso['idIngreso'];

if($tock == null){
	$ingresos3 = mysqli_query($link,"SELECT * FROM ingresos WHERE idLote = '{$nlote}'") or die(mysql_error());
	$tock = mysqli_fetch_array($ingresos3);
	$tock = $tock['cantidad'];
}

//Con el idIngreso busco todos los procesos que se generaron con ese idIngreso
$procesos = mysqli_query($link,"SELECT * FROM procesos JOIN tiposprocesos ON tipoProceso = idTipoProceso WHERE idIngreso = '{$idIngreso}'") or die(mysql_error());
$procesos2 = mysqli_query($link,"SELECT * FROM procesos JOIN tiposprocesos ON tipoProceso = idTipoProceso WHERE idIngreso = '{$idIngreso}'") or die(mysql_error());

//Busco el idProceso
$idProceso = mysqli_fetch_array($procesos2);
$idProceso = $idProceso['idProceso'];

//Con el id proceso busco depositos que correspondan a ese proceso
$depositos = mysqli_query($link,"SELECT * FROM depositos d JOIN procesos p ON d.idProceso = p.idProceso JOIN tiposprocesos tp ON tp.idTipoProceso = p.tipoProceso WHERE idIngreso ={$idIngreso}") or die(mysql_error());
$depositos2 = mysqli_query($link,"SELECT * FROM depositos d JOIN procesos p ON d.idProceso = p.idProceso JOIN tiposprocesos tp ON tp.idTipoProceso = p.tipoProceso WHERE idIngreso ={$idIngreso}") or die(mysql_error());

//Busco el iDeposito
$iDeposito = mysqli_fetch_array($depositos2);
$iDeposito = $iDeposito['iDeposito'];

//Busco las entregas que correspondan o al idDeposito o al idProceso
$entregas = mysqli_query($link,
"SELECT *
FROM entregas
JOIN clientes 
ON cliente = idCliente 
WHERE (idProceso IS NOT NULL AND idProceso IN (SELECT idProceso FROM procesos WHERE idIngreso = {$idIngreso})) 
OR (iDeposito IS NOT NULL AND iDeposito IN (SELECT iDeposito FROM depositos WHERE idProceso IN (SELECT idProceso FROM procesos WHERE idIngreso = {$idIngreso})))") or die(mysql_error());

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
          <h3> Seguimiento de Lote <?php echo $nlote; ?>  ---------------------------------------------------    Stock disponible: <?php echo $tock; ?> </h3>
          <ul class="nav nav-tabs" algin="right">
            <?php if(!isset($_GET['errordat'])){ ?>
            <li>        </li>
			<li><p><a class="btn btn-lg btn-primary" href="form_proceso.php?numLote=<?php echo $idIngreso; ?>" role="button">A proceso &raquo;</a></p></li>
            <li><p><a class="btn btn-lg btn-primary" href="form_deposito.php?numLote=<?php echo $nlote; ?> " role="button">A deposito &raquo;</a></p></li>
            <li><p><a class="btn btn-lg btn-primary" href="form_entrega.php?numLote=<?php echo $nlote; ?> " role="button">A entrega &raquo;</a></p></li>
          </ul>
          <?php }  ?>
          <?php
          if(isset($_GET['errordat'])){
          echo "
          <div class='alert alert-warning-alt alert-dismissable'>
                          <span class='glyphicon glyphicon-certificate'></span>
                          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>
                              ×</button>Debe eliminar todos los procesos antes de eliminar el ingreso y el lote</div>
          ";
          }else{
          echo "";
          }
          ?>
        </div>
      </div>
      <div class="jumbotron">
        <div class="row">
                  <?php if (mysqli_num_rows($ingresos)>0){ ?>
                    <table class="table table-hover">
                      <h3> Ingreso </h3>
                        <thead>
                          	  <th> Fecha </th>
                              <th> Cantidad </th>
							  <?php if($_SESSION["permiso"] == 1) {?> <th> Eliminar </th> <?php }?>
                          </thead>
                          <tbody>
                          	<?php while($arrayIngresos = mysqli_fetch_array($ingresos)){ ?>
                              <tr class="success">
                                  <td> <?php echo $arrayIngresos['fecha']; ?> </td>
                                  <td> <?php echo $arrayIngresos['cantidad']; ?> </td>
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
			  <?php if (mysqli_num_rows($procesos)>0){ ?>
					<div class="jumbotron">
						<div class="row">
							<table class="table table-hover">
								<h3> Procesos </h3>
								<thead>
									<th> Tipo de Proceso </th>
									<th> Fecha </th>
									<th> Cantidad en KG </th>
									<th> Entregar </th>
									<?php if($_SESSION["permiso"] == 1) {?> <th> Eliminar </th> <?php }?>
								</thead>
								<tbody>
									<?php while($arrayProcesos = mysqli_fetch_array($procesos)){ ?>
									<tr class="success">
										<td> <?php echo $arrayProcesos['descripcion']; ?> </td>
										<td> <?php echo $arrayProcesos['fecha']; ?> </td>
										<td> <?php echo $arrayProcesos['cantidad']; ?> </td>
										<td>  <a href="form_entrega.php?id=<?php echo $arrayProcesos['idProceso'];?>&tipo=proceso " role="button"  class="btn btn-info btn-primary btn-block"> Entregar </a></td>
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
					<?php if (mysqli_num_rows($depositos)>0){ ?>
					<div class="jumbotron">
						<div class="row">
							<table class="table table-hover">
								<h3> Deposito </h3>
								<thead>
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
										<td> <?php echo $arrayDepositos['descripcion']; ?> </td>
										<td> <?php echo $arrayDepositos['fecha']; ?> </td>
										<td> <?php echo $arrayDepositos['vencimiento']; ?> </td>
										<td> <?php echo $arrayDepositos['cantidad']; ?> </td>
										<td>  <a href="form_entrega.php?id=<?php echo $arrayDepositos['iDeposito'];?>&tipo=deposito " role="button"  class="btn btn-info btn-primary btn-block"> Entregar </a></td>
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
					<?php if (mysqli_num_rows($entregas)>0){ ?>
					<div class="jumbotron">
						<div class="row">
							<table class="table table-hover">
								<h3> Entrega </h3>
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

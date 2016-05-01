<?php
include('config.php');
if($_SESSION["logeado"] != "SI"){
header ("Location: index.php");
exit;
}

$nlote = $_GET['idLote'];

// Conectar a la base de datos
mysql_connect ($dbhost, $dbusername, $dbuserpass);
mysql_select_db($dbname) or die('No se puede seleccionar la base de datos');

$ingresos = mysql_query("SELECT * FROM ingresos WHERE idLote = '{$nlote}'") or die(mysql_error());
$depositos = mysql_query("SELECT * FROM depositos WHERE idLote = '{$nlote}'") or die(mysql_error());
$entregas = mysql_query("SELECT * FROM entregas WHERE idLote = '{$nlote}'") or die(mysql_error());
$procesos = mysql_query("SELECT * FROM procesos WHERE idLote = '{$nlote}'") or die(mysql_error());
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
              <li class=""><a href="clientes.php">Clientes</a></li>
              <li class=""><a href="proveedores.php">Proveedores</a></li>

			  <?php
					if($_SESSION["permiso"] == 1) {
						?> <li><a href="usuarios.php">Usuarios</a></li><?php
					}
			  ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
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
          <h3> Seguimiento de Lote <?php echo $nlote; ?> </h3>
        </div>
      </div>
      <div class="jumbotron">
        <div class="row">
                  <?php if (mysql_num_rows($ingresos)>0){ ?>
                    <table class="table table-hover">
                      <h3> Ingreso </h3>
                        <thead>
                          	  <th> Fecha </th>
                              <th> Cantidad </th>
                          </thead>
                          <tbody>
                          	<?php while($arrayIngresos = mysql_fetch_array($ingresos)){ ?>
                              <tr class="success">
                                  <td> <?php echo $arrayIngresos['fecha']; ?> </td>
                                  <td> <?php echo $arrayIngresos['cantidad']; ?> </td>
                              </tr>
                              <?php } ?>
                          </tbody>
                    <?php } ?>
                  </table>
              </div>
              </div>
                    <?php if (mysql_num_rows($depositos)>0){ ?>
                  <div class="jumbotron">
                    <div class="row">
                      <table class="table table-hover">
                          <thead>
                                <th> Nº Lote </th>
                                <th> Cantidad </th>
                                <th> Fecha </th>
                                <th> Estado </th>
                                <th> Tipo </th>
                            </thead>
                            <tbody>
                              <?php while($arrayDepositos = mysql_fetch_array($depositos)){ ?>
                                <tr class="success">
                                    <td> <?php echo $depositos['fecha']; ?> </td>
                                    <td> <?php echo $depositos['cantidad']; ?> </td>
                                    <td> <?php echo $depositos['tipoProceso']; ?> </td>
                                    <td> <?php echo $depositos['vencimiento']; ?> </td>
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

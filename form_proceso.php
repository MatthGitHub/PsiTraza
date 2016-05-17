<<<<<<< HEAD
<?php
include('config.php');
if($_SESSION["logeado"] != "SI"){
header ("Location: index.php");
exit;
}

$numLote = $_GET['numLote'];

// Conectar a la base de datos
mysql_connect ($dbhost, $dbusername, $dbuserpass);
mysql_select_db($dbname) or die('No se puede seleccionar la base de datos');
$query = mysql_query("SELECT * FROM tiposprocesos ") or die(mysql_error());


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>A proceso</title>

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

     .media
    {
        /*box-shadow:0px 0px 4px -2px #000;*/
        margin: 20px 0;
        padding:30px;
    }
    .dp
    {
        border:10px solid #eee;
        transition: all 0.2s ease-in-out;
    }
    .dp:hover
    {
        border:2px solid #eee;
        transform:rotate(360deg);
        -ms-transform:rotate(360deg);
        -webkit-transform:rotate(360deg);
        /*-webkit-font-smoothing:antialiased;*/
    }
body
{
    background-color: #1b1b1b;
}

.alert-purple { border-color: #694D9F;background: #694D9F;color: #fff; }
.alert-info-alt { border-color: #B4E1E4;background: #81c7e1;color: #fff; }
.alert-danger-alt { border-color: #B63E5A;background: #E26868;color: #fff; }
.alert-warning-alt { border-color: #F3F3EB;background: #E9CEAC;color: #fff; }
.alert-success-alt { border-color: #19B99A;background: #20A286;color: #fff; }
.glyphicon { margin-right:10px; }
.alert a {color: gold;}

.input-group-addon
{
    background-color: rgb(50, 118, 177);
    border-color: rgb(40, 94, 142);
    color: rgb(255, 255, 255);
}
.form-control:focus
{
    background-color: rgb(50, 118, 177);
    border-color: rgb(40, 94, 142);
    color: rgb(255, 255, 255);
}
.form-signup input[type="text"],.form-signup input[type="password"] { border: 1px solid rgb(50, 118, 177); }
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
              <li class=""><a href="inicio.php">Inicio</a></li>
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


<div class="container">
	<form name="form1" method="post" action="insertar_proceso.php?idLote=<?php echo $numLote ?>">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 class="text-center"> <?php echo $numLote ?> </h3>
                    <form class="form form-signup" role="form">
                    <div class="form-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-chevron-down"></span>Tipo de procesos</span>
                        <div class="col-xs-15 selectContainer">
                            <select class="form-control" name="idTipoProceso">
                           		<option value=""> </option>
                                <?php while($tipos = mysql_fetch_array($query)){ ?>
                                <option value=<?php echo $tipos['idTipoProceso'] ?>><?php echo $tipos['descripcion']?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                 </div>
                    <div class="form-group">
                           <div class="input-group">
                               <span class="input-group-addon"><span class="glyphicon glyphicon-stats"></span></span>
                               <input name="cantidad" type="text" class="form-control"  id="cantidad" value="" placeholder="Cantidad en KG" />
                           </div>
						   
                       </div>
					   <input type="submit" name="Submit" value="Guardar"  class="btn btn-sm btn-primary btn-block">
					</div>   
                
					</form>
            </div>
                    




<?php
if(isset($_GET['sucess'])){
echo "
<div class='alert alert-success-alt alert-dismissable'>
                <span class='glyphicon glyphicon-certificate'></span>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>
                    ×</button>Listo! Tu registro fue hecho satisfactoriamente.</div>
";
}else{
echo "";
}
?>
<?php
if(isset($_GET['errordat'])){
echo "
<div class='alert alert-warning-alt alert-dismissable'>
                <span class='glyphicon glyphicon-certificate'></span>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>
                    ×</button>Ha habido un error al insertar los valores.</div>
";
}else{
echo "";
}
?>
<?php
if(isset($_GET['errordb'])){
echo "
<div class='alert alert-danger-alt alert-dismissable'>
                <span class='glyphicon glyphicon-certificate'></span>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>
                    ×</button>Error, no ha introducido todos los datos.</div>
";
}else{
echo "";
}
?>
        </div>
    </div>

	</div>
</form>


    </div> <!-- /container -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
=======
<?php
include('config.php');
if($_SESSION["logeado"] != "SI"){
header ("Location: index.php");
exit;
}

$numLote = $_GET['numLote'];

// Conectar a la base de datos
mysql_connect ($dbhost, $dbusername, $dbuserpass);
mysql_select_db($dbname) or die('No se puede seleccionar la base de datos');
$query = mysql_query("SELECT * FROM tiposprocesos ") or die(mysql_error());


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>A proceso</title>

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

     .media
    {
        /*box-shadow:0px 0px 4px -2px #000;*/
        margin: 20px 0;
        padding:30px;
    }
    .dp
    {
        border:10px solid #eee;
        transition: all 0.2s ease-in-out;
    }
    .dp:hover
    {
        border:2px solid #eee;
        transform:rotate(360deg);
        -ms-transform:rotate(360deg);
        -webkit-transform:rotate(360deg);
        /*-webkit-font-smoothing:antialiased;*/
    }
body
{
    background-color: #1b1b1b;
}

.alert-purple { border-color: #694D9F;background: #694D9F;color: #fff; }
.alert-info-alt { border-color: #B4E1E4;background: #81c7e1;color: #fff; }
.alert-danger-alt { border-color: #B63E5A;background: #E26868;color: #fff; }
.alert-warning-alt { border-color: #F3F3EB;background: #E9CEAC;color: #fff; }
.alert-success-alt { border-color: #19B99A;background: #20A286;color: #fff; }
.glyphicon { margin-right:10px; }
.alert a {color: gold;}

.input-group-addon
{
    background-color: rgb(50, 118, 177);
    border-color: rgb(40, 94, 142);
    color: rgb(255, 255, 255);
}
.form-control:focus
{
    background-color: rgb(50, 118, 177);
    border-color: rgb(40, 94, 142);
    color: rgb(255, 255, 255);
}
.form-signup input[type="text"],.form-signup input[type="password"] { border: 1px solid rgb(50, 118, 177); }
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
              <li class=""><a href="inicio.php">Inicio</a></li>
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


<div class="container">
	<form name="form1" method="post" action="insertar_proceso.php?idLote=<?php echo $numLote ?>">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 class="text-center"> <?php echo $numLote ?> </h3>
                    <form class="form form-signup" role="form">
                    <div class="form-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-chevron-down"></span>Tipo de procesos</span>
                        <div class="col-xs-15 selectContainer">
                            <select class="form-control" name="idTipoProceso">
                           		<option value=""> </option>
                                <?php while($tipos = mysql_fetch_array($query)){ ?>
                                <option value=<?php echo $tipos['idTipoProceso'] ?>><?php echo $tipos['descripcion']?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                 </div>
                    <div class="form-group">
                           <div class="input-group">
                               <span class="input-group-addon"><span class="glyphicon glyphicon-stats"></span></span>
                               <input name="cantidad" type="text" class="form-control"  id="cantidad" value="" placeholder="Cantidad en KG" />
                           </div>
						   
                       </div>
					   <input type="submit" name="Submit" value="Guardar"  class="btn btn-sm btn-primary btn-block">
					</div>   
                
					</form>
            </div>
                    




<?php
if(isset($_GET['sucess'])){
echo "
<div class='alert alert-success-alt alert-dismissable'>
                <span class='glyphicon glyphicon-certificate'></span>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>
                    ×</button>Listo! Tu registro fue hecho satisfactoriamente.</div>
";
}else{
echo "";
}
?>
<?php
if(isset($_GET['errordat'])){
echo "
<div class='alert alert-warning-alt alert-dismissable'>
                <span class='glyphicon glyphicon-certificate'></span>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>
                    ×</button>Ha habido un error al insertar los valores.</div>
";
}else{
echo "";
}
?>
<?php
if(isset($_GET['errordb'])){
echo "
<div class='alert alert-danger-alt alert-dismissable'>
                <span class='glyphicon glyphicon-certificate'></span>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>
                    ×</button>Error, no ha introducido todos los datos.</div>
";
}else{
echo "";
}
?>
        </div>
    </div>

	</div>
</form>


    </div> <!-- /container -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
>>>>>>> 7368f1fa91c06f74b61fa0d88eff70f899e21054

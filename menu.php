<?php
include('config.php');
if($_SESSION["logeado"] != "SI"){
	header ("Location: index.php");
	exit;
}

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
<h1>Sistema de Trazabilidad</h1>
<p>
<a class="btn btn-lg btn-primary" href="nuevo_lote.php" role="button">Nuevo Lote &raquo;</a>
</p>
<p>
<a class="btn btn-lg btn-primary" href="buscar_lote.php" role="button">Seguimiento Lote &raquo;</a>
</p>
</div>

</div> <!-- /container -->


?>

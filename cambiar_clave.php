<?php
include('config.php');
    // Primero comprobamos que ningún campo esté vacío y que todos los campos existan.
    if(isset($_POST['claveA']) && !empty($_POST['claveA']) &&
    isset($_POST['claveN']) && !empty($_POST['claveN']) && 
	($_POST['claveA'] == $_POST['claveA'])){
        // Si entramos es que todo se ha realizado correctamente
		$claveA = md5($_POST['claveA']);
		
        $link = mysql_connect ($dbhost, $dbusername, $dbuserpass);
        mysql_select_db($dbname,$link);
		
        // Con esta sentencia SQL insertaremos los datos en la base de datos
        mysql_query("UPDATE usuarios SET password =".$claveA." WHERE username =".$_SESSION['username'],$link);

        // Ahora comprobaremos que todo ha ido correctamente
        $my_error = mysql_error($link);

        if(!empty($my_error)) {

            header ("Location: form_cambiar_clave.php?errordat");

        } else {

             header ("Location: inicio.php");

        }

    } else {

         header ("Location: form_cambiar_clave.php?errordb");

    }

?>
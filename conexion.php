<?php
$bd = mysqli_connect("localhost", "root", "", "agenda");

// Comprobamos si ha habido algún error en la conexión
if (!$bd){
	die ("<b>Imposible conectar con la base de datos</b>");
}
?>
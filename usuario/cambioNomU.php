<?php
session_start();

include "conexion.php";

$actual = $_SESSION["usuario"];
$nuevo = trim($_POST["nuevoNombre"]);

if (!preg_match('/^@[A-Za-z0-9._-]{4,20}$/', $nuevo)) {
    die("Nombre de usuario no válido.");
}

$sql = "UPDATE usuarios SET nombreUsuario = '$nuevo' WHERE nombreUsuario = '$actual'";

if (mysqli_query($bd, $sql)) {
    $_SESSION["usuario"] = $nuevo;
    header("Location: bienvenida.php");
    exit;
} else {
    echo "Error al cambiar el nombre: " . mysqli_error($bd);
}
?>

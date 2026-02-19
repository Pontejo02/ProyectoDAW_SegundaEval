<?php
session_start();

include "../conexion.php";

$usuario = $_SESSION["usuario"];

$sql = "DELETE FROM usuarios WHERE nombreUsuario = '$usuario'";

if (mysqli_query($bd, $sql)) {
    session_destroy();
    header("Location: ../index.php");
    exit;
} else {
    echo "Error al eliminar la cuenta: " . mysqli_error($bd);
}
?>
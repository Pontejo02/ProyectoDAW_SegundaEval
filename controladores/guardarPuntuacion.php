<?php
session_start();
require_once "../modelos/conexion.php";


$bd = ModeloConexion::conectar();

if (!isset($_SESSION["idUsuario"])) {
    exit("Usuario no autenticado");
}

$idUsuario = $_SESSION["idUsuario"];
$puntuacion = $_POST["puntuacion"] ?? 0;
$tiempo = $_POST["tiempo"] ?? "00:00:00";

$sql = "INSERT INTO puntuaciones (fecha, puntuacion, tiempo, idUsuario)
        VALUES (NOW(), ?, ?, ?)";

$stmt = $bd->prepare($sql);

if (!$stmt) {
    die("Error en la consulta: " . $bd->error);
}

$stmt->bind_param("isi", $puntuacion, $tiempo, $idUsuario);
$stmt->execute();

echo "ok";
?>

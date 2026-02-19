<?php
session_start();
echo "Estoy aquí";
require_once "conexion.php";

if (!isset($_SESSION["idUsuario"])) {
    exit("Usuario no autenticado");
}

$idUsuario = $_SESSION["idUsuario"];
$puntuacion = $_POST["puntuacion"] ?? 0;
$tiempo = $_POST["tiempo"] ?? "00:00:00"; // tiempo de juego

$sql = "INSERT INTO puntuaciones (fecha, puntuacion, tiempo, idUsuario)
        VALUES (NOW(), ?, ?, ?)";

$stmt = $bd->prepare($sql);
$stmt->bind_param("isi", $puntuacion, $tiempo, $idUsuario);
$stmt->execute();

echo "ok";
?>

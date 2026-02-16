<?php
session_start();
echo "Estoy aquí";
require_once "conexion.php";

if (!isset($_SESSION["idUsuario"])) {
    exit("Usuario no autenticado");
}

$idUsuario = $_SESSION["idUsuario"];
$puntuacion = $_POST["puntuacion"] ?? 0;
$idNivel = $_POST["idNivel"] ?? 1; // o el nivel actual

$sql = "INSERT INTO puntuaciones (fecha, puntuacion, idNiveles, idUsuario)
        VALUES (NOW(), ?, ?, ?)";

$stmt = $bd->prepare($sql);
$stmt->bind_param("iii", $puntuacion, $idNivel, $idUsuario);
$stmt->execute();

echo "ok";
?>

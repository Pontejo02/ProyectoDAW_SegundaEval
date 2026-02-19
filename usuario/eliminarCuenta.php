<?php
session_start(); // iniciar sesión

// Conexión a la base de datos
require_once "../modelos/conexion.php"; // ajusta la ruta

// Si no hay sesión iniciada, redirigir
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../vistas/formUsuario.php");
    exit();
}

$idUsuario = $_SESSION['idUsuario'];

// Conectamos usando tu clase ModeloConexion
$bd = ModeloConexion::conectar(); // ahora $bd es un objeto mysqli

// Preparar y ejecutar eliminación
$sql = "DELETE FROM usuarios WHERE idUsuario = ?";
$stmt = $bd->prepare($sql);

if (!$stmt) {
    die("Error en la consulta: " . $bd->error);
}

$stmt->bind_param("i", $idUsuario);

if ($stmt->execute()) {
    // Destruir sesión y redirigir al inicio
    session_unset();
    session_destroy();
    header("Location: ../index.php?eliminado=ok");
    exit();
} else {
    die("Error al eliminar la cuenta: " . $stmt->error);
}
?>

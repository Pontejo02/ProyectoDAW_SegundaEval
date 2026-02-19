<?php
session_start(); // iniciar sesión

// Conexión a la base de datos
require_once "../modelos/conexion.php"; // ruta correcta

// Si no hay sesión iniciada, redirigimos al formulario
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../vistas/formUsuario.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nuevoNombre = trim($_POST["nuevoNombre"]);
    $idUsuario = $_SESSION["idUsuario"];

    // Validación: debe empezar con @ y tener entre 4 y 20 caracteres
    if (!preg_match('/^@[A-Za-z0-9._-]{4,20}$/', $nuevoNombre)) {
        die("Nombre inválido. Debe empezar con @ y tener entre 4 y 20 caracteres.");
    }

    // Crear conexión usando tu clase ModeloConexion
    $bd = ModeloConexion::conectar(); // 🔹 importante, ahora $bd es un objeto mysqli

    // Preparar y ejecutar actualización
    $stmt = $bd->prepare("UPDATE usuarios SET nombreUsuario = ? WHERE idUsuario = ?");

    if (!$stmt) {
        die("Error en la consulta: " . $bd->error);
    }

    $stmt->bind_param("si", $nuevoNombre, $idUsuario);

    if ($stmt->execute()) {
        $_SESSION["usuario"] = $nuevoNombre; // actualizar sesión
        header("Location: ../vistas/bienvenida.php?cambio=ok"); // redirige a bienvenida
        exit();
    } else {
        die("Error al actualizar el nombre: " . $stmt->error);
    }

} else {
    // Si se accede sin POST, redirigimos a bienvenida
    header("Location: ../vistas/bienvenida.php");
    exit();
}

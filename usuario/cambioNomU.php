<?php
session_start();
require "../conexion.php";

if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../formUsuario.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nuevoNombre = trim($_POST["nuevoNombre"]);
    $idUsuario = $_SESSION["idUsuario"];

    // Validación igual que en el registro
    if (!preg_match('/^@[A-Za-z0-9._-]{4,20}$/', $nuevoNombre)) {
        die("Nombre inválido. Debe empezar con @ y tener entre 4 y 20 caracteres.");
    }

    $sql = "UPDATE usuarios SET nombreUsuario = ? WHERE idUsuario = ?";
    $stmt = $bd->prepare($sql);
    $stmt->bind_param("si", $nuevoNombre, $idUsuario);

    if ($stmt->execute()) {
        $_SESSION["usuario"] = $nuevoNombre; // actualizar sesión
        header("Location: ../bienvenida.php?cambio=ok");
        exit();
    } else {
        echo "Error al actualizar el nombre.";
    }
}
?>


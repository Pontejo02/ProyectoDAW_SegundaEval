<?php
$primera = true;
$nombre = "";
$password = "";
$errores = [];

if (isset($_POST["enviar"])) {
    $primera = false;

    if (isset($_POST["nomUsuario"])) {
        $nomUsuario = trim($_POST["nomUsuario"]);
        if (empty($nombre)) {
            $errores[] = "Debes introducir tu nombre de usuario.";
        }
    //Validar caracteres permitidos después del @. son alfanumericos . - _
    else if (!preg_match('/^@[A-Za-z0-9._-]+$/', $nomUsuario)) {
        $errores[] = "El nombre de usuario solo puede contener letras, números, puntos, guiones y guiones bajos.";
    }
}

    if (isset($_POST["password"])) {
        $password = trim($_POST["password"]);
        if (empty($password)) {
            $errores[] = "Debes introducir tu contraseña.";
        }
    }


    // Si no hay errores → comprobar en la base de datos
    if (empty($errores)) {
        // Aquí iría la conexión a la BD
    }
}
?>

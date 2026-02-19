<?php
session_start();
require_once("../modelos/Usuario.php");

if (isset($_POST["enviar"])) {

    $nomUsuario = $_POST["nomUsuario"];
    $password = $_POST["password"];

    $usuarioDB = Usuario::login($nomUsuario);

    if ($usuarioDB && password_verify($password, $usuarioDB["contraseniaUsuario"])) {

        $_SESSION["usuario"] = $usuarioDB["nombreUsuario"];
        $_SESSION["idUsuario"] = $usuarioDB["idUsuario"];

        header("Location: ../vistas/bienvenida.php");
        exit;

    } else {
        header("Location: ../../index.php?error=1");
    }
}

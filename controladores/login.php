<?php
// controladores/ControladorLogin.php
require_once "./modelos/conexion.php";
require_once "./modelos/modeloUsuarios.php";

class ControladorLogin {
    public $errorNombre = "";
    public $errorContrasenia = "";
    public $nombreUsuario = "";
    public $contrasenia = "";
    public $primera = true;

    public function procesarLogin() {
        session_start();

        if (isset($_POST["enviar"])) {
            $this->primera = false;
            $this->nombreUsuario = trim($_POST["nomUsuario"] ?? "");
            $this->contrasenia = trim($_POST["password"] ?? "");

            // Validaciones
            if (empty($this->nombreUsuario)) {
                $this->errorNombre = "Debes introducir tu nombre de usuario.";
            } elseif ($this->nombreUsuario[0] !== '@') {
                $this->errorNombre = "El nombre de usuario debe empezar con @.";
            } elseif (!preg_match('/^@[A-Za-z0-9._-]+$/', $this->nombreUsuario)) {
                $this->errorNombre = "Solo se permiten letras, números, puntos, guiones y guiones bajos.";
            }

            if (empty($this->contrasenia)) {
                $this->errorContrasenia = "Debes introducir tu contraseña.";
            }

            // Intentar login
            if ($this->errorNombre === "" && $this->errorContrasenia === "") {
                $bd = ModeloConexion::conectar();
                $usuarioModelo = new ModeloUsuario($bd);
                $usuarioBD = $usuarioModelo->iniciarSesion($this->nombreUsuario, $this->contrasenia);

                if ($usuarioBD) {
                    $_SESSION["usuario"] = $usuarioBD["nombreUsuario"];
                    $_SESSION["idUsuario"] = $usuarioBD["idUsuario"];
                    header("Location: ./vistas/bienvenida.php");
                    exit;
                } else {
                    $this->errorContrasenia = "Usuario o contraseña incorrectos.";
                }
            }
        }
    }
}
?>

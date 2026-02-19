<?php
// controlador/ControladorRegistro.php
require_once "../modelos/conexion.php";
require_once "../modelos/modeloUsuarios.php";

class ControladorRegistro {
    public $primera = true;

    // Campos
    public $nombreReal = "";
    public $apellido1 = "";
    public $apellido2 = "";
    public $nombreUsuario = "";
    public $mailUsuario = "";
    public $contraseniaUsuario = "";
    public $edad = "";
    public $genero = "";
    public $continente = "";
    public $referencia = "";
    public $consentimiento = "";

    // Errores
    public $errNombreReal = "";
    public $errApellido1 = "";
    public $errApellido2 = "";
    public $errNombreUsuario = "";
    public $errMail = "";
    public $errPass = "";
    public $errEdad = "";
    public $errGenero = "";
    public $errContinente = "";
    public $errConsent = "";

    public function procesarRegistro() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->primera = false;

            // Validaciones
            $this->nombreReal = trim($_POST["nombreReal"] ?? "");
            if (empty($this->nombreReal)) $this->errNombreReal = "Debes introducir tu nombre real.";
            elseif (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúñÑ ]+$/', $this->nombreReal)) $this->errNombreReal = "El nombre solo puede contener letras y espacios.";

            $this->apellido1 = trim($_POST["apellido1"] ?? "");
            if (!empty($this->apellido1) && !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúñÑ ]+$/', $this->apellido1)) $this->errApellido1 = "El apellido debe contener letras.";

            $this->apellido2 = trim($_POST["apellido2"] ?? "");
            if (!empty($this->apellido2) && !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúñÑ ]+$/', $this->apellido2)) $this->errApellido2 = "El apellido debe contener letras.";

            $this->nombreUsuario = trim($_POST["nombreUsuario"] ?? "");
            if (empty($this->nombreUsuario)) $this->errNombreUsuario = "Debes introducir un nombre de usuario.";
            elseif (!preg_match('/^@[A-Za-z0-9._-]{4,20}$/', $this->nombreUsuario)) $this->errNombreUsuario = "Debe empezar con @ y tener entre 4 y 20 caracteres. Solo permite letras, números, puntos, guiones y guiones bajos.";

            $this->mailUsuario = trim($_POST["mailUsuario"] ?? "");
            if (empty($this->mailUsuario)) $this->errMail = "Debes introducir un correo electrónico.";
            elseif (!filter_var($this->mailUsuario, FILTER_VALIDATE_EMAIL)) $this->errMail = "El formato del correo no es válido.";

            $this->contraseniaUsuario = trim($_POST["contraseniaUsuario"] ?? "");
            if (empty($this->contraseniaUsuario)) $this->errPass = "Debes introducir una contraseña.";
            elseif (strlen($this->contraseniaUsuario) < 6) $this->errPass = "La contraseña debe tener al menos 6 caracteres.";

            $this->edad = trim($_POST["edad"] ?? "");
            if (!empty($this->edad) && ($this->edad < 0 || $this->edad > 99)) $this->errEdad = "La edad debe estar entre 0 y 99.";

            $this->genero = $_POST["genero"] ?? "";

            $this->continente = $_POST["continente"] ?? "";
            if ($this->continente === "") $this->errContinente = "Debes seleccionar un continente.";

            $this->referencia = trim($_POST["referencia"] ?? "");

            if (!isset($_POST["consentimiento"])) $this->errConsent = "Debes aceptar el consentimiento.";

            // Si no hay errores, insertamos
            // Si no hay errores, insertamos
            if (
                $this->errNombreReal === "" && $this->errApellido1 === "" && $this->errApellido2 === "" &&
                $this->errNombreUsuario === "" && $this->errMail === "" && $this->errPass === "" &&
                $this->errEdad === "" && $this->errContinente === "" && $this->errConsent === ""
            ) {
                $bd = ModeloConexion::conectar();
                $usuarioModelo = new ModeloUsuario($bd);

                $datosUsuario = [
                    'nombreUsuario' => $this->nombreUsuario,
                    'nombreReal' => $this->nombreReal,
                    'apellido1' => $this->apellido1,
                    'apellido2' => $this->apellido2,
                    'mailUsuario' => $this->mailUsuario,
                    'continente' => $this->continente,
                    'contraseniaUsuario' => $this->contraseniaUsuario,
                    'edad' => $this->edad,
                    'genero' => $this->genero,
                    'referencia' => $this->referencia
                ];

                $idNuevoUsuario = $usuarioModelo->crearUsuario($datosUsuario);

                if ($idNuevoUsuario) {
                    // Iniciar sesión y redirigir
                    session_start();
                    $_SESSION["usuario"] = $this->nombreUsuario;
                    $_SESSION["idUsuario"] = $idNuevoUsuario;
                    header("Location: ../vistas/bienvenida.php");
                    exit;
                } else {
                    // Aquí puedes poner un error si falla la inserción
                    echo "<p class='error'>No se pudo crear el usuario. Inténtalo más tarde.</p>";
                }
            }

        }
    }
}
?>

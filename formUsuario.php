<!--Validaciones del formulario + conexion + insert ----------------------------------------------------->
<?php
$primera = true;

/* variables de campos */
$nombreReal = "";
$apellido1 = "";
$apellido2 = "";
$patronApellido= "/^[A-Za-zÁÉÍÓÚáéíóúñÑ ]+$/";
$nombreUsuario = "";
$mailUsuario = "";
$contraseniaUsuario = "";
$edad = "";
$genero = "";
$continente = "";
$referencia = "";
$consentimiento = "";

/* variables de errores */
$errNombreReal = "";
$errApellido1 = "";
$errApellido2 = "";
$errNombreUsuario = "";
$errMail = "";
$errPass = "";
$errEdad = "";
$errGenero = "";
$errContinente = "";
$errConsent = "";

/* Empiezan todas las validaciones=================================================================================*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $primera = false;

    /* nombre real */
    if (isset($_POST["nombreReal"])) {
        $nombreReal = trim($_POST["nombreReal"]);
        if (empty($nombreReal)) {
            $nombreReal ="";
            $errNombreReal = "Debes introducir tu nombre real.";
        } elseif (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúñÑ ]+$/', $nombreReal)) {
            $nombreReal ="";
            $errNombreReal = "El nombre solo puede contener letras y espacios.";
        }
    }

    /* apellido 1 */
    if (isset($_POST["apellido1"])) {
        $apellido1 = trim($_POST["apellido1"]);
        if (!empty($apellido1) && !preg_match($patronApellido, $apellido1)) {
            $apellido1="";
            $errApellido1 = "El apellido debe contener letras.";
        }
    }

    /* apellido 2 */
    if (isset($_POST["apellido2"])) {
        $apellido2= trim($_POST["apellido2"]);
        if (!empty($apellido2) && !preg_match($patronApellido, $apellido2)) {
            $apellido2="";
            $errApellido2= "El apellido debe contener letras.";
        }
    }

    /* nombre usuario */
    if (isset($_POST["nombreUsuario"])){
        $nombreUsuario = trim($_POST["nombreUsuario"]);
        if (empty($nombreUsuario)) {
            $nombreUsuario="";
            $errNombreUsuario= "Debes introducir un nombre de usuario.";
        } elseif (!preg_match('/^@[A-Za-z0-9._-]{4,20}$/',$nombreUsuario)) {
            $nombreUsuario="";
            $errNombreUsuario= "Debe empezar con @ y tener entre 4 y 20 caracteres. Solo permite letras, números, puntos, guiones y guiones bajos.";
        }
    }

    /* mail */
    if (isset($_POST["mailUsuario"])) {
        $mailUsuario = trim($_POST["mailUsuario"]);
        if (empty($mailUsuario)) {
            $mailUsuario="";
            $errMail="Debes introducir un correo electrónico.";
        } elseif(!filter_var($mailUsuario, FILTER_VALIDATE_EMAIL)) {
            $mailUsuario="";
            $errMail="El formato del correo no es válido.";
        }
    }

    /* contraseña */
    if (isset($_POST["contraseniaUsuario"])) {
        $contraseniaUsuario = trim($_POST["contraseniaUsuario"]);
        if (empty($contraseniaUsuario)) {
            $contraseniaUsuario = "";
            $errPass= "Debes introducir una contraseña.";
        } elseif (strlen($contraseniaUsuario) < 6) {
            $contraseniaUsuario = "";
            $errPass= "La contraseña debe tener al menos 6 caracteres.";
        }
    }

    /* edad */
    if (isset($_POST["edad"])) {
        $edad = trim($_POST["edad"]);
        if (!empty($edad) && ($edad < 0 || $edad > 99)) {
            $errEdad = "La edad debe estar entre 0 y 99.";
        }
    }

    /* genero */
    if (isset($_POST["genero"])) {
        $genero = $_POST["genero"];
    }

    /* continente*/
    if (isset($_POST["continente"])) {
        $continente = $_POST["continente"];
        if ($continente === "") {
            $errContinente = "Debes seleccionar un continente.";
        }
    }

    /* referencia */
    if (isset($_POST["referencia"])) {
        $referencia = trim($_POST["referencia"]);
    }

    /* consentimiento */
    if (!isset($_POST["consentimiento"])) {
        $errConsent = "Debes aceptar el consentimiento.";
    }

/* SI NO HAY ERRORES, HACEMOS CONEXION E INSERTS =====================================================================*/
    if (
        $errNombreReal === "" && $errApellido1 === "" && $errApellido2 === "" && $errNombreUsuario === "" &&
        $errMail === "" && $errPass === "" && $errEdad === "" && $errContinente === "" && $errConsent === ""
    ) {
        include_once "conexion.php";

        // Encriptar contraseña (tu tabla debería ser VARCHAR(255))
        $contraseniaHash = password_hash($contraseniaUsuario, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios 
        (nombreUsuario, nombreReal, apellido1, apellido2, mailUsuario, continente, contraseniaUsuario, edad, genero, referencia)
        VALUES ( '$nombreUsuario', '$nombreReal', '$apellido1', '$apellido2', '$mailUsuario', '$continente', '$contraseniaHash', '$edad', '$genero', '$referencia')";

        // Ejecutar consulta
        if ($res = mysqli_query($bd, $sql)) {
            if (mysqli_affected_rows($bd) > 0) {
                //empezamos una sesion antes de ir a bienvenida
                session_start();
                $idRecienCreado = mysqli_insert_id($bd);
                $_SESSION["usuario"] = $nombreUsuario;
                $_SESSION["idUsuario"] = $idRecienCreado;
                // Redirigir a la página de bienvenida y envia el usuario por url
                header("Location: bienvenida.php?usuario=" . urlencode($nombreUsuario));
                exit;
            } else {
                echo "<b>No se ha insertado el usuario</b>";
            }
        } else {
            echo "<b>Error:</b> " . $sql . "<br>" . mysqli_error($bd);
        }
        // Cerrar conexión
        mysqli_close($bd);
    }
}
?>

<!--Empieza el html------------------------------------------------------>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formulario Usuarios</title>
<link href="./css/expUsuario.css" rel="stylesheet">
<!-- Fuente de google fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Doto:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
<p id="logo">WORLD BLOCKS</p>


<form action="#" method="POST">
    <div id="btnAtras"><b>←</b></div>
    <h2 class="centrar">Registro de Usuario</h2>
    <div class="contenedor-flex">

    <div class="columna">
        <div class="campo">
            <label>Nombre real</label>
            <input type="text" name="nombreReal" value="<?php echo $nombreReal; ?>">
            <?php if(!$primera && $errNombreReal !== "") echo "<p class='error'>$errNombreReal</p>"; ?>
        </div>

        <div class="campo">
            <label>Primer apellido</label>
            <input type="text" name="apellido1" value="<?php echo $apellido1; ?>">
            <?php if (!$primera && $errApellido1 !== "") echo "<p class='error'>$errApellido1</p>"; ?>
        </div>

        <div class="campo">
            <label>Segundo apellido</label>
            <input type="text" name="apellido2" value="<?php echo $apellido2; ?>">
            <?php if(!$primera && $errApellido2 !== "") echo "<p class='error'>$errApellido2</p>"; ?>
        </div>

        <div class="campo">
            <label>Nombre de usuario *</label>
            <input type="text" name="nombreUsuario" required value="<?php echo $nombreUsuario; ?>" placeholder="@...">
            <?php if(!$primera && $errNombreUsuario !== "") echo "<p class='error'>$errNombreUsuario</p>"; ?>
        </div>

        <div class="campo">
            <label>Correo electrónico *</label>
            <input type="email" name="mailUsuario" required value="<?php echo $mailUsuario; ?>">
            <?php if(!$primera && $errMail !== "") echo "<p class='error'>$errMail</p>"; ?>
        </div>

        <div class="campo">
            <label>Contraseña *</label>
            <input type="password" name="contraseniaUsuario" required value="">
            <?php if(!$primera && $errPass !== "") echo "<p class='error'>$errPass</p>"; ?>
        </div>
    </div>

    <div class="columna">
        <div class="campo">
            <label>Edad</label>
            <input type="number" name="edad" min="0" max="99" value="<?php echo $edad; ?>">
            <?php if(!$primera && $errEdad !== "") echo "<p class='error'>$errEdad</p>"; ?>
        </div>

    <div class="campo">
        <label>Género</label>
        <div class="genero-opciones">
            <label><input type="radio" name="genero" value="M" <?php if($genero=="M") echo "checked"; ?>> Masculino</label>
            <label><input type="radio" name="genero" value="F" <?php if($genero=="F") echo "checked"; ?>> Femenino</label>
            <label><input type="radio" name="genero" value="NB" <?php if($genero=="ND") echo "checked"; ?>> Prefiero no decirlo</label>
        </div>
    </div>


        <div class="campo">
            <label>Continente</label>
                <select name="continente">
                    <option value="" disabled selected hidden>Seleccione</option>
                    <option value="África"   <?php if($continente=="África") echo "selected"; ?>>África</option>
                    <option value="América"  <?php if($continente=="América") echo "selected"; ?>>América</option>
                    <option value="Asia"     <?php if($continente=="Asia") echo "selected"; ?>>Asia</option>
                    <option value="Europa"   <?php if($continente=="Europa") echo "selected"; ?>>Europa</option>
                    <option value="Oceanía"  <?php if($continente=="Oceanía") echo "selected"; ?>>Oceanía</option>
                </select>
                <?php if(!$primera && $errContinente !== "") echo "<p class='error'>$errContinente</p>"; ?>
        </div>

        <div class="campo">
            <label>¿Cómo nos conociste?</label>
            <textarea name="referencia"><?php echo $referencia; ?></textarea>
        </div>
        <div class="campo">
            <label class="checkbox-texto">
                <input type="checkbox" name="consentimiento" required>
                Confirmo que proporciono estos datos de manera voluntaria para la mejora de la plataforma.
        </label>
        </div>
        <div class="btncontainer">
        <button class="boton" type="submit">Registrarme</button>
        </div>
    </div>
  
</div>


    
</form>
    <footer> 
        <div class="ticker"> La información que nos facilitas es tratada con total confidencialidad. No compartimos tus datos con terceros ni los utilizamos para enviar publicidad no deseada. Solo empleamos estos datos para gestionar tu registro y mejorar tu experiencia en nuestra plataforma. </div> 
    </footer>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const btnAtras = document.getElementById("btnAtras");

    btnAtras.addEventListener("click", () => {
        window.location.href = "index.php";
    });
});
</script>

</body>
</html>

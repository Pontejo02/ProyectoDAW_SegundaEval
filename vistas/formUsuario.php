<?php
require_once "../controladores/controladorFormulario.php";

$registro = new ControladorRegistro();
$registro->procesarRegistro();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registro de Usuario</title>
<link rel="icon" href="../media/W.png" type="image/x-icon">
<link href="../css/expUsuario.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Doto:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>

<p id="logo">WORLD BLOCKS</p>

<form id="formRegistro" action="#" method="POST">
    <div id="btnAtras"><b>←</b></div>
    <h2 class="centrar">Registro de Usuario</h2>
    <div class="contenedor-flex">
        <div class="columna">
            <div class="campo">
                <label>Nombre real</label>
                <input type="text" name="nombreReal" value="<?php echo $registro->nombreReal; ?>">
                <?php if(!$registro->primera && $registro->errNombreReal !== "") echo "<p class='error'>$registro->errNombreReal</p>"; ?>
            </div>
            <div class="campo">
                <label>Primer apellido</label>
                <input type="text" name="apellido1" value="<?php echo $registro->apellido1; ?>">
                <?php if(!$registro->primera && $registro->errApellido1 !== "") echo "<p class='error'>$registro->errApellido1</p>"; ?>
            </div>
            <div class="campo">
                <label>Segundo apellido</label>
                <input type="text" name="apellido2" value="<?php echo $registro->apellido2; ?>">
                <?php if(!$registro->primera && $registro->errApellido2 !== "") echo "<p class='error'>$registro->errApellido2</p>"; ?>
            </div>
            <div class="campo">
                <label>Nombre de usuario *</label>
                <input type="text" name="nombreUsuario" required value="<?php echo $registro->nombreUsuario; ?>" placeholder="@...">
                <?php if(!$registro->primera && $registro->errNombreUsuario !== "") echo "<p class='error'>$registro->errNombreUsuario</p>"; ?>
            </div>
            <div class="campo">
                <label>Correo electrónico *</label>
                <input type="email" name="mailUsuario" required value="<?php echo $registro->mailUsuario; ?>">
                <?php if(!$registro->primera && $registro->errMail !== "") echo "<p class='error'>$registro->errMail</p>"; ?>
            </div>
            <div class="campo">
                <label>Contraseña *</label>
                <input type="password" name="contraseniaUsuario" required value="">
                <?php if(!$registro->primera && $registro->errPass !== "") echo "<p class='error'>$registro->errPass</p>"; ?>
            </div>
        </div>

        <div class="columna">
            <div class="campo">
                <label>Edad</label>
                <input type="number" id="edad" name="edad" min="0" max="99" value="<?php echo $registro->edad; ?>">
                <?php if(!$registro->primera && $registro->errEdad !== "") echo "<p class='error'>$registro->errEdad</p>"; ?>
            </div>
            <div class="campo">
                <label>Género</label>
                <div class="genero-opciones">
                    <label><input type="radio" name="genero" value="M" id="genM" <?php if($registro->genero=="M") echo "checked"; ?>> Masculino</label>
                    <label><input type="radio" name="genero" value="F" id="genF" <?php if($registro->genero=="F") echo "checked"; ?>> Femenino</label>
                    <label><input type="radio" name="genero" value="NB" id="genNB" <?php if($registro->genero=="NB") echo "checked"; ?>> Prefiero no decirlo</label>
                </div>
            </div>
            <div class="campo">
                <label>Continente</label>
                <select name="continente">
                    <option value="" disabled hidden>Seleccione</option>
                    <option value="África" <?php if($registro->continente=="África") echo "selected"; ?>>África</option>
                    <option value="América" <?php if($registro->continente=="América") echo "selected"; ?>>América</option>
                    <option value="Asia" <?php if($registro->continente=="Asia") echo "selected"; ?>>Asia</option>
                    <option value="Europa" <?php if($registro->continente=="Europa") echo "selected"; ?>>Europa</option>
                    <option value="Oceanía" <?php if($registro->continente=="Oceanía") echo "selected"; ?>>Oceanía</option>
                </select>
                <?php if(!$registro->primera && $registro->errContinente !== "") echo "<p class='error'>$registro->errContinente</p>"; ?>
            </div>
            <div class="campo">
                <label>¿Cómo nos conociste?</label>
                <textarea name="referencia"><?php echo $registro->referencia; ?></textarea>
            </div>
            <div class="campo">
                <label class="checkbox-texto">
                    <input type="checkbox" name="consentimiento" required>
                    Confirmo que proporciono estos datos de manera voluntaria para la mejora de la plataforma.
                </label>
                <?php if(!$registro->primera && $registro->errConsent !== "") echo "<p class='error'>$registro->errConsent</p>"; ?>
            </div>
            <p id="errorJS" class="error"></p>
            <div class="btncontainer">
                <button class="boton" type="submit">Registrarme</button>
            </div>
        </div>
    </div>
</form>

<footer> 
    <div class="ticker"> La información que nos facilitas es tratada con total confidencialidad. </div> 
</footer>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const btnAtras = document.getElementById("btnAtras");
    btnAtras.addEventListener("click", () => {
        window.location.href = "../index.php";
    });
    const form = document.getElementById("formRegistro");
    const edad = document.getElementById("edad");
    const genero = document.getElementsByName("genero");
    const error = document.getElementById("errorJS");

    form.addEventListener("submit", function(e) {

        error.textContent = "";

        if (edad.value === "" || edad.value < 1 || edad.value > 99) {
            e.preventDefault();
            error.textContent = "La edad debe estar entre 1 y 99";
            edad.style.border = "2px solid red";
            return;
        }

        let generoSeleccionado = false;
        genero.forEach(g => {
            if (g.checked) generoSeleccionado = true;
        });

        if (!generoSeleccionado) {
            e.preventDefault();
            error.textContent = "Debes seleccionar un género";
            return;
        }

    });

    // quitar rojo al escribir
    edad.addEventListener("input", () => {
        edad.style.border = "";
    });
});
</script>

</body>
</html>

<?php
require_once "controladores/login.php";

// Instanciamos el controlador y procesamos login
$login = new ControladorLogin();
$login->procesarLogin();

// Variables para la vista
$primera = $login->primera;
$errorNombre = $login->errorNombre;
$errorContrasenia = $login->errorContrasenia;
$nombreUsuario = $login->nombreUsuario;
$usuario = $_SESSION["usuario"] ?? "Usuario invitado";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>World Blocks</title>
    <link rel="icon" href="./media/W.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="./css/estilos.css">
    <link rel="stylesheet" type="text/css" href="./css/formularios.css">
    <!-- Fuente de google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>

<!-- Ventana oculta de login -->
<div id="introUsuario">
    <img class="salir" id="cerrarUsuario" src="./media/x.png" alt="icono de x para salir">
    <h2>ACCEDE A TU CUENTA</h2>
    <form id="login" method="post" autocomplete="off">
        <div class="campo"> 
            <label for="usuario">Nombre de usuario</label> 
            <input type="text" id="nomUsuario" name="nomUsuario" value="<?php echo $nombreUsuario; ?>" />
            <?php if (!$primera && $errorNombre !== "") echo "<p class='error'>$errorNombre</p>"; ?>
        </div> 

        <div class="campo">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" />
            <?php if (!$primera && $errorContrasenia !== "") echo "<p class='error'>$errorContrasenia</p>"; ?>
        </div>

        <button class="btnVentana" type="submit" name="enviar">Entrar</button>
        <button class="btnVentana" type="button" id="btnRegistro">Registrarme</button>
    </form>
</div>

<?php if (!$primera && ($errorNombre !== "" || $errorContrasenia !== "")) : ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("introUsuario").style.display = "block";
    });
</script>
<?php endif; ?>

<!-- Ventana Cómo jugar -->
<div id="comoJugar">
    <img class="salir" id="cerrar" src="./media/x.png" alt="icono de x para salir">
    <h2>CÓMO JUGAR</h2>
    <p>- Controlas una barra o nave en la parte inferior de la pantalla.</p>
    <p>- Tu objetivo es golpear una pelota para que rebote y rompa todos los ladrillos de la parte superior.</p>
    <p>- Si la pelota cae por debajo de tu barra, pierdes una vida.</p>
    <p>- A veces hay ladrillos que tienen comportamientos espaciales (mirar leyenda bloques).</p>
    <p>- Pasas de nivel cuando eliminas todos los ladrillos.</p>
</div>

<!-- Leyenda bloques -->
<div id="leyendaBloques"> 
    <img class="salir" id="cerrarLeyenda" src="./media/x.png" alt="icono de x para salir">
    <h2>POWER UPS</h2>
    <div> 
        <img src="./media/Europa2.png" alt="Bloque Europa">
        <p>Otorga 1 vida extra.</p>
    </div> 
    <div> 
        <img src="./media/Africa2.png" alt="Bloque África">
        <p>Libera 10 pelotas muy rápidas.</p> 
    </div> 
    <div> 
        <img src="./media/Asia2.png" alt="Bloque Asia"> 
        <p>La pala devora una línea completa de bloques.</p> 
    </div> 
    <div> 
        <img src="./media/America2.png" alt="Bloque América"> 
        <p>Explosión que destruye los bloques adyacentes.</p> 
    </div> 
    <div> 
        <img src="./media/Oceania2.png" alt="Bloque Oceanía"> 
        <p>Pelotas que suben rápido y bajan lentamente.</p> 
    </div> 
</div>

<!-- Contenido principal -->
<p id="logo">WORLD BLOCKS</p>
<div class="menu">
    <div id="usuario">
        <img id="iconoUsuario" src="./media/usuarioMarron.png">
        <p><?php echo $usuario; ?></p>
    </div>
    <a href="vistas/jugar.php" class="boton">Jugar</a>
    <a href="vistas/carrusel.php" class="boton">Niveles</a>
    <button id="instrucciones" class="boton">Instrucciones</button>
    <div id="submenu">
        <button id="btncomoJugar" class="boton">Cómo jugar</button>
        <button id="btnleyendaBloques" class="boton">Leyenda bloques</button>
    </div>
</div>

<footer> 
    <div class="ticker"> WORLD BLOCKS es un juego basado en el Arkanoid tradicional, añadiéndole una temática geográfica. Desarrolladores: Luken Álava y Blanca Beuste </div> 
</footer>

<!-- Scripts -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const btninstrucciones = document.getElementById("instrucciones");
    const btnsubmenu = document.getElementById("submenu");
    const btnUsuario = document.getElementById("usuario");
    const introUsuario = document.getElementById("introUsuario");
    const btnRegistro = document.getElementById("btnRegistro");
    const cerrarUsuario = document.getElementById("cerrarUsuario");
    const btncomoJugar = document.getElementById("btncomoJugar");
    const comoJugar = document.getElementById("comoJugar");
    const cerrar = document.getElementById("cerrar");
    const cerrarLeyenda = document.getElementById("cerrarLeyenda");
    const leyendaBloques = document.getElementById("leyendaBloques");
    const btnleyendaBloques = document.getElementById("btnleyendaBloques");

    btnRegistro.addEventListener("click", () => {
        window.location.href = "./vistas/formUsuario.php";
    });
    cerrarUsuario.addEventListener("click", ()=>{ introUsuario.style.display='none'; });
    cerrar.addEventListener("click", ()=>{ comoJugar.style.display='none'; btnsubmenu.style.display='none'; });
    cerrarLeyenda.addEventListener("click", ()=>{ leyendaBloques.style.display='none'; btnsubmenu.style.display='none'; });
    btninstrucciones.addEventListener("click", ()=>{ btnsubmenu.style.display='block'; });
    btncomoJugar.addEventListener("click", ()=>{ comoJugar.style.display='block'; leyendaBloques.style.display='none'; });
    btnleyendaBloques.addEventListener("click", ()=>{ leyendaBloques.style.display='block'; comoJugar.style.display='none'; });
});

// Botón usuario: abre login o va a bienvenida
<?php if(isset($_SESSION["usuario"])): ?>
document.getElementById("usuario").addEventListener("click", ()=>{
    window.location.href = "./vistas/bienvenida.php";
});
<?php else: ?>
document.getElementById("usuario").addEventListener("click", ()=>{
    document.getElementById("introUsuario").style.display = 'block';
});
<?php endif; ?>
</script>
</body>
</html>

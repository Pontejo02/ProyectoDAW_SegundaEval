<!--Que funcione mi formulario----------------------------->
<?php
session_start();
$usuario = $_SESSION["usuario"] ?? "Usuario invitado";

$primera = true;

$nomUsuario = "";
$password = "";

$errorNom = "";
$errorPassword = "";

if (isset($_POST["enviar"])) {
    $primera = false;

    // VALIDAR NOMBRE
    if (isset($_POST["nomUsuario"])) {
        $nomUsuario = trim($_POST["nomUsuario"]);

        if (empty($nomUsuario)) {
            $errorNom = "Debes introducir tu nombre de usuario.";
        }
        else if ($nomUsuario[0] !== '@') {
            $errorNom = "El nombre de usuario debe empezar con @.";
        }
        else if (!preg_match('/^@[A-Za-z0-9._-]+$/', $nomUsuario)) {
            $errorNom = "Solo se permiten letras, números, puntos, guiones y guiones bajos.";
        }
    }

    // VALIDAR CONTRASEÑA
    if (isset($_POST["password"])) {
        $password = trim($_POST["password"]);

        if (empty($password)) {
            $errorPassword = "Debes introducir tu contraseña.";
        }
    }

    // SI NO HAY ERRORES → LOGIN CORRECTO aqui en realidad iria la conexion de bbdd
    if ($errorNom === "" && $errorPassword === "") {
        echo "<h2>Login correcto</h2>";
        exit;
    }
}
?>
<!--empieza el html-------------------------------------------------------->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <link rel="stylesheet" type="text/css" href="./css/formularios.css">
    <!-- Fuente de google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
<!--ventana oculta de clickar al usuario---------------------------------------->
    <div id="introUsuario">
        <img class="salir" id="cerrarUsuario" src="./media/x.png" alt="icono de x para salir">
        <h2>ACCEDE A TU CUENTA</h2>
        <form id="login" method="post" autocomplete="off">
        <div class="campo"> 
            <label for="usuario">Nombre de usuario</label> 
            <input type="text" id="nomUsuario" name="nomUsuario" value="<?php echo $nomUsuario; ?>" />
            <?php 
            if (!$primera && $errorNom !== "") {
                echo "<p class='error'>$errorNom</p>";
            }
            ?>
        </div> 

        <div class="campo">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" />
            <?php 
            if (!$primera && $errorPassword !== "") {
                echo "<p class='error'>$errorPassword</p>";
            }
            ?>
        </div>

    <button class="btnVentana" type="submit" name="enviar">Entrar</button>
    <button class="btnVentana" type="button">Regístrate</button>
</form>

    </div>

<?php if (!$primera && ($errorNom !== "" || $errorPassword !== "")) : ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("introUsuario").style.display = "block";
    });
</script>
<?php endif; ?>

<!--ventana oculta de clickar Cómo Jugar---------------------------------------->
    <div id="comoJugar">
        <img class="salir" id="cerrar" src="./media/x.png" alt="icono de x para salir">
        <h2>CÓMO JUGAR</h2>
        <p>- Controlas una barra o nave en la parte inferior de la pantalla.</p>
        <p>- Tu objetivo es golpear una pelota para que rebote y rompa todos los ladrillos de la parte superior.</p>
        <p>- Si la pelota cae por debajo de tu barra, pierdes una vida.</p>
        <p>- A veces hay ladrillos que tienen comportamientos espaciales (mirar leyenda bloques).</p>
        <p>- Pasas de nivel cuando eliminas todos los ladrillos.</p>
    </div>
<!--ventana oculta de clickar al usuario---------------------------------------->
    <div  id="leyendaBloques"> 
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
<!--Lo que se ve en la pag. principal--------------------------------------->
    <p id="logo">WORLD BLOCKS</p>

    <div class="menu">
        <div id="usuario">
            <img id="iconoUsuario" src="./media/usuarioMarron.png">
            <p><?php echo $usuario; ?></p>

        </div>
        <a href="jugar.html" class="boton">Jugar</a>
        <a href="carrusel.html" class="boton">Niveles</a>
        <button id="instrucciones" class="boton">Instrucciones</button>
        <div id="submenu">
            <button id="btncomoJugar" class="boton">Cómo jugar</button>
            <button id="btnleyendaBloques" class="boton">Leyenda bloques</button>
        </div>
    </div>
    <footer> 
        <div class="ticker"> WORLD BLOCKS es un juego basado en el Arkanoid tradicional, añadiéndole una temática geografica. Desarrolladores: Luken Álava y Blanca Beuste </div> 
    </footer>
<!--final de lo que se ve---------------------------------------------------->
<!--Funcionamiento de botones------------------------------------------------>        
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const btninstrucciones = document.getElementById("instrucciones");
        const btnsubmenu = document.getElementById("submenu");
        const btnUsuario = document.getElementById("usuario");
        const introUsuario = document.getElementById("introUsuario");
        const cerrarUsuario = document.getElementById("cerrarUsuario");
        const btncomoJugar = document.getElementById("btncomoJugar");
        const comoJugar = document.getElementById("comoJugar");
        const cerrar = document.getElementById("cerrar");
        const cerrarLeyenda = document.getElementById("cerrarLeyenda");
        const leyendaBloques = document.getElementById("leyendaBloques");
        const btnleyendaBloques = document.getElementById("btnleyendaBloques");
        
        btnUsuario.addEventListener("click", ()=>{
            introUsuario.style.display='block';
        });
        cerrarUsuario.addEventListener("click", ()=>{
            introUsuario.style.display='none';
        });
        cerrar.addEventListener("click", ()=>{
            comoJugar.style.display='none';
            btnsubmenu.style.display='none';
        });
        cerrarLeyenda.addEventListener("click", ()=>{
            leyendaBloques.style.display='none';
            btnsubmenu.style.display='none';
        });
        btninstrucciones.addEventListener("click", ()=>{
            btnsubmenu.style.display='block';
        });
        btncomoJugar.addEventListener("click", ()=>{
            comoJugar.style.display='block';
            leyendaBloques.style.display='none';
        });
        btnleyendaBloques.addEventListener("click", ()=>{
            leyendaBloques.style.display='block';
            comoJugar.style.display='none';
        });
    });
    </script>
    <script type="module" src="./main.js"></script>
</body>
</html>

<?php session_start();
$usuario = $_SESSION["usuario"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>huuula</title>
<link href="./css/expUsuario.css" rel="stylesheet">
<link href="./css/consultas.css" rel="stylesheet">
</head>

<body>
<header>
    <p id="logo">WORLD BLOCKS</p>
    <div class="acciones">
        <button class="boton" id="btnInicio"><b>←</b>Inicio</button>
        <button class="boton" id="btnLogout">Cerrar sesión</button>
    </div>
</header>


<div id="rectBienv">
    <h2 class="centrar">¡Bienvenido <?php echo $usuario;?>!</h2>
</div>
<h2 class="centrar">Ajustes cuenta</h2>
<div class="contenedor-botones">
    <!-- Caja oculta para cambiar nombre -->
    <div id="cambiarNombreBox">
    <button class="boton" onclick="mostrarCambioNombre()">Cambiar nombre de usuario</button>    
    <form action="cambiarNombre.php" method="POST">
        <input type="text" name="nuevoNombre" placeholder="Nuevo nombre de usuario" required>
        <button class="boton" type="submit">Guardar</button>
    </form>
    </div>
    <button class="boton" onclick="confirmarEliminacion()">Eliminar esta cuenta</button>
<script>
    function confirmarEliminacion() {
        if (confirm("¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.")) {
            window.location.href = "eliminarCuenta.php";
            }
    }
</script>
</div>

    <hr>
    <h2>Consultas</h2>
<?php include "./consultas/top5.php"; ?>
<?php include "./consultas/mediaPuntuaciones.php"; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const logout = document.getElementById("logout");

    if(logout){
        logout.addEventListener("click", () => {
            window.location.href = "./usuario/cerrarSesion.php";
        });
    }
});
</script>


</body>
</html>
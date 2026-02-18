<?php session_start();
$usuario = $_SESSION["usuario"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Usuario</title>
<link rel="icon" href="./media/W.png" type="image/x-icon">
<link href="./css/expUsuario.css" rel="stylesheet">
<link href="./css/consultas.css" rel="stylesheet">
<!-- Fuente de google fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Doto:wght@100..900&display=swap" rel="stylesheet">
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

    const btnInicio = document.getElementById("btnInicio");
    btnInicio.addEventListener("click", () => {
        window.location.href = "index.php";
    });

    const btnLogout = document.getElementById("btnLogout");
    btnLogout.addEventListener("click", () => {
        window.location.href = "./usuario/cerrarSesion.php";
    });

});
</script>



</body>
</html>

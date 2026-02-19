<?php session_start();
$usuario = $_SESSION["usuario"];
$idusuario = $_SESSION["idUsuario"];

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

<div class="fila">
    <div id="rectBienv">
        <h2 id="bienvenidaU" class="centrar">¡Bienvenido <?php echo $usuario;?>!</h2>
    </div>
    <div class="columnaAjustes">
    <h2 class="centrar">Ajustes cuenta</h2>

    <button class="boton" onclick="mostrarCambioNombre()">Cambiar nombre de usuario</button>

    <form id="cambiarNombre" action="usuario/cambioNomU.php" method="POST">
        <img id="cerrarInv" src="./media/xInversa.png" alt="icono salir">
        <input type="text" name="nuevoNombre" placeholder="@nuevoNombre" required>
        <button class="boton" type="submit">Guardar</button>
    </form>

    <button class="boton" id="btnEliminar" onclick="confirmarEliminacion()">Eliminar esta cuenta</button>


    <script>
        function confirmarEliminacion() {
            if (confirm("¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.")) {
                window.location.href = "./usuario/eliminarCuenta.php";
            }
        }
    </script>
</div>

</div>
    <hr>
    <h2>Consultas</h2>
<?php include "./consultas/top5.php"; ?>
<?php include "./consultas/mediaPuntuaciones.php"; ?>
<?php include "./consultas/historialPartidas.php"; ?>


<script>
document.addEventListener("DOMContentLoaded", function() {

    const btnInicio = document.getElementById("btnInicio");
    if (btnInicio) {
        btnInicio.addEventListener("click", () => {
            window.location.href = "index.php";
        });
    }

    const btnLogout = document.getElementById("btnLogout");
    if (btnLogout) {
        btnLogout.addEventListener("click", () => {
            window.location.href = "./usuario/cerrarSesion.php";
        });
    }

    const formCambiarNombre = document.getElementById("cambiarNombre");
    const btnCambiarNombre = document.querySelector("button[onclick='mostrarCambioNombre()']");
    const btnCerrar = document.getElementById("cerrarInv");

    window.mostrarCambioNombre = function() {
        const form = formCambiarNombre;
        const btnEliminar = document.getElementById("btnEliminar");

        if (form.style.display === "block") {
            form.style.display = "none";
            btnEliminar.style.display = "block"; // aparece al instante
        } else {
            form.style.display = "block";
            btnEliminar.style.display = "none"; // desaparece al instante
        }
    };

    btnCerrar.addEventListener("click", () => {
        formCambiarNombre.style.display = "none";
        document.getElementById("btnEliminar").style.display = "block";
    });


});
</script>

</body>
</html>

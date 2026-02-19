<?php
require_once "../modelos/conexion.php";

// Abrir conexión
$bd = ModeloConexion::conectar();

$idusuario= $_SESSION["idUsuario"];
echo "<h3 class='centrar'>Historial de partidas</h3>";

$sql2 = "SELECT * FROM puntuaciones WHERE idUsuario='$idusuario'";

$res2 = mysqli_query($bd, $sql2);

echo "<div class='centrado'>";
echo "<table class='tabla-ranking'>";
echo "<tr>
        <th>Fecha</th>
        <th>Puntuación</th>
        <th>Tiempo</th>
      </tr>";

while ($fila = mysqli_fetch_assoc($res2)) {
    echo "<tr>";
    echo "<td>".$fila['fecha']."</td>";
    echo "<td>".$fila['puntuacion']."</td>";
    echo "<td>".$fila['tiempo']."</td>";
    echo "</tr>";
}

echo "</table>";
echo "</div>";

// Cerrar conexión
mysqli_close($bd);
?>

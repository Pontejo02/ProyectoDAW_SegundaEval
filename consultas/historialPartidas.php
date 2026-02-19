<?php
$idusuario= $_SESSION["idUsuario"];
echo "<h3>📊 Historial de Partidas</h3>";

$sql2 = "
SELECT * from puntuaciones where idUsuario=$idusuario";

$res2 = mysqli_query($bd, $sql2);

echo "<table class='tabla-ranking'>";
echo "<tr>
        <th>Fecha</th>
        <th>Puntuacion</th>
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
?>

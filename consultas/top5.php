<?php
include "conexion.php";

echo "<h3> TOP 5 Ranking Global</h3>";

$sql = "
SELECT 
    u.nombreUsuario,
    u.continente,
    p.puntuacion
FROM usuarios u
INNER JOIN puntuaciones p ON u.idUsuario = p.idUsuario
ORDER BY p.puntuacion DESC
LIMIT 5
";


$res = mysqli_query($bd, $sql);

echo "<table class='tabla-ranking'>";
echo "<tr>
        <th>Usuario</th>
        <th>Continente</th>
        <th>Puntuacion</th>
      </tr>";

while ($fila = mysqli_fetch_assoc($res)) {
    echo "<tr>";
    echo "<td>".$fila['nombreUsuario']."</td>";
    echo "<td>".$fila['continente']."</td>";
    echo "<td>".$fila['puntuacion']."</td>";
    echo "</tr>";
}

echo "</table>";
?>

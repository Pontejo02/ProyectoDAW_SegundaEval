<?php
include "conexion.php";

echo "<h3> TOP 5 Ranking Global</h3>";

$sql = "
SELECT 
    u.nombreUsuario,
    u.continente,

    SUM(CASE WHEN n.idNiveles = 1 THEN p.puntuacion ELSE 0 END) AS nivel1,
    SUM(CASE WHEN n.idNiveles = 2 THEN p.puntuacion ELSE 0 END) AS nivel2,
    SUM(CASE WHEN n.idNiveles = 3 THEN p.puntuacion ELSE 0 END) AS nivel3,
    SUM(CASE WHEN n.idNiveles = 4 THEN p.puntuacion ELSE 0 END) AS nivel4,
    SUM(CASE WHEN n.idNiveles = 5 THEN p.puntuacion ELSE 0 END) AS nivel5,

    SUM(p.puntuacion) AS totalPuntos

FROM usuarios u
LEFT JOIN puntuaciones p ON u.idUsuario = p.idPuntuacion
LEFT JOIN niveles n ON p.idNiveles = n.idNiveles

GROUP BY u.idUsuario
ORDER BY totalPuntos DESC
LIMIT 5;
";

$res = mysqli_query($bd, $sql);

echo "<table class='tabla-ranking'>";
echo "<tr>
        <th>Usuario</th>
        <th>Continente</th>
        <th>Nivel 1</th>
        <th>Nivel 2</th>
        <th>Nivel 3</th>
        <th>Nivel 4</th>
        <th>Nivel 5</th>
        <th>Total</th>
      </tr>";

while ($fila = mysqli_fetch_assoc($res)) {
    echo "<tr>";
    echo "<td>".$fila['nombreUsuario']."</td>";
    echo "<td>".$fila['continente']."</td>";
    echo "<td>".$fila['nivel1']."</td>";
    echo "<td>".$fila['nivel2']."</td>";
    echo "<td>".$fila['nivel3']."</td>";
    echo "<td>".$fila['nivel4']."</td>";
    echo "<td>".$fila['nivel5']."</td>";
    echo "<td><b>".$fila['totalPuntos']."</b></td>";
    echo "</tr>";
}

echo "</table>";
?>

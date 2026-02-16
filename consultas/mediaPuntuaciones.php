<?php
echo "<h3>📊 Media de puntuaciones por nivel</h3>";

$sql2 = "
SELECT 
    n.nombreNivel,
    AVG(p.puntuacion) AS media
FROM niveles n
LEFT JOIN puntuaciones p ON n.idNiveles = p.idNiveles
GROUP BY n.idNiveles
ORDER BY n.idNiveles;
";

$res2 = mysqli_query($bd, $sql2);

echo "<table class='tabla-consultas'>";
echo "<tr>
        <th>Nivel</th>
        <th>Media de puntuación</th>
      </tr>";

while ($fila = mysqli_fetch_assoc($res2)) {
    echo "<tr>";
    echo "<td>".$fila['nombreNivel']."</td>";
    echo "<td>".round($fila['media'], 2)."</td>";
    echo "</tr>";
}

echo "</table>";
?>

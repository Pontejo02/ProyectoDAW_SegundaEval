<?php
$idusuario= $_SESSION["idUsuario"];
echo "<h3>📊 Media de puntuaciones por usuario</h3>";

$sql2 = "
SELECT 
    n.nombreUsuario,
    AVG(p.puntuacion) AS mediap,
    AVG(p.tiempo) AS mediat
FROM usuarios n
LEFT JOIN puntuaciones p ON n.idUsuario = p.idUsuario
WHERE n.idUsuario = $idusuario
GROUP BY n.idUsuario
";


$res2 = mysqli_query($bd, $sql2);

echo "<table class='tabla-ranking'>";
echo "<tr>
        <th>Usuario</th>
        <th>Media de puntuación</th>
        <th>Media de tiempo</th>
      </tr>";

while ($fila = mysqli_fetch_assoc($res2)) {
    echo "<tr>";
    echo "<td>".$fila['nombreUsuario']."</td>";
    echo "<td>".$fila['mediap']."</td>";
    echo "<td>".$fila['mediat']."</td>";
    echo "</tr>";
}

echo "</table>";
?>

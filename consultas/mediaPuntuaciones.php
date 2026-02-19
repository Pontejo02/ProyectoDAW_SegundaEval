<?php
require_once "../modelos/conexion.php";

$bd = ModeloConexion::conectar();
$idusuario = $_SESSION["idUsuario"];

echo "<h3 class='centrar'>Media de puntuaciones del usuario</h3>";

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
$fila = mysqli_fetch_assoc($res2);

echo "<p class='centrar'>Tu media de puntuación es:</p>";
echo "<p class='grande centrar'>" . ($fila['mediap'] ?? 0) . "</p>";

echo "<p class='centrar'>Tu media de tiempo es:</p>";
echo "<p class='grande centrar'>" . ($fila['mediat'] ?? "00:00:00") . "</p>";

mysqli_close($bd);
?>

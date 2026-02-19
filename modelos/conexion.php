<?php
// models/ModeloConexion.php
class ModeloConexion {
    public static function conectar() {
        $bd = mysqli_connect("localhost", "root", "", "juego");
        if (!$bd) {
            die("<b>Imposible conectar con la base de datos</b>");
        }
        return $bd;
    }
}
?>

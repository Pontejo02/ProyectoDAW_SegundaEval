<?php
// models/ModeloUsuario.php

class ModeloUsuario {
    private $bd;

    public function __construct($bd) {
        $this->bd = $bd;
    }

    // Método para iniciar sesión
    public function iniciarSesion($nombreUsuario, $contrasenia) {
        $sql = "SELECT * FROM usuarios WHERE nombreUsuario = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("s", $nombreUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuarioBD = $resultado->fetch_assoc();
            if (password_verify($contrasenia, $usuarioBD["contraseniaUsuario"])) {
                return $usuarioBD;
            }
        }
        return false;
    }

    // Método para crear usuario
    public function crearUsuario($datos) {
        $contraseniaHash = password_hash($datos['contraseniaUsuario'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios 
        (nombreUsuario, nombreReal, apellido1, apellido2, mailUsuario, continente, contraseniaUsuario, edad, genero, referencia)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param(
            "ssssssssss",
            $datos['nombreUsuario'],
            $datos['nombreReal'],
            $datos['apellido1'],
            $datos['apellido2'],
            $datos['mailUsuario'],
            $datos['continente'],
            $contraseniaHash,
            $datos['edad'],
            $datos['genero'],
            $datos['referencia']
        );

        return $stmt->execute() ? $this->bd->insert_id : false;
    }

    // Método para cambiar el nombre de usuario
    public function cambiarNombreUsuario($idUsuario, $nuevoNombre) {
        $sql = "UPDATE usuarios SET nombreUsuario = ? WHERE idUsuario = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("si", $nuevoNombre, $idUsuario);
        return $stmt->execute();
    }

    // Método para eliminar usuario
    public function eliminarUsuario($idUsuario) {
        $sql = "DELETE FROM usuarios WHERE idUsuario = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        return $stmt->execute();
    }
}
?>

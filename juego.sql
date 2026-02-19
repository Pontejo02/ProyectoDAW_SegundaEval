SET time_zone = "+00:00";

-- Base de datos: `juego`
CREATE DATABASE IF NOT EXISTS juego DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE juego;

-- Estructura de tabla para la tabla `niveles`
/*CREATE TABLE IF NOT EXISTS niveles (
  idNiveles INT AUTO_INCREMENT,
  nombreNivel VARCHAR(20) NOT NULL,
  descripcionNiveles varchar(200),
  PRIMARY KEY (idNiveles)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;*/

-- Estructura de tabla para la tabla `usuarios`
CREATE TABLE usuarios (
    idUsuario INT AUTO_INCREMENT,
    nombreUsuario VARCHAR(50) NOT NULL,
    nombreReal VARCHAR(50),
    apellido1 VARCHAR(50),
    apellido2 VARCHAR(50),
    mailUsuario VARCHAR(100) NOT NULL UNIQUE,
    continente VARCHAR(50),
    contraseniaUsuario VARCHAR(255) NOT NULL,
    edad INT(2),
    genero VARCHAR(2),
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    referencia VARCHAR(100),
    PRIMARY KEY (idUsuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Estructura de tabla para la tabla `puntuaciones`
CREATE TABLE puntuaciones (
    idPuntuacion INT AUTO_INCREMENT,
    fecha DATETIME NOT NULL,
    puntuacion INT NOT NULL,
    tiempo TIME NOT NULL,
    idNiveles INT NOT NULL,
    idUsuario INT NOT NULL,
    PRIMARY KEY (idPuntuacion),
    FOREIGN KEY (idUsuario) 
        REFERENCES usuarios(idUsuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE
    /*FOREIGN KEY (idNiveles) 
        REFERENCES niveles(idNiveles)
        ON DELETE CASCADE
        ON UPDATE CASCADE*/
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


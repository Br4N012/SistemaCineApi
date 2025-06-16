CREATE DATABASE IF NOT EXISTS cine;
use cine;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    correo VARCHAR(100) UNIQUE,
    contraseña VARCHAR(255),
    historial_compras TEXT
);

CREATE TABLE peliculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150),
    sinopsis TEXT,
    duracion INT, -- en minutos
    clasificacion VARCHAR(10),
    genero VARCHAR(50),
    poster VARCHAR(255)
);

ALTER TABLE peliculas
ADD COLUMN mes_estreno VARCHAR(20) DEFAULT NULL;

CREATE TABLE salas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    capacidad INT
);

CREATE TABLE funciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pelicula INT,
    id_sala INT,
    horario DATETIME,
    FOREIGN KEY (id_pelicula) REFERENCES peliculas(id),
    FOREIGN KEY (id_sala) REFERENCES salas(id)
);

CREATE TABLE asientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_funcion INT,
    numero_asiento VARCHAR(10),
    disponible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_funcion) REFERENCES funciones(id)
);

CREATE TABLE boletos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_funcion INT,
    id_asiento INT,
    estado ENUM('disponible', 'comprado') DEFAULT 'disponible',
    fecha_compra DATETIME,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_funcion) REFERENCES funciones(id),
    FOREIGN KEY (id_asiento) REFERENCES asientos(id)
);

CREATE TABLE movimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_funcion INT,
    total_pago DECIMAL(10,2),
    metodo_pago VARCHAR(50),
    fecha DATETIME,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_funcion) REFERENCES funciones(id)
);


INSERT INTO peliculas (titulo, sinopsis, duracion, clasificacion, genero, poster) VALUES
('Inception', 'Un ladrón que roba secretos corporativos a través del uso de la tecnología de sueños compartidos.', 148, 'PG-13', 'Ciencia ficción', 'inception.jpg'),
('Titanic', 'Una historia de amor trágica a bordo del famoso barco.', 195, 'PG-13', 'Romance', 'titanic.jpg');

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


INSERT INTO peliculas VALUES
(1, 'Lilo y Stitch', 'Una solitaria niña hawaiana se hace amiga de un extraterrestre fugitivo y ayuda a sanar a su fragmentada familia.', 159, 'AA', 'Comedia', 'lilo-y-stitch.jpg', 'junio'),
(2, 'Destino final 6', 'A medida que un grupo de socorristas escapan de las garras de la muerte, empiezan a ser asesinados por percances cada vez más improbables y asesinos.', 78, 'C', 'Terror', 'destinofinal.jpg', 'junio'),
(3, 'F1: The Movie', 'Sigue a un piloto de Fórmula Uno que sale de su retiro para convertirse en mentor y formar equipo con un piloto más joven.', 150, 'B15', 'Acción, Drama', 'f1.jpg', 'junio'),
(4, 'Jurassic World: El Renacer', 'Cinco años después de Jurassic World: Dominion, una expedición se aventura en remotas regiones ecuatoriales para extraer ADN de tres enormes criaturas prehistóricas, con el objetivo de lograr un avance médico revolucionario.', 134, 'B', 'Acción, Aventura', 'jurassic.jpg', 'julio'),
(5, 'Superman', 'Un superhéroe, se reconcilia con su herencia y su educación humana. Es la encarnación de la verdad, la justicia y un mañana mejor en un mundo que ve la bondad como algo anticuado.', NULL, 'B', 'Ciencia ficción, Acción', 'superman.jpg', 'julio'),
(6, 'Un viernes de locos 2', 'Años después de que Tess y Anna sufrieran una crisis de identidad, Anna ahora tiene una hija y una hijastra. Enfrentan los desafíos que se presentan cuando dos familias se fusionan. Tess y Anna descubren que un rayo puede caer dos veces.', NULL, 'A', 'Comedia', 'vierneslocos.jpg', 'agosto'),
(7, 'El conjuro 4: últimos ritos', 'Cuando los investigadores paranormales Ed y Lorraine Warren se ven envueltos en otro aterrador caso relacionado con misteriosas criaturas, se ven obligados a resolverlo todo por última vez.', NULL, NULL, 'Terror', 'conjuro.jpg', 'septiembre'),
(8, 'Cómo entrenar a tu dragón', 'Cómo entrenar a tu dragón', NULL, 'AA', 'Aventura, Fantasía', 'dragon.jpg', 'junio'),
(9, 'Elio', 'Elio es transportado por los extraterrestres y se convierte en el elegido para ser embajador galáctico de la Tierra mientras su madre Olga trabaja en el proyecto de alto secreto para descifrar mensajes alienígenas.', 99, 'AA', 'Aventura', 'elio.jpg', 'junio'),
(10, '28 años después', 'Han transcurrido casi 30 años desde que un virus de rabia se escapó de un laboratorio de armas biológicas, y, aún bajo una estricta cuarentena, algunos han logrado adaptarse y sobrevivir en medio de los infectados. Un grupo de estos sobrevivientes vive en una pequeña isla, conectada al continente por una única carretera fuertemente custodiada.', 113, 'B', 'Terror, Suspenso', '28.jpg', 'junio'),
(11, 'El ritual', 'En 1928, una mujer es atormentada por una fuerza maligna inexplicable. Cuando la ciencia falla, un sacerdote rural y un misterioso fraile exorcista enfrentan el mal cara a cara en un convento aislado. Lo que comienza como un ritual sagrado se convierte en una batalla despiadada por el alma de Emma Schmidt... y por la cordura de todos los presentes.', 88, NULL, 'Terror', 'ritual.jpg', 'septiembre'),
(12, 'Nacho Libre', 'Tras ser despreciado por los que le rodean durante toda su vida, un monje sigue su sueño y se pone una careta para hacer horas extra como luchador mexicano.', 82, 'A', 'Comedia', 'nacholibre.jpg', 'agosto'),
(13, 'Scary movie 4: Descuartizada', 'Cindy descubre que su casa está habitada por el espíritu de un niño y se dispone a descubrir quién y porqué lo mató. Al mismo tiempo, una invasión alienígena está en camino.', 73, 'B', 'Comedia', 'scarymovie.jpg', 'julio'),
(14, '¿Y dónde está el piloto?', 'Un hombre con miedo a volar debe asegurarse que un avión aterrice seguro después de que los pilotos enferman.', 88, 'B', 'Comedia', 'piloto.jpg', 'julio'),
(15, 'Duna: Parte Dos', 'Tras los sucesos de la primera parte acontecidos en el planeta Arrakis, el joven Paul Atreides se une a la tribu de los Fremen y comienza un viaje espiritual y marcial para convertirse en mesías, mientras intenta evitar el horrible pero inevitable futuro que ha presenciado: una Guerra Santa en su nombre, que se extiende por todo el universo conocido...', 166, 'B', 'Acción, Aventura', 'duna.jpg', 'agosto'),
(16, 'El código Da Vinci', 'Un asesinato en el interior del Louvre y pistas en las pinturas de Da Vinci, llevaron al descubrimiento de un misterio religioso protegido por una sociedad secreta durante dos mil años, que podría sacudir los cimientos del cristianismo.', 147, 'B15', 'Suspenso', 'codigo.jpg', 'agosto'),
(17, 'Midsommar', 'Una pareja viaja a Suecia a un festival de verano en un pueblo rural, cuando todo parece ser el paseo perfecto, la situación se convierte en una violenta y extraña competencia a las manos de un culto pagano.', 145, 'C', 'Terror, Drama', 'midsommar.jpg', 'julio'),
(18, 'La llegada', 'Después de que doce naves espaciales misteriosas aparecen en todo el mundo, Un lingüista deberá trabajar con los militares para poder comunicarse con las formas de vida extraterrestres.', 116, 'B', 'Drama, Ciencia Ficción', 'llegada.jpg', 'septiembre'),
(19, 'Cuestión de tiempo', 'A la edad de 21 años, Tim descubre que puede viajar en el tiempo y cambiar lo que sucede y ha sucedido en su propia vida. Su decisión de hacer de su mundo un lugar mejor al conseguir una novia resulta no ser tan fácil como podría pensar.', 123, 'B', 'Romance', 'tiempo.jpg', 'julio'),
(20, 'Green Book', 'Un portero italo-americano de clase popular se convierte en el chofer de un pianista afroamericano, a lo largo de tour por el sur de los Estados Unidos en los años sesenta.', 130, 'B', 'Drama', 'greenbook.jpg', 'agosto'),
(21, 'Minari', 'Una familia coreana se muda a Arkansas para comenzar una granja en 1980.', 105, 'A', 'Drama', 'minari.jpg', NULL),
(22, 'Chicuarotes', 'Un grupo de adolescentes en la Ciudad de México buscan una mejor vida.', 95, 'B15', 'Crimen, Drama', 'chicuarotes.jpg', 'agosto'),
(23, 'En presencia del diablo', 'Poco después de que un extraño llege a una pequeña aldea, una misteriosa enfermedad comienza a transmitirse. Un policía, involucrado en el incidente, se ve obligado a resolver el misterio para salvar a su hija.', 156, 'C', 'Terror', 'presencia.jpg', 'septiembre'),
(24, 'Exhuma: La Tumba Del Diablo', 'Una renombrada chamana (Kim Go-eun) y su aprendiz son contratados por una enigmática familia adinerada para investigar la enfermedad sobrenatural que afecta a su hijo primogénito. Con la ayuda de un embalsamador y el experto en feng shui más famoso del país (Choi Min-sik), rastrean una tumba familiar oculta, ubicada en tierra sagrada. Percibiendo un aura ominosa alrededor del lugar, el equipo opta por exhumar y reubicar los restos ancestrales de inmediato. Pero algo mucho más oscuro emerge en la remota montaña, desatando fuerzas sobrenaturales que amenazan con destruirlos a todos.', 134, 'C', 'Terror', 'exhuma.jpg', 'junio'),
(25, 'Memorias de un asesino', 'En una pequeña provincia coreana en 1986, tres detectives buscan al culpable de una serie de violaciones y asesinatos de mujeres jóvenes.', 131, 'C', 'Crimen', 'memoriasasesino.jpg', 'julio'),
(26, 'Un asunto de familia', 'Después de uno de sus habituales hurtos, Osamu y su hijo encuentran a una niña en la calle, aterida de frío. Al principio, la mujer de Osamu no quiere que se quede con ellos, pero acaba apiadándose de ella. A pesar de sobrevivir con dificultades gracias a pequeños robos, la familia es feliz, hasta que un incidente imprevisto revela un secreto que pone a prueba los lazos que les unen.', 121, 'C', 'Drama', 'asuntofamilia.jpg', 'septiembre'),
(27, 'Kung Fusión', 'En Shangai, China, en la década de 1940, un aspirante a gánster quiere unirse a la famosa «Axe Gang», pero los aparentemente inofensivos vecinos de un complejo de viviendas no son lo que parecen.', 95, 'R', 'Comedia', 'kungfusion.jpg', 'septiembre');


INSERT INTO salas (nombre, capacidad) VALUES
('Sala 1', 70),
('Sala 2', 50),
('Sala 3', 80);


INSERT INTO funciones (id_pelicula, id_sala, horario) VALUES
(1, 1, '2025-06-23 18:00:00'),
(1, 1, '2025-06-24 20:30:00');

INSERT INTO funciones (id_pelicula, id_sala, horario) VALUES
(2, 2, '2025-06-23 19:00:00'),
(2, 2, '2025-06-25 17:45:00');


INSERT INTO funciones (id_pelicula, id_sala, horario) VALUES
(3, 3, '2025-06-22 16:30:00'),
(3, 3, '2025-06-26 21:00:00');


INSERT INTO funciones (id_pelicula, id_sala, horario) VALUES
(4, 1, '2025-06-19 22:00:00'),
(4, 2, '2025-06-27 18:15:00');


INSERT INTO funciones (id_pelicula, id_sala, horario) VALUES
(5, 3, '2025-06-22 19:30:00'),
(5, 1, '2025-06-26 17:00:00');

DELIMITER //

CREATE PROCEDURE GenerateSeatsForFunction(IN func_id INT, IN sala_capacity INT, IN sala_rows INT, IN seats_per_row_gen INT, IN last_row_seats_gen INT)
BEGIN
    DECLARE r INT DEFAULT 0;
    DECLARE c INT DEFAULT 0;
    DECLARE current_seat_num VARCHAR(10);
    DECLARE row_letter CHAR(1);
    DECLARE seats_in_current_row INT;

    DELETE FROM asientos WHERE id_funcion = func_id;

    WHILE r < sala_rows DO
        SET row_letter = CHAR(65 + r); -- A, B, C...
        SET seats_in_current_row = IF(r = sala_rows - 1, last_row_seats_gen, seats_per_row_gen);
        SET c = 0;
        WHILE c < seats_in_current_row DO
            SET current_seat_num = CONCAT(row_letter, (c + 1));
            INSERT INTO asientos (id_funcion, numero_asiento, disponible) VALUES (func_id, current_seat_num, TRUE);
            SET c = c + 1;
        END WHILE;
        SET r = r + 1;
    END WHILE;
END //

DELIMITER ;


CALL GenerateSeatsForFunction(1, 70, 7, 10, 10); -- For Func 1 (Sala 1)
CALL GenerateSeatsForFunction(2, 70, 7, 10, 10); -- For Func 2 (Sala 1)
CALL GenerateSeatsForFunction(3, 50, 7, 7, 1); -- For Func 3 (Sala 2, example 7x7+1)
CALL GenerateSeatsForFunction(4, 50, 7, 7, 1); -- For Func 4 (Sala 2)
CALL GenerateSeatsForFunction(5, 80, 8, 10, 10); -- For Func 5 (Sala 3, example 8x10)
CALL GenerateSeatsForFunction(6, 70, 7, 10, 10); -- For Func 6 (Sala 1)
CALL GenerateSeatsForFunction(7, 50, 7, 7, 1); -- For Func 7 (Sala 2)
CALL GenerateSeatsForFunction(8, 80, 8, 10, 10); -- For Func 8 (Sala 3)
CALL GenerateSeatsForFunction(9, 70, 7, 10, 10); -- For Func 9 (Sala 1)
CALL GenerateSeatsForFunction(10, 80, 8, 10, 10); -- For Func 10 (Sala 3)

INSERT INTO usuarios (nombre, correo, contraseña) VALUES ('Invitado General', 'guest@example.com', 'no_password_hash');



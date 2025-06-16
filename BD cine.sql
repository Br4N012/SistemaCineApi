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


INSERT INTO peliculas (id, titulo, sinopsis, duracion, clasificacion, genero, poster) VALUES
(1, 'Lilo & Stitch', 'Una solitaria niña hawaiana se hace amiga de un extraterrestre fugitivo y ayuda a sanar a su fragmentada familia.', NULL, NULL, 'Animación', 'lilo_stitch.jpg'),
(2, 'Destino Final', 'A medida que un grupo de socorristas escapan de las garras de la muerte, empiezan a ser asesinados por percances cada vez más improbables y asesinos.', NULL, NULL, 'Terror', 'destino_final.jpg'),
(3, 'Fórmula 1', 'Sigue a un piloto de Fórmula Uno que sale de su retiro para convertirse en mentor y formar equipo con un piloto más joven.', NULL, NULL, 'Deportes', 'formula_1.jpg'),
(4, 'Jurassic World: The Ultimate Hunt', 'Cinco años después de Jurassic World: Dominion, una expedición se aventura en remotas regiones ecuatoriales para extraer ADN de tres enormes criaturas prehistóricas, con el objetivo de lograr un avance médico revolucionario.', NULL, NULL, 'Ciencia Ficción', 'jurassic_world_ultimate_hunt.jpg'),
(5, 'Superman', 'Un superhéroe, se reconcilia con su herencia y su educación humana. Es la encarnación de la verdad, la justicia y un mañana mejor en un mundo que ve la bondad como algo anticuado.', NULL, NULL, 'Superhéroes', 'superman.jpg'),
(6, 'Freaky Friday 2', 'Años después de que Tess y Anna sufrieran una crisis de identidad, Anna ahora tiene una hija y una hijastra. Enfrentan los desafíos que se presentan cuando dos familias se fusionan. Tess y Anna descubren que un rayo puede caer dos veces.', NULL, NULL, 'Comedia', 'freaky_friday_2.jpg'),
(7, 'El Conjuro', 'Cuando los investigadores paranormales Ed y Lorraine Warren se ven envueltos en otro aterrador caso relacionado con misteriosas criaturas, se ven obligados a resolverlo todo por última vez.', NULL, NULL, 'Terror', 'el_conjuro.jpg'),
(8, 'Cómo entrenar a tu dragón', 'Cómo entrenar a tu dragón', NULL, NULL, 'Animación', 'como_entrenar_a_tu_dragon.jpg'),
(9, 'Elio', 'Elio es transportado por los extraterrestres y se convierte en el elegido para ser embajador galáctico de la Tierra mientras su madre Olga trabaja en el proyecto de alto secreto para descifrar mensajes alienígenas.', NULL, NULL, 'Animación', 'elio.jpg'),
(10, 'Train to Busan', 'Han transcurrido casi 30 años desde que un virus de rabia se escapó de un laboratorio de armas biológicas, y, aún bajo una estricta cuarentena, algunos han logrado adaptarse y sobrevivir en medio de los infectados. Un grupo de estos sobrevivientes vive en una pequeña isla, conectada al continente por una única carretera fuertemente custodiada.', NULL, NULL, 'Terror', 'train_to_busan.jpg'),
(11, 'The Exorcism', 'En 1928, una mujer es atormentada por una fuerza maligna inexplicable. Cuando la ciencia falla, un sacerdote rural y un misterioso fraile exorcista enfrentan el mal cara a cara en un convento aislado. Lo que comienza como un ritual sagrado se convierte en una batalla despiadada por el alma de Emma Schmidt... y por la cordura de todos los presentes.', NULL, NULL, 'Terror', 'the_exorcism.jpg'),
(12, 'Nacho Libre', 'Tras ser despreciado por los que le rodean durante toda su vida, un monje sigue su sueño y se pone una careta para hacer horas extra como luchador mexicano.', NULL, NULL, 'Comedia', 'nacho_libre.jpg'),
(13, 'Scary Movie 3', 'Cindy descubre que su casa está habitada por el espíritu de un niño y se dispone a descubrir quién y porqué lo mató. Al mismo tiempo, una invasión alienígena está en camino.', NULL, NULL, 'Comedia', 'scary_movie_3.jpg'),
(14, 'Airplane!', 'Un hombre con miedo a volar debe asegurarse que un avión aterrice seguro después de que los pilotos enferman.', NULL, NULL, 'Comedia', 'airplane.jpg'),
(15, 'Dune: Parte Dos', 'Tras los sucesos de la primera parte acontecidos en el planeta Arrakis, el joven Paul Atreides se une a la tribu de los Fremen y comienza un viaje espiritual y marcial para convertirse en mesías, mientras intenta evitar el horrible pero inevitable futuro que ha presenciado: una Guerra Santa en su nombre, que se extiende por todo el universo conocido...', NULL, NULL, 'Ciencia Ficción', 'dune_part_two.jpg'),
(16, 'El Código Da Vinci', 'Un asesinato en el interior del Louvre y pistas en las pinturas de Da Vinci, llevaron al descubrimiento de un misterio religioso protegido por una sociedad secreta durante dos mil años, que podría sacudir los cimientos del cristianismo.', NULL, NULL, 'Misterio', 'el_codigo_da_vinci.jpg'),
(17, 'Midsommar', 'Una pareja viaja a Suecia a un festival de verano en un pueblo rural, cuando todo parece ser el paseo perfecto, la situación se convierte en una violenta y extraña competencia a las manos de un culto pagano.', NULL, NULL, 'Terror', 'midsommar.jpg'),
(18, 'La Llegada', 'Después de que doce naves espaciales misteriosas aparecen en todo el mundo, Un lingüista deberá trabajar con los militares para poder comunicarse con las formas de vida extraterrestres.', NULL, NULL, 'Ciencia Ficción', 'la_llegada.jpg'),
(19, 'Cuestión de Tiempo', 'A la edad de 21 años, Tim descubre que puede viajar en el tiempo y cambiar lo que sucede y ha sucedido en su propia vida. Su decisión de hacer de su mundo un lugar mejor al conseguir una novia resulta no ser tan fácil como podría pensar.', NULL, NULL, 'Romance', 'cuestion_de_tiempo.jpg'),
(20, 'Green Book', 'Un portero italo-americano de clase popular se convierte en el chofer de un pianista afroamericano, a lo largo de tour por el sur de los Estados Unidos en los sesenta.', NULL, NULL, 'Drama', 'green_book.jpg'),
(21, 'Minari', 'Una familia coreana se muda a Arkansas para comenzar una granja en 1980.', NULL, NULL, 'Drama', 'minari.jpg'),
(22, 'Roma', 'Un grupo de adolescentes en la Ciudad de México buscan una mejor vida.', NULL, NULL, 'Drama', 'roma.jpg'),
(23, 'El Extraño', 'Poco después de que un extraño llege a una pequeña aldea, una misteriosa enfermedad comienza a transmitirse. Un policía, involucrado en el incidente, se ve obligado a resolver el misterio para salvar a su hija.', NULL, NULL, 'Thriller', 'el_extrano.jpg'),
(24, 'Exhuma', 'Una renombrada chamana (Kim Go-eun) y su aprendiz son contratados por una enigmática familia adinerada para investigar la enfermedad sobrenatural que afecta a su hijo primogénito. Con la ayuda de un embalsamador y el experto en feng shui más famoso del país (Choi Min-sik), rastrean una tumba familiar oculta, ubicada en tierra sagrada. Percibiendo un aura ominosa alrededor del lugar, el equipo opta por exhumar y reubicar los restos ancestrales de inmediato. Pero algo mucho más oscuro emerge en la remota montaña, desatando fuerzas sobrenaturales que amenazan con destruirlos a todos.', NULL, NULL, 'Terror', 'exhuma.jpg'),
(25, 'Memories of Murder', 'En una pequeña provincia coreana en 1986, tres detectives buscan al culpable de una serie de violaciones y asesinatos de mujeres jóvenes.', NULL, NULL, 'Crimen', 'memories_of_murder.jpg'),
(26, 'Un Asunto de Familia', 'Después de uno de sus habituales hurtos, Osamu y su hijo encuentran a una niña en la calle, aterida de frío. Al principio, la mujer de Osamu no quiere que se quede con ellos, pero acaba apiadándose de ella. A pesar de sobrevivir con dificultades gracias a pequeños robos, la familia es feliz, hasta que un incidente imprevisto revela un secreto que pone a prueba los lazos que les unen.', NULL, NULL, 'Drama', 'un_asunto_de_familia.jpg'),
(27, 'Kung Fu Sion', 'En Shangai, China, en la década de 1940, un aspirante a gánster quiere unirse a la famosa «Axe Gang», pero los aparentemente inofensivos vecinos de un complejo de viviendas no son lo que parecen.', NULL, NULL, 'Comedia', 'kung_fu_sion.jpg');

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



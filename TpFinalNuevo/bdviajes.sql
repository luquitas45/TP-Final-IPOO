REATE DATABASE bdviajes; 
USE bdviajes;

-- EMPRESA
CREATE TABLE empresa(
    idempresa BIGINT AUTO_INCREMENT,
    enombre VARCHAR(150),
    edireccion VARCHAR(150),
    activo TINYINT DEFAULT 1,
    PRIMARY KEY (idempresa)
);

-- PERSONA
CREATE TABLE persona (
    idpersona INT AUTO_INCREMENT,
    nombre varchar(50),
    apellido varchar(50),
    activo DEFAULT 1,
    PRIMARY KEY (idpersona)
)

-- RESPONSABLE
CREATE TABLE responsable (
    idresponsable INT,
    rnumeroempleado BIGINT AUTO_INCREMENT,
    rnumerolicencia BIGINT,
    activo TINYINT DEFAULT 1,
    PRIMARY KEY (rnumeroempleado),
    FOREIGN KEY (idresponsable) REFERENCES persona (idpersona)
);

-- VIAJE
CREATE TABLE viaje (
    idviaje BIGINT AUTO_INCREMENT,
    vdestino VARCHAR(150),
    vcantmaxpasajeros INT,
    idempresa BIGINT,
    rnumeroempleado BIGINT,
    vimporte FLOAT,
    activo TINYINT DEFAULT 1,
    PRIMARY KEY (idviaje),
    FOREIGN KEY (idempresa) REFERENCES empresa(idempresa)
        ON UPDATE RESTRICT ON DELETE RESTRICT,
    FOREIGN KEY (rnumeroempleado) REFERENCES responsable(rnumeroempleado)
        ON UPDATE RESTRICT ON DELETE RESTRICT
);

-- PASAJERO
CREATE TABLE pasajero (
    idpasajero INT,
    pdocumento VARCHAR(15),
    ptelefono INT,
    activo TINYINT DEFAULT 1,
    PRIMARY KEY (pdocumento),
    FOREIGN KEY (idpasajero) REFERENCES persona(idpersona)
);

-- TABLA INTERMEDIA: viaje_pasajero
CREATE TABLE viaje_pasajero (
    idviaje BIGINT,
    pdocumento VARCHAR(15),
    PRIMARY KEY (idviaje, pdocumento),
    FOREIGN KEY (idviaje) REFERENCES viaje(idviaje)
        ON UPDATE RESTRICT ON DELETE RESTRICT,
    FOREIGN KEY (pdocumento) REFERENCES pasajero(pdocumento)
        ON UPDATE RESTRICT ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
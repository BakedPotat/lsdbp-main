-- Crear la base de datos
CREATE DATABASE db_users;

-- Usar la base de datos creada
USE db_users;

-- Crear la tabla de usuarios con los nuevos campos
CREATE TABLE usuarios (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    cedula VARCHAR(20) NOT NULL,
    telefono VARCHAR(25) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
	fecha_suscripcion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    suscripcion_activa BOOLEAN NOT NULL DEFAULT 0
);

DELIMITER //
CREATE EVENT desactivar_suscripciones
ON SCHEDULE EVERY 1 DAY
DO
BEGIN
    UPDATE usuarios SET suscripcion_activa = 0 WHERE TIMESTAMPDIFF(DAY, fecha_suscripcion, NOW()) > 30;
END;
//
DELIMITER ;

CREATE TABLE xml_registros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    codigo_venta_unico TEXT NOT NULL,
    xml_content TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

drop event desactivar_suscripciones;

select * from db_users.usuarios;
select * from db_users.xml_registros;

drop database db_users;
drop table db_users.xml_registros;


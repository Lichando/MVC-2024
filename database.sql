-- Crear la base de datos
CREATE DATABASE inmobiliaria_db;
USE inmobiliaria_db;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    fecha_registro DATE NOT NULL DEFAULT CURRENT_DATE,
    estado ENUM('activo', 'inactivo', 'suspendido') DEFAULT 'activo'
);

-- Tabla de rol
CREATE TABLE rol (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,  -- Nombre del rol (ej. 'corredor', 'agente_inmobiliario', etc.)
    descripcion TEXT  -- Descripción del rol
);

-- Tabla de inmobiliarias
CREATE TABLE inmobiliarias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(100) UNIQUE,
    fecha_creacion DATE NOT NULL DEFAULT CURRENT_DATE,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo'
);

-- Tabla de características de propiedades
CREATE TABLE caracteristica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT
);

-- Tabla de imágenes
CREATE TABLE imagenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255) NOT NULL,
    descripcion TEXT
);

-- Tabla de geolocalización
CREATE TABLE geolocalizacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    latitud DECIMAL(10, 8) NOT NULL,
    longitud DECIMAL(11, 8) NOT NULL,
    direccion VARCHAR(255) NOT NULL
);

-- Tabla de propiedades
CREATE TABLE propiedades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_inm INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    descripcion TEXT,
    id_img INT NOT NULL,
    id_geo INT NOT NULL,
    id_car INT NOT NULL,
    estado ENUM('disponible', 'vendido', 'alquilado', 'reservado') DEFAULT 'disponible',
    FOREIGN KEY (id_inm) REFERENCES inmobiliarias(id) ON DELETE CASCADE,
    FOREIGN KEY (id_img) REFERENCES imagenes(id) ON DELETE CASCADE,
    FOREIGN KEY (id_geo) REFERENCES geolocalizacion(id) ON DELETE CASCADE,
    FOREIGN KEY (id_car) REFERENCES caracteristica(id) ON DELETE CASCADE
);

-- Tabla de visitas a propiedades
CREATE TABLE visitas_propiedades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_propiedad INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_visita DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_propiedad) REFERENCES propiedades(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla de transacciones
CREATE TABLE transacciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_propiedad INT NOT NULL,
    id_usuario INT NOT NULL,
    id_inmobiliaria INT NOT NULL,
    tipo ENUM('venta', 'alquiler', 'reserva') NOT NULL,
    fecha_transaccion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_propiedad) REFERENCES propiedades(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_inmobiliaria) REFERENCES inmobiliarias(id) ON DELETE CASCADE
);

-- Tabla de roles de usuarios en inmobiliarias
CREATE TABLE roles_inmobiliarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    id_inm INT NOT NULL,
    rol_id INT NOT NULL,  -- Referencia al ID del rol
    fecha_asignacion DATE NOT NULL DEFAULT CURRENT_DATE,
    estado ENUM('activo', 'inactivo', 'suspendido') DEFAULT 'activo',
    FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_inm) REFERENCES inmobiliarias(id) ON DELETE CASCADE,
    FOREIGN KEY (rol_id) REFERENCES rol(id) ON DELETE CASCADE,  -- Referencia a la tabla de roles
    UNIQUE (user_id, id_inm)  -- Evita duplicar asignaciones
);

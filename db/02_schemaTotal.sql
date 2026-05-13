CREATE DATABASE IF NOT EXISTS proyecto_db_propuesta;
USE proyecto_db_propuesta;

-- ============================================================
-- TABLAS EXISTENTES (SIN MODIFICAR)
-- ============================================================

CREATE TABLE persona_g3 (
  id_persona int(11) NOT NULL AUTO_INCREMENT,
  nombre varchar(50) NOT NULL,
  apellido varchar(50) NOT NULL,
  nombre_de_usuario varchar(50) NOT NULL,
  correo varchar(50) NOT NULL,
  password varchar(255) NOT NULL,
  rol enum('cliente','trabajador','admin','') NOT NULL,
  telefono varchar(50) NOT NULL,
  PRIMARY KEY (id_persona)
) ENGINE=InnoDB;


CREATE TABLE mascota_g3 (
  id_mascota int(11) NOT NULL AUTO_INCREMENT,
  id_persona int(11) NOT NULL,
  nombre varchar(50) NOT NULL,
  fecha_de_nacimiento datetime NOT NULL,
  edad int(11) NOT NULL,
  raza varchar(50) NOT NULL,
  tamanio enum('pequeño','mediano','grande','') NOT NULL,
  color varchar(50) NOT NULL,
  imagen_url varchar(255) DEFAULT NULL,
  PRIMARY KEY (id_mascota),
  KEY id_persona (id_persona)
) ENGINE=InnoDB;


CREATE TABLE tipo_de_servicio_g3 (
  id_tipo_servicio int(11) NOT NULL AUTO_INCREMENT,
  tipo_de_servicio varchar(50) NOT NULL,
  descripcion varchar(255) NOT NULL,
  precio_servicio float NOT NULL,
  imagen_servicio varchar(255) NOT NULL,
  PRIMARY KEY (id_tipo_servicio)
) ENGINE=InnoDB;


CREATE TABLE servicio_g3 (
  id_servicio int(11) NOT NULL AUTO_INCREMENT,
  tipo_de_servicio varchar(50) NOT NULL,
  id_mascota int(11) NOT NULL,
  id_trabajador int(11) NOT NULL,
  horario datetime NOT NULL,
  comentarios varchar(200) NOT NULL,
  monto decimal(10,0) NOT NULL,
  pagado tinyint(1) DEFAULT 0,
  PRIMARY KEY (id_servicio),
  KEY id_mascota (id_mascota),
  KEY id_persona (id_trabajador)
) ENGINE=InnoDB;


CREATE TABLE trabajadores_g3 (
  id_persona int(11) NOT NULL,
  rol varchar(50) NOT NULL,
  tipo_de_servicio varchar(50),
  pass_app varchar(50),
  correo_host varchar(50),
  PRIMARY KEY (id_persona)
) ENGINE=InnoDB;


CREATE TABLE direccion_g3 (
  id_persona int(11) NOT NULL,
  provincia varchar(50) NOT NULL,
  localidad varchar(50) NOT NULL,
  calle varchar(50) NOT NULL,
  altura int(6) NOT NULL,
  PRIMARY KEY (id_persona)
) ENGINE=InnoDB;


CREATE TABLE postulaciones_g3 (
  id int(11) NOT NULL AUTO_INCREMENT,
  nombre varchar(100) NOT NULL,
  apellido varchar(100) NOT NULL,
  correo varchar(150) NOT NULL,
  puesto_aplicado varchar(100) NOT NULL,
  cv_nombre varchar(255) NOT NULL,
  cv_contenido varchar(255) NOT NULL,
  fecha_postulacion datetime DEFAULT CURRENT_TIMESTAMP,
  cv_ruta varchar(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;


-- ============================================================
-- NUEVAS TABLAS
-- ============================================================

-- PROVEEDORES
CREATE TABLE proveedores_g3 (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    cuit VARCHAR(20),
    telefono VARCHAR(50),
    correo VARCHAR(100),
    direccion VARCHAR(150),
    activo TINYINT(1) DEFAULT 1,
    fecha_alta DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- PRODUCTOS / INSUMOS
CREATE TABLE productos_g3 (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    tipo ENUM('PRODUCTO','INSUMO','AMBOS') NOT NULL,
    precio_venta DECIMAL(10,2),
    stock_minimo INT DEFAULT 0
);

-- COMPRAS
CREATE TABLE compras_g3 (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    id_proveedor INT NOT NULL,
    fecha_compra DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    FOREIGN KEY (id_proveedor) REFERENCES proveedores_g3(id_proveedor)
);

CREATE TABLE compra_detalle_g3 (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_compra INT,
    id_producto INT,
    cantidad INT,
    precio_unitario DECIMAL(10,2),
    FOREIGN KEY (id_compra) REFERENCES compras_g3(id_compra),
    FOREIGN KEY (id_producto) REFERENCES productos_g3(id_producto)
);

-- VENTAS
CREATE TABLE ventas_g3 (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    fecha_venta DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    FOREIGN KEY (id_cliente) REFERENCES persona_g3(id_persona)
);

CREATE TABLE venta_detalle_g3 (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT,
    id_producto INT,
    cantidad INT,
    precio_unitario DECIMAL(10,2),
    FOREIGN KEY (id_venta) REFERENCES ventas_g3(id_venta),
    FOREIGN KEY (id_producto) REFERENCES productos_g3(id_producto)
);

-- STOCK
CREATE TABLE movimientos_stock_g3 (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT,
    tipo_movimiento ENUM('ENTRADA','SALIDA','AJUSTE'),
    cantidad INT,
    fecha_movimiento DATETIME DEFAULT CURRENT_TIMESTAMP,
    referencia_tipo VARCHAR(50),
    referencia_id INT,
    FOREIGN KEY (id_producto) REFERENCES productos_g3(id_producto)
);

-- HISTORIA CLINICA
CREATE TABLE historia_clinica_g3 (
    id_historia INT AUTO_INCREMENT PRIMARY KEY,
    id_mascota INT,
    fecha_apertura DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_mascota) REFERENCES mascota_g3(id_mascota)
);

CREATE TABLE atencion_clinica_g3 (
    id_atencion INT AUTO_INCREMENT PRIMARY KEY,
    id_historia INT,
    id_servicio INT,
    fecha_atencion DATETIME DEFAULT CURRENT_TIMESTAMP,
    diagnostico TEXT,
    tratamiento TEXT,
    FOREIGN KEY (id_historia) REFERENCES historia_clinica_g3(id_historia),
    FOREIGN KEY (id_servicio) REFERENCES servicio_g3(id_servicio)
);

-- INSUMOS POR SERVICIO
CREATE TABLE servicio_insumos_g3 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_tipo_servicio INT,
    id_producto INT,
    cantidad INT,
    FOREIGN KEY (id_tipo_servicio) REFERENCES tipo_de_servicio_g3(id_tipo_servicio),
    FOREIGN KEY (id_producto) REFERENCES productos_g3(id_producto)
);


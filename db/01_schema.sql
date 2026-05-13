CREATE TABLE proveedores_g3 (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    cuit VARCHAR(20),
    telefono VARCHAR(50),
    correo VARCHAR(100),
    direccion VARCHAR(150),
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_alta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE historia_clinica_g3 (
    id_historia INT AUTO_INCREMENT PRIMARY KEY,
    id_mascota INT NOT NULL,
    fecha_apertura DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    observaciones_generales TEXT,

    FOREIGN KEY (id_mascota) REFERENCES mascota_g3(id_mascota)
);

CREATE TABLE atencion_clinica_g3 (
    id_atencion INT AUTO_INCREMENT PRIMARY KEY,
    id_historia INT NOT NULL,
    id_servicio INT NULL,
    fecha_atencion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    motivo_consulta VARCHAR(255),
    diagnostico TEXT,
    tratamiento TEXT,
    observaciones TEXT,
    id_profesional INT NULL,

    FOREIGN KEY (id_historia) REFERENCES historia_clinica_g3(id_historia),
    FOREIGN KEY (id_servicio) REFERENCES servicio_g3(id_servicio),
    FOREIGN KEY (id_profesional) REFERENCES persona_g3(id_persona)
);

// YUSQUE
// Productos / insumos

CREATE TABLE productos_g3 (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    tipo ENUM('PRODUCTO','INSUMO','AMBOS') NOT NULL,
    precio_venta DECIMAL(10,2),
    stock_minimo INT DEFAULT 0,
    activo TINYINT(1) NOT NULL DEFAULT 1
);

// Compras a proveedores

CREATE TABLE compras_g3 (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    id_proveedor INT NOT NULL,
    fecha_compra DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    observaciones TEXT,

    FOREIGN KEY (id_proveedor) REFERENCES proveedores_g3(id_proveedor)
);


//Detalle de compra

CREATE TABLE compra_detalle_g3 (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_compra INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    iva DECIMAL(5,2) DEFAULT 0,

    FOREIGN KEY (id_compra) REFERENCES compras_g3(id_compra),
    FOREIGN KEY (id_producto) REFERENCES productos_g3(id_producto)
);

// Ventas a clientes

CREATE TABLE ventas_g3 (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    fecha_venta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    medio_pago VARCHAR(50),

    FOREIGN KEY (id_cliente) REFERENCES persona_g3(id_persona)
);


//Detalle de venta

CREATE TABLE venta_detalle_g3 (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    iva DECIMAL(5,2) DEFAULT 0,

    FOREIGN KEY (id_venta) REFERENCES ventas_g3(id_venta),
    FOREIGN KEY (id_producto) REFERENCES productos_g3(id_producto)
);
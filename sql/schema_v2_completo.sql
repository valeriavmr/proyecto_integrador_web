-- ================================================================
-- SCHEMA v2 — Adiestramiento Canino Tahito — Grupo 3
-- ================================================================
-- Autores módulos:
--   Personas / Auth       → Equipo
--   Gestión de insumos    → Valeria
--   Gestión de stock      → Valeria
--   Gestión proveedores   → Bernardo
--   Historia clínica      → Bernardo
--   Venta de productos    → Yuske
--   Rentabilidad          → Rodrigo


SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------------
-- Limpieza tablas VIEJAS
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS venta_detalle_g3;
DROP TABLE IF EXISTS ventas_g3;
DROP TABLE IF EXISTS compra_detalle_g3;
DROP TABLE IF EXISTS compras_g3;
DROP TABLE IF EXISTS movimientos_stock_g3;
DROP TABLE IF EXISTS servicio_insumos_g3;
DROP TABLE IF EXISTS atencion_clinica_g3;
DROP TABLE IF EXISTS servicio_g3;
DROP TABLE IF EXISTS historia_clinica_g3;
DROP TABLE IF EXISTS mascota_g3;
DROP TABLE IF EXISTS postulaciones_g3;
DROP TABLE IF EXISTS trabajadores_g3;
DROP TABLE IF EXISTS direccion_g3;
DROP TABLE IF EXISTS tipo_de_servicio_g3;
DROP TABLE IF EXISTS productos_g3;
DROP TABLE IF EXISTS proveedores_g3;
DROP TABLE IF EXISTS rentabilidad_g3;
DROP TABLE IF EXISTS persona_g3;

-- ----------------------------------------------------------------
-- Limpieza tablas NUEVAS sin sufijo (para reimportaciones limpias)
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS rentabilidad;
DROP VIEW  IF EXISTS v_rentabilidad_mensual;
DROP TABLE IF EXISTS venta_detalle;
DROP TABLE IF EXISTS ventas;
DROP TABLE IF EXISTS compra_detalle;
DROP TABLE IF EXISTS compras;
DROP TABLE IF EXISTS movimientos_stock;
DROP TABLE IF EXISTS servicio_insumos;
DROP TABLE IF EXISTS atencion_clinica;
DROP TABLE IF EXISTS servicio;
DROP TABLE IF EXISTS historia_clinica;
DROP TABLE IF EXISTS mascota;
DROP TABLE IF EXISTS postulaciones;
DROP TABLE IF EXISTS trabajadores;
DROP TABLE IF EXISTS direccion;
DROP TABLE IF EXISTS tipo_de_servicio;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS proveedores;
DROP TABLE IF EXISTS persona;

SET FOREIGN_KEY_CHECKS = 1;



-- ================================================================
-- BLOQUE 1 — CORE: PERSONAS Y AUTENTICACIÓN
-- ================================================================

CREATE TABLE persona (
    id_persona          INT AUTO_INCREMENT PRIMARY KEY,
    nombre              VARCHAR(100) NOT NULL,
    apellido            VARCHAR(100) NOT NULL,
    nombre_de_usuario   VARCHAR(100) NOT NULL UNIQUE,
    password            VARCHAR(255) NOT NULL,
    rol                 ENUM('cliente','trabajador','admin') NOT NULL DEFAULT 'cliente',
    correo              VARCHAR(150) UNIQUE,
    telefono            VARCHAR(30),
    activo              TINYINT(1)  NOT NULL DEFAULT 1,
    fecha_registro      DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Usuarios del sistema: clientes, trabajadores y administradores';

ALTER TABLE `persona` CHANGE `rol` `rol` ENUM('cliente','trabajador','admin','gestor') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL;

CREATE TABLE direccion (
    id_persona  INT PRIMARY KEY,
    provincia   VARCHAR(100),
    localidad   VARCHAR(100),
    calle       VARCHAR(150),
    altura      VARCHAR(20),
    CONSTRAINT fk_dir_persona
        FOREIGN KEY (id_persona) REFERENCES persona(id_persona)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Dirección postal de una persona (relación 1:1)';


CREATE TABLE trabajadores (
    id_persona          INT PRIMARY KEY,
    rol                 ENUM('admin','trabajador') NOT NULL DEFAULT 'trabajador',
    tipo_de_servicio    VARCHAR(100),
    pass_app            VARCHAR(255),
    correo_host         VARCHAR(150),
    CONSTRAINT fk_trab_persona
        FOREIGN KEY (id_persona) REFERENCES persona(id_persona)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Datos laborales adicionales para personas con rol trabajador o admin';


CREATE TABLE postulaciones (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    nombre              VARCHAR(100) NOT NULL,
    apellido            VARCHAR(100) NOT NULL,
    correo              VARCHAR(150) NOT NULL,
    puesto_aplicado     VARCHAR(150),
    cv_nombre           VARCHAR(255),
    cv_contenido        LONGBLOB,
    cv_ruta             VARCHAR(500),
    fecha_postulacion   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Formulario Trabaja con Nosotros — postulantes externos';


-- ================================================================
-- BLOQUE 2 — MASCOTAS E HISTORIA CLÍNICA (Bernardo)
-- ================================================================

CREATE TABLE mascota (
    id_mascota          INT AUTO_INCREMENT PRIMARY KEY,
    id_persona          INT NOT NULL,
    nombre              VARCHAR(100) NOT NULL,
    fecha_de_nacimiento DATE,
    edad                INT,
    raza                VARCHAR(100),
    tamanio             VARCHAR(50),
    color               VARCHAR(50),
    imagen_url          VARCHAR(500),
    CONSTRAINT fk_masc_persona
        FOREIGN KEY (id_persona) REFERENCES persona(id_persona)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Mascotas registradas por los clientes';


CREATE TABLE historia_clinica (
    id_historia             INT AUTO_INCREMENT PRIMARY KEY,
    id_mascota              INT NOT NULL,
    fecha_apertura          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    observaciones_generales TEXT,
    CONSTRAINT fk_hist_mascota
        FOREIGN KEY (id_mascota) REFERENCES mascota(id_mascota)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Historia clínica de cada mascota (1:1 con mascota)';


-- ================================================================
-- BLOQUE 3 — SERVICIOS Y TURNOS
-- ================================================================

CREATE TABLE tipo_de_servicio (
    id_tipo_servicio    INT AUTO_INCREMENT PRIMARY KEY,
    tipo_de_servicio    VARCHAR(150) NOT NULL UNIQUE,
    descripcion         TEXT,
    precio_servicio     DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    imagen_servicio     VARCHAR(500)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Catálogo de tipos de servicio ofrecidos por el centro';


CREATE TABLE servicio (
    id_servicio         INT AUTO_INCREMENT PRIMARY KEY,
    tipo_de_servicio    VARCHAR(150) NOT NULL
                            COMMENT 'Desnormalizado para preservar historial aunque cambie el catálogo',
    id_mascota          INT NOT NULL,
    id_trabajador       INT NULL
                            COMMENT 'Profesional asignado al turno',
    horario             DATETIME NOT NULL,
    comentarios         TEXT,
    monto               DECIMAL(10,2),
    pagado              TINYINT(1) NOT NULL DEFAULT 0,
    CONSTRAINT fk_serv_mascota
        FOREIGN KEY (id_mascota) REFERENCES mascota(id_mascota)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_serv_trabajador
        FOREIGN KEY (id_trabajador) REFERENCES persona(id_persona)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Turnos/sesiones concretas agendadas para una mascota';


CREATE TABLE atencion_clinica (
    id_atencion         INT AUTO_INCREMENT PRIMARY KEY,
    id_historia         INT NOT NULL,
    id_servicio         INT NULL
                            COMMENT 'Turno que originó esta atención (puede ser NULL si es ingreso manual)',
    id_profesional      INT NULL
                            COMMENT 'Persona con rol trabajador que atendió',
    fecha_atencion      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    motivo_consulta     VARCHAR(255),
    diagnostico         TEXT,
    tratamiento         TEXT,
    observaciones       TEXT,
    CONSTRAINT fk_aten_historia
        FOREIGN KEY (id_historia) REFERENCES historia_clinica(id_historia)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_aten_servicio
        FOREIGN KEY (id_servicio) REFERENCES servicio(id_servicio)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_aten_profesional
        FOREIGN KEY (id_profesional) REFERENCES persona(id_persona)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Registro clínico detallado de cada atención (Bernardo)';


-- ================================================================
-- BLOQUE 4 — INVENTARIO Y PROVEEDORES (Valeria + Bernardo)
-- ================================================================

CREATE TABLE proveedores (
    id_proveedor    INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(100) NOT NULL,
    cuit            VARCHAR(20),
    telefono        VARCHAR(50),
    correo          VARCHAR(100),
    direccion       VARCHAR(150),
    activo          TINYINT(1)  NOT NULL DEFAULT 1,
    fecha_alta      DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Proveedores de insumos y productos (Bernardo)';

--Insumos
CREATE TABLE insumo (
    id_insumo INT AUTO_INCREMENT PRIMARY KEY,
    nombre_insumo VARCHAR(100) NOT NULL,
    descripcion_insumo TEXT,
    tipo_insumo VARCHAR(50),
    costo_unidad DECIMAL(10,2) NOT NULL,
) ENGINE=InnoDB;

CREATE TABLE inventario_insumo (
    id_stock_insumo INT AUTO_INCREMENT PRIMARY KEY,
    id_insumo INT NOT NULL,
    cantidad_actual INT NOT NULL DEFAULT 0,
    param_bajo_stock INT NOT NULL,

    FOREIGN KEY (id_insumo)
        REFERENCES insumo(id_insumo)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE movimientos_insumo (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_stock_insumo INT NOT NULL,
    tipo_movimiento ENUM('entrada', 'salida') NOT NULL,
    cantidad INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_stock_insumo)
        REFERENCES inventario_insumo(id_stock_insumo)
) ENGINE=InnoDB;

--productos

CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(100) NOT NULL,
    descripcion_producto TEXT,
    precio_unitario DECIMAL(10,2) NOT NULL
    ) ENGINE=InnoDB;

    ALTER TABLE productos 
    ADD imagen_producto VARCHAR(255),
    ADD tipo ENUM('Otro','Vacuna','Medicamento') NOT NULL DEFAULT 'Otro',
    ADD activo	TINYINT(1) NOT NULL DEFAULT 1;

CREATE TABLE inventario (
    id_producto_stock INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    cantidad_actual_producto INT NOT NULL DEFAULT 0,
    param_bajo_stock INT NOT NULL,

    CONSTRAINT fk_inventario_producto
    FOREIGN KEY (id_producto)
    REFERENCES productos(id_producto)
    ON DELETE CASCADE
    ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE inventario_movimientos (
id_movimiento_stock INT AUTO_INCREMENT PRIMARY KEY,
id_producto_stock INT NOT NULL,
tipo_movimiento ENUM('entrada', 'salida') NOT NULL,
fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
cantidad_producto INT NOT NULL,

CONSTRAINT fk_movimiento_inventario
FOREIGN KEY (id_producto_stock)
REFERENCES inventario(id_producto_stock)
ON DELETE CASCADE
ON UPDATE CASCADE
) ENGINE=InnoDB; 

CREATE INDEX idx_inventario_producto ON inventario(id_producto);
CREATE INDEX idx_movimientos_stock ON inventario_movimientos(id_producto_stock);



-- ================================================================
-- BLOQUE 5 — COMPRAS (Bernardo)
-- ================================================================

CREATE TABLE compras (
    id_compra       INT AUTO_INCREMENT PRIMARY KEY,
    id_proveedor    INT NOT NULL,
    fecha_compra    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total           DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    observaciones   TEXT,
    CONSTRAINT fk_comp_prov
        FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedor)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Cabecera de cada orden de compra a proveedor (Bernardo)';


CREATE TABLE compra_detalle (
    id_detalle      INT AUTO_INCREMENT PRIMARY KEY,
    id_compra       INT NOT NULL,
    id_producto     INT NOT NULL,
    cantidad        INT           NOT NULL DEFAULT 1,
    precio_unitario DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_cd_compra
        FOREIGN KEY (id_compra) REFERENCES compras(id_compra)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_cd_producto
        FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Ítems detallados de cada compra (Bernardo)';


-- ================================================================
-- BLOQUE 6 — VENTAS DE PRODUCTOS (Yuske)
-- FIX: id_proveedor → id_cliente (era un error semántico)
-- ================================================================

CREATE TABLE ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    total DECIMAL(10,2) NOT NULL,
    id_persona INT NULL,
    id_mascota INT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_ventas_persona
    FOREIGN KEY (id_persona)
    REFERENCES persona(id_persona)
    ON DELETE SET NULL
    ON UPDATE CASCADE,

    CONSTRAINT fk_ventas_mascota
    FOREIGN KEY (id_mascota)
    REFERENCES mascota(id_mascota)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE detalle_venta (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,

    CONSTRAINT fk_detalle_venta
    FOREIGN KEY (id_venta)
    REFERENCES ventas(id_venta)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

    CONSTRAINT fk_detalle_producto
    FOREIGN KEY (id_producto)
    REFERENCES productos(id_producto)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ================================================================
-- BLOQUE 7 — RENTABILIDAD Y GANANCIAS (Rodrigo)
-- ================================================================
-- Diseño:
--   • rentabilidad  → tabla de ajustes manuales (sueldos, otros costos)
--   • v_rentabilidad_mensual → VIEW que auto-calcula ingresos + costos
--     cruzando servicio, ventas y compras en tiempo real.
-- ================================================================

CREATE TABLE rentabilidad (
    id_rentabilidad     INT AUTO_INCREMENT PRIMARY KEY,

    -- Período
    periodo_anio        YEAR        NOT NULL,
    periodo_mes         TINYINT(2)  NOT NULL
                            COMMENT '1 = Enero, 12 = Diciembre',

    -- Costos manuales que no vienen de otras tablas
    costo_sueldos       DECIMAL(12,2) NOT NULL DEFAULT 0.00
                            COMMENT 'Costo laboral del período (ingreso manual)',
    costo_otros         DECIMAL(12,2) NOT NULL DEFAULT 0.00
                            COMMENT 'Gastos varios del período (ingreso manual)',

    -- Metadatos
    notas               TEXT,
    generado_en         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                            ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uk_periodo (periodo_anio, periodo_mes)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Ajustes manuales por período para el cálculo de rentabilidad (Rodrigo)';


-- ================================================================
-- VIEW — v_rentabilidad_mensual
-- Auto-calcula ingresos y costos cruzando las tablas reales.
-- Usar esta vista para los reportes y gráficos de rentabilidad.
-- ================================================================

CREATE OR REPLACE VIEW v_rentabilidad_mensual AS
SELECT
    -- Identificador del período
    COALESCE(r.periodo_anio,  YEAR(ref.fecha))  AS periodo_anio,
    COALESCE(r.periodo_mes,   MONTH(ref.fecha)) AS periodo_mes,

    -- Ingresos por servicios (turnos pagados)
    COALESCE(ing_srv.total_servicios,  0.00) AS ingresos_servicios,

    -- Ingresos por ventas de productos
    COALESCE(ing_vta.total_ventas,     0.00) AS ingresos_ventas,

    -- Total ingresos
    COALESCE(ing_srv.total_servicios, 0.00)
        + COALESCE(ing_vta.total_ventas, 0.00)   AS ingresos_total,

    -- Costos de compras a proveedores
    COALESCE(eg_cmp.total_compras,     0.00) AS costo_compras,

    -- Costos manuales del registro base
    COALESCE(r.costo_sueldos,          0.00) AS costo_sueldos,
    COALESCE(r.costo_otros,            0.00) AS costo_otros,

    -- Total costos
    COALESCE(eg_cmp.total_compras,  0.00)
        + COALESCE(r.costo_sueldos, 0.00)
        + COALESCE(r.costo_otros,   0.00)        AS costo_total,

    -- Ganancia bruta (ingresos - costo de compras solamente)
    ( COALESCE(ing_srv.total_servicios, 0.00)
    + COALESCE(ing_vta.total_ventas,    0.00) )
    - COALESCE(eg_cmp.total_compras,    0.00)    AS ganancia_bruta,

    -- Ganancia neta (ingresos - todos los costos)
    ( COALESCE(ing_srv.total_servicios, 0.00)
    + COALESCE(ing_vta.total_ventas,    0.00) )
    - ( COALESCE(eg_cmp.total_compras,  0.00)
      + COALESCE(r.costo_sueldos,       0.00)
      + COALESCE(r.costo_otros,         0.00) )  AS ganancia_neta,

    -- Margen de rentabilidad (%)
    CASE
        WHEN ( COALESCE(ing_srv.total_servicios, 0.00)
             + COALESCE(ing_vta.total_ventas,    0.00) ) > 0
        THEN ROUND(
            (
              ( COALESCE(ing_srv.total_servicios, 0.00)
              + COALESCE(ing_vta.total_ventas,    0.00) )
              - ( COALESCE(eg_cmp.total_compras,  0.00)
                + COALESCE(r.costo_sueldos,       0.00)
                + COALESCE(r.costo_otros,         0.00) )
            )
            / ( COALESCE(ing_srv.total_servicios, 0.00)
              + COALESCE(ing_vta.total_ventas,    0.00) ) * 100
        , 2)
        ELSE 0
    END AS margen_porcentaje,

    -- Metadatos
    r.notas,
    r.generado_en,
    r.actualizado_en

FROM (
    -- Unión de todos los períodos con datos en alguna tabla
    SELECT DISTINCT YEAR(horario)     AS fecha FROM servicio  WHERE pagado = 1
    UNION
    SELECT DISTINCT YEAR(fecha_venta)           FROM ventas
    UNION
    SELECT DISTINCT YEAR(fecha_compra)          FROM compras
) AS ref_anio
CROSS JOIN (
    SELECT 1 AS mes UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
    UNION SELECT 5  UNION SELECT 6 UNION SELECT 7 UNION SELECT 8
    UNION SELECT 9  UNION SELECT 10 UNION SELECT 11 UNION SELECT 12
) AS meses
-- Alias de referencia completo
INNER JOIN (
    SELECT DISTINCT
        YEAR(horario)  AS anio,
        MONTH(horario) AS mes,
        MAKEDATE(YEAR(horario),1) + INTERVAL (MONTH(horario)-1) MONTH AS fecha
    FROM servicio WHERE pagado = 1
    UNION
    SELECT DISTINCT YEAR(fecha_venta), MONTH(fecha_venta),
        MAKEDATE(YEAR(fecha_venta),1) + INTERVAL (MONTH(fecha_venta)-1) MONTH
    FROM ventas
    UNION
    SELECT DISTINCT YEAR(fecha_compra), MONTH(fecha_compra),
        MAKEDATE(YEAR(fecha_compra),1) + INTERVAL (MONTH(fecha_compra)-1) MONTH
    FROM compras
) AS ref ON ref.anio = ref_anio.fecha AND ref.mes = meses.mes

-- Ingresos servicios del período
LEFT JOIN (
    SELECT
        YEAR(horario)  AS anio,
        MONTH(horario) AS mes,
        SUM(monto)     AS total_servicios
    FROM servicio
    WHERE pagado = 1
    GROUP BY YEAR(horario), MONTH(horario)
) AS ing_srv ON ing_srv.anio = ref.anio AND ing_srv.mes = ref.mes

-- Ingresos ventas del período
LEFT JOIN (
    SELECT
        YEAR(fecha_venta)  AS anio,
        MONTH(fecha_venta) AS mes,
        SUM(total)         AS total_ventas
    FROM ventas
    GROUP BY YEAR(fecha_venta), MONTH(fecha_venta)
) AS ing_vta ON ing_vta.anio = ref.anio AND ing_vta.mes = ref.mes

-- Costos compras del período
LEFT JOIN (
    SELECT
        YEAR(fecha_compra)  AS anio,
        MONTH(fecha_compra) AS mes,
        SUM(total)          AS total_compras
    FROM compras
    GROUP BY YEAR(fecha_compra), MONTH(fecha_compra)
) AS eg_cmp ON eg_cmp.anio = ref.anio AND eg_cmp.mes = ref.mes

-- Ajustes manuales del período
LEFT JOIN rentabilidad AS r
    ON r.periodo_anio = ref.anio AND r.periodo_mes = ref.mes

ORDER BY periodo_anio DESC, periodo_mes DESC;


-- ================================================================
-- FIN DEL SCHEMA v2
-- Base de datos: proyecto_db
-- Versión: 2.0 — Abril 2026
-- ================================================================

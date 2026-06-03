-- ================================================================
-- DATOS DE PRUEBA / DEMO  —  Tahito Adiestramiento Canino
-- ================================================================
-- Este script inserta datos realistas para demostrar el módulo
-- de Rentabilidad y el resto del sistema.
-- ATENCIÓN: Ejecutar DESPUÉS del schema_v2_completo.sql
-- ================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------------
-- 0. Limpieza de datos de prueba previos (sin tocar el schema)
-- ----------------------------------------------------------------
DELETE FROM rentabilidad;
DELETE FROM detalle_venta;
DELETE FROM ventas;
DELETE FROM compra_detalle;
DELETE FROM compras;
DELETE FROM inventario_movimientos;
DELETE FROM inventario;
DELETE FROM inventario_insumo;
DELETE FROM movimientos_insumo;
DELETE FROM atencion_clinica;
DELETE FROM servicio;
DELETE FROM historia_clinica;
DELETE FROM mascota;
DELETE FROM postulaciones;
DELETE FROM trabajadores;
DELETE FROM direccion;
DELETE FROM productos;
DELETE FROM insumo;
DELETE FROM proveedores;
DELETE FROM persona;

SET FOREIGN_KEY_CHECKS = 1;

-- ================================================================
-- BLOQUE 1 — PERSONAS (admin, trabajadores, clientes)
-- ================================================================

-- Contraseña: Admin123! (hash bcrypt)
INSERT INTO persona (id_persona, nombre, apellido, nombre_de_usuario, password, rol, correo, telefono, activo) VALUES
(1,  'Rodrigo',   'Méndez',     'admin_rodrigo',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkZ6HQZO', 'admin',      'admin@tahito.com.ar',      '221-555-0001', 1),
(2,  'Valeria',   'Russo',      'valeria_gestor',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkZ6HQZO', 'gestor',     'valeria@tahito.com.ar',    '221-555-0002', 1),
(3,  'Carlos',    'Fernández',  'carlos_trainer',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkZ6HQZO', 'trabajador', 'carlos@tahito.com.ar',     '221-555-0003', 1),
(4,  'Lucía',     'Gómez',      'lucia_vet',       '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkZ6HQZO', 'trabajador', 'lucia@tahito.com.ar',      '221-555-0004', 1),
(5,  'Martín',    'López',      'martin_bañador',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkZ6HQZO', 'trabajador', 'martin@tahito.com.ar',     '221-555-0005', 1),
-- Clientes
(6,  'Ana',       'Pérez',      'ana_perez',       '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkZ6HQZO', 'cliente',    'ana@gmail.com',            '221-444-1001', 1),
(7,  'Diego',     'Torres',     'diego_torres',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkZ6HQZO', 'cliente',    'diego@hotmail.com',        '221-444-1002', 1),
(8,  'Sofía',     'Martínez',   'sofia_martinez',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkZ6HQZO', 'cliente',    'sofia@gmail.com',          '221-444-1003', 1),
(9,  'Javier',    'Álvarez',    'javier_alvarez',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkZ6HQZO', 'cliente',    'javier@yahoo.com',         '221-444-1004', 1),
(10, 'Camila',    'Ruiz',       'camila_ruiz',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkZ6HQZO', 'cliente',    'camila@gmail.com',         '221-444-1005', 1),
(11, 'Pablo',     'Sánchez',    'pablo_sanchez',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkZ6HQZO', 'cliente',    'pablo@hotmail.com',        '221-444-1006', 1),
(12, 'Florencia', 'Castro',     'flor_castro',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkZ6HQZO', 'cliente',    'flor@gmail.com',           '221-444-1007', 1),
(13, 'Nicolás',   'Herrera',    'nico_herrera',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkZ6HQZO', 'cliente',    'nico@gmail.com',           '221-444-1008', 1);

-- ----------------------------------------------------------------
-- Trabajadores (detalle laboral)
-- ----------------------------------------------------------------
INSERT INTO trabajadores (id_persona, rol, tipo_de_servicio) VALUES
(1, 'admin',      'Adiestramiento Avanzado'),
(3, 'trabajador', 'Adiestramiento Básico'),
(4, 'trabajador', 'Consulta Veterinaria'),
(5, 'trabajador', 'Baño y Peluquería');

-- ----------------------------------------------------------------
-- Direcciones
-- ----------------------------------------------------------------
INSERT INTO direccion (id_persona, provincia, localidad, calle, altura) VALUES
(6,  'Buenos Aires', 'La Plata',  'Calle 7',      '1450'),
(7,  'Buenos Aires', 'La Plata',  'Av. 44',       '892'),
(8,  'Buenos Aires', 'La Plata',  'Diagonal 74',  '330'),
(9,  'Buenos Aires', 'La Plata',  'Calle 13',     '2010'),
(10, 'Buenos Aires', 'La Plata',  'Calle 60',     '765'),
(11, 'Buenos Aires', 'Berisso',   'Calle Nueva',  '1100'),
(12, 'Buenos Aires', 'La Plata',  'Av. 32',       '450'),
(13, 'Buenos Aires', 'La Plata',  'Calle 48',     '1780');

-- ================================================================
-- BLOQUE 2 — TIPOS DE SERVICIO (catálogo)
-- ================================================================
INSERT INTO tipo_de_servicio (id_tipo_servicio, tipo_de_servicio, descripcion, precio_servicio, imagen_servicio) VALUES
(1, 'Adiestramiento Básico',    'Comandos básicos de obediencia: sentado, quieto, ven, talón',  8500.00,  'dog-training.png'),
(3, 'Consulta Veterinaria',     'Revisación clínica, vacunas y desparasitación',                6500.00,  'paseo_img.png'),
(4, 'Baño y Peluquería',        'Baño completo, cepillado, corte de uñas y peluquería',         5200.00,  'banio_img.png'),
(5, 'Guardería Diaria',         'Cuidado integral por día con actividades y alimentación',       4800.00,  'paseo_img.png');

-- ================================================================
-- BLOQUE 3 — MASCOTAS
-- ================================================================
INSERT INTO mascota (id_mascota, id_persona, nombre, fecha_de_nacimiento, edad, raza, tamanio, color) VALUES
(1,  6,  'Rocky',    '2020-03-15', 5, 'Labrador',          'Grande',   'Dorado'),
(2,  6,  'Luna',     '2022-07-22', 2, 'Beagle',            'Mediano',  'Tricolor'),
(3,  7,  'Thor',     '2019-11-08', 6, 'Golden Retriever',  'Grande',   'Dorado'),
(4,  8,  'Mía',      '2021-05-30', 3, 'Bichón Frisé',      'Pequeño',  'Blanco'),
(5,  9,  'Dante',    '2018-09-12', 7, 'Pastor Alemán',     'Grande',   'Negro y fuego'),
(6,  10, 'Coco',     '2023-01-19', 1, 'Chihuahua',         'Pequeño',  'Marrón'),
(7,  11, 'Bella',    '2020-08-25', 4, 'Bulldog Francés',   'Mediano',  'Atigrado'),
(8,  12, 'Simba',    '2021-12-05', 3, 'Poodle',            'Mediano',  'Negro'),
(9,  13, 'Max',      '2022-04-17', 2, 'Rottweiler',        'Grande',   'Negro y fuego'),
(10, 7,  'Canela',   '2023-06-10', 1, 'Mestiza',           'Pequeño',  'Canela');

-- ================================================================
-- BLOQUE 4 — PROVEEDORES
-- ================================================================
INSERT INTO proveedores (id_proveedor, nombre, cuit, telefono, correo, direccion, activo) VALUES
(1, 'VetSupply Argentina',     '30-71234567-9', '0800-333-4001', 'ventas@vetsupply.com.ar',   'Av. Corrientes 1500, CABA',    1),
(2, 'PetNutrición SA',         '30-65432198-1', '011-4555-2233', 'compras@petnutricion.com.ar','Av. Rivadavia 3200, CABA',    1),
(3, 'Equipamiento Canino Pro', '30-58741236-4', '221-490-1122',  'info@equipcanino.com.ar',   'Calle 1 Nro 500, La Plata',   1),
(4, 'FarmaMascotas',           '30-47852369-7', '011-4320-8899', 'farma@farmamascotas.com.ar','Av. Santa Fe 2100, CABA',     1);

-- ================================================================
-- BLOQUE 5 — PRODUCTOS (para ventas y compras)
-- ================================================================
INSERT INTO productos (id_producto, nombre_producto, descripcion_producto, precio_unitario, tipo, activo, id_proveedor) VALUES
(1,  'Vacuna Antirrábica',         'Vacuna anual obligatoria antirrábica',                          3800.00,  'Vacuna',       1, 4),
(2,  'Vacuna Sextuple',            'Vacuna polivalente (moquillo, parvo, hepatitis, etc.)',          5200.00,  'Vacuna',       1, 4),
(3,  'Desparasitante Interno',     'Comprimidos antiparasitarios para perros de todo tamaño',       1800.00,  'Medicamento',  1, 4),
(4,  'Collar Antipulgas 6 meses',  'Collar preventivo contra pulgas y garrapatas',                  2400.00,  'Medicamento',  1, 4),
(5,  'Shampoo Medicado 250ml',     'Shampoo con clorhexidina para piel sensible',                   1200.00,  'Otro',         1, 1),
(6,  'Alimento Premium 15kg',      'Royal Canin - formulación adulto razas medianas',               22500.00, 'Otro',         1, 2),
(7,  'Alimento Cachorro 3kg',      'Pro Plan Puppy - para cachorros de razas pequeñas',             8900.00,  'Otro',         1, 2),
(8,  'Juguete Kong Relleno',       'Juguete interactivo de goma resistente',                        3200.00,  'Otro',         1, 3),
(9,  'Arnés Regulable Talla M',    'Arnés acolchado, regulable, talla mediana',                     4500.00,  'Otro',         1, 3),
(10, 'Cama Ortopédica Grande',     'Cama de espuma viscoelástica para razas grandes',               15800.00, 'Otro',         1, 3);

-- ================================================================
-- BLOQUE 6 — INSUMOS (para uso interno del centro)
-- ================================================================
INSERT INTO insumo (id_insumo, nombre_insumo, descripcion_insumo, tipo_insumo, costo_unidad, id_proveedor) VALUES
(1, 'Shampoo a granel 5L',      'Shampoo genérico para baño masivo',           'Higiene',     890.00,  1),
(2, 'Toallas de microfibra',    'Toallas para secado post-baño',               'Higiene',     450.00,  1),
(3, 'Guantes de examinación',   'Caja de 50 guantes de látex talla M',         'Veterinaria', 1200.00, 4),
(4, 'Alcohol en gel 1L',        'Sanitizante para manos del personal',         'Higiene',     380.00,  4),
(5, 'Bolsas biodegradables',    'Bolsas para residuos orgánicos (x100)',       'Limpieza',    280.00,  1),
(6, 'Desinfectante de pisos 5L','Desinfectante concentrado para pisos',        'Limpieza',    650.00,  1),
(7, 'Golosinas de entrenamiento','Premios para adiestramiento (bolsa 500g)',   'Entrenamiento', 950.00, 2),
(8, 'Clicker de entrenamiento', 'Herramienta de condicionamiento positivo',   'Entrenamiento', 320.00, 3);

-- ================================================================
-- BLOQUE 7 — INVENTARIO INICIAL
-- ================================================================
INSERT INTO inventario_insumo (id_insumo, cantidad_actual, param_bajo_stock) VALUES
(1, 8,  3),
(2, 25, 10),
(3, 4,  5),   -- bajo stock
(4, 12, 5),
(5, 30, 10),
(6, 6,  3),
(7, 15, 8),
(8, 5,  3);

INSERT INTO inventario (id_producto, cantidad_actual_producto, param_bajo_stock) VALUES
(1,  10,  5),
(2,  8,   4),
(3,  20,  8),
(4,  15,  6),
(5,  12,  5),
(6,  3,   5),   -- bajo stock
(7,  6,   4),
(8,  10,  4),
(9,  7,   3),
(10, 2,   3);   -- bajo stock

-- ================================================================
-- BLOQUE 8 — HISTORIA CLÍNICA
-- ================================================================
INSERT INTO historia_clinica (id_historia, id_mascota, fecha_apertura, observaciones_generales) VALUES
(1,  1,  '2025-01-10 09:00:00', 'Perro activo y saludable. Sin antecedentes relevantes.'),
(2,  2,  '2025-01-15 10:30:00', 'Tendencia a la ansiedad. Requiere manejo especial.'),
(3,  3,  '2025-02-01 11:00:00', 'Displasia de cadera leve. Ejercicio moderado.'),
(4,  4,  '2025-02-10 09:30:00', 'Sin antecedentes patológicos. Buena salud.'),
(5,  5,  '2025-02-20 14:00:00', 'Perro de trabajo reconvertido. Alta obediencia.'),
(6,  6,  '2025-03-05 10:00:00', 'Cachorro. Esquema de vacunación en curso.'),
(7,  7,  '2025-03-12 11:30:00', 'Problemas respiratorios leves por braquicefalia.'),
(8,  8,  '2025-03-18 09:00:00', 'Pelo rizado requiere corte mensual.'),
(9,  9,  '2025-04-02 10:00:00', 'Cachorro en etapa de socialización.'),
(10, 10, '2025-04-10 11:00:00', 'Mestiza rescatada. Carácter dócil.');

-- ================================================================
-- BLOQUE 9 — SERVICIOS / TURNOS (Enero 2026 – Mayo 2026)
-- Año 2026 para que aparezcan en el dashboard actual
-- ================================================================

-- === ENERO 2026 ===
INSERT INTO servicio (id_servicio, tipo_de_servicio, id_mascota, id_trabajador, horario, comentarios, monto, pagado) VALUES
(1,  'Adiestramiento Básico',   1, 3, '2026-01-06 10:00:00', 'Primera sesión. Responde bien.', 8500.00, 1),
(2,  'Baño y Peluquería',       4, 5, '2026-01-07 11:00:00', 'Corte estándar para bichón.',    5200.00, 1),
(3,  'Consulta Veterinaria',    6, 4, '2026-01-08 09:00:00', 'Vacuna sextuple cachorro.',      6500.00, 1),
(4,  'Adiestramiento Básico',   2, 3, '2026-01-09 10:00:00', 'Trabajo en calma y vínculo.',    8500.00, 1),
(5,  'Baño y Peluquería',       8, 5, '2026-01-13 11:00:00', 'Corte de poodle.',               5200.00, 1),
(6,  'Adiestramiento Avanzado', 5, 1, '2026-01-14 14:00:00', 'Circuito de agilidad.',         14000.00, 1),
(7,  'Consulta Veterinaria',    3, 4, '2026-01-15 09:00:00', 'Control de displasia de cadera.',6500.00, 1),
(8,  'Baño y Peluquería',       7, 5, '2026-01-16 10:00:00', 'Bulldog. Especial atención.',    5200.00, 1),
(9,  'Adiestramiento Básico',   9, 3, '2026-01-20 10:00:00', 'Inicio de adiestramiento.',      8500.00, 1),
(10, 'Guardería Diaria',        1, 3, '2026-01-21 08:00:00', 'Día completo de guardería.',     4800.00, 1),
(11, 'Guardería Diaria',        3, 3, '2026-01-22 08:00:00', 'Guardería y socialización.',     4800.00, 1),
(12, 'Consulta Veterinaria',    9, 4, '2026-01-23 09:00:00', 'Control de crecimiento.',        6500.00, 1),
(13, 'Baño y Peluquería',       4, 5, '2026-01-27 11:00:00', 'Baño mensual.',                  5200.00, 1),
(14, 'Adiestramiento Básico',   10, 3,'2026-01-28 10:00:00', 'Primera sesión mestiza.',        8500.00, 1),
(15, 'Guardería Diaria',        2, 3, '2026-01-29 08:00:00', 'Guardería diaria.',              4800.00, 0);

-- === FEBRERO 2026 ===
INSERT INTO servicio (id_servicio, tipo_de_servicio, id_mascota, id_trabajador, horario, comentarios, monto, pagado) VALUES
(16, 'Adiestramiento Básico',   1, 3, '2026-02-03 10:00:00', 'Refuerzo de comandos.',          8500.00, 1),
(17, 'Adiestramiento Avanzado', 5, 1, '2026-02-04 14:00:00', 'Pista de obstáculos.',          14000.00, 1),
(18, 'Baño y Peluquería',       8, 5, '2026-02-05 11:00:00', 'Corte poodle mensual.',          5200.00, 1),
(19, 'Consulta Veterinaria',    6, 4, '2026-02-06 09:00:00', 'Segunda vacuna de la serie.',    6500.00, 1),
(20, 'Guardería Diaria',        7, 3, '2026-02-10 08:00:00', 'Guardería Bulldog.',             4800.00, 1),
(21, 'Adiestramiento Básico',   9, 3, '2026-02-11 10:00:00', 'Obediencia básica.',             8500.00, 1),
(22, 'Baño y Peluquería',       4, 5, '2026-02-12 11:00:00', 'Baño bichón.',                   5200.00, 1),
(23, 'Consulta Veterinaria',    1, 4, '2026-02-13 09:00:00', 'Revisación anual Rocky.',        6500.00, 1),
(24, 'Adiestramiento Avanzado', 3, 1, '2026-02-17 14:00:00', 'Golden en agilidad.',           14000.00, 1),
(25, 'Baño y Peluquería',       2, 5, '2026-02-18 11:00:00', 'Baño Beagle.',                   5200.00, 1),
(26, 'Guardería Diaria',        5, 3, '2026-02-19 08:00:00', 'Guardería Pastor.',              4800.00, 1),
(27, 'Consulta Veterinaria',    7, 4, '2026-02-20 09:00:00', 'Control respiratorio.',          6500.00, 1),
(28, 'Adiestramiento Básico',   10, 3,'2026-02-24 10:00:00', 'Comandos voz alta.',             8500.00, 1),
(29, 'Baño y Peluquería',       9, 5, '2026-02-25 11:00:00', 'Baño Rottweiler.',               5200.00, 1),
(30, 'Guardería Diaria',        1, 3, '2026-02-26 08:00:00', 'Guardería Rocky.',               4800.00, 0);

-- === MARZO 2026 (mes más activo) ===
INSERT INTO servicio (id_servicio, tipo_de_servicio, id_mascota, id_trabajador, horario, comentarios, monto, pagado) VALUES
(31, 'Adiestramiento Básico',   1, 3, '2026-03-03 10:00:00', 'Seguimiento mensual.',           8500.00, 1),
(32, 'Adiestramiento Avanzado', 5, 1, '2026-03-04 14:00:00', 'Ejercicio de protección.',      14000.00, 1),
(33, 'Consulta Veterinaria',    6, 4, '2026-03-05 09:00:00', 'Desparasitación interna.',       6500.00, 1),
(34, 'Baño y Peluquería',       4, 5, '2026-03-06 11:00:00', 'Corte estético bichón.',         5200.00, 1),
(35, 'Guardería Diaria',        2, 3, '2026-03-10 08:00:00', 'Guardería Beagle.',              4800.00, 1),
(36, 'Adiestramiento Básico',   9, 3, '2026-03-11 10:00:00', 'Sociabilización grupo.',         8500.00, 1),
(37, 'Baño y Peluquería',       8, 5, '2026-03-12 11:00:00', 'Corte poodle.',                  5200.00, 1),
(38, 'Consulta Veterinaria',    3, 4, '2026-03-13 09:00:00', 'Seguimiento displasia.',         6500.00, 1),
(39, 'Adiestramiento Avanzado', 3, 1, '2026-03-17 14:00:00', 'Golden retriever avanzado.',    14000.00, 1),
(40, 'Baño y Peluquería',       7, 5, '2026-03-18 11:00:00', 'Baño bulldog.',                  5200.00, 1),
(41, 'Guardería Diaria',        5, 3, '2026-03-19 08:00:00', 'Guardería Pastor Alemán.',       4800.00, 1),
(42, 'Consulta Veterinaria',    10, 4,'2026-03-20 09:00:00', 'Control mestiza.',               6500.00, 1),
(43, 'Adiestramiento Básico',   2, 3, '2026-03-24 10:00:00', 'Técnica de jaula.',              8500.00, 1),
(44, 'Baño y Peluquería',       1, 5, '2026-03-25 11:00:00', 'Baño Labrador.',                 5200.00, 1),
(45, 'Guardería Diaria',        9, 3, '2026-03-26 08:00:00', 'Guardería Rottweiler.',          4800.00, 1),
(46, 'Consulta Veterinaria',    4, 4, '2026-03-27 09:00:00', 'Vacuna anual bichón.',           6500.00, 1),
(47, 'Adiestramiento Básico',   7, 3, '2026-03-31 10:00:00', 'Orden y respeto.',               8500.00, 1);

-- === ABRIL 2026 ===
INSERT INTO servicio (id_servicio, tipo_de_servicio, id_mascota, id_trabajador, horario, comentarios, monto, pagado) VALUES
(48, 'Adiestramiento Básico',   1, 3, '2026-04-01 10:00:00', 'Nivel 2 de adiestramiento.',    8500.00,  1),
(49, 'Adiestramiento Avanzado', 5, 1, '2026-04-02 14:00:00', 'Exposición de habilidades.',   14000.00,  1),
(50, 'Baño y Peluquería',       4, 5, '2026-04-03 11:00:00', 'Baño mensual bichón.',           5200.00,  1),
(51, 'Consulta Veterinaria',    6, 4, '2026-04-07 09:00:00', 'Coloca antiparasitario.',        6500.00,  1),
(52, 'Guardería Diaria',        3, 3, '2026-04-08 08:00:00', 'Guardería Golden.',              4800.00,  1),
(53, 'Adiestramiento Básico',   10,3, '2026-04-09 10:00:00', 'Ejercicios de foco.',            8500.00,  1),
(54, 'Baño y Peluquería',       8, 5, '2026-04-10 11:00:00', 'Corte poodle.',                  5200.00,  1),
(55, 'Consulta Veterinaria',    9, 4, '2026-04-14 09:00:00', 'Revisión preventiva.',           6500.00,  1),
(56, 'Adiestramiento Avanzado', 3, 1, '2026-04-15 14:00:00', 'Agility avanzado.',            14000.00,  1),
(57, 'Baño y Peluquería',       2, 5, '2026-04-16 11:00:00', 'Baño Beagle.',                   5200.00,  1),
(58, 'Guardería Diaria',        7, 3, '2026-04-17 08:00:00', 'Guardería Bulldog.',             4800.00,  1),
(59, 'Consulta Veterinaria',    5, 4, '2026-04-22 09:00:00', 'Control anual Pastor.',          6500.00,  1),
(60, 'Adiestramiento Básico',   9, 3, '2026-04-23 10:00:00', 'Ejercicios externos.',           8500.00,  1),
(61, 'Baño y Peluquería',       1, 5, '2026-04-24 11:00:00', 'Baño Labrador.',                 5200.00,  1),
(62, 'Guardería Diaria',        10,3, '2026-04-28 08:00:00', 'Guardería mestiza.',             4800.00,  1),
(63, 'Consulta Veterinaria',    8, 4, '2026-04-29 09:00:00', 'Revisación preventiva.',         6500.00,  1);

-- === MAYO 2026 ===
INSERT INTO servicio (id_servicio, tipo_de_servicio, id_mascota, id_trabajador, horario, comentarios, monto, pagado) VALUES
(64, 'Adiestramiento Avanzado', 5, 1, '2026-05-05 14:00:00', 'Presentación final de nivel.', 14000.00,  1),
(65, 'Baño y Peluquería',       4, 5, '2026-05-06 11:00:00', 'Baño mensual bichón.',           5200.00,  1),
(66, 'Consulta Veterinaria',    6, 4, '2026-05-07 09:00:00', 'Tercera dosis cachorros.',       6500.00,  1),
(67, 'Adiestramiento Básico',   1, 3, '2026-05-08 10:00:00', 'Mantenimiento de hábitos.',      8500.00,  1),
(68, 'Guardería Diaria',        2, 3, '2026-05-12 08:00:00', 'Guardería Beagle.',              4800.00,  1),
(69, 'Baño y Peluquería',       8, 5, '2026-05-13 11:00:00', 'Corte poodle.',                  5200.00,  1),
(70, 'Consulta Veterinaria',    3, 4, '2026-05-14 09:00:00', 'Control anual Golden.',          6500.00,  1),
(71, 'Adiestramiento Básico',   9, 3, '2026-05-15 10:00:00', 'Refuerzo de obediencia.',        8500.00,  1),
(72, 'Adiestramiento Avanzado', 3, 1, '2026-05-19 14:00:00', 'Agilidad nivel 3.',            14000.00,  1),
(73, 'Baño y Peluquería',       7, 5, '2026-05-20 11:00:00', 'Baño Bulldog.',                  5200.00,  1),
(74, 'Guardería Diaria',        5, 3, '2026-05-21 08:00:00', 'Guardería Pastor.',              4800.00,  1),
(75, 'Consulta Veterinaria',    10,4, '2026-05-22 09:00:00', 'Control anual.',                 6500.00,  1),
(76, 'Adiestramiento Básico',   7, 3, '2026-05-26 10:00:00', 'Técnica positiva.',              8500.00,  1),
(77, 'Baño y Peluquería',       1, 5, '2026-05-27 11:00:00', 'Baño Labrador.',                 5200.00,  1),
(78, 'Guardería Diaria',        9, 3, '2026-05-28 08:00:00', 'Guardería Rottweiler.',          4800.00,  1),
(79, 'Consulta Veterinaria',    4, 4, '2026-05-29 09:00:00', 'Revisación preventiva.',         6500.00,  1),
-- Turno sin pagar (mes actual) para mostrar pendiente
(80, 'Adiestramiento Avanzado', 5, 1, '2026-05-30 14:00:00', 'Sesión pendiente de pago.',    14000.00,  0);

-- === JUNIO 2026 (mes actual) ===
INSERT INTO servicio (id_servicio, tipo_de_servicio, id_mascota, id_trabajador, horario, comentarios, monto, pagado) VALUES
(81, 'Adiestramiento Básico',   1, 3, '2026-06-02 10:00:00', 'Inicio junio.',                  8500.00,  1),
(82, 'Baño y Peluquería',       4, 5, '2026-06-03 11:00:00', 'Baño junio.',                    5200.00,  1),
(83, 'Consulta Veterinaria',    6, 4, '2026-06-04 09:00:00', 'Control cachorro.',              6500.00,  1),
(84, 'Adiestramiento Avanzado', 5, 1, '2026-06-05 14:00:00', 'Inicio nivel avanzado.',        14000.00,  1),
(85, 'Guardería Diaria',        3, 3, '2026-06-09 08:00:00', 'Guardería.',                     4800.00,  0),
-- Turnos futuros (pendientes)
(86, 'Adiestramiento Básico',   9, 3, '2026-06-10 10:00:00', 'Turno próximo.',                 8500.00,  0),
(87, 'Consulta Veterinaria',    7, 4, '2026-06-11 09:00:00', 'Turno próximo.',                 6500.00,  0),
(88, 'Baño y Peluquería',       8, 5, '2026-06-12 11:00:00', 'Turno próximo.',                 5200.00,  0);

-- ================================================================
-- BLOQUE 10 — VENTAS DE PRODUCTOS
-- ================================================================

-- Enero 2026
INSERT INTO ventas (id_venta, total, id_persona, id_mascota, fecha) VALUES
(1,  7000.00,  6,  1, '2026-01-08 10:30:00'),
(2,  24300.00, 7,  3, '2026-01-14 12:00:00'),
(3,  5400.00,  8,  4, '2026-01-20 11:00:00'),
(4,  6000.00,  9,  5, '2026-01-22 10:00:00'),
(5,  9700.00,  10, 6, '2026-01-28 14:00:00');

INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, subtotal) VALUES
(1,  1, 1, 3800.00, 3800.00),
(1,  3, 1, 1800.00, 1800.00),
(1,  5, 1, 1200.00, 1200.00),
(2,  6, 1, 22500.00, 22500.00),
(2,  8, 1, 3200.00, 3200.00),
(3,  4, 1, 2400.00, 2400.00),
(3,  5, 2, 1200.00, 2400.00),
(3,  3, 1, 1800.00,  600.00),
(4,  9, 1, 4500.00, 4500.00),
(4,  8, 1, 3200.00, 1500.00),
(5,  7, 1, 8900.00, 8900.00),
(5,  5, 1, 1200.00,  800.00);

-- Febrero 2026
INSERT INTO ventas (id_venta, total, id_persona, id_mascota, fecha) VALUES
(6,  5200.00, 11, 7,  '2026-02-10 10:00:00'),
(7,  8900.00, 12, 8,  '2026-02-14 11:30:00'),
(8,  22500.00, 6, 1,  '2026-02-18 12:00:00'),
(9,  5000.00, 13, 9,  '2026-02-25 10:30:00');

INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, subtotal) VALUES
(6,  2, 1, 5200.00, 5200.00),
(7,  7, 1, 8900.00, 8900.00),
(8,  6, 1, 22500.00, 22500.00),
(9,  9, 1, 4500.00, 4500.00),
(9,  5, 1, 1200.00,  500.00);

-- Marzo 2026
INSERT INTO ventas (id_venta, total, id_persona, id_mascota, fecha) VALUES
(10, 27700.00, 7,  3,  '2026-03-05 10:00:00'),
(11, 10400.00, 8,  4,  '2026-03-10 11:00:00'),
(12, 3200.00,  9,  5,  '2026-03-15 12:00:00'),
(13, 8900.00,  10, 6,  '2026-03-20 10:00:00'),
(14, 6000.00,  11, 7,  '2026-03-26 14:00:00'),
(15, 18000.00, 6,  1,  '2026-03-31 11:00:00');

INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, subtotal) VALUES
(10, 6, 1, 22500.00, 22500.00),
(10, 9, 1, 4500.00,   4500.00),
(10, 5, 1, 1200.00,    700.00),
(11, 2, 1, 5200.00,   5200.00),
(11, 1, 1, 3800.00,   3800.00),
(11, 3, 1, 1800.00,   1400.00),
(12, 8, 1, 3200.00,   3200.00),
(13, 7, 1, 8900.00,   8900.00),
(14, 4, 2, 2400.00,   4800.00),
(14, 5, 1, 1200.00,   1200.00),
(15, 6, 1, 22500.00, 18000.00);

-- Abril 2026
INSERT INTO ventas (id_venta, total, id_persona, id_mascota, fecha) VALUES
(16, 5600.00,  12, 8,  '2026-04-07 10:00:00'),
(17, 22500.00, 7,  3,  '2026-04-14 11:00:00'),
(18, 7200.00,  13, 9,  '2026-04-21 12:00:00'),
(19, 3800.00,  9,  5,  '2026-04-28 10:00:00');

INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, subtotal) VALUES
(16, 3, 2, 1800.00,  3600.00),
(16, 4, 1, 2400.00,  2400.00),
(17, 6, 1, 22500.00, 22500.00),
(18, 9, 1, 4500.00,  4500.00),
(18, 5, 2, 1200.00,  2400.00),
(18, 3, 1, 1800.00,   300.00),
(19, 1, 1, 3800.00,  3800.00);

-- Mayo 2026
INSERT INTO ventas (id_venta, total, id_persona, id_mascota, fecha) VALUES
(20, 15800.00, 8,  4,  '2026-05-06 10:00:00'),
(21, 8900.00,  6,  1,  '2026-05-13 11:00:00'),
(22, 5200.00,  11, 7,  '2026-05-20 12:00:00'),
(23, 6000.00,  7,  10, '2026-05-27 10:00:00');

INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, subtotal) VALUES
(20, 10, 1, 15800.00, 15800.00),
(21, 7,  1, 8900.00,   8900.00),
(22, 2,  1, 5200.00,   5200.00),
(23, 9,  1, 4500.00,   4500.00),
(23, 5,  1, 1200.00,   1200.00),
(23, 3,  1, 1800.00,    300.00);

-- Junio 2026 (mes actual)
INSERT INTO ventas (id_venta, total, id_persona, id_mascota, fecha) VALUES
(24, 22500.00, 6, 1, '2026-06-02 10:00:00'),
(25, 5200.00, 12, 8, '2026-06-03 11:00:00');

INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, subtotal) VALUES
(24, 6, 1, 22500.00, 22500.00),
(25, 2, 1, 5200.00, 5200.00);

-- ================================================================
-- BLOQUE 11 — COMPRAS A PROVEEDORES (costos)
-- ================================================================

-- Enero 2026
INSERT INTO compras (id_compra, id_proveedor, fecha_compra, total, observaciones) VALUES
(1, 4, '2026-01-05 09:00:00', 28000.00, 'Reposición vacunas y medicamentos enero'),
(2, 2, '2026-01-12 10:00:00', 67500.00, 'Stock alimento Premium y Cachorro');

INSERT INTO compra_detalle (id_compra, id_producto, cantidad, precio_unitario) VALUES
(1, 1, 5, 2800.00),   -- Vacuna antirrábica
(1, 2, 3, 4000.00),   -- Vacuna sextuple
(1, 3, 2, 1500.00),   -- Desparasitante
(2, 6, 2, 18500.00),  -- Alimento Premium
(2, 7, 3, 7200.00);   -- Alimento cachorro

-- Febrero 2026
INSERT INTO compras (id_compra, id_proveedor, fecha_compra, total, observaciones) VALUES
(3, 1, '2026-02-03 09:00:00', 15600.00, 'Insumos de higiene y peluquería'),
(4, 4, '2026-02-17 10:00:00', 20800.00, 'Reposición medicamentos');

INSERT INTO compra_detalle (id_compra, id_producto, cantidad, precio_unitario) VALUES
(3, 5, 8, 950.00),    -- Shampoo medicado
(3, 4, 3, 1800.00),   -- Collar antipulgas
(4, 1, 4, 2800.00),   -- Vacuna antirrábica
(4, 3, 5, 1500.00);   -- Desparasitante

-- Marzo 2026
INSERT INTO compras (id_compra, id_proveedor, fecha_compra, total, observaciones) VALUES
(5, 2, '2026-03-04 09:00:00', 89500.00, 'Gran stock alimentos marzo'),
(6, 3, '2026-03-18 10:00:00', 35000.00, 'Equipamiento y accesorios');

INSERT INTO compra_detalle (id_compra, id_producto, cantidad, precio_unitario) VALUES
(5, 6, 3, 18500.00),  -- Alimento Premium
(5, 7, 5, 7200.00),   -- Cachorro
(6, 8, 5, 2500.00),   -- Juguete Kong
(6, 9, 3, 3500.00),   -- Arnés
(6, 10, 1, 12000.00); -- Cama ortopédica

-- Abril 2026
INSERT INTO compras (id_compra, id_proveedor, fecha_compra, total, observaciones) VALUES
(7, 4, '2026-04-08 09:00:00', 25200.00, 'Vacunas y medicamentos abril'),
(8, 1, '2026-04-22 10:00:00', 12000.00, 'Insumos generales');

INSERT INTO compra_detalle (id_compra, id_producto, cantidad, precio_unitario) VALUES
(7, 1, 5, 2800.00),   -- Vacuna antirrábica
(7, 2, 2, 4000.00),   -- Vacuna sextuple
(7, 4, 3, 1800.00),   -- Collar antipulgas
(8, 5, 6, 950.00),    -- Shampoo
(8, 3, 3, 1500.00);   -- Desparasitante

-- Mayo 2026
INSERT INTO compras (id_compra, id_proveedor, fecha_compra, total, observaciones) VALUES
(9, 2, '2026-05-06 09:00:00', 45000.00, 'Reposición alimentos mayo'),
(10, 3, '2026-05-20 10:00:00', 19600.00, 'Accesorios y juguetes');

INSERT INTO compra_detalle (id_compra, id_producto, cantidad, precio_unitario) VALUES
(9,  6, 2, 18500.00),  -- Alimento Premium
(9,  7, 1, 7200.00),   -- Cachorro
(10, 8, 4, 2500.00),   -- Kong
(10, 9, 2, 3500.00);   -- Arnés

-- Junio 2026
INSERT INTO compras (id_compra, id_proveedor, fecha_compra, total, observaciones) VALUES
(11, 4, '2026-06-02 09:00:00', 18000.00, 'Compra inicial junio - medicamentos');

INSERT INTO compra_detalle (id_compra, id_producto, cantidad, precio_unitario) VALUES
(11, 1, 4, 2800.00),
(11, 2, 1, 4000.00),
(11, 3, 3, 1500.00);

-- ================================================================
-- BLOQUE 12 — COSTOS MANUALES DE RENTABILIDAD
-- (sueldos del personal + otros gastos operativos)
-- ================================================================
INSERT INTO rentabilidad (periodo_anio, periodo_mes, costo_sueldos, costo_otros, notas) VALUES
(2026, 1, 75000.00, 12500.00, 'Ene: Sueldos 3 empleados + admin parcial. Servicios y alquiler.'),
(2026, 2, 75000.00, 11800.00, 'Feb: Mes corto. Gas aumentó. Mantenimiento instalaciones $5.500.'),
(2026, 3, 82000.00, 18200.00, 'Mar: Horas extra por alto volumen. Publicidad redes $12.000, limpieza extra $8.000.'),
(2026, 4, 75000.00, 12500.00, 'Abr: Normal. Renovación seguro $8.000.'),
(2026, 5, 75000.00, 14700.00, 'May: Capacitación personal $9.500, mantenimiento equipos $6.200.'),
(2026, 6, 75000.00,  9000.00, 'Jun: Mes en curso. Estimación parcial.');

-- ================================================================
-- BLOQUE 13 — ATENCIONES CLÍNICAS
-- ================================================================
INSERT INTO atencion_clinica (id_historia, id_servicio, id_profesional, fecha_atencion, motivo_consulta, diagnostico, tratamiento) VALUES
(6,  3,  4, '2026-01-08 09:00:00', 'Vacunación cachorro',         'Cachorro sano. Inicio esquema.',         'Vacuna sextuple. Próxima en 21 días.'),
(3,  7,  4, '2026-01-15 09:00:00', 'Control displasia',           'Displasia leve estable.',                'Medicación antiinflamatoria. Reposo relativo.'),
(9,  12, 4, '2026-01-23 09:00:00', 'Control crecimiento',         'Desarrollo normal para la raza.',        'Sin intervención. Seguimiento a los 6 meses.'),
(6,  19, 4, '2026-02-06 09:00:00', 'Segunda vacuna cachorro',     'Respuesta inmune correcta.',             'Segunda dosis sextuple. Antiparasitario interno.'),
(1,  23, 4, '2026-02-13 09:00:00', 'Revisación anual',            'Buen estado general.',                   'Vacuna antirrábica. Desparasitación externa.'),
(3,  38, 4, '2026-03-13 09:00:00', 'Seguimiento displasia',       'Mejoría leve. Músculo más desarrollado.','Continuar fisioterapia. Natación recomendada.'),
(4,  46, 4, '2026-03-27 09:00:00', 'Vacuna anual bichón',         'Peso ideal. Sin hallazgos.',             'Vacuna sextuple y antirrábica.'),
(6,  51, 4, '2026-04-07 09:00:00', 'Antiparasitario trimestral',  'Salud correcta.',                        'Collar antipulgas y desparasitante oral.'),
(9,  55, 4, '2026-04-14 09:00:00', 'Revisión preventiva',         'Cachorro saludable.',                    'Sin intervención. Vacunación completada.');

-- ================================================================
-- BLOQUE 14 — POSTULACIONES (trabaja con nosotros)
-- ================================================================
INSERT INTO postulaciones (nombre, apellido, correo, puesto_aplicado, fecha_postulacion) VALUES
('Tomás',    'Vidal',    'tomas.vidal@gmail.com',   'Adiestrador Canino',   '2026-01-15 10:00:00'),
('Romina',   'Castro',   'romina.c@hotmail.com',    'Peluquero/a Canino',   '2026-02-03 14:30:00'),
('Ezequiel', 'Morales',  'eze.morales@gmail.com',   'Veterinario/a',        '2026-03-10 09:15:00'),
('Laura',    'Benítez',  'laura.benitez@yahoo.com', 'Recepcionista',        '2026-04-22 11:00:00'),
('Gonzalo',  'Fernández','gonza.fern@gmail.com',    'Adiestrador Canino',   '2026-05-05 16:45:00');

-- ================================================================
-- FIN DEL SCRIPT DE DATOS DEMO
-- ================================================================
-- Resumen de datos insertados:
--   • Personas:       13 (1 admin, 1 gestor, 3 trabajadores, 8 clientes)
--   • Mascotas:       10
--   • Tipos Servicio: 5
--   • Servicios:      88 turnos (Ene-Jun 2026)
--   • Ventas:         25 ventas con detalle
--   • Compras:        11 órdenes de compra con detalle
--   • Rentabilidad:   6 meses de costos manuales registrados
--   • Proveedores:    4
--   • Productos:      10
--   • Insumos:        8
--   • Postulaciones:  5
-- ================================================================

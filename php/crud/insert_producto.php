<?php
require_once __DIR__ . '/../../config.php';

require('conexion.php');

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// =======================
// 1. Obtener datos
// =======================

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio_unitario'];
$tipo = $_POST['tipo'];
$param_bajo_stock = $_POST['param_bajo_stock'];

// =======================
// 2. Manejo de imagen
// =======================

$nombreArchivo = null;
$directorio = BASE_PATH . "/uploads/productos/";

// Crear carpeta si no existe
if (!is_dir($directorio)) {
    mkdir($directorio, 0777, true);
}

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {

    $archivoTmp = $_FILES['imagen']['tmp_name'];
    $nombreOriginal = $_FILES['imagen']['name'];

    $ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

    // Validaciones básicas
    $permitidos = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $permitidos)) {
        die("Formato de imagen no permitido");
    }

    if ($_FILES['imagen']['size'] > 2 * 1024 * 1024) {
        die("La imagen supera el tamaño permitido (2MB)");
    }

    // Nombre único
    $nombreArchivo = uniqid("prod_") . "." . $ext;

    move_uploaded_file($archivoTmp, $directorio . $nombreArchivo);
}

// =======================
// 3. Insertar producto
// =======================

$sqlProducto = "INSERT INTO productos (nombre_producto, descripcion_producto, precio_unitario, imagen_producto, tipo, activo)
                VALUES (?, ?, ?, ?, ?,1)";

$stmt = $conn->prepare($sqlProducto);
$stmt->bind_param("ssdss", $nombre, $descripcion, $precio, $nombreArchivo, $tipo);

if (!$stmt->execute()) {
    die("Error al insertar producto: " . $stmt->error);
}

// Obtener ID del producto recién creado
$id_producto = $stmt->insert_id;

// =======================
// 4. Insertar en inventario
// =======================

$sqlInventario = "INSERT INTO inventario (id_producto, cantidad_actual_producto, param_bajo_stock)
                  VALUES (?, 0, ?)";

$stmt2 = $conn->prepare($sqlInventario);
$stmt2->bind_param("ii", $id_producto, $param_bajo_stock);

if (!$stmt2->execute()) {
    die("Error al insertar inventario: " . $stmt2->error);
}

// =======================
// 5. Redirección
// =======================

header("Location: " . BASE_URL . "/php/gestor_inventario/gestion_productos.php?success=1");
exit();

?>
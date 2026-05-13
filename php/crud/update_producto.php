<?php

require_once __DIR__ . '/../../config.php';
require('conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // =========================
    // Obtener datos del formulario
    // =========================

    $id_producto = $_POST['id_producto'] ?? null;

    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');

    $precio_unitario = $_POST['precio_unitario'] ?? null;

    // Inventario
    $param_bajo_stock = $_POST['param_bajo_stock'] ?? null;
    $activo = $_POST['activo'] ?? null;

    // =========================
    // Validaciones básicas
    // =========================

    if (
        empty($id_producto) ||
        empty($nombre) ||
        empty($descripcion) ||
        empty($tipo) ||
        !is_numeric($precio_unitario) ||
        !is_numeric($param_bajo_stock) ||
        !is_numeric($activo)
    ) {
        die("Datos inválidos.");
    }

    try {

        // =========================
        // Iniciar transacción
        // =========================

        $conn->begin_transaction();

        // =========================
        // Manejo de imagen
        // =========================

        $nombreArchivo = null;
        $hayNuevaImagen = false;

        if (
            isset($_FILES['imagen']) &&
            $_FILES['imagen']['error'] === 0
        ) {

            $directorio = BASE_PATH . "/uploads/productos/";

            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }

            $archivoTmp = $_FILES['imagen']['tmp_name'];
            $nombreOriginal = $_FILES['imagen']['name'];

            $ext = strtolower(
                pathinfo($nombreOriginal, PATHINFO_EXTENSION)
            );

            $permitidos = [
                'jpg',
                'jpeg',
                'png',
                'webp',
                'jfif'
            ];

            $ext = trim(strtolower($ext));

            if (!in_array($ext, $permitidos)) {

                throw new Exception(
                    "Formato de imagen no permitido: " . $ext
                );
            }

            if (!in_array($ext, $permitidos)) {
                throw new Exception(
                    "Formato de imagen no permitido"
                );
            }

            if ($_FILES['imagen']['size'] > 2 * 1024 * 1024) {
                throw new Exception(
                    "La imagen supera los 2MB"
                );
            }

            $nombreArchivo = uniqid("prod_") . "." . $ext;

            if (
                !move_uploaded_file(
                    $archivoTmp,
                    $directorio . $nombreArchivo
                )
            ) {
                throw new Exception(
                    "Error al subir imagen"
                );
            }

            $hayNuevaImagen = true;
        }

        // =========================
        // ACTUALIZAR PRODUCTO
        // =========================

        $sinProveedor = empty($_POST['id_proveedor']);

        // =====================================================
        // CASO 1:
        // SIN proveedor + SIN imagen
        // =====================================================

        if ($sinProveedor && !$hayNuevaImagen) {

            $stmtProducto = $conn->prepare("
                UPDATE productos
                SET
                    nombre_producto = ?,
                    descripcion_producto = ?,
                    tipo = ?,
                    precio_unitario = ?,
                    activo = ?,
                    id_proveedor = NULL
                WHERE id_producto = ?
            ");

            $stmtProducto->bind_param(
                "sssdii",
                $nombre,
                $descripcion,
                $tipo,
                $precio_unitario,
                $activo,
                $id_producto
            );
        }

        // =====================================================
        // CASO 2:
        // SIN proveedor + CON imagen
        // =====================================================

        elseif ($sinProveedor && $hayNuevaImagen) {

            $stmtProducto = $conn->prepare("
                UPDATE productos
                SET
                    nombre_producto = ?,
                    descripcion_producto = ?,
                    tipo = ?,
                    precio_unitario = ?,
                    activo = ?,
                    imagen_producto = ?,
                    id_proveedor = NULL
                WHERE id_producto = ?
            ");

            $stmtProducto->bind_param(
                "sssdisi",
                $nombre,
                $descripcion,
                $tipo,
                $precio_unitario,
                $activo,
                $nombreArchivo,
                $id_producto
            );
        }

        // =====================================================
        // CASO 3:
        // CON proveedor + SIN imagen
        // =====================================================

        elseif (!$sinProveedor && !$hayNuevaImagen) {

            $id_proveedor = (int) $_POST['id_proveedor'];

            $stmtProducto = $conn->prepare("
                UPDATE productos
                SET
                    nombre_producto = ?,
                    descripcion_producto = ?,
                    tipo = ?,
                    precio_unitario = ?,
                    activo = ?,
                    id_proveedor = ?
                WHERE id_producto = ?
            ");

            $stmtProducto->bind_param(
                "sssdiii",
                $nombre,
                $descripcion,
                $tipo,
                $precio_unitario,
                $activo,
                $id_proveedor,
                $id_producto
            );
        }

        // =====================================================
        // CASO 4:
        // CON proveedor + CON imagen
        // =====================================================

        else {

            $id_proveedor = (int) $_POST['id_proveedor'];

            $stmtProducto = $conn->prepare("
                UPDATE productos
                SET
                    nombre_producto = ?,
                    descripcion_producto = ?,
                    tipo = ?,
                    precio_unitario = ?,
                    activo = ?,
                    imagen_producto = ?,
                    id_proveedor = ?
                WHERE id_producto = ?
            ");

            $stmtProducto->bind_param(
                "sssdissi",
                $nombre,
                $descripcion,
                $tipo,
                $precio_unitario,
                $activo,
                $nombreArchivo,
                $id_proveedor,
                $id_producto
            );
        }

        // =========================
        // Ejecutar update producto
        // =========================

        if (!$stmtProducto->execute()) {
            throw new Exception(
                "Error al actualizar producto: " .
                $stmtProducto->error
            );
        }

        // =========================
        // Actualizar inventario
        // =========================

        $stmtInventario = $conn->prepare("
            UPDATE inventario
            SET
                param_bajo_stock = ?
            WHERE id_producto = ?
        ");

        $stmtInventario->bind_param(
            "ii",
            $param_bajo_stock,
            $id_producto
        );

        if (!$stmtInventario->execute()) {
            throw new Exception(
                "Error al actualizar inventario: " .
                $stmtInventario->error
            );
        }

        // =========================
        // Confirmar cambios
        // =========================

        $conn->commit();

        header(
            "Location: ../gestor_inventario/inventario_productos.php?success=1"
        );

        exit();

    } catch (Exception $e) {

        $conn->rollback();

        die(
            "Error al actualizar el producto: " .
            $e->getMessage()
        );
    }

} else {

    die("Método no permitido.");
}
?>
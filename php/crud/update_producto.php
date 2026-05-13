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

    // Campo de inventario
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
        // Actualizar tabla productos
        // =========================

        if (empty($_POST['id_proveedor'])) {

            // SIN proveedor

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

            if (!$stmtProducto) {
                throw new Exception(
                    "Error en prepare producto: " . $conn->error
                );
            }

            $stmtProducto->bind_param(
                "sssdii",
                $nombre,
                $descripcion,
                $tipo,
                $precio_unitario,
                $activo,
                $id_producto
            );

        } else {

            // CON proveedor

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

            if (!$stmtProducto) {
                throw new Exception(
                    "Error en prepare producto: " . $conn->error
                );
            }

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

        // Ejecutar update producto
        if (!$stmtProducto->execute()) {
            throw new Exception(
                "Error al actualizar producto: " .
                $stmtProducto->error
            );
        }

        // =========================
        // Actualizar tabla inventario
        // =========================

        $stmtInventario = $conn->prepare("
            UPDATE inventario
            SET
                param_bajo_stock = ?
            WHERE id_producto = ?
        ");

        if (!$stmtInventario) {
            throw new Exception(
                "Error en prepare inventario: " .
                $conn->error
            );
        }

        $stmtInventario->bind_param(
            "ii",
            $param_bajo_stock,
            $id_producto
        );

        // Ejecutar update inventario
        if (!$stmtInventario->execute()) {
            throw new Exception(
                "Error al actualizar inventario: " .
                $stmtInventario->error
            );
        }

        // =========================
        // Confirmar transacción
        // =========================

        $conn->commit();

        // =========================
        // Redirección
        // =========================

        header(
            "Location: ../gestor_inventario/inventario_productos.php?success=1"
        );

        exit();

    } catch (Exception $e) {

        // Revertir cambios
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
<?php
require_once __DIR__ . '/../../config.php';

require('conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // =========================
    // Obtener datos del formulario
    // =========================

    $nombre = trim($_POST['nombre']) ?? '';
    $descripcion = trim($_POST['descripcion']) ?? '';
    $tipo = trim($_POST['tipo']) ?? '';
    $costo = $_POST['costo_unidad'] ?? null;

    // cantidad mínima aceptable
    $param_bajo_stock = $_POST['param_bajo_stock'] ?? null;

    // =========================
    // Validaciones básicas
    // =========================

    if (
        empty($nombre) ||
        empty($descripcion) ||
        empty($tipo) ||
        !is_numeric($costo)
    ) {
        die("Datos inválidos.");
    }

    try {

        // =========================
        // Iniciar transacción
        // =========================

        $conn->begin_transaction();

        // =========================
        // Insertar insumo
        // =========================

        $stmt = $conn->prepare("
            UPDATE insumo SET
                nombre_insumo = ?,
                descripcion_insumo = ?,
                tipo_insumo = ?,
                costo_unidad = ?
            WHERE id_insumo = ?
        ");

        $stmt->bind_param(
            "sssdi",
            $nombre,
            $descripcion,
            $tipo,
            $costo,
            $_POST['id_insumo']
        );

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar insumo: " . $stmt->error);
        }

        // =========================
        // Confirmar transacción
        // =========================

        $conn->commit();

        header("Location: ../gestor_inventario/inventario_insumos.php?success=1");
    } catch (Exception $e) {
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
} else {
    die("Método no permitido.");
}

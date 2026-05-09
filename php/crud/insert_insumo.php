<?php

require_once __DIR__ . '/../../config.php';
require_once(BASE_PATH . '/php/crud/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // =========================
    // Obtener datos del formulario
    // =========================

    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $tipo = trim($_POST['tipo']);
    $costo = $_POST['costo_unidad'];

    // cantidad mínima aceptable
    $param_bajo_stock = $_POST['param_bajo_stock'];

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
            INSERT INTO insumo
            (
                nombre_insumo,
                descripcion_insumo,
                tipo_insumo,
                costo_unidad
            )
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssd",
            $nombre,
            $descripcion,
            $tipo,
            $costo
        );

        $stmt->execute();

        // Obtener ID generado
        $id_insumo = $conn->insert_id;

        // =========================
        // Insertar inventario
        // =========================

        $stmt2 = $conn->prepare("
            INSERT INTO inventario_insumo
            (
                id_insumo,
                cantidad_actual,
                param_bajo_stock
            )
            VALUES (?, 0, ?)
        ");

        $stmt2->bind_param(
            "ii",
            $id_insumo,
            $param_bajo_stock
        );

        $stmt2->execute();

        // =========================
        // Confirmar transacción
        // =========================

        $conn->commit();

        header("Location: ../gestor_inventario/gestion_insumos.php?success=1");
        exit;

    } catch (Exception $e) {

        // Revertir cambios si algo falla
        $conn->rollback();

        echo "Error al insertar insumo: " . $e->getMessage();
    }
}
?>
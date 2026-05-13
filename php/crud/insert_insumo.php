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

    // Cantidad mínima aceptable
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

        if (empty($_POST['id_proveedor'])) {

            // SIN proveedor

            $sqlInsumo = "
                INSERT INTO insumo
                (
                    nombre_insumo,
                    descripcion_insumo,
                    tipo_insumo,
                    costo_unidad,
                    id_proveedor
                )
                VALUES (?, ?, ?, ?, NULL)
            ";

            $stmt = $conn->prepare($sqlInsumo);

            if (!$stmt) {
                throw new Exception(
                    "Error en prepare insumo: " . $conn->error
                );
            }

            $stmt->bind_param(
                "sssd",
                $nombre,
                $descripcion,
                $tipo,
                $costo
            );

        } else {

            // CON proveedor

            $id_proveedor = (int) $_POST['id_proveedor'];

            $sqlInsumo = "
                INSERT INTO insumo
                (
                    nombre_insumo,
                    descripcion_insumo,
                    tipo_insumo,
                    costo_unidad,
                    id_proveedor
                )
                VALUES (?, ?, ?, ?, ?)
            ";

            $stmt = $conn->prepare($sqlInsumo);

            if (!$stmt) {
                throw new Exception(
                    "Error en prepare insumo: " . $conn->error
                );
            }

            $stmt->bind_param(
                "sssdi",
                $nombre,
                $descripcion,
                $tipo,
                $costo,
                $id_proveedor
            );
        }

        // =========================
        // Ejecutar insert insumo
        // =========================

        if (!$stmt->execute()) {
            throw new Exception(
                "Error al insertar insumo: " . $stmt->error
            );
        }

        // Obtener ID generado
        $id_insumo = $stmt->insert_id;

        // =========================
        // Insertar inventario
        // =========================

        $sqlInventario = "
            INSERT INTO inventario_insumo
            (
                id_insumo,
                cantidad_actual,
                param_bajo_stock
            )
            VALUES (?, 0, ?)
        ";

        $stmt2 = $conn->prepare($sqlInventario);

        if (!$stmt2) {
            throw new Exception(
                "Error en prepare inventario: " . $conn->error
            );
        }

        $stmt2->bind_param(
            "ii",
            $id_insumo,
            $param_bajo_stock
        );

        // =========================
        // Ejecutar insert inventario
        // =========================

        if (!$stmt2->execute()) {
            throw new Exception(
                "Error al insertar inventario: " . $stmt2->error
            );
        }

        // =========================
        // Confirmar transacción
        // =========================

        $conn->commit();

        header(
            "Location: ../gestor_inventario/gestion_insumos.php?success=1"
        );

        exit;

    } catch (Exception $e) {

        // Revertir cambios si algo falla
        $conn->rollback();

        die(
            "Error al insertar insumo: " .
            $e->getMessage()
        );
    }
}
?>
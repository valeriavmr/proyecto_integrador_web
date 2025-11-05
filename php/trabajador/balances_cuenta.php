<?php
// Incluir configuración y conexión
require_once __DIR__ . '/../../config.php';
session_start();

//Evito a usuarios no autorizados
include_once('../admin/auth.php');

require_once(BASE_PATH . '/php/crud/conexion.php');
require_once(BASE_PATH . '/php/crud/consultas_varias.php');

if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_URL . '/php/login.php');
    exit();
}

// Obtener filtros del formulario
$filtro_mes = $_GET['mes'] ?? '';
$filtro_servicio = $_GET['tipo_servicio'] ?? '';

// --- Construcción de la Consulta SQL ---
$sql = "
    SELECT 
        id_servicio,
        tipo_de_servicio,
        horario,
        monto,
        pagado
    FROM servicio_g3
    WHERE 1=1
";

$params = [];
$types = "";

// Filtro por Tipo de Servicio
if (!empty($filtro_servicio)) {
    $sql .= " AND tipo_de_servicio = ?";
    $types .= "s";
    $params[] = $filtro_servicio;
}

// Filtro por Mes
if (!empty($filtro_mes)) {
    $sql .= " AND DATE_FORMAT(horario, '%Y-%m') = ?";
    $types .= "s";
    $params[] = $filtro_mes;
}

$sql .= " ORDER BY horario DESC";

$stmt = $conn->prepare($sql);
if (!empty($types)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$servicios_raw = $stmt->get_result();


$balances = [];

while ($row = $servicios_raw->fetch_assoc()) {
    $mes_anio = date('Y-m', strtotime($row['horario']));
    $tipo = $row['tipo_de_servicio'];

    if (!isset($balances[$mes_anio][$tipo])) {
        $balances[$mes_anio][$tipo] = [
            'total_monto' => 0,
            'pagado_status' => $row['pagado'], 
            'servicios' => []
        ];
    }
    
    // Sumar el monto y almacenar el detalle
    $balances[$mes_anio][$tipo]['total_monto'] += $row['monto'];
    $balances[$mes_anio][$tipo]['servicios'][] = $row;
}


// Función para obtener la lista de tipos de servicio disponibles para el filtro
$tipos_servicio = obtenerTiposServicio($conn); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Mensual de Servicios</title>
    <link rel="stylesheet" href="../../css/toggle_switch.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/main_cliente_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/servicios_cliente.css?v=<?= time() ?>">
</head>
<body>
    <?php include('header_trabajador.php'); ?>
    <main class="balance-container">
        <h1>📊 Balance Mensual de Servicios</h1>

        <form method="GET" action="balances_cuenta.php" class="filtro-form">
            <label for="mes">Filtrar por Mes/Año:</label>
            <input type="month" id="mes" name="mes" value="<?= htmlspecialchars($filtro_mes) ?>">
            
            <label for="tipo_servicio">Filtrar por Servicio:</label>
            <select id="tipo_servicio" name="tipo_servicio">
                <option value="">-- Todos --</option>
                <?php foreach ($tipos_servicio as $tipo): ?>
                    <option value="<?= htmlspecialchars($tipo) ?>" <?= ($filtro_servicio == $tipo) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tipo) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit" class="btn-filtrar">Aplicar Filtros</button>
            <a href="balances_cuenta.php" class="btn-limpiar">Limpiar Filtros</a>
        </form>

        <?php if (empty($balances)): ?>
            <p>No hay servicios registrados que cumplan con los filtros.</p>
        <?php else: ?>
            
            <div class="tabla-container">
                <table>
                    <thead>
                        <tr>
                            <th>Mes</th>
                            <th>Tipo de Servicio</th>
                            <th>Total Generado</th>
                            <th>Estado de Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($balances as $mes_anio => $tipos): ?>
                            <?php foreach ($tipos as $tipo => $data): ?>
                                <?php
                                $estado_pagado = $data['pagado_status'];
                                $total_monto = number_format($data['total_monto'], 2);
                                ?>
                                <tr data-mes="<?= $mes_anio ?>" data-tipo="<?= htmlspecialchars($tipo) ?>">
                                    <td><?= date('F Y', strtotime($mes_anio)) ?></td>
                                    <td><?= htmlspecialchars($tipo) ?></td>
                                    <td>$<?= $total_monto ?></td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" 
                                                   class="pagado-toggle" 
                                                   data-mes-anio="<?= $mes_anio ?>"
                                                   data-tipo-servicio="<?= htmlspecialchars($tipo) ?>"
                                                   <?= $estado_pagado ? 'checked' : '' ?>>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
        <?php endif; ?>
        
    </main>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('.pagado-toggle');
            
            toggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const mesAnio = this.getAttribute('data-mes-anio');
                    const tipoServicio = this.getAttribute('data-tipo-servicio');
                    const nuevoEstado = this.checked ? 1 : 0;
                    
                    fetch('../crud/update_balance.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `mes_anio=${mesAnio}&tipo_servicio=${tipoServicio}&pagado=${nuevoEstado}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Estado de pago actualizado con éxito.');
                        } else {
                            alert('Error al actualizar el estado de pago: ' + data.message);
                            this.checked = !this.checked; 
                        }
                    })
                    .catch(error => {
                        console.error('Error de red:', error);
                        alert('Error de conexión al actualizar el pago.');
                        this.checked = !this.checked;
                    });
                });
            });
        });
    </script>
    
    <?php include('../footer.php'); ?>
</body>
</html>
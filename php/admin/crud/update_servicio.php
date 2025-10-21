<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

    //Recupero los datos del turno
    $id_turno = $_GET['id_servicio'] ?? $_POST['id_servicio'] ?? null;
    $mascota_id = $_POST['mascota'] ?? null;
    $tipo_servicio = $_POST['tipo_servicio'] ?? null;
    $trabajador_id = $_POST['trabajador'] ?? null;
    $fecha = $_POST['fecha'] ?? null;
    $hora = $_POST['hora'] ?? null;
    $detalles = $_POST['detalles'] ?? null;

    //Si se han enviado los datos, los valido y actualizo el turno
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //Validación básica
        $errores = [];
        if (empty($mascota_id)) {
            $errores[] = "La mascota es obligatoria.";
        }
        if (empty($trabajador_id)) {
            $errores[] = "El trabajador es obligatorio.";
        }
        if (empty($fecha)) {
            $errores[] = "La fecha es obligatoria.";
        }
        if (empty($hora)) {
            $errores[] = "La hora es obligatoria.";
        }
        if (empty($detalles)) {
            $detalles = "N/A";
        }

        //Si no hay errores, actualizo el turno
        if (empty($errores)) {
            require('../../crud/conexion.php');

            //Combino fecha y hora en un solo datetime
            $horario = date('Y-m-d H:i:s', strtotime("$fecha $hora"));

            //Actualizo el turno en la base de datos
            $stmt = $conn->prepare("UPDATE servicio SET id_mascota = ?, tipo_de_servicio = ?, id_trabajador = ?, horario = ?, comentarios = ? WHERE id_servicio = ?");
            $stmt->bind_param("isissi", $mascota_id, $tipo_servicio, $trabajador_id, $horario, $detalles, $id_turno);
            if ($stmt->execute()) {
                header("Location: ../tabla_historico_servicios.php");
                exit();
            } else {
                $errores[] = "Error al actualizar el turno: " . $stmt->error;
            }
            $stmt->close();
            $conn->close();
        }
    }
?>
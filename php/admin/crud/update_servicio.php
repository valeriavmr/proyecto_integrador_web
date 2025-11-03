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
    $horario_p = $_POST['horario'] ?? null;
    $detalles = $_POST['detalles'] ?? null;
    $pagado = $_POST['pagado'] ?? 0;

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
        if (empty($fecha) && empty($horario_p)) {
            $errores[] = "La fecha es obligatoria.";
        }
        if (empty($hora) && empty($horario_p)) {
            $errores[] = "La hora es obligatoria.";
        }
        if (empty($detalles)) {
            $detalles = "N/A";
        }

        //Si no hay errores, actualizo el turno
        if (empty($errores)) {
            require('../../crud/conexion.php');

            //Combino fecha y hora en un solo datetime
            if(empty($horario_p)){
                $horario = date('Y-m-d H:i:s', strtotime("$fecha $hora"));
            }else{
                $horario = $horario_p;
            }

            //Actualizo el turno en la base de datos
            $stmt = $conn->prepare("UPDATE servicio SET id_mascota = ?, tipo_de_servicio = ?, id_trabajador = ?, horario = ?, comentarios = ?, pagado = ? WHERE id_servicio = ?");
            $stmt->bind_param("isissii", $mascota_id, $tipo_servicio, $trabajador_id, $horario, $detalles, $pagado, $id_turno);
            if ($stmt->execute()) {
                if($_SESSION['rol']=='admin'){
                    header("Location: ../tabla_historico_servicios.php");
                }elseif($_SESSION['rol']=='trabajador'){
                    header("Location: ../../trabajador/detalle_turno_trabajador.php?id_servicio=$id_turno");}
                exit();
            } else {
                $errores[] = "Error al actualizar el turno: " . $stmt->error;
            }
            $stmt->close();
            $conn->close();
        }
    }
?>
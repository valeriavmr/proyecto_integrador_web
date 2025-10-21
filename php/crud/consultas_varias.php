<?php

function buscarNombreCompletoPorId($conn, $id_persona) {
    $sql = "SELECT nombre, apellido FROM persona WHERE id_persona = '$id_persona'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['nombre'] . ' ' . $row['apellido'];
    } else {
        return "Desconocido";
    }
}


//Select de mascota por id
function obtenerNombreMascota($conn,$id_mascota) {
    $sql = "SELECT nombre FROM mascota WHERE id_mascota = '$id_mascota'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['nombre'];
    } else {
        return "Desconocida";
    }
}

//devoler nombre y apellido del usuario
function obtenerNombreUsuario($conn, $usuario) {
    $sql = "SELECT nombre, apellido FROM persona WHERE nombre_de_usuario = '$usuario'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['nombre'] . ' ' . $row['apellido'];
    } else {
        return "Usuario Desconocido";
    }
}

//Verifica si el nombre de usuario ya existe
function verificarNombreUsuario($conn, $username) {
    $sql = "SELECT COUNT(*) AS count FROM persona WHERE nombre_de_usuario = '$username'";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['count'] > 0; // Devuelve true si el nombre de usuario existe, false en caso contrario
    } else {
        return false; // En caso de error en la consulta, se asume que no existe
    }
}

//verifica si el correo ya existe
function verificarCorreo($conn, $correo) {
    $sql = "SELECT COUNT(*) AS count FROM persona WHERE correo = '$correo'";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['count'] > 0; // Devuelve true si el correo existe, false en caso contrario
    } else {
        return false; // En caso de error en la consulta, se asume que no existe
    }
}

//Devuelve el id de persona a partir del nombre de usuario
function obtenerIdPersona($conn, $username) {   
    $sql = "SELECT id_persona FROM persona WHERE nombre_de_usuario = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id_persona'];
    } else {
        return null; // Retorna null si no se encuentra el usuario
    }
}

//Devuelve los nombres de las mascotas de un cliente por usuarioname
function obtenerMascotasPorUsuario($conn, $username) {

    $id_persona = obtenerIdPersona($conn, $username);
    if ($id_persona === null) {
        return []; // Retorna un array vacío si no se encuentra el usuario
    }

    $sql = "SELECT * FROM mascota WHERE id_persona = '$id_persona'";
    $result = $conn->query($sql);
    $mascotas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $mascotas[] = [
                'id_mascota' => $row['id_mascota'],
                'nombre' => $row['nombre'],
                'color' => $row['color'],
                'edad' => $row['edad'],
                'raza' => $row['raza'],
                'tamanio' => $row['tamanio']
            ];
        }
    }
    return $mascotas;
}

//Devuelve la dirección de una persona por su id
function obtenerDireccionPorIdPersona($conn, $id_persona) {
    $sql = "SELECT * FROM direccion WHERE id_persona = '$id_persona'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null; // Retorna null si no se encuentra la dirección
    }
}

//Devuelve una lista de las personas con rol trabajador del mismo barrio que el cliente
function obtenerTrabajadores($conn, $id_cliente) {
    //Primero obtener el barrio del cliente
    $direccion_cliente = obtenerDireccionPorIdPersona($conn, $id_cliente);
    if ($direccion_cliente === null) { 
        return []; // Retorna un array vacío si no se encuentra la dirección del cliente
    }
    $barrio_cliente = $direccion_cliente['localidad'];
    //Luego obtener los trabajadores del mismo barrio
    $sql = "SELECT id_persona, nombre, apellido FROM persona WHERE rol = 'trabajador' and id_persona in(select id_persona from direccion where localidad = '$barrio_cliente')";
    $result = $conn->query($sql);
    $trabajadores = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $trabajadores[] = [
                'id_trabajador' => $row['id_persona'],
                'nombre_completo' => $row['nombre'] . ' ' . $row['apellido']
            ];
        }
    }
    return $trabajadores;
}

function info_servicio($conn, $id_servicio) {
    $sql = "SELECT * FROM servicio WHERE id_servicio = '$id_servicio'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null; // Retorna null si no se encuentra el servicio
    }
}

function obtenerHorasDisponibles($conn, $id_trabajador, $fecha) {
    date_default_timezone_set('America/Argentina/Buenos_Aires'); // Ajusta a la hora de Bs. Aires

    // Normalizar fecha
    $fecha = date('Y-m-d', strtotime($fecha));

    // Generar horarios de 9 a 17
    $horarios = [];
    for ($h = 9; $h <= 17; $h++) {
        $hora = sprintf('%02d:00:00', $h);
        $horarios[] = $hora;
    }

    // Obtener horarios ocupados
    $sql = "SELECT horario FROM servicio WHERE id_trabajador = '$id_trabajador' AND DATE(horario) = '$fecha'";
    $result = $conn->query($sql);

    $ocupadas = [];
    while ($row = $result->fetch_assoc()) {
        $hora_ocupada = date('H:i:s', strtotime($row['horario']));
        $ocupadas[] = $hora_ocupada;
    }

    // Es hoy?
    $esHoy = ($fecha === date('Y-m-d'));
    $horaActualInt = (int)date('H'); // Solo la hora, como entero

    // Filtrar
    $disponibles = array_filter($horarios, function ($hora) use ($ocupadas, $esHoy, $horaActualInt) {
        $horaTurno = (int)substr($hora, 0, 2);

        if (in_array($hora, $ocupadas)) {
            return false;
        }

        if ($esHoy && $horaTurno <= $horaActualInt) {
            return false;
        }

        return true;
    });

    // Formatear salida
    return array_map(function ($h) {
        return substr($h, 0, 5); // 10:00:00 -> 10:00
    }, $disponibles);
}


/*Seccion de administrador*/

//Eliminar direccion por id

function deleteDireccionPorId($conn, $id_persona){
    $sql = 'DELETE FROM direccion WHERE id_persona = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_persona);

    $stmt->execute();

}

//Eliminar a persona por id
function deletePersonaPorId($conn, $id_persona){
    $sql = 'DELETE FROM persona where id_persona = ?';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_persona);
    
    //Primero elimino la direccion y luego la persona
    deleteDireccionPorId($conn, $id_persona);
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Cuenta eliminada exitosamente";
    }
    else{
        $_SESSION['mensaje'] = "Error al eliminar la cuenta";
    }

    header('Location: ../admin/tabla_personas.php');
    exit();
}

//Select de turnos activos
function selectTurnosActivosYPasados($conn, $paraActivos){
    $sql = 'SELECT * FROM servicio';

    if($paraActivos){
        $sql .=' where horario >= NOW()';
    }

    $result = $conn->query($sql);

    return $result;
}

/* Seccion de selects para generar pdfs */

//Select de las personas
function selectAllPersonas($conn){

    $sql = 'SELECT * FROM persona';

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
            $firstRow = $result->fetch_assoc();
            $columnNames = array_keys($firstRow);

            $rows = [];
            $rows[] = $firstRow;

            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return [$rows, $columnNames];
    } else {
        return [[], []]; // Retorna vacío si no hay personas en la base
    }

}

//Select de todos los turnos, tanto activos como finalizados
function selectAllServicios($conn, $turnosActivos){

    $sql = 'SELECT * FROM servicio';

    if($turnosActivos == true){
        $sql = $sql . ' where horario >= NOW()';
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
            $firstRow = $result->fetch_assoc();
            $columnNames = array_keys($firstRow);

            $rows = [];
            $rows[] = $firstRow;

            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return [$rows, $columnNames];
    } else {
        return [[], []]; // Retorna vacío si no hay turnos en la base
    }

}


function selectAllMascotas($conn) {
    // La consulta SQL que obtuvimos de tu primer mensaje
    $sql = "
        SELECT 
            m.id_mascota,
            m.nombre AS nombre_mascota,
            m.fecha_de_nacimiento,
            m.edad,
            m.raza,
            m.tamanio,
            m.color,
            p.nombre_de_usuario AS duenio
        FROM mascota m
        INNER JOIN persona p ON m.id_persona = p.id_persona
        ORDER BY p.nombre_de_usuario ASC, m.nombre ASC
    ";

    $result = $conn->query($sql);

    if (!$result) {
        // En un entorno de producción, es mejor registrar el error y mostrar un mensaje genérico.
        // Pero para debug, este mensaje es útil.
        die("❌ Error en la consulta SQL: " . $conn->error);
    }

    $datos_mascotas = [];
    $columnas = [];

    if ($result->num_rows > 0) {
        // 1. Obtener los nombres de las columnas (solo se hace una vez)
        $fields = $result->fetch_fields();
        foreach ($fields as $field) {
            $columnas[] = $field->name;
        }

        // 2. Obtener todas las filas de datos
        while ($fila = $result->fetch_assoc()) {
            $datos_mascotas[] = $fila;
        }
    }
    
    // Devolvemos el array de datos y el array de columnas, tal como espera tu script.
    return [$datos_mascotas, $columnas];
}
?>
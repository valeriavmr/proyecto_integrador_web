<?php
/* Archivo con funciones reutilizables en varios archivos */

//Devuelve el nombre_de_usuario a partir del id de persona
function obtenerUsername($conn, $id_persona) {
    $sql = "SELECT nombre_de_usuario FROM persona WHERE id_persona = '$id_persona'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['nombre_de_usuario'];
    } else {
        return null;
    }
}

//Devuelve el nombre completo de una persona por su id
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

//Devuelve la mascota por id
function obtenerMascotaPorId($conn, $id_mascota) {
    $sql = "SELECT * FROM mascota WHERE id_mascota = '$id_mascota'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null; // Retorna null si no se encuentra la mascota
    }
}

//Devuelve todas las mascotas
function obtenerMascotas($conn) {
    $sql = "SELECT * FROM mascota";
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
    $sql = "SELECT id_persona FROM persona WHERE nombre_de_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id_persona'];
    } else {
        return null;
    }
}

//Devuelve el id de la persona que tiene un correo
function obtenerIdPersonaPorCorreo($conn, $correo) {
    $sql = "SELECT id_persona FROM persona WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id_persona'];
    } else {
        return null;
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

//Devolver a la persona por id
function getPersonaPorId($conn, $id) {
    $sql = "SELECT * FROM persona WHERE id_persona = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null; // Si no hay resultados
}

//Devolver direccion de la persona por id
function getDireccionPorId($conn, $id) {
    $sql = "SELECT * FROM direccion WHERE id_persona = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null; // Si no hay resultados
}

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

//select que verifica que el username no exista o que corresponda a la misma persona
function usernameDisponible($conn, $id_persona, $username){

    $sql = 'SELECT COUNT(*) AS count from persona where nombre_de_usuario = ?';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        if($row['count'] > 0){
            $id_username = obtenerIdPersona($conn,$username);
            return $id_username == $id_persona;
        }else{
            return true;
        }
    } else {
        return false; // En caso de error en la consulta, se asume que no existe
    }
}

//Funcion que valida que un correo este disponible o que sea del mismo usuario que lo ingreso
function correoDisponible($conn, $id_persona, $correo) {
    $sql = 'SELECT COUNT(*) AS count FROM persona WHERE correo = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        if ($row['count'] > 0) {
            // El correo existe, así que verifico si es el mismo usuario
            $id_correo = obtenerIdPersonaPorCorreo($conn, $correo);
            return $id_correo == $id_persona; // true si es mío, false si es de otro
        } else {
            return true; // está disponible
        }
    } else {
        return false; // error en la consulta
    }
}

//Select de turnos de una persona
function selectTurnosDePersona($conn, $id_persona){

    $sql = 'SELECT * FROM servicio where id_mascota IN (SELECT id_mascota FROM mascota WHERE id_persona = ?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_persona);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si hay resultados
    if ($result && $result->num_rows > 0) {
        $turnos = [];
        while ($row = $result->fetch_assoc()) {
            $turnos[] = $row;
        }
        return $turnos;
    }
    return null; // Si no hay resultados
}

//Devolver turno por id
function obtenerTurnoPorId($conn, $id_servicio) {
    $sql = "SELECT * FROM servicio WHERE id_servicio = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_servicio);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null; // Si no hay resultados
}

//Funcion para verificar si el tipo de servicio ya existe
function tipoServicioExiste($conn, $tipo_servicio) {
    $sql = "SELECT COUNT(*) AS count FROM tipo_de_servicio WHERE lower(tipo_de_servicio) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", strtolower($tipo_servicio));
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['count'] > 0; // Devuelve true si el tipo de servicio existe, false en caso contrario
    } else {
        return false; // En caso de error en la consulta, se asume que no existe
    }
}

//Funcion que devuelve todos los tipos de servicios y los nombres de las columnas
function obtenerTiposDeServicios($conn) {
    $sql = "SELECT * FROM tipo_de_servicio";
    $result = $conn->query($sql);
    $tipos_servicios = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tipos_servicios[] = [
                'id_tipo_servicio' => $row['id_tipo_servicio'],
                'tipo_de_servicio' => $row['tipo_de_servicio'],
                'descripcion' => $row['descripcion'],
                'precio_servicio' => $row['precio_servicio'],
                'imagen_servicio' => $row['imagen_servicio']
            ];
        }
    }
    return $tipos_servicios;
}

//Funcion que devuelve la ruta completa de las imagenes de los tipos de servicios
function obtenerRutaImagenTipoServicio($conn, $id_tipo_servicio,$nombre_proyecto) {
    $sql = "SELECT imagen_servicio FROM tipo_de_servicio WHERE id_tipo_servicio = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_tipo_servicio);
    $stmt->execute();
    $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Usar el nombre del dominio actual
        $host = $_SERVER['HTTP_HOST'];
        return "http://$host/$nombre_proyecto/uploads/" . $row['imagen_servicio'];
    }
    return null; // Si no hay resultados
}

//Funcion que devuelve un tipo de servicio por id
function obtenerTipoDeServicioPorId($conn, $id_tipo_servicio) {
    $sql = "SELECT * FROM tipo_de_servicio WHERE id_tipo_servicio = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_tipo_servicio);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null; // Si no hay resultados
}

//Funcion que verifica si un tipo de servicio existe por tipo_de_servicio y no es el mismo id
function tipoServicioExisteExcluyendoId($conn, $tipo_servicio, $id_tipo_servicio) {
    $sql = "SELECT COUNT(*) AS count FROM tipo_de_servicio WHERE lower(tipo_de_servicio) = ? AND id_tipo_servicio != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", strtolower($tipo_servicio), $id_tipo_servicio);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['count'] > 0; // Devuelve true si el tipo de servicio existe, false en caso contrario
    } else {
        return false; // En caso de error en la consulta, se asume que no existe
    }
}

//Funcion para eliminar las mascotas de una persona por su id
function deleteMascotasPorPersonaId($conn, $id_persona){
    $sql = 'DELETE FROM mascota WHERE id_persona = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_persona);

    $stmt->execute();
}

//Funcion para eliminar los turnos de una persona por su id
function deleteTurnosPorPersonaId($conn, $id_persona){
    $sql = 'DELETE FROM servicio WHERE id_mascota IN (SELECT id_mascota FROM mascota WHERE id_persona = ?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_persona);

    $stmt->execute();
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
            p.nombre_de_usuario AS dueño
        FROM mascota m
        INNER JOIN persona p ON m.id_persona = p.id_persona
        ORDER BY p.nombre_de_usuario ASC, m.nombre ASC
    ";

    $result = $conn->query($sql);

    if (!$result) {

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
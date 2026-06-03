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

//Actualiza la contraseña por username
function cambiarPassPorUsername($conn, $username,$pass_nueva){

    $hash = password_hash($pass_nueva, PASSWORD_DEFAULT);

    $sql ="UPDATE persona set password=? where nombre_de_usuario=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss",$hash,$username);

    if($stmt->execute()){
        header('Location: ../login.php?mensaje=Contraseña cambiada exitosamente');
    }else{
        header('Location: ../login.php?mensaje=No se pudo cambiar la contraseña');
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
                'tamanio' => $row['tamanio'],
                'imagen_url' => $row['imagen_url']
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

// Devuelve una lista de las personas con rol 'trabajador' con la especialidad indicada
function obtenerTrabajadores($conn, $id_cliente, $especialidad) {
    // Consulta con alias y JOINs
    $sql = "
        SELECT 
            p.id_persona AS id_trabajador,
            CONCAT(p.nombre, ' ', p.apellido) AS nombre_completo
        FROM persona AS p
        INNER JOIN trabajadores AS t ON p.id_persona = t.id_persona
        WHERE 
            p.rol = 'trabajador'
            AND t.tipo_de_servicio = ?
    ";

    // Usamos consulta preparada para evitar inyección SQL
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $especialidad);
    $stmt->execute();
    $result = $stmt->get_result();

    $trabajadores = [];
    while ($row = $result->fetch_assoc()) {
        $trabajadores[] = $row; // ya viene con id_trabajador y nombre_completo
    }

    $stmt->close();
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

    $stmt->close();
}

//Elimino el registro de la persona en la tabla de trabajadores
function deleteTrabajadorPorId($conn, $id_persona){

    $sql = 'DELETE FROM trabajadores WHERE id_persona = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_persona);

    $stmt->execute();

    $stmt->close();

}

//Eliminar a persona por id
function deletePersonaPorId($conn, $id_persona){
    session_start();
    include_once('/proyecto_adiestramiento_tahito/config.php');

    $sql = 'DELETE FROM persona where id_persona = ?';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_persona);
    
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Cuenta eliminada exitosamente";
        header('Location: ' . BASE_URL . '/php/admin/tabla_personas.php');
    }
    else{
        $_SESSION['mensaje'] = "Error al eliminar la cuenta";
    }
    $stmt->close();

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
function obtenerRutaImagenTipoServicio($conn, $id_tipo_servicio, $nombre_proyecto = '') {
    $sql = "SELECT imagen_servicio FROM tipo_de_servicio WHERE id_tipo_servicio = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_tipo_servicio);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Usar BASE_URL definida en config.php para que funcione con php -S y XAMPP
        $base = defined('BASE_URL') ? BASE_URL : ('http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
        return $base . '/uploads/' . $row['imagen_servicio'];
    }
    return null;
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

//Funcion que devuelve las columnas y los registros de la tabla trabajadores
function selectAllTrabajadores($conn){

        $sql = 'SELECT * FROM trabajadores';

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

//Funcion que devuelve el registro de un trabajador por su id_persona
function obtenerTrabajadorPorId($conn, $id_persona) {
    $sql = "SELECT * FROM trabajadores WHERE id_persona = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_persona);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null; // Si no hay resultados
}

//Funcion que devuelve la pass_app de un trabajador admin por su id_persona

function obtenerPassAppPorId($conn, $id_persona) {
    $sql = "SELECT pass_app FROM trabajadores WHERE id_persona = ? AND rol = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_persona);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['pass_app'];
    }
    return null; // Si no hay resultados
}

//Funcion para obtener el monto que corresponde a un tipo de servicio
function obtenerMontoServicio($conn, $tipo_servicio) {
    $sql = "SELECT precio_servicio FROM tipo_de_servicio WHERE tipo_de_servicio = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tipo_servicio);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['precio_servicio'];
    } else {
        return null; // Retorna null si no se encuentra el tipo de servicio
    }
}

//Funcion que devuelve los turnos pendientes de un trabajador
function turnosPendientesTrabajador($conn, $id_trabajador){

    $sql = "SELECT * FROM servicio WHERE id_trabajador = ? and horario >= NOW()";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$id_trabajador);

    $stmt->execute();
    $result = $stmt->get_result();

    if($result && $result->num_rows >0){
        $turnos = [];
        while ($row = $result->fetch_assoc()) {
            $turnos[] = $row;
        }
        return $turnos;
    }else{
        return [];
    }

}

//Funcion que devuelve los turnos de un trabajador
function turnosDeTrabajador($conn, $id_trabajador){

    $sql = "SELECT * FROM servicio WHERE id_trabajador = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$id_trabajador);

    $stmt->execute();
    $result = $stmt->get_result();

    if($result && $result->num_rows >0){
        $turnos = [];
        while ($row = $result->fetch_assoc()) {
            $turnos[] = $row;
        }
        return $turnos;
    }else{
        return [];
    }

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

        die("Error en la consulta SQL: " . $conn->error);
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


function obtenerTiposServicio($conn) {
    $sql = "SELECT DISTINCT tipo_de_servicio FROM servicio ORDER BY tipo_de_servicio ASC";
    $result = $conn->query($sql);
    $tipos = [];
    while ($row = $result->fetch_assoc()) {
        $tipos[] = $row['tipo_de_servicio'];
    }
    return $tipos;
}

//Funcion que devuelve los insumos con stock bajo, con su nombre y cantidad actual
function obtenerInsumosConBajoStock($conn) {
    $sql = "SELECT i.id_insumo, i.nombre_insumo, ii.cantidad_actual FROM insumo i 
    INNER JOIN inventario_insumo ii ON i.id_insumo = ii.id_insumo WHERE ii.cantidad_actual <= 
    ii.param_bajo_stock";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $insumos_bajo_stock = [];
    while ($row = $result->fetch_assoc()) {
        $insumos_bajo_stock[] = $row;
    }
    return $insumos_bajo_stock;
}

//Funcion que devuelve los productos con stock bajo, con su nombre y cantidad actual
function obtenerProductosConBajoStock($conn) {
    $sql = "SELECT p.id_producto, p.nombre_producto, ip.cantidad_actual_producto FROM productos p 
    INNER JOIN inventario ip ON p.id_producto = ip.id_producto 
    WHERE ip.cantidad_actual_producto <= ip.param_bajo_stock";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $productos_bajo_stock = [];
    while ($row = $result->fetch_assoc()) {
        $productos_bajo_stock[] = $row;
    }
    return $productos_bajo_stock;
}

//Funcion que devuelve la cantidad total de insumos en stock
function getCantidadTotalInsumos($conn) {
    $sql = "SELECT COALESCE(SUM(cantidad_actual), 0) AS total_insumos FROM inventario_insumo";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_insumos'];
    }
    return 0;
}

//Funcion que devuelve la cantidad total de productos en stock
function getCantidadTotalProductos($conn) {
    $sql = "SELECT COALESCE(SUM(cantidad_actual_producto), 0) AS total_productos FROM inventario";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_productos'];
    }
    return 0;
}

function getProveedores($conn) {
    $sql = "SELECT * FROM proveedores";
    $result = $conn->query($sql);
    $proveedores = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $proveedores[] = $row;
        }
    }else{
        return []; // Retorna vacío si no hay proveedores en la base
    }
    return $proveedores;
}

// ================================================================
// FUNCIONES DE RENTABILIDAD Y REPORTES (Módulo Rodrigo)
// ================================================================

/**
 * Obtiene los datos de rentabilidad mensual desde la vista v_rentabilidad_mensual.
 * Si no existe la vista (tabla vacía), construye los datos directamente.
 */
function getRentabilidadMensual($conn, $anio = null) {
    if ($anio === null) {
        $anio = date('Y');
    }
    
    // Intentar usar la vista
    $sql = "SELECT * FROM v_rentabilidad_mensual WHERE periodo_anio = ? ORDER BY periodo_mes ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $anio);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $datos = [];
    while ($row = $result->fetch_assoc()) {
        $datos[] = $row;
    }
    $stmt->close();
    
    // Si la vista no devuelve datos, construir manualmente
    if (empty($datos)) {
        $datos = construirRentabilidadManual($conn, $anio);
    }
    
    return $datos;
}

/**
 * Construye datos de rentabilidad manualmente cuando la vista no tiene datos.
 */
function construirRentabilidadManual($conn, $anio) {
    $datos = [];
    
    for ($mes = 1; $mes <= 12; $mes++) {
        // Ingresos por servicios
        $sql_srv = "SELECT COALESCE(SUM(monto), 0) AS total FROM servicio 
                    WHERE pagado = 1 AND YEAR(horario) = ? AND MONTH(horario) = ?";
        $stmt = $conn->prepare($sql_srv);
        $stmt->bind_param("ii", $anio, $mes);
        $stmt->execute();
        $ing_srv = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();
        
        // Ingresos por ventas
        $sql_vta = "SELECT COALESCE(SUM(total), 0) AS total FROM ventas 
                    WHERE YEAR(fecha) = ? AND MONTH(fecha) = ?";
        $stmt = $conn->prepare($sql_vta);
        $stmt->bind_param("ii", $anio, $mes);
        $stmt->execute();
        $ing_vta = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();
        
        // Costos por compras
        $sql_cmp = "SELECT COALESCE(SUM(total), 0) AS total FROM compras 
                    WHERE YEAR(fecha_compra) = ? AND MONTH(fecha_compra) = ?";
        $stmt = $conn->prepare($sql_cmp);
        $stmt->bind_param("ii", $anio, $mes);
        $stmt->execute();
        $costo_compras = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();
        
        // Costos manuales
        $sql_man = "SELECT COALESCE(costo_sueldos, 0) AS sueldos, COALESCE(costo_otros, 0) AS otros, notas 
                    FROM rentabilidad WHERE periodo_anio = ? AND periodo_mes = ?";
        $stmt = $conn->prepare($sql_man);
        $stmt->bind_param("ii", $anio, $mes);
        $stmt->execute();
        $res_man = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        $sueldos = $res_man ? floatval($res_man['sueldos']) : 0;
        $otros = $res_man ? floatval($res_man['otros']) : 0;
        $notas = $res_man ? $res_man['notas'] : null;
        
        $ing_total = floatval($ing_srv) + floatval($ing_vta);
        $costo_total = floatval($costo_compras) + $sueldos + $otros;
        $ganancia_bruta = $ing_total - floatval($costo_compras);
        $ganancia_neta = $ing_total - $costo_total;
        $margen = $ing_total > 0 ? round(($ganancia_neta / $ing_total) * 100, 2) : 0;
        
        // Solo incluir meses que tengan algún dato
        if ($ing_total > 0 || $costo_total > 0) {
            $datos[] = [
                'periodo_anio' => $anio,
                'periodo_mes' => $mes,
                'ingresos_servicios' => number_format($ing_srv, 2, '.', ''),
                'ingresos_ventas' => number_format($ing_vta, 2, '.', ''),
                'ingresos_total' => number_format($ing_total, 2, '.', ''),
                'costo_compras' => number_format($costo_compras, 2, '.', ''),
                'costo_sueldos' => number_format($sueldos, 2, '.', ''),
                'costo_otros' => number_format($otros, 2, '.', ''),
                'costo_total' => number_format($costo_total, 2, '.', ''),
                'ganancia_bruta' => number_format($ganancia_bruta, 2, '.', ''),
                'ganancia_neta' => number_format($ganancia_neta, 2, '.', ''),
                'margen_porcentaje' => $margen,
                'notas' => $notas,
                'generado_en' => null,
                'actualizado_en' => null
            ];
        }
    }
    
    return $datos;
}

/**
 * Guarda o actualiza los costos manuales de un período.
 */
function guardarCostosManuales($conn, $anio, $mes, $sueldos, $otros, $notas) {
    $sql = "INSERT INTO rentabilidad (periodo_anio, periodo_mes, costo_sueldos, costo_otros, notas)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                costo_sueldos = VALUES(costo_sueldos),
                costo_otros = VALUES(costo_otros),
                notas = VALUES(notas)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iidds", $anio, $mes, $sueldos, $otros, $notas);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

/**
 * Obtiene los años disponibles con datos para el selector de rentabilidad.
 */
function getAniosConDatos($conn) {
    $anios = [];
    
    $queries = [
        "SELECT DISTINCT YEAR(horario) AS anio FROM servicio WHERE pagado = 1",
        "SELECT DISTINCT YEAR(fecha) AS anio FROM ventas",
        "SELECT DISTINCT YEAR(fecha_compra) AS anio FROM compras",
        "SELECT DISTINCT periodo_anio AS anio FROM rentabilidad"
    ];
    
    foreach ($queries as $sql) {
        $result = $conn->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                if ($row['anio']) $anios[] = intval($row['anio']);
            }
        }
    }
    
    $anios = array_unique($anios);
    if (empty($anios)) $anios[] = intval(date('Y'));
    rsort($anios);
    return $anios;
}

/**
 * Reporte de ventas con detalle por rango de fechas.
 */
function getReporteVentas($conn, $desde, $hasta) {
    $sql = "SELECT 
                v.id_venta,
                v.fecha,
                v.total,
                COALESCE(CONCAT(p.nombre, ' ', p.apellido), 'Consumidor Final') AS cliente,
                COALESCE(m.nombre, '-') AS mascota,
                GROUP_CONCAT(CONCAT(dv.cantidad, 'x ', pr.nombre_producto) SEPARATOR ', ') AS productos
            FROM ventas v
            LEFT JOIN persona p ON v.id_persona = p.id_persona
            LEFT JOIN mascota m ON v.id_mascota = m.id_mascota
            INNER JOIN detalle_venta dv ON v.id_venta = dv.id_venta
            INNER JOIN productos pr ON dv.id_producto = pr.id_producto
            WHERE v.fecha BETWEEN ? AND ?
            GROUP BY v.id_venta, v.fecha, v.total, cliente, mascota
            ORDER BY v.fecha DESC";
    
    $stmt = $conn->prepare($sql);
    $hasta_fin = $hasta . ' 23:59:59';
    $stmt->bind_param("ss", $desde, $hasta_fin);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $ventas = [];
    while ($row = $result->fetch_assoc()) {
        $ventas[] = $row;
    }
    $stmt->close();
    return $ventas;
}

/**
 * Reporte de servicios agrupados por tipo, con totales pagados/no pagados.
 */
function getReporteServicios($conn, $desde, $hasta) {
    $sql = "SELECT 
                tipo_de_servicio,
                COUNT(*) AS total_turnos,
                SUM(CASE WHEN pagado = 1 THEN 1 ELSE 0 END) AS turnos_pagados,
                SUM(CASE WHEN pagado = 0 THEN 1 ELSE 0 END) AS turnos_no_pagados,
                COALESCE(SUM(CASE WHEN pagado = 1 THEN monto ELSE 0 END), 0) AS ingresos_cobrados,
                COALESCE(SUM(CASE WHEN pagado = 0 THEN monto ELSE 0 END), 0) AS ingresos_pendientes,
                COALESCE(SUM(monto), 0) AS ingresos_total
            FROM servicio
            WHERE horario BETWEEN ? AND ?
            GROUP BY tipo_de_servicio
            ORDER BY ingresos_total DESC";
    
    $stmt = $conn->prepare($sql);
    $hasta_fin = $hasta . ' 23:59:59';
    $stmt->bind_param("ss", $desde, $hasta_fin);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $servicios = [];
    while ($row = $result->fetch_assoc()) {
        $servicios[] = $row;
    }
    $stmt->close();
    return $servicios;
}

/**
 * Reporte de compras agrupadas por proveedor.
 */
function getReporteCompras($conn, $desde, $hasta) {
    $sql = "SELECT 
                c.id_compra,
                c.fecha_compra,
                c.total,
                c.observaciones,
                pv.nombre AS proveedor,
                GROUP_CONCAT(CONCAT(cd.cantidad, 'x ', pr.nombre_producto, ' ($', FORMAT(cd.precio_unitario, 2), ')') SEPARATOR ', ') AS detalle
            FROM compras c
            INNER JOIN proveedores pv ON c.id_proveedor = pv.id_proveedor
            LEFT JOIN compra_detalle cd ON c.id_compra = cd.id_compra
            LEFT JOIN productos pr ON cd.id_producto = pr.id_producto
            WHERE c.fecha_compra BETWEEN ? AND ?
            GROUP BY c.id_compra, c.fecha_compra, c.total, c.observaciones, pv.nombre
            ORDER BY c.fecha_compra DESC";
    
    $stmt = $conn->prepare($sql);
    $hasta_fin = $hasta . ' 23:59:59';
    $stmt->bind_param("ss", $desde, $hasta_fin);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $compras = [];
    while ($row = $result->fetch_assoc()) {
        $compras[] = $row;
    }
    $stmt->close();
    return $compras;
}

/**
 * Ranking de productos más vendidos por cantidad y monto.
 */
function getProductosTopVendidos($conn, $desde, $hasta, $limit = 10) {
    $sql = "SELECT 
                pr.id_producto,
                pr.nombre_producto,
                pr.precio_unitario AS precio_actual,
                SUM(dv.cantidad) AS cantidad_vendida,
                SUM(dv.subtotal) AS monto_total,
                COUNT(DISTINCT dv.id_venta) AS num_ventas
            FROM detalle_venta dv
            INNER JOIN productos pr ON dv.id_producto = pr.id_producto
            INNER JOIN ventas v ON dv.id_venta = v.id_venta
            WHERE v.fecha BETWEEN ? AND ?
            GROUP BY pr.id_producto, pr.nombre_producto, pr.precio_unitario
            ORDER BY cantidad_vendida DESC
            LIMIT ?";
    
    $stmt = $conn->prepare($sql);
    $hasta_fin = $hasta . ' 23:59:59';
    $stmt->bind_param("ssi", $desde, $hasta_fin, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    $stmt->close();
    return $productos;
}

/**
 * Obtiene resumen rápido de KPIs para el mes actual.
 */
function getKPIsMesActual($conn) {
    $anio = date('Y');
    $mes = date('n');
    
    // Ingresos servicios
    $stmt = $conn->prepare("SELECT COALESCE(SUM(monto), 0) AS t FROM servicio WHERE pagado=1 AND YEAR(horario)=? AND MONTH(horario)=?");
    $stmt->bind_param("ii", $anio, $mes);
    $stmt->execute();
    $ing_srv = floatval($stmt->get_result()->fetch_assoc()['t']);
    $stmt->close();
    
    // Ingresos ventas
    $stmt = $conn->prepare("SELECT COALESCE(SUM(total), 0) AS t FROM ventas WHERE YEAR(fecha)=? AND MONTH(fecha)=?");
    $stmt->bind_param("ii", $anio, $mes);
    $stmt->execute();
    $ing_vta = floatval($stmt->get_result()->fetch_assoc()['t']);
    $stmt->close();
    
    // Costos compras
    $stmt = $conn->prepare("SELECT COALESCE(SUM(total), 0) AS t FROM compras WHERE YEAR(fecha_compra)=? AND MONTH(fecha_compra)=?");
    $stmt->bind_param("ii", $anio, $mes);
    $stmt->execute();
    $costo_cmp = floatval($stmt->get_result()->fetch_assoc()['t']);
    $stmt->close();
    
    // Costos manuales
    $stmt = $conn->prepare("SELECT COALESCE(costo_sueldos,0) AS s, COALESCE(costo_otros,0) AS o FROM rentabilidad WHERE periodo_anio=? AND periodo_mes=?");
    $stmt->bind_param("ii", $anio, $mes);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    $sueldos = $res ? floatval($res['s']) : 0;
    $otros = $res ? floatval($res['o']) : 0;
    
    $ingresos = $ing_srv + $ing_vta;
    $costos = $costo_cmp + $sueldos + $otros;
    $ganancia = $ingresos - $costos;
    $margen = $ingresos > 0 ? round(($ganancia / $ingresos) * 100, 1) : 0;
    
    return [
        'ingresos' => $ingresos,
        'costos' => $costos,
        'ganancia' => $ganancia,
        'margen' => $margen,
        'ing_servicios' => $ing_srv,
        'ing_ventas' => $ing_vta,
        'costo_compras' => $costo_cmp,
        'costo_sueldos' => $sueldos,
        'costo_otros' => $otros
    ];
}
?>
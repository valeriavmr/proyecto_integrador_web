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
    $horarios = [];
    for ($h = 9; $h <= 17; $h++) {
        $hora = sprintf('%02d:00:00', $h);
        $horarios[] = $hora;
    }

    // Buscar los datetime ocupados ese día
    $sql = "SELECT horario FROM servicio WHERE id_trabajador = '$id_trabajador' AND DATE(horario) = '$fecha'";
    $result = $conn->query($sql);

    $ocupadas = [];
    while ($row = $result->fetch_assoc()) {
        $hora_ocupada = date('H:i:s', strtotime($row['horario']));
        $ocupadas[] = $hora_ocupada;
    }

    // Filtrar
    $disponibles = array_filter($horarios, function ($hora) use ($ocupadas) {
        return !in_array($hora, $ocupadas);
    });

    // Convertir a formato HH:MM para mostrar en el select
    return array_map(function ($h) {
        return substr($h, 0, 5); // "10:00:00" → "10:00"
    }, $disponibles);
}


?>
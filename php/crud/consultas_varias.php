<?php
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

//Devuelve una lista de las personas con rol trabajador
function obtenerTrabajadores($conn) {
    $sql = "SELECT id_persona, nombre, apellido FROM persona WHERE rol = 'trabajador'";
    $result = $conn->query($sql);
    $trabajadores = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $trabajadores[] = [
                'id' => $row['id_persona'],
                'nombre_completo' => $row['nombre'] . ' ' . $row['apellido']
            ];
        }
    }
    return $trabajadores;
}

?>
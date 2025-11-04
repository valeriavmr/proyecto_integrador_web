<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Conexión a la base de datos
    require('../../crud/conexion.php');
    include_once('../../crud/consultas_varias.php');

    # Obtener datos del formulario
    $tipo_servicio = $_POST['tipo_servicio'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];

    // Verificar si el tipo de servicio ya existe
    if (tipoServicioExiste($conn, $tipo_servicio)) {
        header("Location: ../add_tipo_servicio_admin.php?error=El tipo de servicio ya existe");
        exit();
    }
    $imagen = $_FILES['imagen'];

    // Validar y procesar la imagen
    $imagen_nombre = basename($imagen['name']);
    $imagen_ruta = '../../../uploads/' . $imagen_nombre;
    move_uploaded_file($imagen['tmp_name'], $imagen_ruta);


    // Insertar en la base de datos
    $sql = "INSERT INTO tipo_de_servicio_g3 (tipo_de_servicio, descripcion, precio_servicio, imagen_servicio) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $tipo_servicio, $descripcion, $precio, $imagen_nombre);
    if ($stmt->execute()) {
        header("Location: ../servicios_admin.php?mensaje=Tipo de servicio agregado exitosamente");
        exit();
    } else {
        header("Location:../add_tipo_servicio_admin.php?error='{$stmt->error}'");
        exit();
    }
}
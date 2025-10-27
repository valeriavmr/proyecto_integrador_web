<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar usuario</title>
    <link rel="stylesheet" href="../../css/tablas_admin.css">
    <link rel="stylesheet" href="../../css/buscar_persona.css">
</head>
<body>
    <?php
    if (session_status() == PHP_SESSION_NONE) { 
                session_start(); 
            }

        //Validacion de permisos
        require_once('auth.php');

        //Inserto el header
        include('header_admin.php');

        //Para traer el valor de los filtros
        require('../crud/conexion.php');
        include_once('../crud/consultas_varias.php');

        $usuario = $_SESSION['username'];

        $id_persona = obtenerIdPersona($conn,$usuario);

        // Obtenemos los datos de la persona
        $persona = getPersonaPorId($conn, $id_persona);

        $columnNames = array_filter(array_keys($persona), function($col) {return $col !== 'password';});

        // Inicializo los resultados
        $resultados = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filtro']) && isset($_POST['valor'])){
            //Me traigo los valores de búsqueda
            $campo_persona = $_POST['filtro'] ?? '';
            $valor_campo = $_POST['valor'] ?? '';

            // Validamos que el campo exista realmente en la tabla
            if (in_array($campo_persona, $columnNames)) {

                // Usamos LOWER para hacer la búsqueda insensible a mayúsculas
                $sql = "SELECT * FROM persona WHERE LOWER($campo_persona) LIKE ?";
                $stmt = $conn->prepare($sql);
                $param = '%' . strtolower($valor_campo) . '%';
                $stmt->bind_param("s", $param);

                // Ejecutamos y obtenemos resultados
                $stmt->execute();
                $result = $stmt->get_result();
                $resultados = $result->fetch_all(MYSQLI_ASSOC);
            }
        }
        ?>
<main>
    <h1>Buscar persona</h1>
    <form action="" method="post">
        <label for="filtro"></label>
        <select name="filtro" id="filtro" required>
        <option value="" disabled selected>Seleccione el filtro de búsqueda</option>
        <?php
        foreach($columnNames as $column){
            echo "<option value='$column'>". ucfirst($column) ."</option>";
        }
        ?>
        </select>
        <label for="valor"></label>
        <input type="text" name="valor" id="valor" placeholder="Ingrese el valor de búsqueda" required size="50">
        <input type="submit" value="Buscar" id="botonBuscar">
    </form>
    <?php if (!empty($resultados)): ?>
    <section>
            <h3>Resultados:</h3>
    <table>
        <thead>
            <tr>
                <?php foreach (array_keys($resultados[0]) as $col): ?>
                    <th><?= ucfirst($col) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados as $fila): ?>
                <tr>
                    <?php foreach ($fila as $clave => $valor): ?>
                        <td><a href="detalle_usuario.php?id_persona=<?php echo 
                        htmlspecialchars($fila['id_persona'])?>"><?= ($clave === 'password') ? '********' : 
                        htmlspecialchars($valor) ?></a></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filtro'])): ?>
    <p>No se encontraron resultados.</p>
    </section>
<?php endif; ?>
<section id="volver_s">
        <a href="personas_admin.php">Volver a Administración de personas</a>
</section>
</main>
<?php
include('../footer.php');
?> 
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar tipo de servicio</title>
    <link rel="stylesheet" href="../../css/solicitar_turno.css?v=<?= time() ?>">
</head>
<body>
    <?php
    if(session_status() == PHP_SESSION_NONE) { 
        session_start(); 
    }

    require_once('auth.php');
    include('header_admin.php');
    ?>
    <main>
    <fieldset>
        <h2>Agregar nuevo tipo de servicio</h2>
        <form action="crud/insert_tipo_servicio.php" method="post" enctype="multipart/form-data">
            <label for="tipo_servicio"></label>
            <input type="text" id="tipo_servicio" size="50" placeholder="Ingrese el nombre del Tipo de servicio" name="tipo_servicio" required><br>
            <label for="descripcion"></label>
            <input type="text" id="descripcion" size="255" placeholder="Ingrese la descripción" name="descripcion" required><br>
            <label for="precio"></label>
            <input type="number" step="any" id="precio"  placeholder="Ingrese el precio" name="precio" required><br>
            <label for="imagen">Imagen representativa del tipo de servicio:</label><br>
            <input type="file" id="imagen" name="imagen" accept="image/*" required>
            <br>
            <input type="submit" value="Agregar tipo de servicio" id="add_tipo_servicio_btn">
        </form>
        <?php if (isset($_GET['error'])): ?>
            <p style="color:red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>
    </fieldset>
    <section id="back_section">
        <a href="servicios_admin.php">Volver a Administración de servicios</a>
    </section>
    </main>
    <?php
    include('../footer.php');
    ?>
</body>
</html>
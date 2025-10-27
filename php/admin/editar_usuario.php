<?php
if (session_status() == PHP_SESSION_NONE) { 
                session_start(); 
            }
$id_persona = $_GET['id_persona'] ?? $_POST['id_persona'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuario</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
    <link rel="stylesheet" href="../../css/editar_usuario_admin.css">
</head>
<body>
<?php
    //Validacion de permisos
    require_once('auth.php');

    //Inserto el header
    include('header_admin.php');

    //Para traer los datos de la persona y su direccion
    require('../crud/conexion.php');
    include_once('../crud/consultas_varias.php');

    $id_persona = $_GET['id_persona'] ?? $_POST['id_persona'] ?? null;

    // Obtenemos los datos de la persona
    $persona = getPersonaPorId($conn, $id_persona);

    // Obtenemos los datos de la dirección, puede no existir aún
    $direccion = getDireccionPorId($conn, $id_persona) ?? [];
    ?>
<main>
    <h1>Editar usuario</h1>
    <section>
        <h2>Información de perfil</h2>
        <form action="" method="post" id="form_editar_persona"> 
            <input type="hidden" name="id_persona" value="<?php echo htmlspecialchars($id_persona); ?>">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" size="50" id="nombre" required value=<?php echo htmlspecialchars($persona['nombre'])?>>
            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" size="50" id="apellido" required value=<?php echo htmlspecialchars($persona['apellido'])?>>
            <label for="username">Nombre de usuario:</label>
            <input type="text" name="username" size="50" id="username" required value="<?php echo $_POST['username'] ?? htmlspecialchars($persona['nombre_de_usuario']) ?>" onchange="this.form.submit()">
            <?php
            $username = $_POST['username'] ?? '';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!usernameDisponible($conn, $id_persona, $username)) {
                    $_SESSION['mensaje_username'] = "El nombre de usuario ya está ocupado. Seleccione otro.";
                } else {
                    unset($_SESSION['mensaje_username']);
                }
            }
            ?>
            <?php if(isset($_SESSION['mensaje_username'])): ?>
                <p class="error" style="color:red;"><?php echo htmlspecialchars($_SESSION['mensaje_username']); ?></p>
            <?php endif; 
            unset($_SESSION['mensaje_username']);
            ?>
            <label for="correo">Correo Electrónico:</label>
            <input type="email" name="correo" size="50" id="correo" required value=<?php echo $_POST['correo'] ?? htmlspecialchars($persona['correo'])?> onchange="this.form.submit()">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $correo = $_POST['correo'] ?? '';
                if (!correoDisponible($conn, $id_persona, $correo)) {
                    $_SESSION['mensaje_correo'] = "El correo ya está registrado. Use otro.";
                } else {
                    unset($_SESSION['mensaje_correo']);
                }
            }
            ?>
            <?php if(isset($_SESSION['mensaje_correo'])): ?>
                <p class="error" style="color:red;"><?php echo htmlspecialchars($_SESSION['mensaje_correo']); ?></p>
            <?php endif; 
            unset($_SESSION['mensaje_correo ']);
            ?>
            <label for="rol">Tipo de usuario:</label>
            <select name="rol" id="rol" required>
                <option value=<?php echo $persona['rol']?> selected><?php echo htmlspecialchars($persona['rol'])?></option>
                <option value="admin">Administrador</option>
                <option value="cliente">Cliente</option>
                <option value="trabajador">Trabajador</option>
            </select>
            <label for="tel">Teléfono:</label>
            <input type="tel" name="tel" id="tel" minlength="10"
            maxlength="11" required value=<?php echo htmlspecialchars($persona['telefono'])?>>
            <input type="submit" value="Guardar cambios" id="btn_guardar_persona">
        </form>
    </section>
    <section>
        <h2>Información de dirección</h2>
        <form action="crud/update_direccion.php" method="post" id="form_editar_direccion">
            <input type="hidden" name="id_persona" value="<?php echo htmlspecialchars($id_persona); ?>">
            <label for="localidad">Localidad:</label>
            <select name="localidad" id="localidad" required>
                <option value="CABA" selected>Ciudad Autónoma de Buenos Aires</option>
            </select>
            <label for="barrio">Seleccione su barrio</label>
            <select name="barrio" id="barrio" required>
                <option value=<?php echo htmlspecialchars($direccion['localidad'])?> selected><?php echo htmlspecialchars($direccion['localidad'])?></option>
                <?php include('../barrios.php'); ?>
            </select>
            <label for="calle">Calle:</label>
            <input type="text" name="calle" size="50" id="calle" required value=<?php echo htmlspecialchars($direccion['calle'])?>>
            <label for="altura">Altura:</label>
            <input type="number" name="altura" id="altura" min="1" max="20000" required value=<?php echo htmlspecialchars($direccion['altura'])?>>
            <input type="submit" value="Guardar cambios" id="btn_guardar_direccion">
        </form>
    </section>
    <section id="volver_s">
        <a href="tabla_personas.php">Volver a registro de personas</a>
    </section>
</main>
<script>
const form = document.getElementById('form_editar_persona');
const btnGuardar = document.getElementById('btn_guardar_persona');

form.addEventListener('submit', function(e) {
    // Revisamos si hay errores visibles
    const errores = document.querySelectorAll('.error');
    let hayErrores = false;
    errores.forEach(err => {
        if (err.textContent.trim() !== '') hayErrores = true;
    });

    if (hayErrores) {
        e.preventDefault(); // cancela el submit
        alert('Corrige los errores antes de guardar los cambios.');
    } else {
        // Si no hay errores, agregamos la acción real
        btnGuardar.setAttribute('formaction', 'crud/update_persona.php');
    }
});
</script>
<?php
include('../footer.php');
?>
</body>
</html>
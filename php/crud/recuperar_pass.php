<?php

require_once('conexion.php');

//Para cambiar la contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pass_nueva'])) {
        include_once('consultas_varias.php');
        cambiarPassPorUsername($conn,$_POST['username'],$_POST['pass_nueva']);
        exit();
        }

//Código inicial
$username = $_POST['username'] ?? '';
$correo = $_POST['correo'] ?? '';

//Hago un select para verificar que las credenciales le corresponden a una cuenta
$sql = "SELECT * from persona_g3 where nombre_de_usuario = ? AND correo = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ss",$username,$correo);

$stmt->execute();

$persona = $stmt->get_result();

if(!$persona || $persona->num_rows == 0){
    header('Location: ../login.php?error=Credenciales incorrectas');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="../../css/login_styles.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <main>
        <form action="" method="post">
            <fieldset>
            <h2>Cambiar contraseña</h2>
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
            <label for="pass_nueva">Contraseña nueva</label><br>
            <input type="password" required size="50" minlength="8" 
            placeholder="Ingrese su nueva contraseña" name="pass_nueva"><br>
            <button type="submit" id="btn_change_pass">Cambiar contraseña</button><br>
            <a href="../login.php" id="link_main">Cancelar</a>
            </fieldset>
        </form>
    </main>
    <?php include_once('../footer.php');?>
</body>
</html>
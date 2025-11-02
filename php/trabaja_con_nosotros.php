<?php
// Configuración de las vacantes
$vacantes = [   
    [
        'id' => 'desarrollador-web',
        'titulo' => 'Desarrollador Web Junior',
        'descripcion' => 'Buscamos un apasionado del código con conocimientos en HTML, CSS y JavaScript. Se valorará experiencia con PHP.',
        'imagen' => '../recursos/img/dev.jpg'
    ],
    [
        'id' => 'disenador-grafico',
        'titulo' => 'Diseñador Gráfico Senior',
        'descripcion' => 'Creativo con experiencia en Adobe Creative Suite. Capaz de liderar la identidad visual de la marca.',
        'imagen' => '../recursos/img/design.jpg' 

    ],
    [
        'id' => 'Paseador de Perros',
        'titulo' => 'Paseador de Perros',
        'descripcion' => 'Aceptamos cuidadores y paseadores con o sin experiencia. Los únicos requisitos son: amor por los perros y brindar lo mejor de tí.',
        'imagen' => '../recursos/img/paseador.jpg' 

    ]
    
    ];   

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adiestramiento canino Tahito</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
   
    <link rel="stylesheet" href="../css/trabaja.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
</head>
<body>
    <header>
        <a class="img" href="main_guest.php">
            <img src="../recursos/logsinfondo.png" alt="">
        </a>
        <nav>
            <ul id="nav_menu"   >
                <li><a href="main_guest.php#servicios">Servicios</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                <li><a href="registro.php">Registrase</a></li>
                <li><a href="login.php">Ingresar</a></li>
            </ul>
        </nav>         
        
    </header>

    <main>
    <h1>¡Únete a Nuestro Equipo!</h1>
    <div class="vacantes-grid">
        <?php foreach ($vacantes as $vacante): ?>
            <div class="tarjeta-vacante">
                <img src="<?php echo $vacante['imagen']; ?>" alt="Imagen de la vacante">
                <h2><?php echo $vacante['titulo']; ?></h2>
                <p><?php echo $vacante['descripcion']; ?></p>
                <button class="boton-aplicar" data-puesto_id="<?php echo $vacante['id']; ?>">
                    Aplicar ahora
                </button>
            </div>
        <?php endforeach; ?>
    </div>



    <?php foreach ($vacantes as $vacante): ?>
        <div id="modal-<?php echo $vacante['id']; ?>" class="modal">
            <div class="modal-contenido">
                <span class="cerrar-modal">&times;</span>
                <h3>Postularse para: <?php echo $vacante['titulo']; ?></h3>
                
                <form action="/proyecto_adiestramiento_tahito/php/procesar_postulacion.php" method="POST" enctype="multipart/form-data">    
                    <input type="hidden" name="puesto" value="<?php echo $vacante['titulo']; ?>">
                    
                    <label for="nombre-<?php echo $vacante['id']; ?>">Nombre:</label>
                    <input type="text" id="nombre-<?php echo $vacante['id']; ?>" name="nombre" required>

                    <label for="apellido-<?php echo $vacante['id']; ?>">Apellido:</label>
                    <input type="text" id="apellido-<?php echo $vacante['id']; ?>" name="apellido" required>

                    <label for="correo-<?php echo $vacante['id']; ?>">Correo Electrónico:</label>
                    <input type="email" id="correo-<?php echo $vacante['id']; ?>" name="correo" required>

                    <label for="cv-<?php echo $vacante['id']; ?>">Adjuntar CV (PDF, DOCX):</label>
                    <input type="file" id="cv-<?php echo $vacante['id']; ?>" name="cv_file" accept=".pdf,.doc,.docx" required>

                    <button type="submit">Enviar Postulación</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>



    <script src="script.js"></script>
    <?php
    include('footer.php');
    ?>
    </main>
</body>
</html>
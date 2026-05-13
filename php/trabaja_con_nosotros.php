<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabaja con nosotros - Tahito</title>
    <link rel="stylesheet" href="../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/main_guest_styles.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/login_styles.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/trabaja.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
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
            'id' => 'paseador-de-perros',
            'titulo' => 'Paseador de Perros',
            'descripcion' => 'Aceptamos cuidadores y paseadores con o sin experiencia. Los únicos requisitos son: amor por los perros y brindar lo mejor de ti.',
            'imagen' => '../recursos/img/paseador.jpg' 
        ]
    ];
    ?>

    <header class="container main-header">
        <a class="img" href="main_guest.php">
            <img src="../recursos/logsinfondo.png" alt="Tahito Logo">
        </a>
        <nav>
            <ul id="nav_menu">
                <li><a href="main_guest.php#servicios">Servicios</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                <li><a href="main_guest.php">Home</a></li>
            </ul>
        </nav>
        <div id="nav_registro">
            <a id="link_login" href="login.php" class="nav-link">Ingresar</a>
            <a id="link_registro" href="registro.php" class="btn-primary" style="padding: 0.5rem 1rem;">Registrarse</a>
        </div>
    </header>

    <main>
        <section class="vacantes-hero container">
            <h1>Únete a nuestro equipo</h1>
            <p>En Tahito buscamos personas comprometidas con el bienestar animal. Elegí la posición que más te entusiasme.</p>
        </section>

        <section class="container">
            <div class="vacantes-grid">
                <?php foreach ($vacantes as $vacante): ?>
                    <div class="tarjeta-vacante">
                        <div class="tarjeta-img-wrapper">
                            <img src="<?php echo $vacante['imagen']; ?>" alt="<?php echo $vacante['titulo']; ?>">
                        </div>
                        <div class="tarjeta-body">
                            <h2><?php echo $vacante['titulo']; ?></h2>
                            <p><?php echo $vacante['descripcion']; ?></p>
                            <button class="btn-primary boton-aplicar" data-puesto_id="<?php echo $vacante['id']; ?>">
                                Aplicar ahora
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Modales -->
        <?php foreach ($vacantes as $vacante): ?>
            <div id="modal-<?php echo $vacante['id']; ?>" class="modal">
                <div class="modal-contenido auth-card" style="max-width: 560px; width: 90%;">
                    <span class="cerrar-modal">&times;</span>
                    <h3>Postularse para: <?php echo $vacante['titulo']; ?></h3>
                    <p style="margin-bottom: 1.5rem;">Completá el formulario y adjuntá tu CV.</p>
                    
                    <form action="procesar_postulacion.php" method="POST" enctype="multipart/form-data" class="auth-form">    
                        <input type="hidden" name="puesto" value="<?php echo $vacante['titulo']; ?>">
                        
                        <div class="form-group">
                            <label for="nombre-<?php echo $vacante['id']; ?>">Nombre</label>
                            <input type="text" id="nombre-<?php echo $vacante['id']; ?>" name="nombre" required class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="apellido-<?php echo $vacante['id']; ?>">Apellido</label>
                            <input type="text" id="apellido-<?php echo $vacante['id']; ?>" name="apellido" required class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="correo-<?php echo $vacante['id']; ?>">Correo electrónico</label>
                            <input type="email" id="correo-<?php echo $vacante['id']; ?>" name="correo" required class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="cv-<?php echo $vacante['id']; ?>">Adjuntar CV (PDF, DOCX)</label>
                            <input type="file" id="cv-<?php echo $vacante['id']; ?>" name="cv_file" accept=".pdf,.doc,.docx" required class="form-input" style="padding: 0.5rem;">
                        </div>
                        <button type="submit" class="btn-primary w-100">Enviar postulación</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

        <script src="script.js"></script>
        <?php include('footer.php'); ?>
    </main>
</body>
</html>
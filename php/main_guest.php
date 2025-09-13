<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adiestramiento canino Tahito</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/main_guest_styles.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
</head>
<body>
    <header>
        <a class="img" href="main_guest.php">
            <img src="../recursos/logsinfondo.png" alt="">
        </a>
        <nav>
            <ul id="nav_menu">
                <li><a href="#servicios">Servicios</a></li>
                <li><a href="#">Contacto</a></li>
                <li><a href="#">Trabaja con nosotros</a></li>
            </ul>
        </nav>
        <div id="nav_registro">
            <a id="link_registro" href="registro.php">Registrarse</a>
            <a id="link_login" href="login.php">Ingresar</a>
        </div>
    </header>
    <main>
        <section id="hero">
        <article>
            <div>
            <h2>Adiestramiento Canino Tahito</h2>
            <br>
            <p>
            Adiestramiento positivo CABA<br>
            Técnicas modernas con amor<br>
            Servicios a domicilio<br>
            Consultas por mensaje directo</p>
            <br><br><br>
            <button id="btn_hero"><a href="login.php">Empezar</a></button>
            </div>
            <img src="../recursos/adiestramiento-canino-hero.avif" alt="adiestramiento canino">
        </article>
        </section>
        <section id="servicios">
            <h2>Nuestros Servicios</h2>
            <div id="tarjetas_s">
                <?php
                include('servicios.php');
                ?>
            </div>
        </section>
    </main>
    <?php
    include('footer.php');
    ?>
</body>
<link rel="stylesheet" href="../css/footer_styles.css">
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adiestramiento canino Tahito</title>
    
    <!-- Design System Theme -->
    <link rel="stylesheet" href="../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/main_guest_styles.css?v=<?= time() ?>">
    
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
</head>
<body>
    <header class="container main-header">
        <a class="img" href="main_guest.php">
            <img src="../recursos/logsinfondo.png" alt="Tahito Logo">
        </a>
        <nav>
            <ul id="nav_menu">
                <li><a href="#servicios">Servicios</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                <li><a href="trabaja_con_nosotros.php">Trabaja con nosotros</a></li>
            </ul>
        </nav>
        <div id="nav_registro">
            <a id="link_login" href="login.php" class="nav-link">Ingresar</a>
            <a id="link_registro" href="registro.php" class="btn-primary" style="padding: 0.5rem 1rem;">Registrarse</a>
        </div>
    </header>
    <main>
        <section id="hero" class="container">
            <article class="hero-split">
                <div class="hero-content">
                    <h1>Centro Veterinario <br/><span class="text-accent">Tahito</span></h1>
                    <p class="hero-subtitle">
                        El cuidado de tu mascota es nuestra prioridad. Ofrecemos servicios veterinarios integrales, adiestramiento y tambien contamos con productos para su mascota.
                    </p>
                    <a href="login.php" class="btn-primary mt-4">Comenzar ahora</a>
                </div>
                <div class="hero-visual">
                    <div class="hero-image-wrapper">
                        <img src="../recursos/adiestramiento-canino-hero.avif" alt="adiestramiento canino">
                    </div>
                </div>
            </article>
        </section>
        
        <section id="servicios" class="container">
            <h2>Nuestros Servicios</h2>
            <div id="tarjetas_s" class="services-masonry">
                <?php
                include('servicios.php');
                ?>
            </div>
        </section>
    </main>
    <?php
    include('footer.php');
    ?>
    <script>
      // Sticky header scroll shadow
      const mainHeader = document.querySelector('.main-header');
      window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
          mainHeader.classList.add('scrolled');
        } else {
          mainHeader.classList.remove('scrolled');
        }
      }, { passive: true });
    </script>
</body>
</html>
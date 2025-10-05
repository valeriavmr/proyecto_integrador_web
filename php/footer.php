<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/proyecto_adiestramiento_tahito/css/footer_styles.css?v=<?= time() ?>">
</head>
<body>
    <?php
    echo '
    <footer id="footer">';
    ?>
    <section id="contacto_section">
    <article>
        <h3>Nuestras redes</h3>
        <ul>
            <li><a href="https://instagram.com"><img src="/proyecto_adiestramiento_tahito/recursos/instagram_icon.png" alt="">@adiestramientocanino_tahito</a></li>
            <li><a href="#"><img src="/proyecto_adiestramiento_tahito/recursos/phone_icon.png" alt="">+541122334455</a></li>
            <li><a href="#"><img src="/proyecto_adiestramiento_tahito/recursos/mail_icon.png" alt="">adiestramientocanino_tahito@gmail.com</a></li>
        </ul>
    </article>
    </section>
    <?php
    echo'
        <article>
        <p>Valeria Moreno - Yuskeily Avila</p>
        <p>2025©</p>
        <br>
        </article>
    </footer>';
    ?>
</body>
</html>
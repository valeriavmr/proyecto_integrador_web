<?php
    $lista_servicios = [["Adiestramiento canino", "Servicio de adiestramiento canino a domicilio. Modalidad de suscripción y cita disponibles.",
"../recursos/dog-training.png"],
    ["Paseo canino", "Servicio a domicilio de paseo canino. Horarios flexibles","../recursos/paseo_img.png"],
["Baño y peluquería","Servicio de baño, secado y corte a domicilio","../recursos/banio_img.png"]];


for($i=0;$i<sizeof($lista_servicios);$i++){
    $servicio = $lista_servicios[$i];
    echo "<article>
    <a href='login.php' title='Ingresa para más información sobre " . $servicio[0] . "'>
    <h3>" . $servicio[0] . "</h3><br>
    <p>" . $servicio[1] . "</p><br>
    <img src=" . $servicio[2] . "></a>
    </article>";
}
?>
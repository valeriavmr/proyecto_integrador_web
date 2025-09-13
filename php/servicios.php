<?php
    $lista_servicios = [["Adiestramiento canino", "Servicio de adiestramiento canino a domicilio. Modalidad de suscripción y cita disponibles."],
    ["Paseo canino", "Servicio a domicilio de paseo canino. Horarios flexibles"],
["Baño y peluquería","Servicio de baño, secado y corte a domicilio"]];


for($i=0;$i<sizeof($lista_servicios);$i++){
    $servicio = $lista_servicios[$i];
    echo "<article><h3>" . $servicio[0] . "</h3><p>" .
    $servicio[1] . "</p></article>";
}
?>
<?php
if (session_status() == PHP_SESSION_NONE) { 
                session_start(); 
            }
    
    if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol']!= 'trabajador' && $_SESSION['rol']!='gestor')) {
    // si no es admin, lo redirigís fuera
    header("Location: ../no_autorizado.php");
        exit;
    }
?>
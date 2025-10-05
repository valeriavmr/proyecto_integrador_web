<?php
if (session_status() == PHP_SESSION_NONE) { 
                session_start(); 
            }
    
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    // si no es admin, lo redirigís fuera
    header("Location: ../no_autorizado.php");
        exit;
    }
?>
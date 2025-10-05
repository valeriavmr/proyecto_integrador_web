<?php
    //Para evitar el warning
    if (session_status() == PHP_SESSION_NONE) { 
                session_start(); 
            }
    
    //Si la sesion está activa, borro las variables de sesion y los cookies
    if (session_status() == PHP_SESSION_ACTIVE) {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        header("Location: main_guest.php");
    }
    ?>
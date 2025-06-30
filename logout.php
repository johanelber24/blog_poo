<?php

    require_once './includes/app.php';
    require_once './includes/header.php';

    autenticado();

    if(isset($_SESSION['usuario'])) {
        session_destroy();
    }

    header('location: index.php');

?>
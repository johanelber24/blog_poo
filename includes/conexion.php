<?php

function conectarDB() {

    $servidor = 'localhost';
    $usuario = 'root';
    $password = 'root';
    $database = 'blog_johan';

    $db = mysqli_connect($servidor, $usuario, $password, $database);
    
    if(!$db) {
        echo 'Error no se pudo conectar';
        exit;
    }

    return $db;
}

?>
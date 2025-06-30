<?php

use App\Articulo;

require_once './includes/app.php';

autenticado();

noAutenticado();

// ID ARTICULO
$idarticulo = $_GET['id'];

if(!$idarticulo) {
    header('location: index.php');
}

// Consulta eliminar
$articulo = new Articulo;
$articulo->id = $idarticulo;
$save = $articulo->eliminar();

if($save) {
    header('location: mis-articulos.php');
}

?>
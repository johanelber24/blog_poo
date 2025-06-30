<?php

use App\Comentario;

require_once './includes/app.php';

autenticado();

noAutenticado();

// ID Comentario
$idcomentario = $_GET['id'];
$idarticulo = $_GET['articulo'];

if(!$idcomentario && $idarticulo) {
    header('location: index.php');
}

// Consulta eliminar
$comentario = new Comentario;
$comentario->id = $idcomentario;
$save = $comentario->eliminar();

if($save) {
    header('location: articulo.php?id=' . $idarticulo);
}

?>
<?php

function mostrarError($errores, $campo) {
    $alerta = '';

    if(isset($errores[$campo]) && !empty($campo)) {
        $alerta = "<div class='alerta alerta-error'>" . $errores[$campo] . "</div>";
    }

    return $alerta;
}

function autenticado() {
    if(isset($_SERVER)) {
        session_start();
    }
}

function noAutenticado() {

    if(!isset($_SESSION['usuario'])) {
        header('location: index.php');
    }

}

function estaAutenticado() {

    if(isset($_SESSION['usuario'])) {
        header('location: index.php');
    }

}

function authUsuario() : bool {

    if(isset($_SESSION['usuario'])) {
        return true;
    }

    return false;
}

function consultarCategorias($db) {
    $sql = "SELECT * FROM categorias";
    $response = mysqli_query($db, $sql);

    $categorias = array();

    if($response && mysqli_num_rows($response) >= 1) {
        $categorias = $response;
    }

    return $categorias;
}

function consultarCategoria($db, $id) {
    $sql = "SELECT * FROM categorias WHERE idcategoria = $id";
    $response = mysqli_query($db, $sql);

    $categoria = array();

    $categoria = $response;

    return $categoria;
}

function consultarArticulo($db, $id) {
    $sql = "SELECT a.*, c.nombre AS 'categoria', CONCAT(u.nombres, ' ', u.apellidos) AS 'usuario', u.idusuario "
        . "FROM articulos a " .
           "INNER JOIN categorias c ON a.idcategoria = c.idcategoria ".
           "INNER JOIN usuarios u ON a.idusuario = u.idusuario ".
           "WHERE a.idarticulo = $id";

    $response = mysqli_query($db, $sql);

    $articulo = array();

    if($response && mysqli_num_rows($response) >= 1) {
        $articulo = $response;
    }

    return $articulo;
}

function consultarArticulos($db, $limit = null, $categoria = null, $usuario = null, $busqueda = null) {
    $sql = "SELECT a.*, c.nombre as 'categoria' FROM articulos a " . 
    "INNER JOIN categorias c ON c.idcategoria = a.idcategoria ";

    if(!empty($categoria)) {
        $sql .= "WHERE a.idcategoria = $categoria ";
    }

    if(!empty($usuario)) {
        $sql .= "WHERE a.idusuario = $usuario ";
    }

    if(!empty($busqueda)) {
        $sql .= "WHERE a.titulo LIKE '%$busqueda%'";
    }

    $sql .= "ORDER BY a.idarticulo DESC ";

    if($limit) {
        $sql .= "LIMIT 5";
    }

    $response = mysqli_query($db, $sql);

    $articulos = array();

    if($response && mysqli_num_rows($response) >= 1) {
        $articulos = $response;
    }

    return $articulos;
}

function consultaComentarios($db, $id) {

    $sql = "SELECT c.*, CONCAT(u.nombres, ' ', u.apellidos) AS 'usuario' "
    . "FROM comentarios c " .
       "INNER JOIN usuarios u ON c.idusuario = u.id ".
       "WHERE c.idarticulo = $id ORDER BY c.id ASC";

    $response = mysqli_query($db, $sql);

    $comentarios = array();

    if($response && mysqli_num_rows($response) >= 1) {
        $comentarios = $response;
    }

    return $comentarios;
}

function debuguear($debug) {
    echo '<pre>';
    var_dump($debug);
    echo '</pre>';
}

function s($valor) {
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
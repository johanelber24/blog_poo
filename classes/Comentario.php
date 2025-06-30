<?php

namespace App;

class Comentario extends ActiveRecord {

    protected static $tabla = 'Comentarios';
    protected static $columnasDB = ['id', 'contenido', 'fecha', 'idusuario', 'idarticulo'];

    public $id;
    public $contenido;
    public $fecha;
    public $idusuario;
    public $idarticulo;
    public $usuario;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->contenido = $args['contenido'] ?? '';
        $this->fecha = date('Y-m-d');
        $this->idusuario = $args['idusuario'] ?? null;
        $this->idarticulo = $args['idarticulo'] ?? null;
    }

    public function consultaComentarios($id) {

        $query = "SELECT c.*, CONCAT(u.nombres, ' ', u.apellidos) AS 'usuario' "
        . "FROM comentarios c " .
           "INNER JOIN usuarios u ON c.idusuario = u.id ".
           "WHERE c.idarticulo = $id ORDER BY c.id ASC";


        $resultado = self::consultarSQL($query);
        
        return $resultado;
    }

    public function validarComentario() {
        if(!$this->contenido) {
            self::$errores['comentario'] = 'Debe escribir un comentario';
        } else if(strlen($this->contenido) < 10) {
            self::$errores['comentario'] = 'El comentario debe tener mínimo 10 caracteres';
        }

        return self::$errores;
    }

}
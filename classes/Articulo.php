<?php

namespace App;

class Articulo extends ActiveRecord {

    protected static $tabla = 'Articulos';
    protected static $columnasDB = ['id', 'idcategoria', 'idusuario', 'titulo', 'resumen', 'descripcion', 'fecha'];

    public $id;
    public $idcategoria;
    public $categoria;
    public $idusuario;
    public $usuario;
    public $titulo;
    public $resumen;
    public $descripcion;
    public $fecha;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->idcategoria = $args['idcategoria'] ?? null;
        $this->idusuario = $args['idusuario'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->resumen = $args['resumen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->fecha = date('Y-m-d');
    }

    public function consultarArticulos($limit = null, $categoria = null, $usuario = null, $busqueda = null) {
        $query = "SELECT a.*, c.nombre as 'categoria' FROM articulos a " . 
        "INNER JOIN categorias c ON c.id = a.idcategoria ";
    
        if(!empty($categoria)) {
            $query .= "WHERE a.idcategoria = $categoria ";
        }
    
        if(!empty($usuario)) {
            $query .= "WHERE a.idusuario = $usuario ";
        }
    
        if(!empty($busqueda)) {
            $query .= "WHERE a.titulo LIKE '%$busqueda%'";
        }
    
        $query .= "ORDER BY a.id DESC ";
    
        if($limit) {
            $query .= "LIMIT 5";
        }

        $resultado = self::consultarSQL($query);

        if(!$resultado) {
            self::setError('general', 'Error al preparar la consulta: ' . self::$db->error);
            return false;
        }
        
        return $resultado;
    }

    public function consultarArticulo($id) {
        $query = "SELECT a.*, c.nombre AS 'categoria', CONCAT(u.nombres, ' ', u.apellidos) AS 'usuario' "
        . "FROM articulos a " .
           "INNER JOIN categorias c ON a.idcategoria = c.id ".
           "INNER JOIN usuarios u ON a.idusuario = u.id ".
           "WHERE a.id = $id";

        $resultado = self::consultarSQL($query);
        if(!$resultado) {
            self::setError('general', 'Error al preparar la consulta: ' . self::$db->error);
            return false;
        }

        return array_shift($resultado);
    }

    public function validarArticulo() {
        if(!$this->titulo) {
            self::$errores['titulo'] = 'El campo titulo esta vacío';
        }

        if(!$this->resumen) {
            self::$errores['resumen'] = 'El campo resumen esta vacío';
        } else if(strlen($this->resumen) > 80) {
            self::$errores['resumen'] = 'El resumen no debe tener más de 80 caracteres';
        }

        if(!$this->descripcion) {
            self::$errores['descripcion'] = 'El campo descripcion esta vacío';
        }

        if(!$this->idcategoria) {
            self::$errores['categoria'] = 'No selecciono una categoria';
        }

        return self::$errores;
    }

}
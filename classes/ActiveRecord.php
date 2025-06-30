<?php

namespace App;

class ActiveRecord {

    // Base de datos
    protected static $db;
    protected static $columnasDB;
    protected static $tabla = '';
    protected $id;

    // Validación
    protected static $errores = [];

    // Conexion a BD
    public static function setDB($database) {
        self::$db = $database;
    }

    // Validacion - mostrar errores
    public static function setError($tipo, $mensaje) {
        static::$errores[$tipo] = $mensaje;
    }

    public function validar() {
        static::$errores = [];
        return static::$errores;
    }

    public static function getErrores() {
        return static::$errores;
    }

    // Crear objeto en memoria que es igual al de la DB
    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach($registro as $key => $value) {
            if(property_exists( $objeto, $key )) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    // Consulta SQL para crear objeto en Memoria
    public static function consultarSQL($query) {
        // Consultar a la DB
        $resultado = self::$db->query($query);

        // Iterar resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        // Liberar la memoria
        $resultado->free();

        // retornar los resultados
        return $array;
    } 


    // REGISTROS
    // Listar todo
    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Buscar por registro unico
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = " . $id;
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }

    // Buscar registro por columna
    public static function where($columna, $valor) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE " . $columna . " = '$valor'";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }

    // Consulta Libre
    public static function SQL($consulta) {
        $query = $consulta;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Obtener Registros con cierta cantidad
    public static function get($limite) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT $limite";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Identificar y unir los atributos de la BD
    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    // Sanitizar los datos antes de guardarlos en la BD
    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value ) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }

        return $sanitizado;
    }

    // Sincroniza BD con Objetos en memoria
    public function sincronizar($args=[]) { 
        foreach($args as $key => $value) {
            if(property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    // CRUD
    // Guardar consulta
    public function guardar() {
        $resultado = '';
        if(!is_null($this->id)) {
            // Actualizar
            $resultado = $this->actualizar();
        } else {
            // Crear
            $resultado = $this->crear();
        }

        return $resultado;
    }

    // Crear
    public function crear() {
        // Primero sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // INSERTAR
        $query = "INSERT INTO " . static::$tabla . " ( ";
        $query .= join(',', array_keys($atributos));
        $query .= " ) VALUES ('";
        $query .= join("', '", array_values($atributos));
        $query .= "') ";

        // Resultado de la consulta
        $resultado = self::$db->query($query);

        return $resultado;
    }

    // Actualizar
    public function actualizar() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Iterar para ir agregando cada campo de la BD
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        // Consulta SQL
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 "; 

        // Actualizar BD
        $resultado = self::$db->query($query);
        return $resultado;
    }

    // Eliminar un Registro por su ID
    public function eliminar() {
        $query = "DELETE FROM "  . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";

        $resultado = self::$db->query($query);
        return $resultado;
    }

}
?>
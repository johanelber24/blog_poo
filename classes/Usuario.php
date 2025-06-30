<?php

namespace App;

class Usuario extends ActiveRecord {

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombres', 'apellidos', 'email', 'password', 'pin', 'fecha'];

    public $id;
    public $nombres;
    public $apellidos;
    public $email;
    public $password;
    public $pin;
    public $fecha;

    public function __construct($args = [])
    {
        $this->id = $args['idusuario'] ?? NULL;
        $this->nombres = $args['nombres'] ?? '';
        $this->apellidos = $args['apellidos'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->pin = $args['pin'] ?? '';
        $this->fecha = date('Y-m-d');
    }

    public function validarRegister() {
        if(!$this->nombres) {
            self::$errores['nombres'] = 'El campo nombre esta vacío';
        } else if(!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $this->nombres)) {
            self::$errores['nombres'] = 'El nombre no es válido';
        }

        if(!$this->apellidos) {
            self::$errores['apellidos'] = 'El campo apellido esta vacío';
        } else if(!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $this->apellidos)) {
            self::$errores['apellidos'] = 'El apellido no es válido';
        }

        if(!$this->email) {
            self::$errores['email'] = 'El campo email esta vacío';
        } else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$errores['email'] = 'El email no es válido';
        }

        if(!$this->password) {
            self::$errores['password'] = 'El campo password esta vacío';
        } else if(strlen($this->password) < 6) {
            self::$errores['password'] = 'El password debe tener mínimo 6 caracteres';
        }

        if(!$this->pin) {
            self::$errores['pin'] = "El campo pin esta vacío";
        } else if(!preg_match('/^\d{8}$/', $this->pin)) {
            self::$errores['pin'] = "El pin no es válido";
        }

        return self::$errores;
    }

    // Hashear password
    public function hashearPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function validarLogin() {
        if(!$this->email) {
            self::$errores['email'] = 'El campo email esta vacío';
        } else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$errores['email'] = 'El email no es válido';
        }

        if(!$this->password) {
            self::$errores['password'] = 'El campo password esta vacío';
        }

        return self::$errores;
    }

    // Comprobar password
    public function comprobarPassword($password) {
        $resultado = password_verify($password, $this->password);

        if(!$resultado) {
            self::$errores['password'] = 'El password es incorrecto';
        } else {
            return true;
        }
    }

    // Usuario ya existe (register)
    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = ? LIMIT 1";
        $stmt = self::$db->prepare($query);

        if(!$stmt) {
            self::setError('general', 'Error al preparar la consulta: ' . self::$db->error);
            return false;
        }

        $stmt->bind_param('s', $this->email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if($resultado && $resultado->num_rows) {
            self::$errores['email'] = 'Este email ya se encuentra en uso';
        }

        $stmt->close();

        return $resultado;
    }

    // Validar reset password
    public function validarReset() {
        if(!$this->email) {
            self::$errores['email'] = 'El campo email esta vacío';
        } else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$errores['email'] = 'El email no es válido';
        }

        if(!$this->pin) {
            self::$errores['pin'] = 'El campo pin esta vacío';
        } else if(!preg_match('/^\d{8}$/', $this->pin)) {
            self::$errores['pin'] = 'El pin no es válido';
        }

        if(!$this->password) {
            self::$errores['password'] = 'El campo password esta vacío';
        } else if(strlen($this->password) < 6) {
            self::$errores['password'] = 'El password debe tener mínimo 6 caracteres';
        }

        return self::$errores;
    }

    public function cambiarPassword($password) {
        $query = "UPDATE usuarios SET password = ? WHERE email = ?";
        $stmt = self::$db->prepare($query);

        if (!$stmt) {
            self::$errores['general'] = 'Error al preparar la consulta: ' . self::$db->error;
            return false;
        }

        $stmt->bind_param('ss', $password, $this->email);
        $resultado = $stmt->execute();

        $stmt->close();
        
        return $resultado;
    }

    public function validarCambiarPerfil() {

        if(!$this->nombres) {
            self::$errores['nombres'] = "El campo nombre esta vacío";
        } else if(is_numeric($this->nombres)) {
            self::$errores['nombres'] = "El nombre no es válido";
        }

        if(!$this->apellidos) {
            self::$errores['apellidos'] = "El campo apellido esta vacío";
        } else if(is_numeric($this->apellidos)) {
            self::$errores['apellidos'] = "Los apellidos no son válidos";
        }

        if(!$this->email) {
            self::$errores['email'] = 'El campo email esta vacío';
        } else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$errores['email'] = 'El email no es válido';
        }

        if(!$this->pin) {
            self::$errores['pin'] = 'El campo pin esta vacío';
        } else if(!preg_match('/^\d{8}$/', $this->pin)) {
            self::$errores['pin'] = 'El pin no es válido';
        }

        return self::$errores;
    }
}

?>
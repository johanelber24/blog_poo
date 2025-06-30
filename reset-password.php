<?php

use App\Usuario;

    $titulo_page = 'Restablecer Contraseña';
    require_once './includes/app.php';
    require_once './includes/header.php';

    estaAutenticado();

    // Usuario
    $usuario = new Usuario;

    // Errores
    $errores = Usuario::getErrores();

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Sincronizando
        $usuario->sincronizar($_POST);

        // Validar
        $errores = $usuario->validarReset();
            
        // Si los campos estan completos
        if(empty($errores)) {
            $usuarioPin = Usuario::where('email', $usuario->email);

            if($usuarioPin) {
                // Verificar pin
                if($usuario->pin === $usuarioPin->pin) {
                    // Si todo esta bien
                    $usuario->hashearPassword();
                    
                    $resultado = $usuario->cambiarPassword($usuario->password);

                    if($resultado) {
                        header('location: login.php');
                    }

                } else {
                    Usuario::setError('pin', 'El pin es incorrecto');
                    $errores = Usuario::getErrores();
                }
            } else {
                Usuario::setError('email', 'No existe usuario con este email');
                $errores = Usuario::getErrores();
            }
        }
    }
?>

    <div class="layout__blog-form">
        <div class="blog-form">
            <h2 class="blog-form__title">Restablecer Contraseña</h2>

            <form class="blog-form__formulario" method="POST">
                <div class="formulario__campo">
                    <label for="email" class="formulario__label">Email</label>
                    <input type="email" name="email" id="email" class="formulario__input" value="<?php
                        echo s($usuario->email ?? '');
                    ?>" />
                    <?php
                        if(!empty($errores['email'])) {
                            echo mostrarError($errores, 'email');
                        }
                    ?>
                </div>
                <div class="formulario__campo">
                    <label for="pin" class="formulario__label">PIN Respaldo</label>
                    <input type="text" name="pin" id="pin" class="formulario__input" value="<?php
                        echo s($usuario->pin ?? '');
                    ?>" />
                    <?php
                        if(!empty($errores['pin'])) {
                            echo mostrarError($errores, 'pin');
                        }
                    ?>
                </div>

                <div class="formulario__campo">
                    <label for="password" class="formulario__label">Nueva Contraseña</label>
                    <input type="password" name="password" id="password" class="formulario__input" />
                    <?php
                        if(!empty($errores['password'])) {
                            echo mostrarError($errores, 'password');
                        }
                    ?>
                </div>

                <input type="submit" class="formulario__submit" value="Restablecer Contraseña">
            </form>

            <div class="blog-form__links">
                <a href="login.php" class="blog-form__link">¿Ya tienes una cuenta? Inicia Sesión</a>
            </div>
        </div>
    </div>

<?php
    require_once './includes/footer.php';
?>
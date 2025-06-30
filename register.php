<?php

    $titulo_page = 'Registro';
    require_once './includes/app.php';
    require_once './includes/header.php';

    use App\Usuario;

    estaAutenticado();

    // Usuario
    $usuario = new Usuario;

    // Errores
    $errores = Usuario::getErrores();
    
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Asignando POST a usuario, sincronizando...
        $usuario->sincronizar($_POST);

        // Validar
        $errores = $usuario->validarRegister();

        // Si no hay errores
        if(empty($errores)) {
            // Verificar si el email ya esta registrado
            $resultado = $usuario->existeUsuario();
            
            if($resultado->num_rows) {
                $errores = Usuario::getErrores();
            } else {
                // Hashear password
                $usuario->hashearPassword();

                // Crear usuario
                $guardar = $usuario->guardar();
                
                // Redireccionar
                if($guardar) {
                    header('location: login.php');
                }
            }
        }
    }
?>

    <div class="layout__blog-form">
        <main class="blog-form">
            <h2 class="blog-form__title">Registro</h2>

            <form class="blog-form__formulario" method="POST">
                <div class="formulario__campo">
                    <label for="nombres" class="formulario__label">Nombres</label>
                    <input type="text" name="nombres" id="nombres" class="formulario__input" value="<?php
                        echo s($usuario->nombres ?? '');
                    ?>"  />
                    <?php
                        if(!empty($errores['nombres'])) {
                            echo mostrarError($errores, 'nombres');
                        }
                    ?>
                </div>
                <div class="formulario__campo">
                    <label for="apellidos" class="formulario__label">Apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" class="formulario__input" value="<?php
                        echo s($usuario->apellidos ?? '');
                    ?>"  />
                    <?php
                        if(!empty($errores['apellidos'])) {
                            echo mostrarError($errores, 'apellidos');
                        }
                    ?>
                </div>
                <div class="formulario__campo">
                    <label for="email" class="formulario__label">Email</label>
                    <input type="email" name="email" id="email" class="formulario__input" value="<?php
                        echo s($usuario->email ?? '');
                    ?>"  />
                    <?php
                        if(!empty($errores['email'])) {
                            echo mostrarError($errores, 'email');
                        }
                    ?>
                </div>
                <div class="formulario__campo">
                    <label for="password" class="formulario__label">Contraseña</label>
                    <input type="password" name="password" id="password" class="formulario__input"  />
                    <?php
                        if(!empty($errores['password'])) {
                            echo mostrarError($errores, 'password');
                        }
                    ?>
                </div>

                <div class="formulario__campo">
                    <label for="pin" class="formulario__label">PIN Respaldo (Número 8 digitos)</label>
                    <input type="text" name="pin" class="formulario__input" value="<?php
                        echo s($usuario->pin ?? '');
                    ?>"  />
                    <?php
                        if(!empty($errores['pin'])) {
                            echo mostrarError($errores, 'pin');
                        }
                    ?>
                </div>

                <input type="submit" class="formulario__submit" value="Registrarse">
            </form>

            <div class="blog-form__links">
                <a href="login.php" class="blog-form__link">¿Ya tienes una cuenta? Inicia Sesión</a>
            </div>
        </main>
    </div>

<?php
    require_once './includes/footer.php';
?>
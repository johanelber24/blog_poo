<?php

use App\Usuario;

    $titulo_page = 'Editar Perfil';
    require_once './includes/app.php';
    require_once './includes/header.php';

    // Si no esta autenticado
    noAutenticado();

    // Recoger datos del usuario logeado
    $idusuario = $_SESSION['usuario']->id;

    // Usuario
    $usuario = new Usuario;
    $usuario = $usuario::find($idusuario);

    // Errores
    $errores = Usuario::getErrores();

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Sincronizar datos
        $usuario->sincronizar($_POST);

        // Errores
        $errores = $usuario->validarCambiarPerfil();

        // Si no hay errores
        if(empty($errores)) {

            $guardar = $usuario->guardar();

            if($guardar) {
                $_SESSION['usuario'] = $usuario;
                header('location: perfil.php');
            }

        }
    }
?>

    <main class="layout__blog-form">
        <div class="blog-form">
            <h2 class="blog-form__title">Editar Perfil</h2>

            <form class="blog-form__formulario" method="POST">
                <div class="formulario__campo">
                    <label for="nombres" class="formulario__label">Nombre</label>
                    <input type="text" name="nombres" id="nombres" class="formulario__input" value="<?php
                        echo s($usuario->nombres ?? '');
                    ?>" />
                    <?php
                        if(!empty($errores['nombres'])) {
                            echo mostrarError($errores, 'nombres');
                        }
                    ?>
                </div>
                <div class="formulario__campo">
                    <label for="apellidos" class="formulario__label">Apellido</label>
                    <input type="text" name="apellidos" id="apellidos" class="formulario__input" value="<?php
                        echo s($usuario->apellidos ?? '');
                    ?>" />
                    <?php
                        if(!empty($errores['apellidos'])) {
                            echo mostrarError($errores, 'apellidos');
                        }
                    ?>
                </div>

                <div class="formulario__campo">
                    <label for="pin" class="formulario__label">PIN Respaldo (Número 8 digitos)</label>
                    <input type="text" name="pin" id="pin" class="formulario__input" value="<?php
                        echo s($usuario->pin ?? '');
                    ?>" />
                    <?php
                        if(!empty($errores['pin'])) {
                            echo mostrarError($errores, 'pin');
                        }
                    ?>
                </div>

                <input type="submit" class="formulario__submit" value="Actualizar datos">
            </form>
        </div>
    </main>

<?php
    require_once './includes/footer.php';
?>
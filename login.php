<?php
    $titulo_page = 'Login';
    require_once './includes/app.php';
    require_once './includes/header.php';

    use App\Usuario;

    estaAutenticado();

    // Usuario
    $usuario = new Usuario;

    // Errores
    $errores = Usuario::getErrores();

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $auth = new Usuario($_POST);

        $errores = $auth->validarLogin();

        // Si no hay errores
        if(empty($errores)) {
            // verificar que el usuario exista
            $usuario = Usuario::where('email', $auth->email);

            // Si hay usuario
            if($usuario) {
                // debuguear($auth);
                // Verificamos si el password ingresado es correcto
                if($usuario->comprobarPassword($auth->password)) {
                    session_start();

                    $_SESSION['usuario'] = $usuario;
                    header('location: index.php');
                } else {
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
        
        <main class="blog-form">
            <h2 class="blog-form__title">Login</h2>

            <form class="blog-form__formulario" method="POST">
                <div class="formulario__campo">
                    <label for="email" class="formulario__label">Email</label>
                    <input type="email" name="email" id="email" class="formulario__input" value="<?php
                        echo s($auth->email ?? '');
                    ?>" />
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

                <input type="submit" class="formulario__submit" value="Iniciar Sesión">
            </form>

            <div class="blog-form__links">
                <a href="register.php" class="blog-form__link">¿No tienes una cuenta? Registrate</a>
                <a href="reset-password.php" class="blog-form__link">¿Olvidaste tu contraseña?</a>
            </div>
        </main>

    </div>

<?php
    require_once './includes/footer.php';
?>
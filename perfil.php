<?php

use App\Usuario;

    $titulo_page = 'Mi Perfil';
    require_once './includes/app.php';
    require_once './includes/header.php';

    noAutenticado();

    // Recoger datos del usuario logeado
    $idusuario = $_SESSION['usuario']->id;

    // Usuario
    $usuario = new Usuario;
    $usuario = $usuario::find($idusuario);

?>

    <main class="layout__perfil container">
        <div class="perfil">
            <h2 class="perfil__title">Mi Perfil</h2>

            <div class="perfil__info">
                <p class="perfil__nombres">Nombres: <?php echo s($usuario->nombres); ?></p>
                <p class="perfil__apellidos">Apellidos: <?php echo s($usuario->apellidos); ?></p>
                <p class="perfil__correo">Correo: <?php echo s($usuario->email); ?></p>
                <p class="perfil__pin">PIN: <?php echo s($usuario->pin); ?></p>

                <a href="editar-perfil.php" class="perfil__editar">Editar Perfil</a>
            </div>
        </div>
    </main>

<?php
    require_once './includes/footer.php';
?>
<?php

use App\Articulo;
use App\Comentario;

    $titulo_page = 'Articulo';
    require_once './includes/app.php';
    require_once './includes/header.php';

    // ID ARTICULO
    $idarticulo = $_GET['id'];

    if(!$idarticulo) {
        header('location: index.php');
    }

    // Consulta articulos
    $articulo = new Articulo;
    $articuloConsulta = $articulo->consultarArticulo($idarticulo);

    if(!$articuloConsulta) {
        header('location: index.php');
    }

    // Consulta comentarios
    $comentario = new Comentario;
    $comentarios = $comentario->consultaComentarios($idarticulo);

    // Solo pueden comentar usuarios registrados
    $auth = authUsuario();

    // Cantidad comentarios
    $cantComentarios = 0;

    if(!empty($comentarios)) {
        $cantComentarios = count($comentarios);
    }

    // Errores
    $errores = Comentario::getErrores();

    // Publicar comentario
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $comentario->sincronizar($_POST);

        // Validar
        $errores = $comentario->validarComentario();

        // Si no hay errores
        if(empty($errores)) {
            // ID usuario
            $idusuario = $_SESSION['usuario']->id;

            // Asignando id usuario y articulo
            $comentario->idusuario = $idusuario;
            $comentario->idarticulo = $idarticulo;

            // Consulta
            $guardar = $comentario->guardar();


            if($guardar) {
                header('location: articulo.php?id='. $idarticulo);
            }
        }
    }

?>

    <div class="layout__blog container">
        <main class="layout__main">

            <article class="articulo articulo--v2">
                <h2 class="articulo__title articulo__title--v2"><?php echo s($articuloConsulta->titulo); ?></h2>
                <p class="articulo__info articulo__info--v2"><?php echo '(ID: ' .$articuloConsulta->idusuario . ') ' . s($articuloConsulta->usuario); ?> | <?php echo s($articuloConsulta->fecha); ?></p>
                <a href="categoria.php?id=<?php echo s($articuloConsulta->idcategoria); ?>" class="articulo__categoria-link"><?php echo s($articuloConsulta->categoria); ?></a>
                <p class="articulo__description articulo__description--v2"><?php echo s($articuloConsulta->descripcion); ?></p>
                <?php
                if(isset($_SESSION['usuario']) && $_SESSION['usuario']->id === $articuloConsulta->idusuario):
                ?>
                <div class="articulo__options">
                    <a class="articulo-options__btn articulo-options__btn--editar" href="editar-articulo.php?id=<?php echo $articuloConsulta->id; ?>"><i class="fa-solid fa-edit"></i> Editar</a>
                    <a class="articulo-options__btn articulo-options__btn--eliminar" href="eliminar-articulo.php?id=<?php echo $articuloConsulta->id; ?>"><i class="fa-solid fa-trash-alt"></i> Eliminar</a>
                </div>
                <?php
                endif;
                ?>
                <div class="articulo__comentarios">
                    <h3 class="articulo-comentarios__title">Comentarios (<?php echo s($cantComentarios); ?>)</h3>

                    <?php
                    if($auth):
                    ?>
                    <form method="POST" class="formulario-comentarios">
                        <div class="formulario__campo">
                            <textarea name="contenido" id="contenido" rows="3" class="formulario__input formulario__input--comentarios" placeholder="Escribe un comentario..."><?php
                            ?></textarea>
                            <?php
                                if(!empty($errores['comentario'])) {
                                    echo mostrarError($errores, 'comentario');
                                }
                            ?>
                        </div>

                        <input type="submit" class="formulario__submit formulario__submit--comentarios" value="Comentar">
                    </form>
                    <?php
                    endif;
                    ?>

                    <?php
                    if(empty($comentarios)):
                    ?>
                    <p class="articulo-comentarios__vacio">Aún no hay comentarios</p>

                    <?php
                    endif;
                    ?>

                    <?php
                    if(!empty($comentarios)):
                    foreach($comentarios as $comentarioX):
                    ?>
                    <div class="articulo__comentario">
                        <div class="articulo-comentario__bt">
                            <?php
                            // CHECK AL USUARIO AUTOR DEL ARTICULO
                            if($comentarioX->idusuario == $articuloConsulta->idusuario) {
                                $userAutor = '<span class="articulo-comentario__autor"><i class="fa-solid fa-certificate" title="Autor del artículo"></i> Autor</span>';
                            } else {
                                $userAutor = '';
                            }
                            ?>
                            <h4 class="articulo-comentario__user"><?php echo '(ID: ' . s($comentarioX->idusuario) . ') ' . s($comentarioX->usuario) . $userAutor; ?></h4>
                            <p class="articulo-comentario__content"><?php echo s($comentarioX->contenido); ?></p>
                            <p class="articulo-comentario__fecha"><?php echo s($comentarioX->fecha); ?></p>
                            <?php 
                            if(isset($_SESSION['usuario']) && $_SESSION['usuario']->id == $comentarioX->idusuario):
                            ?>
                            <a class="articulo-comentario__eliminar" href="eliminar-comentario.php?id=<?php echo $comentarioX->id . "&articulo=" . $idarticulo; ?>">Eliminar</a>
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>
                    <?php
                    endforeach;
                    endif;
                    ?>
                </div>
            </article>

        </main>

        <?php
            require_once './includes/aside.php';
        ?>
    </div>

<?php
    require_once './includes/footer.php';
?>
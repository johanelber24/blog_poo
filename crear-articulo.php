<?php

use App\Articulo;
use App\Categoria;

    $titulo_page = 'Nuevo Artículo';
    require_once './includes/app.php';
    require_once './includes/header.php';

    noAutenticado();

    // Categorias
    $categoria = new Categoria;
    $categorias = $categoria::all();

    // Articulo
    $articulo = new Articulo;

    // Errores
    $errores = Articulo::getErrores();

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Id categoria
        $idcategoria = isset($_POST['idcategoria']) ? $_POST['idcategoria'] : '';
        $articulo->idcategoria = $idcategoria;

        $articulo->sincronizar($_POST);
        $articulo->idusuario = $_SESSION['usuario']->id;

        $errores = $articulo->validarArticulo();

        // Si no hay errores
        if(empty($errores)) {
            $guardar = $articulo->guardar();

            if($guardar) {
                header('location: mis-articulos.php');
            }
        }
    }
?>

    <div class="layout__blog-form">
        
        <main class="blog-form">
            <h2 class="blog-form__title">Nuevo Artículo</h2>

            <form class="blog-form__formulario" method="POST">
                <div class="formulario__campo">
                    <label for="titulo" class="formulario__label">Titulo</label>
                    <input type="titulo" name="titulo" id="titulo" class="formulario__input" value="<?php
                        echo s($articulo->titulo ?? '');
                    ?>" />
                    <?php
                        if(!empty($errores['titulo'])) {
                            echo mostrarError($errores, 'titulo');
                        }
                    ?>
                </div>

                <div class="formulario__campo">
                    <label for="resumen" class="formulario__label">Resumen</label>
                    <textarea name="resumen" id="resumen" rows="2" class="formulario__input"><?php
                        echo s($articulo->resumen ?? '');
                    ?></textarea>
                    <?php
                        if(!empty($errores['resumen'])) {
                            echo mostrarError($errores, 'resumen');
                        }
                    ?>
                </div>

                <div class="formulario__campo">
                    <label for="descripcion" class="formulario__label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="5" class="formulario__input"><?php
                        echo s($articulo->descripcion ?? '');
                    ?></textarea>
                    <?php
                        if(!empty($errores['descripcion'])) {
                            echo mostrarError($errores, 'descripcion');
                        }
                    ?>
                </div>

                <div class="formulario__campo">
                    <label for="categoria" class="formulario__label">Categoria</label>
                    <select name="idcategoria" id="categoria" class="formulario__input">
                        <?php 
                            if(!empty($categorias)):
                            foreach($categorias as $categoria):
                        ?>
                            <option value="<?php echo $categoria->id; ?>"
                            <?php if($categoria->id === $articulo->idcategoria) { echo 'selected'; }?>
                            >
                                <?php echo $categoria->nombre; ?>
                            </option>
                        <?php
                            endforeach;
                            endif;
                        ?>
                    </select>
                    <?php
                        if(!empty($errores['categoria'])) {
                            echo mostrarError($errores, 'categoria');
                        }
                    ?>
                </div>

                <input type="submit" class="formulario__submit" value="Guardar">
            </form>
        </main>

    </div>

<?php
    require_once './includes/footer.php';
?>
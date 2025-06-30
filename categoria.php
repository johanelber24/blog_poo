<?php

use App\Articulo;
use App\Categoria;

    $titulo_page = 'Categoria';
    require_once './includes/app.php';
    require_once './includes/header.php';


    // Consulta id
    if(empty($_GET['id'])) {
        header('location: index.php');
    }

    // Id categoria
    $idcategoria = $_GET['id'];

    // Categoria
    $categoria = new Categoria;
    $categorias = $categoria::find($idcategoria);

    // Articulos por categoria
    $articulo = new Articulo;
    $articulos = $articulo->consultarArticulos(null,$idcategoria);

    if(!$categorias) {
        header('location: index.php');
    }

?>

    <div class="layout__blog container">
        <main class="layout__main">
            <h2 class="main__title">Articulos de <?php echo htmlspecialchars($categorias->nombre); ?></h2>

            <p class="categoria__descripcion"><?php echo htmlspecialchars($categorias->descripcion); ?></p>

            <section class="articulos">

                <?php
                if(!empty($articulos)):
                foreach($articulos as $articulo):
                ?>
                <article class="articulo">
                    <a href="articulo.php?id=<?php echo htmlspecialchars($articulo->id); ?>" class="articulo__link">
                        <h3 class="articulo__title"><?php echo htmlspecialchars($articulo->titulo); ?></h3>
                        <p class="articulo__info"><?php echo htmlspecialchars($articulo->categoria); ?> | <?php echo htmlspecialchars($articulo->fecha); ?></p>
                        <p class="articulo__description"><?php echo htmlspecialchars($articulo->resumen);?></p>
                    </a>
                </article>
                <?php
                endforeach;
                elseif(empty($articulos)):
                ?>
                <div class="alerta alerta-error">
                    No hay articulos para mostrar
                </div>
                <?php
                endif;
                ?>
            </section>
        </main>

        <?php
            require_once './includes/aside.php';
        ?>
    </div>

<?php
    require_once './includes/footer.php';
?>
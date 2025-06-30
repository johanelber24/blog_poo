<?php

use App\Articulo;

    $titulo_page = 'Artículos';
    require_once './includes/app.php';
    require_once './includes/header.php';

    // Consultar si hay articulos, si no hay no se busca nada
    $articulo = new Articulo;
    $articulosConsulta = $articulo->all();

    if(empty($articulosConsulta)) {
        header('location: index.php');
    }

    // Busqueda string
    $busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';

    if(strlen($busqueda) < 2) {
        header('location: index.php');
    }

    // Consulta busqueda
    $articulos = $articulo->consultarArticulos(null,null,null,$busqueda);

?>

    <div class="layout__blog container">
        <main class="layout__main">
            <h2 class="main__title">Búsqueda: <?php echo s($busqueda); ?></h2>

            <section class="articulos">

                <?php
                if(!empty($articulos)):
                foreach($articulos as $articulo):
                ?>
                <article class="articulo">
                    <a href="articulo.php?id=<?php echo s($articulo->id); ?>" class="articulo__link">
                        <h3 class="articulo__title"><?php echo s($articulo->titulo); ?></h3>
                        <p class="articulo__info"><?php echo s($articulo->categoria); ?> | <?php echo s($articulo->fecha); ?></p>
                        <p class="articulo__description"><?php echo s($articulo->resumen); ?></p>
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
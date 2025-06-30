<?php

use App\Articulo;

    $titulo_page = 'Inicio';
    require_once './includes/app.php';
    require_once './includes/header.php';

    // Consulta articulos
    $articulo = new Articulo;
    $articulos = $articulo->consultarArticulos(true);
?>

    <div class="layout__blog container">
        <main class="layout__main">
            <h2 class="main__title">Últimos Artículos</h2>

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
                ?>
                <div class="articulos__ver-todos">
                    <a href="articulos.php" class="btn__ver-articulos">Ver todos los articulos</a>
                </div>
                <?php
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
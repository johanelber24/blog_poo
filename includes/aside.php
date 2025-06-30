<?php

    use App\Categoria;
    require_once './includes/app.php';

    // Categoria
    $categoria = new Categoria;
    $categorias = $categoria::all();

?>

<aside class="layout__aside">
    <div class="aside__buscador">
        <h3 class="buscador__title">Buscar</h3>

        <form action="buscar.php" method="POST" class="buscador__formulario">
            <div class="buscador__contain">
                <input type="text" name="busqueda" class="buscador__input" minlength="2" required />
                <button type="submit" value="Buscar" class="buscador__submit"><i class="fa-solid fa-search"></i></button>
            </div>
        </form>
    </div>

    <div class="aside__categorias">
        <h3 class="categorias__title">Categorias</h3>

        <ul class="categorias__list">
            <?php
            if(!empty($categorias)):
            foreach($categorias as $categoria):
            ?>
            <li class="categorias__item">
                <i class="fa-solid fa-check"></i><a href="categoria.php?id=<?php echo $categoria->id; ?>" class="categorias__link"><?php echo $categoria->nombre; ?></a>
            </li>
            <?php
            endforeach;
            elseif(empty($categorias)):
            ?>
            <div class="alerta alerta-error">No hay categorias para mostrar</div>
            <?php
            endif;
            ?>
        </ul>
    </div>
</aside>
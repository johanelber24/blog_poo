<?php
    $titulo_page = 'Editar Categoria';
    require_once './includes/app.php';
    require_once './includes/header.php';

    noAutenticado();

    // Consulta id
    if(!empty($_GET['id'])) {
        $idcategoria = $_GET['id'];

        $sql = "SELECT * FROM categorias WHERE idcategoria = $idcategoria";
        $response = mysqli_query($db, $sql);

        $categoria = mysqli_fetch_assoc($response);
        $nombre = $categoria['nombre'];
    } else {
        header('location: categorias.php');
    }


    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nombre = isset($_POST['nombre']) ? mysqli_real_escape_string($db, $_POST['nombre']) : false;

        // Validar
        $errores = array();
        if(empty($nombre)) {
            $errores['nombre'] = 'El campo nombre esta vació';
        } else if(is_numeric($nombre)) {
            $errores['nombre'] = 'El nombre no es válido';
        }

        // Si no hay errores
        if(count($errores) === 0) {
            // query
            $sql = "UPDATE categorias SET nombre = '$nombre' WHERE idcategoria = $idcategoria";
            $save = mysqli_query($db, $sql);

            if($save) {
                header('location: categorias.php');
            }
        }
    }
?>

    <div class="layout__blog-form">
        
        <main class="blog-form">
            <h2 class="blog-form__title">Editar Categoria</h2>

            <form class="blog-form__formulario" method="POST">
                <div class="formulario__campo">
                    <label for="nombre" class="formulario__label">Nombre</label>
                    <input type="nombre" name="nombre" id="nombre" class="formulario__input" value="<?php
                        if(!empty($nombre)) {
                            echo $nombre;
                        } else {
                            echo '';
                        }
                    ?>" />
                    <?php
                        if(!empty($errores['nombre'])) {
                            echo mostrarError($errores, 'nombre');
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
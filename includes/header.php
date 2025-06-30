<?php

    // Errores
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once 'helpers.php';

    // Iniciar Session
    autenticado();

    // Usuario
    $auth = authUsuario();

    if($auth) {
        $user = $_SESSION['usuario']->nombres . ' ' . $_SESSION['usuario']->apellidos;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Johan Elber">
    <meta name="description" content="Proyecto BLOG X">
    <title>Blog X | <?php echo htmlspecialchars($titulo_page); ?></title>
    <!-- FONTS -->
    <link rel="stylesheet" href="/assets/fonts/fontawesome-free-6.1.2-web/css/all.min.css">
    <link rel="stylesheet" href="/assets/fonts/roboto/roboto.css">
    <!-- STYLES -->
    <link rel="stylesheet" href="/assets/css/normalize.css">
    <link rel="stylesheet" href="/assets/css/generals.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
    
    <div class="layout">

        <header class="layout__header">
            <div class="header__container container">
                <div class="header__left">
                    <a href="index.php" class="header__link">
                        <h1 class="header__logo">BLOG X</h1>
                    </a>
                </div>

                <div class="header__right">
                    <?php if(!isset($_SESSION['usuario'])) : ?>
                        <div class="header__nav-responsive">
                            <div class="nav-responsive__burger">
                                <i class="fa-solid fa-bars"></i>
                            </div>
                            <div class="nav-responsive__content">
                                <nav class="nav-responsive__list">
                                    <a href="login.php" class="nav__link nav__link--login">Iniciar Sesión</a>
                                    <a href="register.php" class="nav__link nav__link--register">Registrarse</a>
                                </nav>
                            </div>
                        </div>
                        <nav class="header__nav">
                            <a href="login.php" class="nav__link nav__link--login">Iniciar Sesión</a>
                            <a href="register.php" class="nav__link nav__link--register">Registrarse</a>
                        </nav>
                        <?php endif; ?>
                        

                        <?php if(isset($_SESSION['usuario'])) : ?>
                        <p class="user-welcome">Bienvenido: <?php echo htmlspecialchars($user); ?></p>
                        <div class="user-dropdown__content">
                            <div class="user-dropdown__icon">
                                <i class="fa-solid fa-user"></i>
                            </div>

                            <div class="user-dropdown__menu">
                                <p class="user-dropdown__username"><?php echo htmlspecialchars($user); ?></p>
                                <nav class="user-dropdown__nav">
                                    <ul class="user-dropdown__list">
                                        <li class="user-dropdown__item">
                                            <a href="perfil.php" class="user-dropdown__link"><span class="user-dropdown__link-icon"><i class="fa-solid fa-user-tie"></i></span>Perfil</a>
                                        </li>
                                        <li class="user-dropdown__item">
                                            <a href="mis-articulos.php" class="user-dropdown__link"><span class="user-dropdown__link-icon"><i class="fa-solid fa-laptop"></i></span>Mis Artículos</a>
                                        </li>
                                        <li class="user-dropdown__item">
                                            <a href="logout.php" class="user-dropdown__link"><span class="user-dropdown__link-icon"><i class="fa-solid fa-right-from-bracket"></i></span>Cerrar Sesión</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <div class="layout__banner">
            <div class="layout__container container">
                <div class="banner__info">
                    <h2 class="banner__title">Bienvenido a BLOG X</h2>
                    <p class="banner__text">Historias, pensamientos y un poco de café.</p>
                </div>
            </div>
        </div>


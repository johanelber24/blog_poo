<?php

require_once 'conexion.php';
require_once 'helpers.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Conectamos a la bd
$db = conectarDB();

use App\ActiveRecord;

ActiveRecord::setDB($db);

?>
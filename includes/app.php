<?php

use Dotenv\Dotenv;
use Model\ActiveRecord;
use Model\Database;
require __DIR__ . '/../vendor/autoload.php';

// Añadir Dotenv
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

require 'funciones.php';

$database = new Database();
$db = $database->getConnection();

//Conectarnos a la base de datos
ActiveRecord::setDB($db);

<?php

use \Alice\Framework\Configuration;
// Contrôleur frontal : instancie un routeur pour traiter la requête entrante
// Utilisation de l'autoloader de composer
require_once 'vendor/autoload.php';

use \Alice\Framework\Router;
date_default_timezone_set(Configuration::get("timezone"));
$router = new Router();
$router->routingRequest();

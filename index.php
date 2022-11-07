<?php

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\dispatch\Dispatcher;

require_once 'vendor/autoload.php';

session_start();
ConnectionFactory::setConfig("db.config.ini");
if (!isset($_GET['action'])) $_GET['action'] = null;

$dispatcher = new Dispatcher();
$dispatcher->run();
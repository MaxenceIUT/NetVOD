<?php

require_once 'vendor/autoload.php';

session_start();
ConnectionFactory::setConfig("db.config.ini");
if (!isset($_GET['action'])) $_GET['action'] = null;

$dispatcher = new Dispatcher();
$dispatcher->run();
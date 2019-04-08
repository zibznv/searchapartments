<?php

error_reporting(E_ALL);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
//define('VIEWS_PATH', ROOT.DS.'views');

require_once(ROOT.DS.'config'.DS.'init.php');

session_start();

App::run($_SERVER['REQUEST_URI']);



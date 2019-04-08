<?php

// Своя функция автолоада
function myAutoload($class_name){
	
    $systemLib_path = ROOT.DS.'lib'.DS.'system_lib'.DS.strtolower($class_name).'.class.php';
    $entityLib_path = ROOT.DS.'lib'.DS.'entity_lib'.DS.strtolower($class_name).'.php';
	
    $controllers_path = ROOT.DS.'controllers'.DS.str_replace('controller', '', strtolower($class_name)).'.controller.php';
	
    $model_path = ROOT.DS.'models'.DS.strtolower($class_name).'.php';
	
	if ( file_exists($systemLib_path) ){
        require_once($systemLib_path);
	} elseif ( file_exists($entityLib_path) ){
        require_once($entityLib_path);	
    } elseif ( file_exists($controllers_path) ){
        require_once($controllers_path);
    } elseif ( file_exists($model_path) ){
        require_once($model_path);
	} else {
        throw new Exception('Failed to include class: '.$class_name);
    }
}

// Регистрация своего автолоада - require_once (ROOT.DS.'vendor'.DS.'autoload.php');
spl_autoload_register('myAutoload');

// Подключение файла конфигурационных параметров
require_once(ROOT.DS.'config'.DS.'config.php');

// Подключение файла общих функций
require_once(ROOT.DS.'config'.DS.'functions.php');

// Регистрация глобальной функции перевода
function __($key, $default_value = 'Key not found!'){
    return Lang::get($key, $default_value);
}
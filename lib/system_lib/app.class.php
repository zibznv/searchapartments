<?php

class App {

    protected static $router;
    public static $db;

    public static function getRouter(){return self::$router;}

    public static function run($uri){
        // Создаем новый объект Router из которого получим готовые controller action параметры
        self::$router = new Router($uri);
		
        // Создаем объект подключения к базе данных
        //self::$db = new DB(Config::get('db.host'), Config::get('db.user'), Config::get('db.password'), Config::get('db.db_name'));
		self::$db = Pdodb::getInstance()->getPdo();
		
        // Подключаем файл мультиязычности
		
        if(Session::get('lang')){
            Lang::load(Session::get('lang'));// иначе меняем на выбранный пользователем
        }else{
            Lang::load(self::$router->getLanguage());// по умолчанию
        }

        //Получаем классы Controller и Action
        $controller_class = ucfirst(self::$router->getController()).'Controller';
        $controller_action = strtolower(self::$router->getAction());
		
		// Контроль доступа и защита от прямых запросов страниц
		$typeAcc = Config::get('typeListAccess');
		if($controller_class == 'CheckInOutController' || in_array(Session::get('typeAccess'), $typeAcc)){	
			// Вызываем метод(action) данного контроллера
			$controller_object = new $controller_class($data=array(), self::$router->getParams());
			if ( method_exists($controller_object, $controller_action) ){
			// Выполняем метод контроллера если он существует
			   $controller_object->$controller_action();
			} else {
				throw new Exception('Method '.$controller_action.' of class '.$controller_class.' does not exist.');
			}
		}else{
			Router::redirect('/login');
		}	
    }

}
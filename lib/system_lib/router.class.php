<?php

class Router{

    protected $uri;
    protected $controller;
    protected $action;
    protected $params;
    protected $language;

    public function getUri(){return $this->uri;}

    public function getController(){return $this->controller;}

    public function getAction(){return $this->action;}

    public function getParams(){return $this->params;}

    public function getLanguage(){return $this->language;}

    public function __construct($uri){
        $this->uri = urldecode(trim($uri, '/'));

        // Получение параметров по умолчанию:
        $this->language = Config::get('default_language');
        $this->controller = Config::get('default_controller');
        $this->action = Config::get('default_action');
		
		//Загрузка файла путей listOfRoutes.php
        $listRoutes = require_once(ROOT.DS.'config'.DS.'listOfRoutes.php');
		

        /* Получение команды и дополнительных параметров из URL */
        $uri_parts = explode('?', $this->uri);
        $comandParam = '/' . $uri_parts[0];
		
		/* Получение контроллера, действия и дополнительных параметров */
		foreach ($listRoutes as $item){
		
			$pattern = $item->pattern;
			foreach ($item->params as $key => $value){
                $pattern = str_replace('['.$key.']', "$value", $pattern);
            }
			$pattern = '@^' . $pattern . '$@'; // @ - ограничитель паттерна для регулярного выражения
			 
			if(preg_match($pattern, $comandParam, $matches)){
				
                $this->controller = $item->controller;
                $this->action = $item->action;
                array_shift($matches);// Удаление лишнего элемента из массива созданного preg_matches
                $this->params = array_combine(array_keys($item->params), $matches);
				
                /* Дополнительно параметры запроса будут помещены в GET массив с теми же ключами */
                $_GET += $this->params;
                break;
            }
			
			
        }
/*
        
        foreach ($listRoutes as $item){
            $pattern = $item->pattern;

            foreach ($item->params as $key => $value){
                $pattern = str_replace('['.$key.']', "($value)", $pattern);
            }
            $pattern = '@^' . $pattern . '$@';// @ - ограничитель паттерна для регулярного выражения
			
            if(preg_match($pattern, $uri, $matches)){
                $this->controller = $item->controller;
                $this->action = $item->action;
                array_shift($matches);// Удаление лишнего элемента из массива созданного preg_matches
                $this->params = array_combine(array_keys($item->params), $matches);
				
                //Дополнительно параметры запроса будут помещены в GET массив с теми же ключами
                $_GET += $this->params;
                break;
            }
        }*/
    }
    // Функция редиректа
    public static function redirect($location){
        header("Location: $location");
    }
}
/*
	$uri = '\/handLer_4586-customerForm-checkIn-45.36';
	$keyss = array('id'=>'([0-9]+)', 'form'=>'([a-z]+)', 'access'=>'([a-z]+)', 'count'=>'([.,0-9]+)');
	
	//$pattern = '/[a-z]+_([0-9]+)-([a-z]+)-([a-z]+)-([.,0-9]+)/i';
	$uri_comm = explode('_', $uri);
	$pattern = $uri_comm[0];
	
	
	$uri_params = explode('-', $uri_comm[1]);
	
	foreach ($keyss as $key => $item){
		$uri_params_txt .= '-'.$item;
	}
	$uri_params_txt .= '/i';
	$uri_params_txt = '/' . $pattern . '_' . ltrim($uri_params_txt, '-');
	
	echo $uri_params_txt;
	echo '</br>';
	preg_match($uri_params_txt, $uri, $matches);
	
	array_shift($matches);
	$params = array_combine(array_keys($keyss), $matches);
	
	echo '<pre>'; print_r($params); echo '</pre>'; echo '<br>';
*/
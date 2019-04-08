<?php

class View{

	public static function outputOnDisplay($shablon, array $dataTwig){
		
		/* Имя сайта на закладку, внесение в массив */
		$dataTwig['site_name'] = Config::get('site_name'); 
		
		/* Параметры по умолчанию формы поиска вариантов проживания */	
		$dataTwig['listTown'] = Config::get('townList'); 			// Список городов
		$dataTwig['listType'] = Config::get('typeApartmentList'); 	// Список типов апартаментов
		$dataTwig['activeTown'] = 'Batumi';							// Город по умолчанию
		$dataTwig['activeType'] = 'Standart';						// Тип апартаментов по умолчанию
		$dataTwig['seatCount'] = 1;									// Количество мест по умолчанию
		$dataTwig['roomCount'] = 1;									// Количество комнат по умолчанию

		/* Вывод в заголовок данных по активному Customer */
		$dataTwig['nameCustomer'] = Session::get('nameCustomer');	// Имя активного Customer
		$dataTwig['phoneCustomer'] = Session::get('phoneCustomer');	// Телефон активного Customer
		$dataTwig['emailCustomer'] = Session::get('emailCustomer');	// Почта активного Customer
		$dataTwig['typeAccess'] = Session::get('typeAccess');		// Тип доступа в систему активного Customer
		
		/* Создание ключа безопасности формы ввода */
		$dataTwig['form_key'] = bin2hex(openssl_random_pseudo_bytes(16));
		$_SESSION['array_key'][] = $dataTwig['form_key'];
		
		/* Проверка на наличие сообщения и внесение в массив */
		if(Session::hasFlash()){ $dataTwig['flash_message'] = Session::flash(); }
		
		//if(Session::get('typeAccess')){$dataTwig['modeAccess'] = Session::get('typeAccess');}
		//else{$dataTwig['modeAccess'] = 'login';}
	
		/* Загрузка файла перевода с выбранного языка */
		//$dataAll = array_merge($dataTwig, Lang::$dataFl);
	
		/* Для начала работы c API достаточно подключить класс Twig_Autoloader: */
		require_once (ROOT.DS.'vendor'.DS.'autoload.php');

		$loader = new Twig_Loader_Filesystem(ROOT.DS.'views');
		$twig = new Twig_Environment($loader, array('cache' => false, 'autoescape'=>false, 'debug'=>false)); // 'cache_dir'*/
		
		echo $twig->render($shablon, $dataTwig);
		
	}
}



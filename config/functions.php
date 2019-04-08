<?php

	// Проверка наличия необходимых данных для записи нового Путевого листа
	function MonitoringParameters ( array $fieldArrNew,  array $post_array ) {
		
		$recordingAllowed = true;
		foreach ($fieldArrNew as $key => $value){
			if ( !isset($post_array[$key]) || $post_array[$key] == '' || $post_array[$key] == '--all--'){ 
				Session::setFlash('Не введено значение ' . $value . '!');
				$recordingAllowed = false;
			}
		}
		return $recordingAllowed;
	}


	// Определение сезонности date("Y-m-d H:i:s")
	function definitionOfSeasonality () {

		$activeDate = date("n");
		$seasonYear = ($activeDate > 4 && $activeDate < 10)? "Теплый" : "Холодный";
		return $seasonYear;		
	}
	
	// Отправка SMS сообщения
	function sendSMSCurl ($phones, $content) {
		$request = 'login=zibznv&psw=ZIBznv@1412&phones=' . $phones . '&mes='. urlencode($content).'&charset=utf-8';
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://smsc.ua/sys/send.php?'); 
		curl_setopt($curl, CURLOPT_POST, 1); // тип запроса POST
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request); // указатель на тело пост запроса
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // хотим получить ответ от сервера в переменную
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}
	
	// Функция проверки типа и уровня доступа для возможности выполнения команды пользователя
	function verificationAccessRights(array $typeAccess, $levelAccess){
		
		if(in_array(Session::get('role'), $typeAccess)){
			if(Session::get('level') >= $levelAccess){
				return true;
			} else {
				Session::setFlash('Your access level does not match the selected action!');
				return false;
			}
		} else {
			Session::setFlash('Your access type does not match the selected action!');
			return false;
		}
	}
	
	
	
	
	
	
	
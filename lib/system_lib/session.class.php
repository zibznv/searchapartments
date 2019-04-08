<?php

class Session{

    //public static $flash_message;

	// Метод для записи сообщения
    public static function setFlash($message){ 
		if (isset($_SESSION['flash_message'])){ $_SESSION['flash_message'] = $message . '<br>' . $_SESSION['flash_message']; }
		else{ $_SESSION['flash_message'] = $message; }
	}

	// Метод для проверки наличия сообщения
    public static function hasFlash(){
		if (isset($_SESSION['flash_message'])){ return true; }
		else{ return false;}
    }

	// Метод возврата сообщения
    public static function flash(){
        $time_var = $_SESSION['flash_message'];
        $_SESSION['flash_message'] = null;
		//$time_varArray = explode("<br>", $time_var);
		//$time_varArray = array_reverse($time_varArray);
		return $time_var;
    }

	// Получение списка ключей безопасности формы
	public static function getKeyFormList(){
		
		$countItem = count($_SESSION['array_key']);
		if($countItem > 3){
			$countVar = $countItem - 3;
			for($i=$countVar;$i>0;$i--){
				array_shift($_SESSION['array_key']);
			}
		}
		return $_SESSION['array_key'];
	}
	
	// Установка значения элемента массива $_SESSION
    public static function set($key, $value){ $_SESSION[$key] = $value; }

	// Получение значения элемента массива $_SESSION
    public static function get($key){
        if ( isset($_SESSION[$key]) ){ return $_SESSION[$key]; }
        return null;
    }

	// Удаление элемента массива $_SESSION
    public static function delete($key){
        if ( isset($_SESSION[$key]) ){ unset($_SESSION[$key]); }
    }

	// Получение значения элемента массива $_SESSION['post_array']
    public static function getP($key){
        if ( isset($_SESSION['post_array'][$key]) ){ return $_SESSION['post_array'][$key]; }
        return null;
    }
	
	// Получение значения элемента массива $_SESSION['files_array']
    public static function getF($key){
        if ( isset($_SESSION['files_array'][$key]) ){ return $_SESSION['files_array'][$key]; }
        return null;
    }
	
	// Удаление $_SESSION
    public static function destroy(){ session_destroy(); }
}






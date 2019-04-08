<?php

class Lang{

    public static $dataFl;

    public static function load($lang_code){
        $lang_file_path = ROOT.DS.'lang'.DS.strtolower($lang_code).'.php';

        if ( file_exists($lang_file_path) ){
            self::$dataFl = include($lang_file_path);
			//echo '<pre>'; print_r(self::$dataFl); echo '</pre>'; echo '<br>'; 
        } else {
            throw new Exception('Lang file not found: '.$lang_file_path);
        }
    }

    public static function get($key, $default_value = 'Key not found!'){
        return isset(self::$dataFl[strtolower($key)]) ? self::$dataFl[strtolower($key)] : $default_value;
    }
}
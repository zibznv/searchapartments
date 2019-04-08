<?php

class Pdodb		//Singleton Pattern
{
    private static $instance = null;
    private $pdo; //@var \PDO
	
	private function __construct() {   
	
        $dsn = 'mysql:host=' . Config::get('db.host') . '; port=3306; dbname='. Config::get('db.db_name') . '; charset=utf8'; 
		$options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ];
		
		//$pdo = new PDO($dsn, 'testuser', 'testpassword', $options); Пример использования
		$this->pdo = new PDO($dsn, Config::get('db.user'), Config::get('db.password'), $options);
    }

	private function __clone() {}
    private function __wakeup() {}

	public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Pdodb();
        }
        return self::$instance;
    }
	
	public function getPdo() { // @return \PDO
        return $this->pdo;
    }
	
	
    
	
    
    
}
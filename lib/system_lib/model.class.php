<?php

class Model {

    protected $db;

    public function __construct(){
        $this->db = App::$db;
	}
	
	// Action to change the database record     Нужен ID
	public function updateAction($object){
		
		$data_object = $object->returnArray();  // Преобразовать объект в массив
		$tableName = trim($object->getClass()); // Получить название таблицы
		$tableName = trim($this->db->quote($tableName), "'"); // Защита от SQL инъекций и удаление кавычек
		
		// "UPDATE users set email = :email where lastname=:lastname"
		$sql_text = 'UPDATE '.$tableName. ' SET ';

		foreach($data_object as $key=>$value){
            if($key == 'id'){ continue; }
			$sql_text .= $key . '=:' . $key . ', ';
		}
		$sql_text = trim($sql_text);
		$sql_text = rtrim($sql_text, ",");
		$sql_text .= ' WHERE id = :id';

		try {
			$stmt = $this->db->prepare($sql_text);
			foreach($data_object as $key=>$value){
				$stmt->bindValue(':'.$key, $value);
			}
			$stmt -> execute();
			Session::setFlash('The element with id = ' . $object->getId() . ' was changed! Action completed!');
		}	
		catch(PDOException $e){
			Session::setFlash('Query execution failed : '.$e->getMessage());
		}
	}
	
	// Action to delete a database record
	public function deleteAction($tableName, $id){
		
		$tableNameProt = trim($this->db->quote($tableName), "'"); // Защита от SQL инъекций и удаление кавычек
		
		try {
			$stmt = $this->db->prepare('DELETE FROM ' . $tableNameProt . ' WHERE id = :id');
			$stmt->bindValue(':id', $id);
			$stmt -> execute();
			Session::setFlash('The element with id = ' . $id . ' removed from database! Action completed!');
		}
		catch(PDOException $e){
			Session::setFlash('Query execution failed : '.$e->getMessage());
		}
	}
	
	// Action to save a new database record
	public function newAction($object){
		
		$data_object = $object->returnArray();  // Преобразовать объект в массив
		$tableName = trim($object->getClass()); // Получить название таблицы
		$tableName = trim($this->db->quote($tableName), "'"); // Защита от SQL инъекций и удаление кавычек
		
		// "INSERT INTO planets(name, color) VALUES(:name, :color)"
		$sql_text = 'INSERT INTO '.$tableName.'(';
		
		// name, color) VALUES(
		foreach($data_object as $key=>$value){
			if($key == 'id'){ continue; }
			$sql_text .= $key . ', ';
		}
		$sql_text = trim($sql_text);
		$sql_text = rtrim($sql_text, ",");
		$sql_text .= ') VALUES(';
		// :name, :color)
		foreach($data_object as $key=>$value){
			if($key == 'id'){ continue; }
			$sql_text .= ':'.$key.', ';
		}
		$sql_text = trim($sql_text);
		$sql_text = rtrim($sql_text, ",");
		$sql_text .= ')';
		
		try {
			$stmt = $this->db->prepare($sql_text);
			foreach($data_object as $key=>$value){
				if($key == 'id'){ continue; }
				$stmt->bindValue(':'.$key, $value);
			}
			$stmt -> execute();
			$id = $this->db->lastInsertId();
			Session::setFlash('The new record with id = ' .$id  . ' is stored in the database! Action completed!');
			return $id;
		}	
		catch(PDOException $e){
			Session::setFlash('Query execution failed : '.$e->getMessage());
		}
	}
	
	// Action to search for a database entry by criterion (only string $searchCriteria!)
	public function findAction($tableName, $searchCriteria, $value, $objName){
		
		// Защита от SQL инъекций и удаление кавычек
		$tableName = trim($this->db->quote($tableName), "'"); 
		$searchCriteria = trim($this->db->quote($searchCriteria), "'"); 
		$value = trim($this->db->quote($value), "'"); 
		$objN = trim($this->db->quote($objName), "'"); 
		
		try {
			$stmt = $this->db->query("SELECT * FROM ".$tableName." WHERE ".$searchCriteria." = '".$value."'");
			$stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $objN);
			$answer = $stmt->fetch();
			Session::setFlash('Query - table = ' . $tableName . ': ' . $searchCriteria . ' = ' . $value . ' fulfilled! Action completed!');
			return $answer;
		}
		catch(PDOException $e){
			Session::setFlash('Query execution failed : '.$e->getMessage());
		}
	}
	
	// Action to getting all table elements in object view
    public function get_allAction($tableName){

        $tableN = trim($this->db->quote($tableName), "'"); // Защита от SQL инъекций и удаление кавычек
		$obj_array = array();
		
        try {
			$stmt = $this->db->query("SELECT * FROM " .$tableN. " WHERE 1");
			$stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, ucfirst($tableN));
			while ($items = $stmt->fetch()) {
				$obj_array[] = $items;
			}
			//Session::setFlash('Action to retrieve all table elements is completed');
            return $obj_array;
        }
        catch(PDOException $e){
            Session::setFlash('Query execution failed : '.$e->getMessage());
        }
    }
	
}






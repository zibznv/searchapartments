<?php

class SampleobjectModel extends Model {

    public function __construct(){
        parent::__construct();
    }

	// Action to retrieve all table elements in the form of an object onSort
    public function get_allActionSort($tableName){

        $tableN = trim($this->db->quote($tableName), "'"); // Защита от SQL инъекций и удаление кавычек
		$obj_array = array();
		
        try {
			$stmt = $this->db->query("SELECT * FROM " .$tableN. " ORDER BY modelCars, licensePlate");
			$stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, ucfirst($tableN));
			while ($items = $stmt->fetch()) {
				$obj_array[] = $items;
			}
			Session::setFlash('Action to retrieve all sort table elements is completed!');
            return $obj_array;
        }
        catch(PDOException $e){
            Session::setFlash('Query execution failed : '.$e->getMessage());
        }
    }
	
	
	
	
	
}
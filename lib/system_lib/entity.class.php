<?php

class Entity {
	
    private $id;
	
    public function __construct( $data = array()){
		foreach($data as $key=>$value){
			if (property_exists($this, $key)){
				$setname = 'set'.ucfirst($key);
				$this->{$setname}($value);
			}
		}
	}
	public function returnArray() { return get_object_vars($this); }
	public function getClass() { return get_class($this); }
	
	public function setId($value){ $this->id = $value; }
	public function getId(){ return $this->id; }

}

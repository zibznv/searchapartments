<?php

class CostApartment extends Entity {
	
    private $apartmentID;             // ID апартаментов в базе данных
	private $januaryCost;             // Стоимость сдачи январь
	private $februaryCost;            // Стоимость сдачи февраль
	private $marchCost;               // Стоимость сдачи март
	private $aprilCost;               // Стоимость сдачи апрель
	private $mayCost;             	  // Стоимость сдачи май
	private $juneCost;             	  // Стоимость сдачи июнь
	private $julyCost;                // Стоимость сдачи июль
	private $augustCost;              // Стоимость сдачи август
	private $septemberCost;           // Стоимость сдачи сентябрь
	private $octoberCost;             // Стоимость сдачи октябрь
	private $novemberCost;            // Стоимость сдачи ноябрь
	private $decemberCost;            // Стоимость сдачи декабрь
	
	public function getCostMonth($number){
	/* Метод получения стоимости номера в сутки по номеру месяца в году */
		$monthArray = ['1'=>'januaryCost', '2'=>'februaryCost', '3'=>'marchCost', 
					   '4'=>'aprilCost', '5'=>'mayCost', '6'=>'juneCost', 
					   '7'=>'julyCost', '8'=>'augustCost', '9'=>'septemberCost', 
					   '10'=>'octoberCost', '11'=>'novemberCost', '12'=>'decemberCost'];
		return $this->$monthArray[$number]
	}
	
    public function setApartmentID($apartmentID){ $this->apartmentID = $apartmentID; }
    public function getApartmentID(){ return $this->apartmentID; }
    public function setJanuaryCost($januaryCost){ $this->januaryCost = $januaryCost; }
    public function getJanuaryCost(){ return $this->januaryCost; }
    public function setFebruaryCost($februaryCost){ $this->februaryCost = $februaryCost; }
    public function getFebruaryCost(){ return $this->februaryCost; }
    public function setMarchCost($marchCost){ $this->marchCost = $marchCost; }
    public function getMarchCost(){ return $this->marchCost; }
    public function setAprilCost($aprilCost){ $this->aprilCost = $aprilCost; }
    public function getAprilCost(){ return $this->aprilCost; }
    public function setMayCost($mayCost){ $this->mayCost = $mayCost; }
    public function getMayCost(){ return $this->mayCost; }
    public function setJuneCost($juneCost){ $this->juneCost = $juneCost; }
    public function getJuneCost(){ return $this->juneCost; }
    public function setJulyCost($julyCost){ $this->julyCost = $julyCost; }
    public function getJulyCost(){ return $this->julyCost; }
    public function setAugustCost($augustCost){ $this->augustCost = $augustCost; }
    public function getAugustCost(){ return $this->augustCost; }
    public function setSeptemberCost($septemberCost){ $this->septemberCost = $septemberCost; }
    public function getSeptemberCost(){ return $this->septemberCost; }
    public function setOctoberCost($octoberCost){ $this->octoberCost = $octoberCost; }
    public function getOctoberCost(){ return $this->octoberCost; }
    public function setNovemberCost($novemberCost){ $this->novemberCost = $novemberCost; }
    public function getNovemberCost(){ return $this->novemberCost; }
    public function setDecemberCost($decemberCost){ $this->decemberCost = $decemberCost; }
    public function getDecemberCost(){ return $this->decemberCost; }
	
	

}

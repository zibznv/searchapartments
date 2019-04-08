<?php

class Apartment
{
    private $id;
    private $numberOnDoor;             // Номер на входной двери
    private $nameСomplexORBI;          // Название комплекса ORBI
    private $typeApartment;            // Тип апартаментов
    private $townLocation;             // Город расположения
	private $addressComplex;           // Адрес комплекса
    private $squareApartment;          // Площадь номера м2
	private $countRooms;          	   // Количество комнат
    private $ownerID;                  // ID владельца номера
    private $statusApartment;          // Рабочий статус номера
	private $numberSeats;              // Количество мест в номере
    private $coordinates;              // GPS координаты комплекса
    private $pathFoto;                 // Путь к фотокаталогу
    private $balancePayments;          // Баланс оплат по номеру
	private $commentApartment;         // Комментарии и пожелания
	
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
    public function setNumberOnDoor($numberOnDoor){ $this->numberOnDoor = $numberOnDoor; }
    public function getNumberOnDoor(){ return $this->numberOnDoor; }
    public function setNameСomplexORBI($nameСomplexORBI){ $this->nameСomplexORBI = $nameСomplexORBI; }
    public function getNameСomplexORBI(){ return $this->nameСomplexORBI; }
    public function setTypeApartment($typeApartment){ $this->typeApartment = $typeApartment; }
    public function getTypeApartment(){ return $this->typeApartment; }
    public function setTownLocation($townLocation){ $this->townLocation = $townLocation; }
    public function getTownLocation(){ return $this->townLocation; }
    public function setAddressComplex($addressComplex){ $this->addressComplex = $addressComplex; }
    public function getAddressComplex(){ return $this->addressComplex; }
    public function setSquareApartment($squareApartment){ $this->squareApartment = $squareApartment; }
    public function getSquareApartment(){ return $this->squareApartment; }
	public function setCountRooms($countRooms){ $this->countRooms = $countRooms; }
    public function getCountRooms(){ return $this->countRooms; }
    public function setOwnerID($ownerID){ $this->ownerID = $ownerID; }
    public function getOwnerID(){ return $this->ownerID; }
    public function setStatusApartment($statusApartment){ $this->statusApartment = $statusApartment; }
    public function getStatusApartment(){ return $this->statusApartment; }
    public function setNumberSeats($numberSeats){ $this->numberSeats = $numberSeats; }
    public function getNumberSeats(){ return $this->numberSeats; }
    public function setCoordinates($coordinates){ $this->coordinates = $coordinates; }
    public function getCoordinates(){ return $this->coordinates; }
    public function setPathFoto($pathFoto){ $this->pathFoto = $pathFoto; }
    public function getPathFoto(){ return $this->pathFoto; }
    public function setBalancePayments($balancePayments){ $this->balancePayments = $balancePayments; }
    public function getBalancePayments(){ return $this->balancePayments; }
    public function setCommentApartment($commentApartment){ $this->commentApartment = $commentApartment; }
    public function getCommentApartment(){ return $this->commentApartment; }

	
}

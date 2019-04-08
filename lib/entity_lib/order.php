<?php

class Order extends Entity {
	
    private $dateOrder;             // Дата и время отправки заказа
	private $town;             		// Город заказа
	private $dateArrival;           // Дата прибытия
	private $dateDepartures;        // Дата выезда
	private $seatCount;             // Необходимое количество мест
	private $roomCount;             // Необходимое количество комнат
	private $typeApartment;         // Необходимый тип номера
	private $idCustomer;            // ID клиента после регистрации или авторизации
	private $idPartner;             // ID партнера после согласования заказа
	private $textAlertSent;         // Текст посланного партнеру сообщения
	private $dateConfirmed;         // Дата подтверждения заявки на аренду
	private $servicesAmount;        // Сумма оплаты от партнера
	private $paymentInformation;    // Информация по оплате
	
    public function setDateOrder($dateOrder){ $this->dateOrder = $dateOrder; }
    public function getDateOrder(){ return $this->dateOrder; }
	public function setTown($town){ $this->town = $town; }
    public function getTown(){ return $this->town; }
	public function setDateArrival($dateArrival){ $this->dateArrival = $dateArrival; }
    public function getDateArrival(){ return $this->dateArrival; }
	public function setDateDepartures($dateDepartures){ $this->dateDepartures = $dateDepartures; }
    public function getDateDepartures(){ return $this->dateDepartures; }
	public function setSeatCount($seatCount){ $this->seatCount = $seatCount; }
    public function getSeatCount(){ return $this->seatCount; }
	public function setRoomCount($roomCount){ $this->roomCount = $roomCount; }
    public function getRoomCount(){ return $this->roomCount; }
	public function setTypeApartment($typeApartment){ $this->typeApartment = $typeApartment; }
    public function getTypeApartment(){ return $this->typeApartment; }
	public function setIdCustomer($idCustomer){ $this->idCustomer = $idCustomer; }
    public function getIdCustomer(){ return $this->idCustomer; }
	public function setIdPartner($idPartner){ $this->idPartner = $idPartner; }
    public function getIdPartner(){ return $this->idPartner; }
	public function setTextAlertSent($textAlertSent){ $this->textAlertSent = $textAlertSent; }
    public function getTextAlertSent(){ return $this->textAlertSent; }
	public function setDateConfirmed($dateConfirmed){ $this->dateConfirmed = $dateConfirmed; }
    public function getDateConfirmed(){ return $this->dateConfirmed; }
	public function setServicesAmount($servicesAmount){ $this->servicesAmount = $servicesAmount; }
    public function getServicesAmount(){ return $this->servicesAmount; }
	public function setPaymentInformation($paymentInformation){ $this->paymentInformation = $paymentInformation; }
    public function getPaymentInformation(){ return $this->paymentInformation; }	
	
	
}

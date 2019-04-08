<?php

class Customer {
	
    private $id;
    private $userCustomer;             // Юзер клиента
    private $passwordCustomer;         // Пароль клиента
    private $nameCustomer;             // Имя клиента
    private $surnameCustomer;          // Фамилия клиента
	private $statusCustomer;           // Статус клиента
    private $typeCustomer;             // Тип клиента
	private $phoneCustomer;            // Телефон клиента
    private $emailCustomer;            // Электронная почта клиента
	private $bankcardNumber;           // Номер банковской карты
    private $accountBalance;           // Бухгалтерский баланс
	private $genderCustomer;           // Пол клиента
    private $birthDate;				   // Дата рождения клиента
	private $photoCustomer;            // Имя файла фото
    private $commentCustomer;          // Комментарии по клиенту
	
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
    public function setUserCustomer($userCustomer){ $this->userCustomer = $userCustomer; }
    public function getUserCustomer(){ return $this->userCustomer; }
	public function setPasswordCustomer($passwordCustomer){ $this->passwordCustomer = $passwordCustomer; }
    public function getPasswordCustomer(){ return $this->passwordCustomer; }
	public function setNameCustomer($nameCustomer){ $this->nameCustomer = $nameCustomer; }
    public function getNameCustomer(){ return $this->nameCustomer; }
	public function setSurnameCustomer($surnameCustomer){ $this->surnameCustomer = $surnameCustomer; }
    public function getSurnameCustomer(){ return $this->surnameCustomer; }
	public function setStatusCustomer($statusCustomer){ $this->statusCustomer = $statusCustomer; }
    public function getStatusCustomer(){ return $this->statusCustomer; }
	public function setTypeCustomer($typeCustomer){ $this->typeCustomer = $typeCustomer; }
    public function getTypeCustomer(){ return $this->typeCustomer; }
	public function setPhoneCustomer($phoneCustomer){ $this->phoneCustomer = $phoneCustomer; }
    public function getPhoneCustomer(){ return $this->phoneCustomer; }
	public function setEmailCustomer($emailCustomer){ $this->emailCustomer = $emailCustomer; }
    public function getEmailCustomer(){ return $this->emailCustomer; }
	public function setBankcardNumber($bankcardNumber){ $this->bankcardNumber = $bankcardNumber; }
    public function getBankcardNumber(){ return $this->bankcardNumber; }
	public function setAccountBalance($accountBalance){ $this->accountBalance = $accountBalance; }
    public function getAccountBalance(){ return $this->accountBalance; }
	public function setGenderCustomer($genderCustomer){ $this->genderCustomer = $genderCustomer; }
    public function getGenderCustomer(){ return $this->genderCustomer; }
	public function setBirthDate($birthDate){ $this->birthDate = $birthDate; }
    public function getBirthDate(){ return $this->birthDate; }
	public function setPhotoCustomer($photoCustomer){ $this->photoCustomer = $photoCustomer; }
    public function getPhotoCustomer(){ return $this->photoCustomer; }
	public function setCommentCustomer($commentCustomer){ $this->commentCustomer = $commentCustomer; }
    public function getCommentCustomer(){ return $this->commentCustomer; }


}

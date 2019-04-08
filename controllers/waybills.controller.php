<?php

class WaybillsController extends Controller {
//echo '<pre>'; print_r($this->data); echo '</pre>'; echo '<br>';  

    public function __construct($data, $params) {
        parent::__construct($data, $params);
    }

    public function indexAction(){

		if(!$_POST){
			
			if(is_object(Session::get('waybill_form'))){ // Вывод заполненной формы
			
				$this->data = Session::get('waybill_form')->returnArray();
				
				// Перестройка формата для вывода поля datetime-local
				$this->data['dateTimeDeparture'] = str_replace(' ', "T", trim(Session::get('waybill_form')->getDateTimeDeparture()));
				$this->data['dateTimeReturn'] = str_replace(' ', "T", trim(Session::get('waybill_form')->getDateTimeReturn()));
				
				// Получение массива всех сотрудников, авто и смарт карт ( Входные данные OBJECT + метка активного)
				$this->data['visitor'] = $this->model->get_allAction('visitors');
				$this->data['activeUsername'] = Session::get('waybill_form')->getIdStaff();
				
				$this->data['idAvto'] = $this->model->get_allAction('cars');
				$this->data['activeIdAvto'] = Session::get('waybill_form')->getIdAvto();
				
				$this->data['idSmcard'] = $this->model->get_allAction('smarts');
				$this->data['smartDefaultFilterProvider'] = Config::get('smartDefaultFilterProvider');
				$this->data['activeIdSmcard'] = Session::get('waybill_form')->getIdSmcard();
				
				$this->data['idDriver'] = $this->model->get_allAction('drivers');
				$this->data['driverDefaultFilterStatus'] = Config::get('driverDefaultFilterStatus');
				$this->data['activeIdDriver'] = Session::get('waybill_form')->getIdDriver();
				
				// Массив вариантов для всех сезонов и владельцев авто
				$this->data['season'] = Config::get('seasonList');
				$this->data['activeSeason'] = Session::get('waybill_form')->getSeason();
				
				$this->data['idCompany'] = Config::get('companyOwnerList');
				$this->data['activeIdCompany'] = Config::get('companyDefaultFilterSender');
				
				// Получение массива всех ПЛ и фильтров Обратная сортировка
				//$this->data['allWaybills'] = $this->model->get_allAction('waybills');
				$this->data['allWaybills'] = $this->model->allWBsortDesc();
				
				$this->data['waybillFilterOwner'] = Session::get('waybillFilterOwner');
				$this->data['waybillFilterLPlate'] = Session::get('waybillFilterLPlate');
				$this->data['waybillFilterDriver'] = Session::get('waybillFilterDriver');
				$this->data['waybillFilterSmart'] = Session::get('waybillFilterSmart');
				
				// Получение массива заправок по Путевому листу
				if (Session::get('arrayFueling') != null){
					$this->data['arrayFuelings'] = Session::get('arrayFueling');
					Session::delete('arrayFueling');
				}else{
					$waybillNumber = Session::get('waybill_form')->getWaybillNum();
					$this->data['arrayFuelings'] = $this->model->get_allActionFuelingWB($waybillNumber);
				}
				
				View::outputOnDisplay($this->shablon, $this->data);// Запуск Twig и вывод на экран
				exit();
				
 			}else{
				
				// Получение массива всех сотрудников, авто и смарт карт ( Входные данные OBJECT + метка активного)
				$this->data['visitor'] = $this->model->get_allAction('visitors');
				
				$this->data['idAvto'] = $this->model->get_allAction('cars');
				
				$this->data['idSmcard'] = $this->model->get_allAction('smarts');
				$this->data['smartDefaultFilterProvider'] = Config::get('smartDefaultFilterProvider');
				
				$this->data['idDriver'] = $this->model->get_allAction('drivers');
				$this->data['driverDefaultFilterStatus'] = Config::get('driverDefaultFilterStatus');
				
				// Массив вариантов для всех сезонов и владельцев авто
				$this->data['season'] = Config::get('seasonList');
				$this->data['idCompany'] = Config::get('companyOwnerList');
				$this->data['activeIdCompany'] = Config::get('companyDefaultFilterSender');
				
				// Получение массива всех ПЛ и фильтров
				//$this->data['allWaybills'] = $this->model->get_allAction('waybills');
				$this->data['allWaybills'] = $this->model->allWBsortDesc();
				
				$this->data['waybillFilterOwner'] = Session::get('waybillFilterOwner');
				$this->data['waybillFilterLPlate'] = Session::get('waybillFilterLPlate');
				$this->data['waybillFilterDriver'] = Session::get('waybillFilterDriver');
				$this->data['waybillFilterSmart'] = Session::get('waybillFilterSmart');
				
                View::outputOnDisplay($this->shablon, $this->data); // Вывод пустой формы
				exit();	
			}
		}
		
		if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['hashText'] && in_array($_POST['hashText'], Session::getKeyFormList())){
			
			// Передача Post массива через сессию новому роуту
			Session::set('post_array', $_POST);
			// Передача Files массива через сессию новому роуту
			Session::set('files_array', $_FILES);	
			
			// Проверка наличия необходимых данных для записи нового Путевого листа
			$fieldArrNew = array (
			'waybillNum' => 'Номер путевого листа', 
			'dateTimeDeparture' => 'Дата и время выезда автомобиля', 
			'dateTimeReturn' => 'Дата и время заезда автомобиля', 
			'speedometerDeparture' => 'Спидометр при выезде автомобиля',
			'speedometerReturn' => 'Спидометр при заезде автомобиля',
			'bingoFuelDeparture' => 'Остаток топлива при выезде в литрах',
			'bingoFuelReturn' => 'Остаток топлива при заезде в литрах' );
			
			//if(isset($_POST['new_waybill']) && $_POST['waybillNum'] != ''){ Router::redirect('/new_waybill'); }
			if(isset($_POST['new_waybill']) && MonitoringParameters($fieldArrNew, Session::get('post_array'))){ Router::redirect('/new_waybill'); }
			
			elseif (isset($_POST['edit_waybill']) && $_POST['id'] != '' && MonitoringParameters($fieldArrNew, Session::get('post_array'))) { Router::redirect('/edit_waybill'); }
			
			elseif (isset($_POST['delete_waybill']) && $_POST['id'] != ''){ Router::redirect('/del_waybill'); }
			
			elseif (isset($_POST['firmOwnerWB'])){ Router::redirect('/filterFO_WB'); }
			
			elseif (isset($_POST['idCarWB'])){	Router::redirect('/filterLP_WB'); }
			
			elseif (isset($_POST['driverNameWB'])){ Router::redirect('/filterDN_WB'); }
			
			elseif (isset($_POST['smartCardWB'])){ Router::redirect('/filterSC_WB'); }
			
			elseif (isset($_POST['ClearWB'])){ Router::redirect('/clear_waybForm'); }
			
			else{
				$wayb = new Waybills(Session::get('post_array'));
				Session::set('waybill_form', $wayb);
				Session::setFlash('Action failed. Not enough data!');
				Router::redirect('/waybills');
			}
		}
	}

	// Запись нового элемента
	public function new_waybAction(){

		if(verificationAccessRights(array('admin', 'logistics'), 5)){
			$wayb = new Waybills(Session::get('post_array'));
            $idNewRec = $this->model->newAction($wayb);
			$wayb -> setId($idNewRec);
			Session::set('waybill_form', $wayb);
        }
        Router::redirect('/waybills');
	}
	
	// Удаление элемента
	public function del_waybAction(){

        if(verificationAccessRights(array('admin', 'logistics'), 8)){
			$writeOffDt = Session::get('waybill_form')->getWriteOffDate();
			if($writeOffDt == null || $writeOffDt == '0000-00-00'){
				
				$this->model->deleteAction('waybills', Session::get('waybill_form')->getId());
				Session::setFlash('The waybill was successfully deleted!');
				
				// Очистка базы заправок от привязки к старым данным ПЛ
				$waybillNumber = Session::getP('waybillNum');
				$arrFill = $this->model->get_allActionFuelingWB($waybillNumber);
				foreach ($arrFill as $value){
					$value -> setWaybillNumber(null);
					$this->model->updateAction($value);
				}
				Session::setFlash('Заправки активного ПЛ отсоединены!');
				
			}else{
				Session::setFlash('Can not delete a decommissioned waybill!');
			}
        }
        Router::redirect('/waybills');
	}
	
	// Редактирование элемента 
	public function edit_waybAction(){

        if(verificationAccessRights(array('admin', 'logistics'), 8)){
			$writeOffDt = Session::get('post_array')['writeOffDate'];
			if($writeOffDt == null || $writeOffDt == '0000-00-00'){
				$wayb = new Waybills(Session::get('post_array'));
				$this->model->updateAction($wayb);
				Session::set('waybill_form', $wayb);
			}else{
				Session::setFlash('Can not edit a decommissioned waybill!');
			}	
        }
        Router::redirect('/waybills');
	}
	
	// Создание  фильтра компании владельца авто по ПЛ
    public function fltFOAction(){
		
		if(verificationAccessRights(array('admin', 'logistics'), 5)){
			if(Session::getP('idCompany') == '--all--'){ Session::delete('waybillFilterOwner'); }
			else { Session::set('waybillFilterOwner', Session::getP('idCompany')); }
			Session::setFlash("waybillFilterOwner = " . Session::getP('idCompany') . "!");
		}	
		Router::redirect('/waybills');
	}
	
	// Создание  фильтра госномера авто по ПЛ
    public function fltLPAction(){
		
		if(verificationAccessRights(array('admin', 'logistics'), 5)){
			if(Session::getP('idAvto') == '--all--'){ Session::delete('waybillFilterLPlate'); }
			else { Session::set('waybillFilterLPlate', Session::getP('idAvto')); }
			Session::setFlash("waybillFilterLPlate = " . Session::getP('idAvto') . "!");
		}	
		Router::redirect('/waybills');
	}
	
	// Создание  фильтра водителя авто по ПЛ
    public function fltDNAction(){
		
		if(verificationAccessRights(array('admin', 'logistics'), 5)){
			if(Session::getP('idDriver') == '--all--'){ Session::delete('waybillFilterDriver'); }
			else { Session::set('waybillFilterDriver', Session::getP('idDriver')); }
			Session::setFlash("waybillFilterDriver = " . Session::getP('idDriver') . "!");
		}	
		Router::redirect('/waybills');
	}
	
	// Создание  фильтра Cмарт карты по ПЛ
    public function fltSCAction(){
	
		if(verificationAccessRights(array('admin', 'logistics'), 5)){
			if(Session::getP('idSmcard') == '--all--'){ Session::delete('waybillFilterSmart'); }
			else { Session::set('waybillFilterSmart', Session::getP('idSmcard')); }
			Session::setFlash("waybillFilterSmart = " . Session::getP('idSmcard') . "!");
		}
		Router::redirect('/waybills');
	}
	
	// Очистка формы для начала ввода нового ПЛ
	public function clearFormAction(){ 
	
		Session::delete('waybill_form');
		Router::redirect('/waybills');
	}
	
	// Вывод формы при выборе заправки в списке
    public function waybIdAction(){

		if(verificationAccessRights(array('admin', 'mechanic', 'logistics'), 5)){
			$id = trim($this->params['id'], '[]');
			Session::set('waybill_form', $this->model->findAction('waybills', 'id', $id, 'Waybills'));
			Session::setFlash('Путевой лист выбран в списке!');
		}	
        Router::redirect('/waybills');
    }
	
	// Получение массива заправок по Путевому листу + запись номера ПЛ на заправки
    public function loadFillAction(){
		
		if(verificationAccessRights(array('admin', 'logistics'), 5)){
			
			if(Session::get('waybill_form') !== null){
				$fieldArrNew = array (
					'waybillNum' => 'Номер путевого листа', 
					'dateTimeDeparture' => 'Дата и время выезда автомобиля', 
					'dateTimeReturn' => 'Дата и время заезда автомобиля' );
					
				if (MonitoringParameters($fieldArrNew, Session::get('waybill_form')->returnArray())){
					
					// Очистка базы заправок от привязки к старым данным ПЛ
					$timeStart = date_format(new DateTime('2018-03-01T00:00:01'), 'Y-m-d H:i:s');
					$timeFinish = date('Y-m-d H:i:s');
					$waybillNumber = Session::get('waybill_form')->getWaybillNum();
					
					$arrFill = $this->model->get_allActionFuelingWB('fillingchecks', $waybillNumber, $timeStart, $timeFinish);
					foreach ($arrFill as $value){
						$value -> setWaybillNumber(null);
						$this->model->updateAction($value);
					}
					Session::setFlash('Заправки активного ПЛ отсоединены!');
					
					// Привязка заправок к ПЛ по новым даннымidSmcard
					$timeStart = date_format(new DateTime(Session::get('waybill_form')->getDateTimeDeparture()), 'Y-m-d H:i:s');
					$timeFinish = date_format(new DateTime(Session::get('waybill_form')->getDateTimeReturn()), 'Y-m-d H:i:s');
					$smartNum = Session::get('waybill_form')->getIdSmcard();
					$arrFill = $this->model->get_allActionFueling('fillingchecks', $smartNum, $timeStart, $timeFinish);
					
					$arrFillFinal = array();
					// Исключение заправок занятых другими ПЛ
					foreach ($arrFill as $value){
						
						if($value->getWaybillNumber() == null || $value->getWaybillNumber() == Session::get('waybill_form')->getWaybillNum()){ 
							$arrFillFinal[] = $value; 
						} else {
							Session::setFlash('Заправка ' . $value->getIdRecord() . ' синхронизирована с ПЛ № ' . $value->getWaybillNumber() . ' !');
						} 
					}
					Session::set('arrayFueling', $arrFillFinal);
					// Подсчет заправленного топлива и запись в заправки номера ПЛ
					$fuelTotal = 0;
					foreach ($arrFillFinal as $value){
						$value -> setWaybillNumber(Session::get('waybill_form')->getWaybillNum());
						$this->model->updateAction($value);
						$fuelTotal += $value -> getQuantityLiters();
					}
					
					Session::get('waybill_form') -> setRefillLiters($fuelTotal);
					
					// Получение суммарного времени длительности ПЛ
					Session::get('waybill_form') -> setTravelTime(round((strtotime(Session::get('waybill_form')->getDateTimeReturn())
					- strtotime(Session::get('waybill_form')->getDateTimeDeparture()))/(60*60*24), 2, PHP_ROUND_HALF_UP));
					
					// Сохранение данных
					$this->model->updateAction(Session::get('waybill_form'));
					Session::setFlash('Заправки активного ПЛ синхронизированы со списком заправок!');
				}else{
					Session::setFlash('Action failed. Not enough data!');
				}
				
			} else {
				Session::setFlash('No waybill is selected. Action canceled!');
			}
		}	
		Router::redirect('/waybills');
    }
	
	// Загрузка данных GPS в новый ПЛ
	public function loadGPSAction(){	
		
		if(verificationAccessRights(array('admin', 'logistics'), 5)){
		
			if(Session::get('waybill_form') !== null){
			
				$fieldArrNew = array (
					'waybillNum' => 'Номер путевого листа', 
					'dateTimeDeparture' => 'Дата и время выезда автомобиля', 
					'dateTimeReturn' => 'Дата и время заезда автомобиля', 
					'idAvto' => 'Госномер автомобиля');
			
				if (MonitoringParameters($fieldArrNew, Session::get('waybill_form')->returnArray())){
					
					$startData = str_ireplace(' ', '%20', Session::get('waybill_form') -> getDateTimeDeparture());
					$finishData = str_ireplace(' ', '%20', Session::get('waybill_form') -> getDateTimeReturn());
					$activeCar = $this->model->findAction('cars', 'licensePlate', Session::get('waybill_form')->getIdAvto(), 'Cars');
					$deviceId = $activeCar->getIdGPS();
				
					$wbGPS = new GPS($startData, $finishData, $deviceId);
					
					// Добавление в комментарий времени пропадания сигнала GPS
					$commentWB = Session::get('waybill_form') -> getCommentsWaybill();
					Session::get('waybill_form') -> setCommentsWaybill('Time of Lost GPS - ' . $wbGPS->getMissingOccurancesTime() . ' ' . $commentWB);
					// Получение пройденного расстояния по ПЛ
					Session::get('waybill_form') -> setDistanceGps($wbGPS->getDistance());
					// Получение времени движения по ПЛ
					Session::get('waybill_form') -> setMovingTime(conversionHours($wbGPS->getMoveDuration()));
					// Получение времени стоянок по ПЛ
					Session::get('waybill_form') -> setParkingTime($wbGPS->getParkDuration());
					// Получение маршрута движения
					Session::get('waybill_form') -> setRouteItinerary($wbGPS->getRouteList());
					
					$this->model->updateAction(Session::get('waybill_form'));
					//Session::setFlash('The data of the GPS\'s company received!');
				} else {
					Session::setFlash('Action failed. Not enough data!');
				}	
			} else {
				Session::setFlash('No waybill is selected. Action canceled!');
			}
		}	
		Router::redirect('/waybills');
	}
	    
	// Полный перерасчет ПЛ
	public function recountWBAction(){
		
		if(verificationAccessRights(array('admin', 'logistics'), 5)){

			if(Session::get('waybill_form') !== null){

				$fieldArrNew = array (
					'waybillNum' => 'Номер путевого листа', 
					'dateTimeDeparture' => 'Дата и время выезда автомобиля', 
					'dateTimeReturn' => 'Дата и время заезда автомобиля', 
					'speedometerDeparture' => 'Спидометр при выезде автомобиля',
					'speedometerReturn' => 'Спидометр при заезде автомобиля',
					'bingoFuelDeparture' => 'Остаток топлива при выезде в литрах',
					'bingoFuelReturn' => 'Остаток топлива при заезде в литрах',
					'refillLiters' => 'Заправлено литров за командировку',
					'idCompany' => 'Компания отправитель автомобиля (Плательщик)',
					'idAvto' => 'Госномер автомобиля',
					'idDriver' => 'Фамилия Имя Отчество водителя',
					'idSmcard' => 'Номер смарт карты',
					'season' => 'Время года ( Зима / Лето )',
					'idStaff' => 'Сотрудник создавший ПЛ',
					'parkingTime' => 'Время стоянок, час',
					'cargoWeight' => 'Вес груза в тоннах',
					'returnWeight' => 'Вес возвратов в тоннах',
					'distanceGps' => 'Пробег по GPS в километрах');
					
				if (MonitoringParameters($fieldArrNew, Session::get('waybill_form')->returnArray())){
					
					$writeOff = Session::get('waybill_form')->getWriteOffDate();   // Дата списания ПЛ
					if($writeOff == null || $writeOff == '0000-00-00'){
						
						$distanceStart = Session::get('waybill_form') -> getSpeedometerDeparture();
						$distanceFinish = Session::get('waybill_form') -> getSpeedometerReturn();	
						$distance = $distanceFinish - $distanceStart; 					 // Провег по спидометру SPD в километрах
						$distanceG = Session::get('waybill_form') -> getDistanceGps();	 // Пробег по GPS в километрах
						$refillLtr = Session::get('waybill_form') -> getRefillLiters();	 // Заправлено литров за командировку
						$FuelDeparture = Session::get('waybill_form') -> getBingoFuelDeparture(); // Остаток топлива при выезде в литрах
						$FuelReturn = Session::get('waybill_form') -> getbingoFuelReturn(); // Остаток топлива при заезде в литрах
						
						// Получаем объект автомобиля из ПЛ
						$carWB = $this->model->findAction('cars', 'licensePlate', Session::get('waybill_form')->getIdAvto(), 'Cars');
						// Получаем необходимые для расчета параметры
						$rateSummer = $carWB -> getBaseRateSummer();    			// Базовая норма расхода лето
						$rateWinter = $carWB -> getBaseRateWinter();    			// Базовая норма расхода зима
						$tonneKilometer = $carWB -> getTonneKilometerRatio(); 		// Коэффициент тонно/км
						//$onWarmup = $carWB -> getNormOnWarmup();    				// Норма на прогрев в день
						$standAloneHeater = $carWB -> getStandAloneHeaterRate();	// Норма на автономный обогреватель в час
						
						// Ввод пробега в форму
						Session::get('waybill_form') -> setDistanceSpd($distance);	
						
						// Расхождение в пробегах SPD / GPS  в %
						$dispSG = round(($distance / $distanceG) * 100 - 100, 2, PHP_ROUND_HALF_UP);				
						Session::get('waybill_form') -> setDiscrepancySG($dispSG);			// Ввод расхождения в форму
						
						// Расход топлива по Путевому листу л.
						$fuelConsByWB = $FuelDeparture + $refillLtr - $FuelReturn;
						
						// Расход топлива, л/100км
						$fuelConsump = round(($fuelConsByWB / $distance) * 100, 2, PHP_ROUND_HALF_UP);       
						Session::get('waybill_form') -> setFuelConsumption($fuelConsump);	// Ввод расхода топлива, л/100км в форму
						
						// Расчет перерасхода/экономии топлива по ПЛ
						$travelDay = Session::get('waybill_form') -> getTravelTime();       // Время в пути, дней
						$movTime = Session::get('waybill_form') -> getMovingTime(); 		// Время в движении, час
						$seasonWB = Session::get('waybill_form') -> getSeason();            // Время года ( Зима / Лето )
						
						if($seasonWB === 'Теплый'){
							$consumpDelta = $fuelConsByWB - (($distance/100) * $rateSummer);
						}
						elseif($seasonWB === 'Холодный'){
							$consumpDelta = $fuelConsByWB - (($distance/100) * $rateWinter + ($movTime * $standAloneHeater));
						}
						Session::get('waybill_form') -> setConsumptionDelta(round($consumpDelta, 2, PHP_ROUND_HALF_UP));	// Ввод перерасхода/экономии в форму
						
						// Коментарии по данному ПЛ
						$commentWB = Session::get('waybill_form') -> getCommentsWaybill();
						Session::get('waybill_form') -> setCommentsWaybill($commentWB . ' Расчет ПЛ - ' . date('Y-m-d H:i:s')); 
						
						$this->model->updateAction(Session::get('waybill_form')); 			// Сохранение данных
						Session::setFlash('Calculation of the active travel list is done!');
						
					} else {
						Session::setFlash('The travel list is written off! Calculation is impossible!');
					}
				} else {
					Session::setFlash('Action failed. Not enough data!');
				}	
			} else {
				Session::setFlash('No waybill is selected. Action canceled!');
			}	
		}	
		Router::redirect('/waybills');
    }
	
	//  Запуск процедуры установки даты списания для WB и их заправок
	public function writOffWBAction(){

        if(verificationAccessRights(array('admin'), 8)){
		
			$rrz = $this->model->checkNonSynRefills();
			if(isset($rrz['id'])){
				Session::setFlash('Команда не выполнена. Есть не синхронизированные заправки!');
				Router::redirect('/waybills');
			}
			
			// Получаем все не списанные ПЛ
			$listWriteOffWB = $this->model->allNotWriteOffWB();
			$writeOffDate = date('Y-m-d');
			$countWB = 0;
			$countFL = 0;
			foreach ($listWriteOffWB as $value){
				
					$value -> setWriteOffDate($writeOffDate);
					$commentWB = $value -> getCommentsWaybill();
					$value -> setCommentsWaybill('Installed writeOffDate - ' . $writeOffDate . ' ' . $commentWB);
					$this -> model -> updateAction($value);
					$countWB ++;
					// Получение заправок и установка даты списания
					$waybillNumber = $value->getWaybillNum();
					$arrayFuelings = $this->model->get_allActionFuelingWB($waybillNumber);
					foreach ($arrayFuelings as $itemFill){
						$itemFill -> setWriteOffDate($writeOffDate);
						$this -> model -> updateAction($itemFill);
						$countFL ++;
					}
				}
			Session::setFlash('Processing of ' . $countWB . ' way sheets and ' . $countFL . ' fillingchecks!');
		}else{
			Session::setFlash('Your access level does not match the selected action!');
		}	
        Router::redirect('/waybills');
	}
	
	//  Запуск процедуры отмены последнего списания
	public function lastWrOffDateAction(){
		
		if(verificationAccessRights(array('admin'), 8)){
		
			// Определение даты последнего списания
			$lastDate = $this -> model -> lastDateWriteOffWB();
			if($lastDate['MAX(`writeOffDate`)'] == null || $lastDate['MAX(`writeOffDate`)'] == '0000-00-00'){
				
				Session::setFlash('Action is impossible! No decommissioned travel sheets!');
				Router::redirect('/waybills');
				exit();
			}
			// Получаем все списанные ПЛ последнего списания
			$listWriteOffWB = $this->model->allWBLastDate($lastDate['MAX(`writeOffDate`)']);
			$countWB = 0;
			$countFL = 0;
			foreach ($listWriteOffWB as $value){
				$value -> setWriteOffDate(null);
				$commentWB = $value -> getCommentsWaybill();
				$value -> setCommentsWaybill('Annulment writeOffDate - ' . date('Y-m-d') . ' ' . $commentWB);
				$this -> model -> updateAction($value);
				$countWB ++;
				// Получение заправок и установка даты списания
				$waybillNumber = $value->getWaybillNum();
				$arrayFuelings = $this->model->get_allActionFuelingWB($waybillNumber);
				foreach ($arrayFuelings as $itemFill){
					$itemFill -> setWriteOffDate(null);
					$this -> model -> updateAction($itemFill);
					$countFL ++;
				}
			}
			Session::setFlash('Processing of ' . $countWB . ' way sheets and ' . $countFL . ' fillingchecks!');
        }
        Router::redirect('/waybills');
	}	
	
	//  Запуск процедуры 
	public function fuelInTankAction(){
		
		if(verificationAccessRights(array('admin', 'logistics'), 5)){
			
			$fieldArrNew = array (
				'waybillNum' => 'Номер путевого листа', 
				'idGPS' => 'Код автомобиля у поставщика GPS услуг',
				'dateTimeDeparture' => 'Дата и время выезда автомобиля',
				'dateTimeReturn' => 'Дата и время заезда автомобиля' );
				
			//if (MonitoringParameters($fieldArrNew, Session::get('waybill_form')->returnArray())){
		
				$tm = Session::get('waybill_form') -> getDateTimeReturn();
				$tmNew = date('Y-m-d H:i:s', strtotime($tm. ' + 20 min'));
				
				$startTime = str_ireplace(' ', '%20', Session::get('waybill_form') -> getDateTimeDeparture());
				$endTime = str_ireplace(' ', '%20', $tmNew);
				// Получаем объект автомобиля из ПЛ
				$carWB = $this->model->findAction('cars', 'licensePlate', Session::get('waybill_form')->getIdAvto(), 'Cars');
				$deviceNum = $carWB -> getIdGPS();
				// Получаем объект суммарных данных по топливу активного WB
				$fuelTot = new GPSFuelTotal($startTime, $endTime, $deviceNum);
				//header("Status: All OK!", true, 200);
				//echo $fuelTot -> getEndValue();
				//echo json_encode($fuelTot -> getEndValue());
				//echo Session::get('waybill_form') -> getWaybillNum();
				//echo serialize(Session::get('waybill_form'));
				//echo $_POST['address'];
				//echo serialize($_POST);
				
				header("Content-Type: text/xml");
				echo "<? xml version=\"1.0\" encoding=\"utf-8\" ?>";
				?>
				<totals>
					<startValue><? echo $fuelTot->getStartValue(); //Количество топлива в баке на начало периода, л ?></startValue>
					<endValue><? echo $fuelTot->getEndValue(); //Количество топлива в баке на конец периода, л ?></endValue>
					<charge><? echo $fuelTot->getCharge(); //Заправлено топлива за период, л ?></charge>
					<discharge><? echo $fuelTot->getDischarge(); //Слито топлива за период, л ?></discharge>
					<expense><? echo $fuelTot->getExpense(); //Расход топлива за период, л ?></expense>
					<race><? echo $fuelTot->getRace(); //Расстояние пройденное за период, km ?></race>
				</totals>
				<?php
			//} 
		}
	}	
}
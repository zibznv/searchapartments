<?php
//echo '<pre>'; print_r($this->data); echo '</pre>'; echo '<br>'; 
class TasksController extends Controller {

    public function __construct($data, $params) {
        parent::__construct($data, $params);
    }

    public function indexAction(){

		// Контроль видимости списка задач ВСЕ - СВОИ
		if(Session::get('security_form')->getAccess() == 'admin' ){ 
			Session::set('taskFilterRecipient', null);
		} else { Session::set('taskFilterRecipient', Session::get('security_form')->getUsername()); }
	
       	if(!$_POST){

		// Предварительная загрузка данных
			$this->data['typeTask'] = Config::get('typeListTask'); 		// Загрузка списка видов задач
			$this->data['statusTask'] = Config::get('statusListTask');  // Загрузка списка статусов задач
			$this->data['recipientTask'] = $this->model->get_allAction('visitors');  // Загрузка списка сотрудников
			$this->data['authorTask'] = array('--all--', 'Sistem Task', Session::get('security_form')->getUserName());
			$this->data['taskObject'] = $this->model->get_allAction('cars'); // Загрузка списка автомобилей
			// Получение массива всех задач и фильтров
			$this->data['allTasks'] = $this->model->get_allAction('tasks');
			$this->data['taskFilterType'] = Session::get('taskFilterType');
			$this->data['taskFilterStatus'] = Session::get('taskFilterStatus');
			$this->data['taskFilterRecipient'] = Session::get('taskFilterRecipient');
			$this->data['taskFilterAuthor'] = Session::get('taskFilterAuthor');		
			$this->data['taskFilterObject'] = Session::get('taskFilterObject');		

			if(is_object(Session::get('task_form'))){ // Вывод заполненной формы
			
				$this->data =  array_merge(Session::get('task_form')->returnArray(), $this->data);
				
				// Получить активный тип задачи
				$this->data['activeTypeTask'] = Session::get('task_form')->getTypeTask();
				// Получить активный статус задачи
				$this->data['activeStatusTask'] = Session::get('task_form')->getStatusTask();
				// Получить активного получателя задачи
				$this->data['activeRecipientTask'] = Session::get('task_form')->getRecipientTask();
				// Получить активного автора задачи
				$this->data['activeAuthorTask'] = Session::get('task_form')->getAuthorTask();
				// Получить активный объект задачи
				$this->data['activeTaskObject'] = Session::get('task_form')->getTaskObject();
			}
				View::outputOnDisplay($this->shablon, $this->data);// Запуск Twig и вывод на экран
				exit();
		}
		
		if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['hashText'] && in_array($_POST['hashText'], Session::getKeyFormList())){
			
			// Передача Post массива через сессию новому роуту
			Session::set('post_array', $_POST);
			
			$fieldArrNew = array (
			'typeTask' => 'Тип задачи', 
			'descriptionTask' => 'Описание задачи', 
			'taskObject' => 'Объект принадлежности задачи', 
			'recipientTask' => 'Исполнитель задачи',
			'deadlineTask' => 'Крайний срок выполнения задачи',
			'authorTask' => 'Автор задачи' );
			
			if(isset($_POST['new_task']) && MonitoringParameters($fieldArrNew, Session::get('post_array'))){ Router::redirect('/new_task'); }
			
			elseif (isset($_POST['edit_task']) && $_POST['id'] != '' ) { Router::redirect('/edit_task'); }
			
			elseif (isset($_POST['delete_task']) && $_POST['id'] != ''){ Router::redirect('/del_task'); }
			
			elseif (isset($_POST['ClearTS'])){ Router::redirect('/clear_taskForm'); }
			
			elseif (isset($_POST['typeTaskFl'])){ 
				Session::set('taskFilterType', (Session::getP('typeTask') == '--all--')?  null : Session::getP('typeTask'));
				Session::setFlash("taskFilterType = " . Session::getP('typeTask') . "!");
				Router::redirect('/tasks');
			}
			elseif (isset($_POST['statusTaskFl'])){ 
				Session::set('taskFilterStatus', (Session::getP('statusTask') == '--all--')?  null : Session::getP('statusTask'));
				Session::setFlash("taskFilterStatus = " . Session::getP('statusTask') . "!");
				Router::redirect('/tasks');
			}
			elseif (isset($_POST['recipientTaskFl'])){ 
				Session::set('taskFilterRecipient', (Session::getP('recipientTask') == '--all--')?  null : Session::getP('recipientTask'));
				Session::setFlash("taskFilterRecipient = " . Session::getP('recipientTask') . "!");
				Router::redirect('/tasks');
			}
			elseif (isset($_POST['authorTaskFl'])){ 
				Session::set('taskFilterAuthor', (Session::getP('authorTask') == '--all--')?  null : Session::getP('authorTask'));
				Session::setFlash("taskFilterAuthor = " . Session::getP('authorTask') . "!");
				Router::redirect('/tasks');
			}
			elseif (isset($_POST['taskObjectFl'])){ 
				Session::set('taskFilterObject', (Session::getP('taskObject') == '--all--')?  null : Session::getP('taskObject'));
				Session::setFlash("taskFilterObject = " . Session::getP('taskObject') . "!");
				Router::redirect('/tasks');
			}
			else{
				$taskFrm = new Tasks(Session::get('post_array'));
				Session::set('task_form', $taskFrm);
				Session::setFlash('Action failed. Not enough data!');
				Router::redirect('/tasks');
			}
			
		}
	}

	// Очистка формы для начала ввода новой задачи 
	public function clearFormAction(){ 
		Session::delete('task_form');
		Router::redirect('/tasks');
	}

	// Удаление элемента 
	public function del_taskAction(){

        if(verificationAccessRights(array('admin'), 5)){
				$this->model->deleteAction('tasks', Session::get('task_form')->getId());
				Session::setFlash('The task was successfully deleted!');
        }
        Router::redirect('/tasks');
	}
	
	// Запись нового элемента
	public function createTSAction(){

		if(verificationAccessRights(array('admin'), 8)){
			$newTask = new Tasks(Session::get('post_array'));
			$newTask->setCreateDate(date('Y-m-d')); // Установка даты создания
			$newTask->setStatusTask('Создана'); // Установка статуса задачи
			
			// Контроль наличия подобных задач в незакрытых статусах
			$uniq = $this->model->checkUniquenessTask($newTask->getTypeTask(), $newTask->getDescriptionTask(), $newTask->getTaskObject());
			if(!count($uniq) >= 1){
				
				$newTask->setId($this->model->newAction($newTask));
				Session::set('task_form', $newTask);
				
				$content = 'По объекту ' . $newTask->getTaskObject() . ' сформирована задача - ' . $newTask->getDescriptionTask() . ' Срок до: ' . $newTask->getDeadlineTask() . ' Автор: ' . $newTask->getAuthorTask();
				// Отправка EMail
				$executor = $this->model->findAction('visitors', 'username', $newTask->getRecipientTask(), 'Visitors');
				$addressList = array($executor->getEmail(), 'i.zaychenko@avtek.ua');
				Sendmailtolist::sendMail($content, $addressList);
				// Отправка SMS сообщения
				//sendSMSCurl ('+380675080709', $content);
				sendSMSCurl ($executor->getTel(), $content);
				Session::setFlash('The SMS and EMail messages was sent successfully!');	
			}
        }
        Router::redirect('/tasks');
	}
	
	// Редактирование элемента 
	public function edit_taskAction(){

        if(verificationAccessRights(array('admin'), 8)){
				$newTask = new Tasks(Session::get('post_array'));
				$this->model->updateAction($newTask);
				Session::set('task_form', $newTask);
        }
        Router::redirect('/tasks');
	}
	
	// Вывод формы при выборе задачи в списке
    public function taskIdAction(){

		if(verificationAccessRights(array('admin', 'mechanic', 'logistics'), 5)){
			$id = trim($this->params['id'], '[]');
			Session::set('task_form', $this->model->findAction('tasks', 'id', $id, 'Tasks'));
			Session::setFlash('Задача выбрана в списке!');
		}	
        Router::redirect('/tasks');
    }
	
	// Контроль срока действия страховых полисов + задача date('Y-m-d', strtotime($Date. ' + 2 days'));
	public function insurContAction(){
	
		if(verificationAccessRights(array('admin', 'mechanic', 'logistics'), 5)){
			$listCarIns = $this->model->allCarsByExpInsur();
			foreach ($listCarIns as $value){
				if($value->getVehicleStatus() == 'Working' || $value->getVehicleStatus() == 'On repair'){
					
					$insurCoupDate = ($value->diffDays > 0)? $value->getInsurancePolicyCouponDate(): date('Y-m-d', strtotime(date('Y-m-d'). ' + 2 days'));
					$arrayTask = array(
					'typeTask' => 'Полис ГО', 				   // Тип задачи
					'statusTask' => 'Создана', 				   // Статус задачи
					'createDate' => date('Y-m-d'), 			   // Дата создания задачи
					'descriptionTask' => 'Получить полис ГО!', // Описание задачи (Что необходимо сделать)
					'taskObject' => $value->getLicensePlate(), // Объект принадлежности задачи (например автомобиль)
					'recipientTask' => 'migor', 			   // Исполнитель задачи (кому адресована задача)
					'deadlineTask' => $insurCoupDate, 		   // Крайний срок выполнения задачи
					'commentDone' => '', 					   // Описание сделанного по задаче
					'doneDate' => '', 						   // Дата выполнения и закрытия задачи
					'authorTask' => 'Sistem Task'); 		   // Автор задачи (источник создания)
				
					$newTask = new Tasks($arrayTask);
					// Контроль наличия подобных задач в незакрытых статусах
					$uniq = $this->model->checkUniquenessTask($newTask->getTypeTask(), $newTask->getDescriptionTask(), $newTask->getTaskObject());
					if(!count($uniq) >= 1){
						$this->model->newAction($newTask);
						$content = 'По объекту ' . $newTask->getTaskObject() . ' сформирована задача - ' . $newTask->getDescriptionTask() . ' Срок до: ' . $newTask->getDeadlineTask() . ' Автор: ' . $newTask->getAuthorTask();
						// Отправка EMail
						$addressList = array('i.zaychenko@avtek.ua', 'i.melnikov@avtek.ua', 's.yaremenko@avtek.ua');
						Sendmailtolist::sendMail($content, $addressList);
						// Отправка SMS сообщения
						sendSMSCurl ('+380675080709', $content);
						sendSMSCurl ('+380676596378', $content);
						Session::setFlash('The SMS and EMail messages was sent successfully!');	
					}
				}
			}
			Session::setFlash('The control over the validity of insurance policies is complete!');
		}	
        Router::redirect('/tasks');
    }
	
	// Контроль срока действия страховых полисов Casco + задача
	public function cascoContAction(){

		if(verificationAccessRights(array('admin', 'mechanic', 'logistics'), 5)){
			$listCarIns = $this->model->allCarsByExpCasco();
			foreach ($listCarIns as $value){
				if($value->getVehicleStatus() == 'Working' || $value->getVehicleStatus() == 'On repair'){
					
					$cascoCoupDate = ($value->diffDays > 0)? $value->getInsuranceCASCOCouponDate(): date('Y-m-d', strtotime(date('Y-m-d'). ' + 2 days'));
					$arrayTask = array(
					'typeTask' => 'Полис КАСКО', 				  // Тип задачи
					'statusTask' => 'Создана', 				      // Статус задачи
					'createDate' => date('Y-m-d'), 			   	  // Дата создания задачи
					'descriptionTask' => 'Получить полис КАСКО!', // Описание задачи (Что необходимо сделать)
					'taskObject' => $value->getLicensePlate(),    // Объект принадлежности задачи (например автомобиль)
					'recipientTask' => 'migor', 			      // Исполнитель задачи (кому адресована задача)
					'deadlineTask' => $cascoCoupDate, 		      // Крайний срок выполнения задачи
					'commentDone' => '', 					      // Описание сделанного по задаче
					'doneDate' => '', 						      // Дата выполнения и закрытия задачи
					'authorTask' => 'Sistem Task'); 		      // Автор задачи (источник создания)
				
					$newTask = new Tasks($arrayTask);
					// Контроль наличия подобных задач в незакрытых статусах
					$uniq = $this->model->checkUniquenessTask($newTask->getTypeTask(), $newTask->getDescriptionTask(), $newTask->getTaskObject());
					if(!count($uniq) >= 1){
						$this->model->newAction($newTask);
						$content = 'По объекту ' . $newTask->getTaskObject() . ' сформирована задача - ' . $newTask->getDescriptionTask() . ' Срок до: ' . $newTask->getDeadlineTask() . ' Автор: ' . $newTask->getAuthorTask();
						// Отправка EMail
						$addressList = array('i.zaychenko@avtek.ua', 'i.melnikov@avtek.ua', 's.yaremenko@avtek.ua');
						Sendmailtolist::sendMail($content, $addressList);
						// Отправка SMS сообщения
						sendSMSCurl ('+380675080709', $content);
						sendSMSCurl ('+380676596378', $content);
						Session::setFlash('The SMS and EMail messages was sent successfully!');	
					}
				}
			}
			Session::setFlash('The control over the validity of insurance policies CASCO is complete!!');
		}
        Router::redirect('/tasks');
    }
	
	// Контроль проведения технического обслуживания + задача
	public function maintContAction(){
		
		if(verificationAccessRights(array('admin', 'mechanic', 'logistics'), 5)){
			$listActiveCars = $this->model->get_allAction('cars');
			foreach ($listActiveCars as $value){

				Session::setFlash($value->getLicensePlate());
				if($value->getTypeCar() == 'Cargo' || $value->getTypeCar() == 'Truck' || $value->getTypeCar() == 'Car'){
					if($value->getVehicleStatus() == 'Working' || $value->getVehicleStatus() == 'On repair'){
						
						// Поиск последнего ПЛ
						$lastWB = $this->model->lastWBAction($value->getLicensePlate());
						if(is_object($lastWB)){
							$lastSPD = $lastWB->getSpeedometerReturn(); // Последний спидометр
							$diffDay = $lastWB->diffDays; // Количество дней с последнего заезда на базу
							$startData = str_ireplace(' ', '%20', $lastWB->getDateTimeReturn()); // Метка времени возврата по последнему ПЛ
							unset($lastWB);
						} else {
							$lastSPD = 0; // Последний спидометр
							$diffDay = 30; // Количество дней с последнего заезда на базу
							$startData = str_ireplace(' ', '%20', date('Y-m-01 00:00:01')); // Метка времени возврата по последнему ПЛ
						}
						
						$finishData = str_ireplace(' ', '%20', date('Y-m-d H:i:s')); // Метка времени момента запуска
						$deviceId = $value->getIdGPS(); // Номер устройства GPS автомобиля
						$lastMTSpd = $value->getSpeedometerLastMaintenance(); // Спидометр последнего ТО
						$intervalMT = $value->getMaintenanceIntervals(); // Интервал проведениия ТО
						
						if($diffDay < 60){
							$gpsObj = new GPS($startData, $finishData, $deviceId);
							if(is_object($gpsObj)){
								$distance = $gpsObj->getDistance(); // Пробег с даты закрытия последнего ПЛ
								unset($gpsObj);
							} else {
								$distance = 0;
							}
						} else {
							$startData = str_ireplace(' ', '%20', date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'). ' - 31 days'))); // 
							$gpsObj = new GPS($startData, $finishData, $deviceId);
							if(is_object($gpsObj)){
								$distance = $gpsObj->getDistance(); // Пробег с даты закрытия последнего ПЛ
								unset($gpsObj);
							} else {
								$distance = 0;
							}
						}
						// Расчет необходимости создания задачи на Техническое обслуживание
						$nextMT = $intervalMT + $lastMTSpd; // Спидометр следующего ТО (расчетный)
						$distanceGPS = $lastSPD + $distance; // Фактический пробег с погрешностью
						$delta = $nextMT - $distanceGPS;
						
						if($delta < 1000){
							
							$delta = round($delta, 1, PHP_ROUND_HALF_UP);
							// Создание задачи на ТО
							$deadlineDate = date('Y-m-d', strtotime(date('Y-m-d'). ' + 7 days'));
							$arrayTask = array(
							'typeTask' => 'Плановое ТО', 				  // Тип задачи
							'statusTask' => 'Создана', 				      // Статус задачи
							'createDate' => date('Y-m-d'), 			   	  // Дата создания задачи
							// Описание задачи (Что необходимо сделать)
							'descriptionTask' => 'Провести очередное ТО! Delta mileage - ' . $delta . ' км.', 
							'taskObject' => $value->getLicensePlate(),    // Объект принадлежности задачи (например автомобиль)
							'recipientTask' => 'migor', 			      // Исполнитель задачи (кому адресована задача)
							'deadlineTask' => $deadlineDate, 		      // Крайний срок выполнения задачи
							'commentDone' => '', 					      // Описание сделанного по задаче
							'doneDate' => '', 						      // Дата выполнения и закрытия задачи
							'authorTask' => 'Sistem Task'); 		      // Автор задачи (источник создания)
						
							$newTask = new Tasks($arrayTask);
							// Контроль наличия подобных задач в незакрытых статусах
							$uniq = $this->model->checkUniquenessTask($newTask->getTypeTask(), $newTask->getDescriptionTask(), $newTask->getTaskObject());
							if(!count($uniq) >= 1){
								$this->model->newAction($newTask);
								$content = 'По объекту ' . $newTask->getTaskObject() . ' сформирована задача - ' . $newTask->getDescriptionTask() . ' Срок до: ' . $newTask->getDeadlineTask() . ' Автор: ' . $newTask->getAuthorTask();
								// Отправка EMail
								$addressList = array('i.zaychenko@avtek.ua', 'i.melnikov@avtek.ua', 's.yaremenko@avtek.ua');
								Sendmailtolist::sendMail($content, $addressList);
								// Отправка SMS сообщения
								// Определение телефона водителя
								$driverObj = $this->model->findAction('drivers', 'id', $value->getFixedDriver(), 'Drivers');
								$telDriver = $driverObj->getDrPhone();
								
								$phones = explode("/", $telDriver); //  получить все номера!
								$contentDR = 'Прибыть на СТО для проведения технического обслуживания!';
								foreach($phones as $val){
									$valueTxt = str_ireplace('-', '', $val);
									if($value->getCmcAllowed() == 'true'){sendSMSCurl($valueTxt, $contentDR);}
								}
								sendSMSCurl ('+380675080709', $content);
								sendSMSCurl ('+380676596378', $content);
								Session::setFlash('The SMS and EMail messages was sent successfully!');	
							}
						}
					}
				}
			}
			Session::setFlash('The control of carrying out of maintenance of cars is complete!');
		}
        Router::redirect('/tasks');
    }
	
	// Контроль даты поверки тахографов + задача 
	public function tahogContAction(){

		if(verificationAccessRights(array('admin', 'mechanic', 'logistics'), 5)){	
			$listCarIns = $this->model->allCarsByExpTachog();
			foreach ($listCarIns as $value){
				if($value->getVehicleStatus() == 'Working' || $value->getVehicleStatus() == 'On repair'){
					
					$tachCoupDate = ($value->diffDays > 0)? $value->getTachographInspectionDate(): date('Y-m-d', strtotime(date('Y-m-d'). ' + 7 days'));
					$arrayTask = array(
					'typeTask' => 'Поверка тахографа', 		   // Тип задачи
					'statusTask' => 'Создана', 				   // Статус задачи
					'createDate' => date('Y-m-d'), 			   // Дата создания задачи
					'descriptionTask' => 'Провести поверку тахоспидометра!', // Описание задачи (Что необходимо сделать)
					'taskObject' => $value->getLicensePlate(), // Объект принадлежности задачи (например автомобиль)
					'recipientTask' => 'migor', 			   // Исполнитель задачи (кому адресована задача)
					'deadlineTask' => $tachCoupDate, 		   // Крайний срок выполнения задачи
					'commentDone' => '', 					   // Описание сделанного по задаче
					'doneDate' => '', 						   // Дата выполнения и закрытия задачи
					'authorTask' => 'Sistem Task'); 		   // Автор задачи (источник создания)
				
					$newTask = new Tasks($arrayTask);
					// Контроль наличия подобных задач в незакрытых статусах
					$uniq = $this->model->checkUniquenessTask($newTask->getTypeTask(), $newTask->getDescriptionTask(), $newTask->getTaskObject());
					if(!count($uniq) >= 1){
						$this->model->newAction($newTask);
						$content = 'По объекту ' . $newTask->getTaskObject() . ' сформирована задача - ' . $newTask->getDescriptionTask() . ' Срок до: ' . $newTask->getDeadlineTask() . ' Автор: ' . $newTask->getAuthorTask();
						// Отправка EMail
						$addressList = array('i.zaychenko@avtek.ua', 'i.melnikov@avtek.ua', 's.yaremenko@avtek.ua');
						Sendmailtolist::sendMail($content, $addressList);
						// Отправка SMS сообщения
						sendSMSCurl ('+380675080709', $content);
						sendSMSCurl ('+380676596378', $content);
						Session::setFlash('The SMS and EMail messages was sent successfully!');	
					}
				}
			}
			Session::setFlash('The control date for checking the tachographs of cars is complete!');
		}	
        Router::redirect('/tasks');
    }
	
	// Взять задачу в работу (сотрудник)
	public function startTaskAction(){
		
		if(verificationAccessRights(array('admin', 'mechanic', 'logistics'), 5)){	
			if(Session::get('task_form') !== null){
				$statusTs = Session::get('task_form')->getStatusTask();	
				if($statusTs == 'Создана' || $statusTs == 'Просрочена'){
					Session::get('task_form')->setStatusTask('В работе');
					Session::get('task_form')->setCommentDone(Session::get('task_form')->getCommentDone() . ' Взята в работу - ' . date('Y-m-d'));
					$this->model->updateAction(Session::get('task_form'));
					Session::setFlash('Change the status of the active task to Work!');
				} else {
					Session::setFlash('The current status of the active task does not match the command. Action canceled!');
				}
			} else {
				Session::setFlash('No tasks selected. Action canceled!');
			}	
		}	
		Router::redirect('/tasks');
    }
	
	// Отменить задачу (сотрудник)
	public function canselTaskAction(){
		
		if(verificationAccessRights(array('admin', 'mechanic', 'logistics'), 5)){	
			if(Session::get('task_form') !== null){
				$statusTs = Session::get('task_form')->getStatusTask();	
				if($statusTs == 'Создана' || $statusTs == 'В работе' || $statusTs == 'Просрочена'){
					Session::get('task_form')->setStatusTask('Отменена');
					Session::get('task_form')->setDoneDate(date('Y-m-d'));
					Session::get('task_form')->setCommentDone(Session::get('task_form')->getCommentDone() . ' Задача отменена - ' . date('Y-m-d') . ' ' . Session::get('security_form')->getUserName());
					$this->model->updateAction(Session::get('task_form'));
					Session::setFlash('Active task canceled by user!');
				} else {
					Session::setFlash('The current status of the active task does not match the command. Action canceled!');
				}
			} else {
				Session::setFlash('No tasks selected. Action canceled!');
			}		
		}	
		Router::redirect('/tasks');
    }
	
	// Проверить сроки выполнения задач (сотрудник)
	public function deadlineTaskAction(){
		
		if(verificationAccessRights(array('admin', 'mechanic', 'logistics'), 5)){
			$lst = $this->model->allTasksByOverTime();
			foreach($lst as $value){
				$value->setStatusTask('Просрочена');
				$this->model->updateAction($value);
			}
			Session::setFlash('Checking the timeliness of the tasks is completed!');
		}	
        Router::redirect('/tasks');
    }
	
	// Создать Заказ-Наряд на техническое обслуживание
	public function createMTTaskAction(){

	echo 'createMTTaskAction';
		Session::setFlash('Order-Maintenance order created!');
        Router::redirect('/tasks');
    }
	
	// Установить отметку о выполнении задачи
	public function doneTaskAction(){
		
		if(verificationAccessRights(array('admin', 'mechanic', 'logistics'), 5)){
			if(Session::get('task_form') !== null){
				$statusTs = Session::get('task_form')->getStatusTask();	
				if($statusTs == 'В работе' || $statusTs == 'Просрочена'){
					Session::get('task_form')->setStatusTask('Выполнена');
					Session::get('task_form')->setDoneDate(date('Y-m-d'));
					Session::get('task_form')->setCommentDone(Session::get('task_form')->getCommentDone() . ' Задача выполнена - ' . date('Y-m-d') . ' ' . Session::get('security_form')->getUserName());
					$this->model->updateAction(Session::get('task_form'));
					Session::setFlash('Changed the status of the active task to Completed!');
				} else {
					Session::setFlash('The current status of the active task does not match the command. Action canceled!');
				}
			} else {
				Session::setFlash('No tasks selected. Action canceled!');
			}
		}	
		Router::redirect('/tasks');
    }
	
	
	
}
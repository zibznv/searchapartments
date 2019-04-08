<?php
 
class CheckInOutController extends Controller {

    /*public function __construct($data, $params) {
        parent::__construct($data, $params);
    }*/

    public function startAction(){
	
		/* Признак вывода формы поиска вариантов проживания */
		$dataTwig['activeForm'] = 'searchAplication';
		
		/* Запуск Twig и вывод на экран */
		View::outputOnDisplay($this->shablon, $dataTwig);
		exit();
	}
	
	public function indexAction(){
		
		//echo 'Test params';
		//echo $id = trim($this->params['id'], '[]');
		//echo '<pre>'; print_r($_GET); echo '</pre>'; echo '<br>';
		
		// ПРЕДВАРИТЕЛЬНАЯ ЗАГРУЗКА ПАРАМЕТРОВ
		
		/* Массив полей для проверки наличия данных для всех ключевых полей */ 
		$fieldArrNew = array ( 
			'userCustomer' => 'Юзер клиента', 
			'passwordCustomer' => 'Пароль клиента', 
			'nameCustomer' => 'Имя клиента', 
			'surnameCustomer' => 'Фамилия клиента',
			'statusCustomer' => 'Статус клиента',
			'typeCustomer' => 'Тип клиента', 
			'phoneCustomer' => 'Телефон клиента', 
			'emailCustomer' => 'Электронная почта клиента',
			'bankcardNumber' => 'Номер банковской карты',
			'genderCustomer' => 'Пол клиента', 
			'birthDate' => 'Дата рождения клиента');
		
		/* Признак вывода формы поиска вариантов проживания */
		$dataTwig['activeForm'] = 'searchAplication'; 
		
		/* Признак вывода определенной формы */
		$dataTwig['currentForm'] = null; // 'checkIn'
		
		if(!$_POST && 1 ){
			
		}
		elseif(1) {
			
		}
		elseif(1) {
			
		}
		
		
		
		
		
		
		

		if(!$_POST){

		
			$this->data['typeTask'] = Config::get('typeListTask'); 		// Загрузка списка видов задач
			$this->data['recipientTask'] = $this->model->get_allAction('visitors');  // Загрузка списка сотрудников
			// Получение массива всех задач и фильтров
			$this->data['allTasks'] = $this->model->get_allAction('tasks');

			if(is_object(Session::get('task_form'))){ // Вывод заполненной формы
			
				$this->data =  array_merge(Session::get('task_form')->returnArray(), $this->data);
				
				// Получить активный статус задачи
				$this->data['activeStatusTask'] = Session::get('task_form')->getStatusTask();
			}
				View::outputOnDisplay($this->shablon, $this->data);// Запуск Twig и вывод на экран
				exit();
		}
		
		if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['hashText']) && in_array($_POST['hashText'], Session::getKeyFormList())){
			
			// Передача Post массива через сессию новому роуту
			Session::set('post_array', $_POST);
			
			
			if(isset($_POST['new_customer']) && MonitoringParameters($fieldArrNew, Session::get('post_array'))){ Router::redirect('/new_customer'); }
			
			elseif (isset($_POST['edit_customer']) && $_POST['id'] != '' ) { Router::redirect('/edit_customer'); }
			
			elseif (isset($_POST['delete_customer']) && $_POST['id'] != ''){ Router::redirect('/delete_customer'); }
			
			
			
		} else {
			
			// Имя сайта на закладку, внесение в массив
			

			View::outputOnDisplay($this->shablon, $dataTwig);// Запуск Twig и вывод на экран
			exit();
			
		}
	}

	
	
		
		
		
	
}

//echo '<pre>'; print_r($this->data); echo '</pre>'; echo '<br>';
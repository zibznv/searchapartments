<?php

/* Список маршрутов сайта*/

/* Если с паттерном необходимо передать один или несколько параметров то
 * строка объекта выглядит следующим образом:
 * new RouteList(pattern:'/book_[id]\.html', controller:'BookController',
 * action:'showAction', array('id' => '[0-9]+')
 * Далее программа заменяет выражение [id] на [0-9]+
 * Несколько параметров через дефис '/book_[id]_[marker]\.html'
 * Далее необходимо добавить регулярное выражение для нового параметра params
 * array('id' => '[0-9]+', 'marker' => '[a-z]+')
 */

return array(
	new RouteList('/','CheckInOut','startAction'),						// Вывод стартовой страницы
	new RouteList('/handler_[id]-[form]','CheckInOut','indexAction', 
		array('id'=>'([0-9]+)', 'form'=>'([a-z]+)')),				// Вывод на страницу обработчика нажатия
	new RouteList('/logIn', 'CheckInOut','checkInAction'),				// Вывод меню регистрации
	new RouteList('/logOut','CheckInOut','checkOutAction'), 			// Сброс типа доступа. Переход на стартовую страницу
	//new RouteList('/foto_emp_[fname]','CheckInOut','foto_empAction', array('fname' => '.+')),	// Вывод фотографий сотрудников
    new RouteList('/new_customer','CheckInOut','/new_customerAction'), 			// Запись нового customer
	new RouteList('/edit_customer','CheckInOut','/edit_customerAction'), 		// Редактирование старого customer
	new RouteList('/delete_customer','CheckInOut','/delete_customerAction'), 	// Удаление старого customer
    new RouteList('/reset_customer','CheckInOut','/reset_customerAction'), 		// Очистка формы ввода customer
	new RouteList('','','Action'), // 
    new RouteList('','','Action'), // 
	new RouteList('','','Action'), // 
	new RouteList('','','Action'), // 
    new RouteList('','','Action'), // 
	new RouteList('','','Action'), // 
	new RouteList('','','Action'), // 
    new RouteList('','','Action'), // 
    new RouteList('','',''));


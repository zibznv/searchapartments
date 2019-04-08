<?php

	Config::set('site_name', 'PropertyRENTAL'); /* Имя сайта на закладке */

	Config::set('languages', array('en', 'ru', 'uk')); /* Список возможных языков */

	Config::set('default_language', 'ru'); /* Язык сайта по умолчанию */
	Config::set('default_controller', 'CheckInOut'); /* Контроллек по умолчанию */
	Config::set('default_action', 'indexAction'); /* Действие Action по умолчанию */

	/* Параметры подключения к базе данных */
	Config::set('db.host', 'localhost');
	Config::set('db.user', 'root');//zibznv
	Config::set('db.password', '');//ZIBznv@1412
	Config::set('db.db_name', 'rental');

	/* Список режимов доступа */
	Config::set('typeListAccess', array('admin', 'customer', 'partner', 'newRole'));

	/* Список типов апартаментов */
	Config::set('typeApartmentList', array('Standart', 'Luxe', 'Vip', 'Sharing', 'NewType'));

	/* Список доступных городов */
	Config::set('townList', array('Batumi', 'Tbilisi', 'Bakuriani', 'Lviv', 'newTown'));
	
	/* Список статусов посетителей и партнеров */
	Config::set('listStatusCustomer', array('active', 'blocked', 'vip', 'newstatus'));
	
	/* Список статусов посетителей и партнеров */
	Config::set('genderList', array('man', 'woman'));
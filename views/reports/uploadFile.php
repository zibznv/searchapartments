<?php

// Перенос файлов на сервер

	$uploads_dir = (Session::get('uploadPath') !== null)? Session::get('uploadPath') : '../webroot/uploads';
	$allowTypes = array('image/jpeg', 'image/jpg', 'image/png', 'text/xml'); 

	if (!empty($_FILES) && $_SERVER['REQUEST_METHOD']=='POST' && $_POST['hashText'] && in_array($_POST['hashText'], Session::getKeyFormList())){
		
		foreach ($_FILES["img"]["error"] as $key => $error) {
			
			if( $error == UPLOAD_ERR_INI_SIZE ){ Session::setFlash("Размер принятого файла превысил максимально допустимый размер, который задан директивой upload_max_filesize конфигурационного файла php.ini."); }
			if( $error == UPLOAD_ERR_FORM_SIZE ){ Session::setFlash("Размер загружаемого файла превысил значение MAX_FILE_SIZE, указанное в HTML-форме."); }
			if( $error == UPLOAD_ERR_PARTIAL ){ Session::setFlash("Загружаемый файл был получен только частично."); }
			if( $error == UPLOAD_ERR_NO_FILE ){ Session::setFlash("Файл не был загружен."); }
			if( $error == UPLOAD_ERR_NO_TMP_DIR ){ Session::setFlash("Отсутствует временная папка."); }
			if( $error == UPLOAD_ERR_CANT_WRITE ){ Session::setFlash("Не удалось записать файл на диск."); }
			if( $error == UPLOAD_ERR_EXTENSION ){ Session::setFlash("PHP-расширение остановило загрузку файла."); }
			
			if ($error == UPLOAD_ERR_OK && in_array($_FILES['img']['type'][$key], $allowTypes)) {
				$tmp_name = $_FILES["img"]["tmp_name"][$key];
				$name = $_FILES["img"]["name"][$key];
				if (move_uploaded_file($tmp_name, "$uploads_dir/$name")) {
					Session::setFlash("Файл {$name} успешно загружен."); 
				}
				else{
					Session::setFlash("Ошибка загрузки файла {$name} из временной папки."); 
				}
			}else{
				Session::setFlash("Недопустимый тип файла"); 
			}
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>

		<meta charset="UTF-8">
		
			<link rel="stylesheet" href="../webroot/css/template.css">
			<link rel="stylesheet" href="../webroot/css/uploadFile.css">
			
			<?php
				if($_SESSION['role'] == 'user'){
					echo '<link rel="stylesheet" href="../webroot/css/button_off1.css">';
				}	
				if($_SESSION['role'] == 'admin'){
					echo '<link rel="stylesheet" href="../webroot/css/button_off1.css">';
					echo '<link rel="stylesheet" href="../webroot/css/admin_off.css">';
				}	
			?>
		
		<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		
		<title><?php echo Config::get('site_name'); ?></title>
		
	</head>

	<body>

		<header>

			<div id='hdr'>
				<img src='../webroot/image/None1.JPG' alt='Logo'>
				<h1><?php echo Config::get('site_name'); ?></h1>
			</div>
			<div id='langpanel'>
				<ul>
					<li><p class = 'ac_type'><?= __('acc_tp'); ?>:&nbsp;<span id='atmode'><?php echo $_SESSION['role']; ?></span></p></li>
					<li><a href='/lang-ru' onclick = "return confirmDelete();">ru</a></li>
					<li><a href='/lang-uk'  onclick = "return confirmDelete();">uk</a></li>
					<li><a href='/lang-en'  onclick = "return confirmDelete();">en</a></li>
					<li><p class = 'userName'>&nbsp;<?php echo $_SESSION['userFIO']; ?></p></li>
				</ul>
			</div>		

			<nav>	
				<ul class='navmenu'>
					<li><a href="/login">CheckIn</a></li>
					<li><a href="/checkout">CheckOut</a></li>
					<li><a href="#" class='btn'>Directories</a>
						<ul>
							<li><a href="/cars" class='btn'>Cars</a></li>
							<li><a href="/drivers" class='btn'>Drivers</a></li>
							<li><a href="/smarts" class='btn'>Smarts</a></li>
							<li><a href="#" class='admin'>Setting</a>
								<ul>
									<li><a href="/loadFile" class='admin'>Uploading files</a></li>
									<li><a href="#" class='admin'>Report 2</a></li>
									<li><a href="#" class='admin'>Setting up Access</a></li>
									<li><a href="/setting" class='admin'>Settings</a></li>
								</ul>
							</li>
						</ul></li>
					<li><a href="/waybills" class='btn'>Waybills</a></li>
					<li><a href="/fillingchecks" class='btn'>Fillingchecks</a></li>
					<li><a href="#" class='btn'>Events</a>
						<ul>
							<li><a href="/events" class='btn'>CREATE Events</a></li>
							<li><a href="#" class='admin'>Task List</a></li>
							<li><a href="#" class='admin'>SMS List</a></li>
							<li><a href="#" class='admin'>EMail List</a></li>
						</ul>
					</li>
					<li><a href="/reports" class='btn'>Reports</a>
						<ul>
							<li><a href="#" class='btn'>Report 1</a></li>
						</ul>
					</li>
					<li><a href="#" class='btn'>Technical Service</a></li>
				</ul>
			</nav>
			
		</header>
		
		<div class='content'>
			<section id="leftsidebar">
				<?php
					$listPath = glob('../webroot/image/carfoto/' . "*" , GLOB_NOSORT);
					echo "<div class='uploadFiles'><ol>";
					echo "<li><a href='/uploadPathN_[../webroot/uploads]'><button class='righButton' title='Выбрать данный каталог для переноса файлов!'>webroot/uploads/..</button></a></li>";
					foreach ($listPath as $item){
						echo "<li><a href='/uploadPathN_[" . $item . "]'><button class='righButton' title='Выбрать данный каталог для переноса файлов!'>" . basename($item) . "</button></a></li>";
					}
					echo "</ol></div>";
					
					$listPath = glob('../webroot/image/userdocfoto/' . "*" , GLOB_NOSORT);
					echo "<div class='uploadFiles'><ol>";
					foreach ($listPath as $item){
						echo "<li><a href='/uploadPathN_[" . $item . "]'><button class='righButton' title='Выбрать данный каталог для переноса файлов!'>" . basename($item) . "</button></a></li>";
					}
					echo "</ol></div>";
				?>
			</section>
			<section id="maincontent">
			
				<form action='/loadFile' method='post' enctype='multipart/form-data' id='uploadFile'>
		
					<div class='flash_msg'>
						<p><?php
							// Создание ключа безопасности формы ввода
							$form_key = bin2hex(openssl_random_pseudo_bytes(16));
							$_SESSION['array_key'][] = $form_key;
							// Вывод сообщений
							if(Session::hasFlash()){
								echo Session::flash();
							}
						?></p>
					</div>
					
					<fieldset id='centre'><legend>Upload Files:</legend>
				
						<br><p class='notes'>Выберите несколько файлов для отправки в webroot/uploads!</p><br><br>
						
						<input type="file" name="img[]" multiple="multiple"/><br><br><br>
				
						<input type='reset' class='user_but' name='reset_uploadFile' value='Undo' title="Сбросить выбранные файлы!">
						<input type='submit' class='user_but' name='send_uploadFile' onclick = "return confirmDelete(); " value='Send' title="Переместить выбранные файлы на сервер!">

					</fieldset>
					
					<input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
					<input type='hidden' name='hashText' value='<?=$form_key?>'>
					
				</form>
				
			</section>
			<section id="rightsidebar">
			
			</section>
		</div>	
		<footer>
			<p>Программный комплекс контроля логистики «GUARDLOG». Версия программы: 001-122017. &copy; Игорь Зайченко</p>
		</footer>
		
		<script type='text/javascript' src="../webroot/js/comand.js"></script>
		
	</body>
</html>

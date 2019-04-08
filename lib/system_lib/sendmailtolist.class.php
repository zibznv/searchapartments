<?php	
	
// Класс для отправки почты через SMTP GMail с использованием HPMailer		
		
class Sendmailtolist {

	public static $addressList = array('i.zaychenko@avtek.ua',  //Список получателей по умолчанию
									   'd.cherniavskaya@avtek.ua');				
	public static $subject = 'Transport Control System Report';		// Тема сообщения
	public static $username = 'znvzib@gmail.com';              		// SMTP username
	public static $password = 'zibznv1412'; 						// SMTP password

	public function __construct() {}

	public static function	sendMail($content, $addressList = null, $pathFile = '../webroot/empty.jpg'){
	
		require_once (ROOT.DS.'vendor'.DS.'PHPMailer'.DS.'PHPMailer'.DS.'src'.DS.'PHPMailer.php');
		require_once (ROOT.DS.'vendor'.DS.'PHPMailer'.DS.'PHPMailer'.DS.'src'.DS.'SMTP.php');
	
		$mail = new PHPMailer(true); // Передача `true` допускает исключения
	
		try { 
			//Server settings
			$mail->setLanguage('ru', ROOT.DS.'vendor'.DS.'phpmailer'.DS.'phpmailer'.DS.'language'.DS); // Перевод на русский язык
			$mail->SMTPDebug = 0;				// Enable SMTP debugging. 0 = off for production use, 1 = client messages, 2 = client and server messages
			$mail->isSMTP(); 					// Set mailer to use SMTP
			$mail->Debugoutput = 'html';		// Ask for HTML-friendly debug output
			$mail->SMTPAuth = true;             // Enable SMTP authentication
			$mail->Port = 587;                  // TCP port to connect to 587 / 465
			$mail->SMTPSecure = 'tls';          // Enable `tls` encryption, `ssl` also accepted (безопасная передача включена ТРЕБУЕТСЯ для Gmail)
			$mail->Host = 'smtp.gmail.com';     // Specify main and backup SMTP servers
			$mail->Username = Sendmailtolist::$username;  // SMTP username
			$mail->Password = Sendmailtolist::$password;  // SMTP password
			$mail->Priority = 3; 				// Приоритет почты, 3 - нормально
			$mail->CharSet = 'UTF-8';			// Кодировка заголовков
			
			//Recipients
			$mail->setFrom(Sendmailtolist::$username, 'Administrator');	// Имя отправителя сообщения
			// Создание списка получателей $mail->addAddress('s.yaremenko@avtek.ua', 'YS');
			if(count($addressList) && is_array($addressList) && count($addressList)>0){
				foreach($addressList as $key=>$value){
					$mail->addAddress($value);
				}
			} else {
				foreach(Sendmailtolist::$addressList as $key=>$value){
					$mail->addAddress($value);
				}
			}
			//Content
			$mail->isHTML(true);                                // Set email format to HTML
			$mail->Subject = Sendmailtolist::$subject;					// Тема сообщения
			// Текст сообщения
			$mail->Body = ($content)? $content : 'Without a message!';								
			$mail->AltBody = 'Transport Control System Report';	// Альтернативное тело письма
			// Отправка прикрепленного файла если существует
			if(file_exists($pathFile)){$mail->addAttachment($pathFile);}
		
			$mail->send();
			Session::setFlash('The message was sent successfully!');
		}
		catch (Exception $e) {
			Session::setFlash('Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
		}
	}
}	
		

<?php
session_start();

require('db.php');

if ($_SERVER['REQUEST_METHOD']=='POST'){
	
	if ($_POST['eres']&&$_POST['eres']==$_SESSION['eres']){
		
		$login=$_POST['login'];
		$password=$_POST['password'];
		
		$password_hash=password_hash($password,PASSWORD_DEFAULT);
		
		$result=register_user($login,$password_hash);
		
		if ($result){
			echo '<a href="authorization.php">Authorization</a>';
		}
		else{
			echo 'Registration is not!!!';
		}
		
	}	
}
else{
	
	$eres= bin2hex(openssl_random_pseudo_bytes(16));
	
	$_SESSION['eres']=$eres;
?>

<form method='post'>
	Login <input type='text' name='login'><br>
	Password <input type='password' name='password'><br>
	<input type='hidden' name='eres' value='<?=$eres;?>'>
	<input type='submit'>
</form>
<?php
}
?>

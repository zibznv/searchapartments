<?php
function register_user($login,$password){

$connect=mysqli_connect('localhost','root','','itea');
	
$query="INSERT INTO users (login,password)
		VALUES ('$login','$password') ";
	
	
	$result=mysqli_query($connect,$query);
	
	if (!$result){
		echo mysqli_error($connect);
	}	
	
	mysqli_close($connect);	
	
	return $result;
}


function get_original_password_hash($login){
	$connect=mysqli_connect('localhost','root','','itea');
	
	$query="SELECT  password FROM users 
		where login='$login' ";
		
	$result=mysqli_query($connect,$query);
	
	if (!$result){
		echo mysqli_error($connect);
	}
	else	{
		while($row=mysqli_fetch_assoc($result)){
			$rows[]=$row;
		}
		
		mysqli_close($connect);	
	
		return $rows[0]['password'];
	}
	
	mysqli_close($connect);		
}


?>





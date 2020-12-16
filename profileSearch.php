<!DOCTYPE html>
<html>
<body>

<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

session_start();

$usernameLookup =  strtolower($_GET['username']);

if(isset($_SESSION['username'])){
	if($_SESSION['username'] == $usernameLookup){
		header("location:./profile.php");	
	}
	else{
	}
}
else{
        header("location:../loginPage.php");
}

?>

</body>
</html

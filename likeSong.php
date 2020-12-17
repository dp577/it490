<!DOCTYPE html>
<html>
<body>

<?php

include 'testRabbitMQClient.php';
session_start();

if(isset($_GET['like'])){
	setLikeTrueFalse($_SESSION['username'], $_GET['like']);
	echo "It worked.";
	header('location:./profile.php');
}
else{
	echo "Didn't work";
}

#header('location:./profile.php');

?>

</body>
</html>

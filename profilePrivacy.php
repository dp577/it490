<!DOCTYPE html>
<html>
<body>

<?php

session_start();

include('testRabbitMQClient.php');

if(isset($_GET["public"])){
	privacySet(1, $_SESSION['username']);
}

if(isset($_GET["private"])){
	privacySet(0, $_SESSION['username']);
}

header('location:./profile.php');

?>

</body>
</html>



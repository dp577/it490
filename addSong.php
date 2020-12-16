<!DOCTYPE html>
<html>
<style>
body {
  background-color: beige;
}

</style>
<body>

<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


<div class="w3-half w3-black">
<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

include('testRabbitMQClient.php');

session_start();

if((isset($_GET['Add']) and isset($_SESSION['username']))){
	echo "Redirect worked.";	
	addSongToProfile($_SESSION['username'], $_GET['Add']);
}

?>
</div>
</body>
</html>


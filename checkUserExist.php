<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include 'testRabbitMQClient.php';
set_error_handler('logErrors');
$check = accountExistsCheck($_GET['username']);

if(!$check){
        header("location:./homepage.php?check=failed");
        exit(0);
}

elseif($check){
	header("location:./profile.php?username=".$_GET['username']."&profileSearch=Search");
	exit(0);
}

?>

#!/usr/bin/php
<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');
include('testRabbitMQClient.php');
set_error_handler('logErrors');
$regUsername = strtolower($_GET['registerUsername']);
$regPassword = strtolower($_GET['registerPassword']);

if($regUsername == '' || $regPassword == '' || $regUsername == null || $regPassword == null){
        echo "Username or Password field left empty, please try again with valid credentials.";
        header("location:../loginPage.php?registration=blank");
	exit();
}

$response = registration($regUsername, $regPassword);

if($response == true){
	header("location:../loginPage.php?registration=successful");
	$logText = " Account registration attempt for User: $regUsername & Password: $regPassword | Registration successful.\n";
	logRegister($logText);
}

elseif($response == false){
	header("location:../loginPage.php?registration=failed");
	$logText = " Account registration attempt for User: $regUsername & Password: $regPassword | Failed because username already exists.\n";
	logRegister($logText);
}

?>

<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function logErrors($errorNumber, $errorString, $errorFile, $errorLine){

	if(($errorNumber != '') or ($errorNumber!= Null)){
	$client = new rabbitMQClient("testRabbitMQ.ini", "logServer");

	$time =  date("M/d/Y | h:i:sa");
	$message = "\n[$time] Error Code: $errorNumber | Description - $errorString | Error Location: $errorFile | On Line: $errorLine";

        $request = array();

        $request['type'] = "logErrors";
        $request['message'] = $message;

        #$response = $client->send_request($request);
        $response = $client->publish($request);

	return $response;
	}
}


function login($username, $password){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

	$request = array();

	$request['type'] = "login";
	$request['username'] = strToLower($username);
	$request['password'] = strToLower($password);
	
	$response = $client->send_request($request);
	#$response = $client->publish($request);
	
	logErrors(error_get_last()["type"], error_get_last()["message"], error_get_last()["file"], error_get_last()["line"]);

	return $response;
}

function registration($username, $password){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	
	$request = array();
	$request['type'] = "registerAccount";
	$request['registerUsername'] = strtolower($username);
	$request['registerPassword'] = strtolower($password);

	$response = $client->send_request($request);
        #$response = $client->publish($request);
	
	logErrors(error_get_last()["type"], error_get_last()["message"], error_get_last()["file"], error_get_last()["line"]);

        return $response;
}

function songSearch(){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

	$request = array();
	$request['type'] = "songSearch";

	$response = $client->send_request($request);
	
	logErrors(error_get_last()["type"], error_get_last()["message"], error_get_last()["file"], error_get_last()["line"]);

	return $response;	
}

function songSearchQuery($song, $album, $artist){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

        $request = array();
        $request['type'] = "songSearchQuery";
	$request['song'] = $song;
	$request['album'] = $album;
	$request['artist'] = $artist;

        $response = $client->send_request($request);

	logErrors(error_get_last()["type"], error_get_last()["message"], error_get_last()["file"], error_get_last()["line"]);

        return $response;
}

function addSongToProfile($username, $trackId){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

        $request = array();
        $request['type'] = "addSong";
        $request['username'] = $username;
        $request['trackId'] = $trackId;

        $response = $client->send_request($request);

	logErrors(error_get_last()["type"], error_get_last()["message"], error_get_last()["file"], error_get_last()["line"]);

        return $response;	
}

function retrieveProfileSongs($username){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

        $request = array();
        $request['type'] = "getProfileSongs";
        $request['username'] = $username;

        $response = $client->send_request($request);

	logErrors(error_get_last()["type"], error_get_last()["message"], error_get_last()["file"], error_get_last()["line"]);

        return $response;
}

function setComments($userProfile, $userCommenting, $date, $comment){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

        $request = array();
        $request['type'] = "setComments";
	$request['userProfile'] = $userProfile;
	$request['userCommenting'] = $userCommenting;
	$request['date'] = $date;
	$request['comment'] = $comment;

        $response = $client->send_request($request);

	logErrors(error_get_last()["type"], error_get_last()["message"], error_get_last()["file"], error_get_last()["line"]);

        return $response;
}

function getComments($userProfile){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

        $request = array();
        $request['type'] = "getComments";
        $request['userProfile'] = $userProfile;

        $response = $client->send_request($request);

	logErrors(error_get_last()["type"], error_get_last()["message"], error_get_last()["file"], error_get_last()["line"]);

        return $response;
}

function getSongDiscovery(){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

        $request = array();
        $request['type'] = "getSongDiscovery";

        $response = $client->send_request($request);

	logErrors(error_get_last()["type"], error_get_last()["message"], error_get_last()["file"], error_get_last()["line"]);

        return $response;	
}

function getRecommendedSongs($username){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

        $request = array();
	$request['type'] = "getRecommendedSongs";
	$request['username'] = "$username";

	logErrors(error_get_last()["type"], error_get_last()["message"], error_get_last()["file"], error_get_last()["line"]);

        $response = $client->send_request($request);

        return $response;
}

function accountExistsCheck($username){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

        $request = array();
        $request['type'] = "accountExistsCheck";
        $request['username'] = "$username";

        $response = $client->send_request($request);

	logErrors(error_get_last()["type"], error_get_last()["message"], error_get_last()["file"], error_get_last()["line"]);

        return $response;
}

function privacySet($privacySetting, $username){
        $client = new rabbitMQClient("testRabbitMQ.ini","testServer");

        $request = array();
        $request['type'] = "setPrivacy";
        $request['privacy'] = $privacySetting;
	$request['username'] = $username;	

        $response = $client->send_request($request);

        return $response;
}

function privacyGet($username){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

        $request = array();
        $request['type'] = "getPrivacy";
        $request['username'] = $username;

        $response = $client->send_request($request);

        return $response;
}

function setLikeTrueFalse($username, $trackId){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

        $request = array();
        $request['type'] = "setLikeTrueFalse";
	$request['username'] = $username;
	$request['trackId'] = $trackId;

        $response = $client->send_request($request);

        return $response;	
}

?>

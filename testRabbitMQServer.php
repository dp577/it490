#!/usr/bin/php
<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

global $mydb;

function doLogin($username, $password){
	global $mydb;
	global $today;
	global $time;
	
	$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

	if ($mydb->errno != 0){
                echo "Failed to execute query:".PHP_EOL;
		echo __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;

		//$logFile = fopen("logs/sql.log", "a");
                //$logText = "Exited due to SQL error.\n";

                //fwrite($logFile, $logText);
                //fclose($logFile);

                exit(0);
        }

	$query = "select password from userCredentials where username='$username';";
	$preResult = $mydb->query($query);
	$result = mysqli_fetch_array($preResult, MYSQLI_ASSOC);
        $finalResult = $result['password'];

	// Check to see if result returned something
	if($preResult->num_rows == 0){
		echo "Null Result\n";
		return false;	
	}

	// Check to see if result is equal to what the user input on webpage
	if($finalResult == $password){
		echo "Successful Result\n";
		return true;
	}
	// Return false in all other cases
	else{
		echo "Failed Result\n";
		return false;
	}
}

function doRegistration($username, $password){
	global $mydb;

	$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

	$query = "select password from userCredentials where username='$username'";
	$insertQuery = "insert into userCredentials (username, password) values ('$username', '$password')";	
	
	$preResult = $mydb->query($query);

	if ($mydb->errno != 0){
                echo "Failed to execute query:".PHP_EOL;
                echo __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		exit(0);
	}

	if($preResult->num_rows >= 1){
                echo "Account already exists.\n";
		return false;
	}

	else if($preResult->num_rows == 0){
		if(mysqli_query($mydb, $insertQuery)){
			echo "New record created successfully.\n";
		}
		return true;
	}
}

function retrieveSongs(){
	global $mydb;
        $server = new rabbitMQServer("testRabbitMQ.ini", "testServer");

	$returnArray = array();
		
	$selectQueryTotal = "select * from trackTable";
	$selectQueryTotalResult = $mydb->query($selectQueryTotal);
	$totalRows = $selectQueryTotalResult->num_rows;

	$selectQuery = "select * from trackTable order by rand()";
        $selectResult = $mydb->query($selectQuery);	
	$c=0;

	while($row = $selectResult->fetch_assoc()){
		$songInfoArray = array($row['trackId'],
			$row['trackKey'],
			$row['trackName'],
			$row['trackAlbum'],
			$row['trackArtist'],
			$row['trackReleaseDate'],
			$row['trackLengthMilliseconds'],
			$row['trackPopularity']);
		array_push($returnArray, $songInfoArray);
		$c++;
		if($c==25){
			return $returnArray;
		}
	}
}

function retrieveSongsQuery($song, $album, $artist){
	global $mydb;
        $server = new rabbitMQServer("testRabbitMQ.ini", "testServer");	
	$returnArray = array();

	if($song == ''){
		$song = '1=1';
	}
	else{
		$song = "trackName like '%$song%'";
	}
	
	if($album == ''){
		$album = '1=1';
	}
	else{
		$album = "trackAlbum like '%$album%'";
	}

	if($artist == ''){
		$artist = '1=1';
	}
	else{
		$artist = "trackArtist like '%$artist%'";
	}

	$selectQuery = "select * from trackTable where $song and $album and $artist";

	echo $selectQuery;

	$selectResult = $mydb->query($selectQuery);
	$count=0;
	$max=50;

	var_dump($selectResult);

	if($selectResult->num_rows > 0){
		while($row = $selectResult->fetch_assoc()){
			$songInfoArray = array($row['trackId'], 
				$row['trackKey'], 
				$row['trackName'], 
				$row['trackAlbum'], 
				$row['trackArtist'], 
				$row['trackReleaseDate'], 
				$row['trackLengthMilliseconds'], 
				$row['trackPopularity']);
			array_push($returnArray, $songInfoArray);
			$count++;
			if($count==$max){
				return $returnArray;
			}
		}
		return $returnArray;
	}		
	elseif($selectResult->num_rows == 0){
		return "no rows";
	}
}

function addSong($username, $trackId){
	global $mydb;
	$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");

	$insertQuery = "insert into profileSongs(trackId, username) values ('$trackId', '$username')";
	
	$selectQuery = "select * from profileSongs where trackId='$trackId' and username='$username'";
	$selectResult = $mydb->query($selectQuery);

	if($selectResult->num_rows == 0){
		echo "Record added to profile.";
		mysqli_query($mydb, $insertQuery);
		return True;
	}
	else{
		echo "Song not added.";
		return False;
	}	
}

function getProfileSongs($username){
	global $mydb;

	$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");
	$songProfileArray = array();

	$songProfileReturnArray = array();	

	$selectQuery = "select * from profileSongs where username='$username'";
	$selectResult = $mydb->query($selectQuery);

	if($selectResult->num_rows > 0){
		while($row = $selectResult->fetch_assoc()){
			array_push($songProfileArray, $row['trackId']);
		}
	}
	elseif($selectResult->num_rows == 0){
		echo "No songs in user account.";
		return False;
	}
	
	for($i = 0; $i < count($songProfileArray); $i++){	
		$selectQuery2 = "select * from trackTable where trackKey='$songProfileArray[$i]'";
		$selectResult = $mydb->query($selectQuery2);

		while($row = $selectResult->fetch_assoc()){
			$songProfileInfoArray = array
				($row['trackId'],
				$row['trackKey'],
				$row['trackName'],
				$row['trackAlbum'],
				$row['trackArtist'],
				$row['trackReleaseDate'],
				$row['trackLengthMilliseconds'],
				$row['trackPopularity'],
				$row['trackMusicKey'],
				$row['trackMode'],
				$row['totalLikes']);
			array_push($songProfileReturnArray, $songProfileInfoArray);
		}
	}
	#var_dump($songProfileReturnArray[0]);
	return $songProfileReturnArray;
}

function setComments($userProfile, $userCommenting, $date, $comment){
	global $mydb;
        $server = new rabbitMQServer("testRabbitMQ.ini", "testServer");

	$insertQuery = "insert into comments (userProfilePage, userCommenting, date, messageContent) values ('$userProfile', '$userCommenting', '$date', '$comment')";

	if(mysqli_query($mydb, $insertQuery)){
		#echo "Comment from $userCommenting on $userProfile's profile was successful.";
		return true;
	}

	elseif(!mysqli_query($mydb, $insertQuery)){
		#echo "Error desc: $mydb->error \n";
		#echo "Comment failed.";
		return false;
	}
}

function getComments($userProfile){
	global $mydb;
	$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");
	
	$returnArray = array();

	$selectQuery = "select userCommenting, date, messageContent from comments where userProfilePage='$userProfile'";
	$selectResult = $mydb->query($selectQuery);
	
	while($row = $selectResult->fetch_assoc()){
		$commentInfo = array($row['userCommenting'],
			$row['date'],
			$row['messageContent']);

		array_push($returnArray, $commentInfo);	
	}
	return $returnArray;
}

function getSongDiscovery(){
	global $mydb;
	$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");
	
	$returnArray = array();
	$c = 0;

	$selectQuery = "select * from trackTable where trackDemoLink!='None' order by rand()";
	$selectResult = $mydb->query($selectQuery);

	while($row = $selectResult->fetch_assoc()){
		$songDiscoveryInfoArray = array($row['trackName'],
                        $row['trackAlbum'], $row['trackArtist'],
			$row['trackDemoLink'],
			$row['trackKey']);
		array_push($returnArray, $songDiscoveryInfoArray);
		
		$c++;
		
		if($c>2){
			return $returnArray;
		}
	}
}

function getRecommendedSongs($username){
	global $mydb;
	$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");
	
	$count=0;
	$max=10;

	$returnArray = array();

	$selectAvgDanceQuery = "select AVG(trackDanceability) from trackTable left outer join profileSongs on trackTable.trackKey = profileSongs.trackId where username='$username'";
	$selectAvgEnergyQuery = "select AVG(trackEnergy) from trackTable left outer join profileSongs on trackTable.trackKey = profileSongs.trackId where username='$username'";
	
	$avgDanceResult = $mydb->query($selectAvgDanceQuery);
	$avgEnergyResult = $mydb->query($selectAvgEnergyQuery);
	
	#var_dump($avgDanceResult);

	while($row = $avgDanceResult->fetch_assoc()){
		if($row['AVG(trackDanceability)'] == Null){
			return false;
		}
		else{
			$avgDance = $row['AVG(trackDanceability)'];
			#echo $avgDance;
		}
	}

	while($row = $avgEnergyResult->fetch_assoc()){
                if($row['AVG(trackEnergy)'] == Null){
                        return false;
		}
		else{
			$avgEnergy = $row['AVG(trackEnergy)'];
                	#echo $avgEnergy;
		}
        }

	$selectAvgProfileQuery = "select * from trackTable where trackEnergy>$avgEnergy-.10 and trackEnergy<$avgEnergy+.10 and trackDanceability<$avgDance+.1 and trackDanceability>$avgDance-.1 order by rand()";
	$selectAvgProfileResult = $mydb->query($selectAvgProfileQuery);

	while($row = $selectAvgProfileResult->fetch_assoc()){
		$songProfileInfoArray = array
                                ($row['trackId'],
                                $row['trackKey'],
                                $row['trackName'],
                                $row['trackAlbum'],
                                $row['trackArtist'],
                                $row['trackReleaseDate'],
                                $row['trackLengthMilliseconds'],
                                $row['trackPopularity'],
                                $row['trackMusicKey'],
				$row['trackMode']);
		array_push($returnArray, $songProfileInfoArray);
		$count++;
		if($count==$max){
			# var_dump($returnArray);
			return $returnArray;
		}
	}
	#var_dump($returnArray);
	return $returnArray;
}

function accountExistsCheck($username){
	global $mydb;
	$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");

	$selectQuery = "select * from userCredentials where username='$username'";
	$selectResult = $mydb->query($selectQuery);

	if($selectResult->num_rows == 0){
		return false;
	}
	else{
		return true;
	}
}

function setPrivacy($privacy, $username){
	global $mydb;
	$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");

	$updateQuery = "update userCredentials set privacy=$privacy where username='$username';";
        $updateResult = $mydb->query($updateQuery);	

	if(mysqli_query($mydb, $updateQuery)){
		echo "Privacy updated.";
	}	
}

function getPrivacy($username){
	global $mydb;
        $server = new rabbitMQServer("testRabbitMQ.ini", "testServer");
	
        $selectQuery = "select privacy from userCredentials where username='$username'";
	$selectResult = $mydb->query($selectQuery);
	
	while($row = $selectResult->fetch_assoc()){
		if($row['privacy'] == 1){
			return 1;
		}
		else{
			return 0;
		}
	}
}

function setLikeFlag($username, $trackId){
        global $mydb;
        $server = new rabbitMQServer("testRabbitMQ.ini", "testServer");
	
	echo "\n$username, $trackId";

	$selectQuery = "select likeFlag from profileSongs where username='$username' and trackId='$trackId'";
	$selectResult = $mydb->query($selectQuery);
	
	while($row = $selectResult->fetch_assoc()){
		if($row['likeFlag'] == 0){
			echo "\nFalse, unliked, switch to true.";
			$updateQuery = "update profileSongs set likeFlag=1 where username='$username' and trackId='$trackId'";
			$updateResult = $mydb->query($updateQuery);
				
			$updateQuery2 = "update trackTable set totalLikes=totalLikes+1 where trackKey='$trackId'";
			$updateResult2 = $mydb->query($updateQuery2);
		}
		elseif($row['likeFlag'] == 1){
			echo "\nTrue, liked, switch to unliked.";
			$updateQuery = "update profileSongs set likeFlag=0 where username='$username' and trackId='$trackId'";
			$updateResult = $mydb->query($updateQuery);

			$updateQuery2 = "update trackTable set totalLikes=totalLikes-1 where trackKey='$trackId'";
			$updateResult2 = $mydb->query($updateQuery2);

			#var_dump($updateResult2);
		}
	}
}

function requestProcessor($request){
	echo "\nReceived Request:\n\n";
	var_dump($request);

	if(!isset($request['type'])){
    		return "ERROR: unsupported message type";
	}

  	switch ($request['type']){
    		case "login":
			return doLogin($request['username'], 
			$request['password']);
		case "registerAccount":
			return doRegistration($request['registerUsername'], 
			$request['registerPassword']);
    		case "validate_session":
      			return doValidate($request['sessionId']);	
		case "songSearch":
			return retrieveSongs();
		case "songSearchQuery":
			return retrieveSongsQuery($request['song'],
				$request['album'], $request['artist']);
		case "addSong":
			return addSong($request['username'], $request['trackId']);
		case "getProfileSongs":
			return getProfileSongs($request['username']);
		case "setComments":
			return setComments($request['userProfile'],
				$request['userCommenting'],
				$request['date'],
				$request['comment']);
		case "getComments":
			return getComments($request['userProfile']);	
		case "getSongDiscovery":
			return getSongDiscovery();
		case "getRecommendedSongs":
			return getRecommendedSongs($request['username']);
		case "accountExistsCheck":
			return accountExistsCheck($request['username']);
		case "setPrivacy":
			return setPrivacy($request['privacy'], $request['username']);
		case "getPrivacy":
			return getPrivacy($request['username']);
		case "setLikeTrueFalse":
			return setLikeFlag($request['username'], $request['trackId']);
	}		
	return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$mydb = new mysqli('localhost','kevin','cdkt','CDKTTechnologies');

if(!$mydb){
	die("Connection failed: ".mysqli_connect_error());
}

$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");
$server->process_requests('requestProcessor');

exit();

?>

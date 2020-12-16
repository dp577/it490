<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include 'testRabbitMQClient.php';
set_error_handler('logErrors');
session_start();
?>

<!DOCTYPE html>
<html>
<body>

<center>

<h1><u>Welcome to Song Discovery</u></h1>

<?php

if(isset($_GET['Add'])){
        $songAdded = addSongToProfile($_SESSION['username'], $_GET['Add']);

        if($songAdded){
                echo "<b>".$_GET['songName']." by ".$_GET['artistName']." has been successfully added to your profile.</b><br><br>";
}
        else{
                echo "<b>Song not added because it is already added to your profile.</b><br><br>";
        }
}

$songDiscoveryInfo = getSongDiscovery();
$len = count($songDiscoveryInfo);

for($i=0; $i<$len; $i++){ ?>

	<iframe src="<?php echo $songDiscoveryInfo[$i][3]?>" frameborder="0" height="100" width="250" title="Test Page"></iframe><br>

<?php
	
	echo "Song - ";
	echo $songDiscoveryInfo[$i][0];
	echo "<br>";
	echo "Album - ";
	echo $songDiscoveryInfo[$i][1];
	echo "<br>";
	echo "Artist - ";
	echo $songDiscoveryInfo[$i][2];
	echo "<br>";
?>
	<form action=./songDiscovery.php>
                <input type="submit" name=<?php echo $songDiscoveryInfo[$i][4]; ?> value="Add to Playlist">
                <input type="hidden" name="Add" value=<?php echo $songDiscoveryInfo[$i][4];?>>
                <input type="hidden" name="songName" value="<?php echo $songDiscoveryInfo[$i][0];?>">
                <input type="hidden" name="artistName" value="<?php echo $songDiscoveryInfo[$i][2];?>">
                </form><br>
<?php
}

?>


<form action="./songDiscovery.php">
<input type="submit" value="Get New Songs" name="generateNewSongs"</input>
</form>

<hr>

<?php

echo '<a href="homepage.php">Homepage</a> | ';
echo '<a href="profile.php">Your Profile</a> | ';
echo '<a href="songSearcher.php">Song Search</a> | ';
echo '<br><br><a href="logout.php?logout">Logout</a>';

?>

</center>
</body>
</html>


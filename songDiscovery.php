<?php
include 'testRabbitMQClient.php';
session_start();
?>

<!DOCTYPE html>
<html>
<body>
<style>
body {
  background-color: lightgrey;
}
</style>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<center>
 <div class="w3-container w3-cyan">
<h1><u>Welcome to Song Discovery</u></h1></div>

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

<div class="w3-row w3-black">

<?php

echo '<div class="w3-quarter w3-black"> <a href="homepage.php">Homepage</a></div>';

echo '<div class="w3-quarter w3-black"> <a href="songDiscovery.php">Song Discovery</a></div>';

echo '<div class="w3-quarter w3-black"><a href="songSearcher.php">Song Search</a></div>';

echo '<div class="w3-quarter w3-black"> <a href="logout.php?logout">Logout</a></div>';
?>

</center>
</body>
</html>

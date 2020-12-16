<!DOCTYPE html>
<html>
<style>
body {
	background-color: beige;
}
table
{
  border-collapse:collapse;
  width:100%;
  border-spacing: 0;
  table-layout:fixed;
  border:1px solid #000000;
}
th,td
{
  border-spacing: 0;
  border:1px solid #000000;
  overflow: hidden;
  text-overflow: ellipsis;
  word-wrap: break-word;
}

</style>
<body>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include ('testRabbitMQClient.php');
include ('functionHolder.php');
set_error_handler('logErrors');
session_start();

if(!isset($_SESSION['username'])){
	header("location:../loginPage.php");
	exit(0);
}

echo '<a href="homepage.php">Homepage</a>';
echo ' | ';
echo '<a href="songDiscovery.php">Song Discovery</a>';
echo ' | ';
echo '<a href="profile.php">Your Profile</a>';
echo ' | ';
echo '<a href="logout.php?logout">Logout</a>';

?>

<h1><u>Welcome to Song Search</u></h1>
<h3>Below is a limited list of songs within our database. <br><br> 
Feel free to use the provided search queries to find a song you are looking for.</h3>

<form action="./songSearcher.php">
        <input type="text" placeholder="Song" name="song"><br>
        <input type="text" placeholder="Album" name="album"><br>
        <input type="text" placeholder="Artist" name="artist"><br><br>

	<input type="submit" value="Search" name="searchQuery"><br><br>
</form>

<form action="./songSearcher.php">
	<input type="submit" value="Random Results" name="random"><br><br>
</form>

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

?>

<table border='2'>

<tr>

<th>Song</th>
<th>Album</th>
<th>Artist</th>
<th>Release Date</th>
<th>Song Length</th>
<th>Popularity</th>
<th>Add Song to Profile</th>

</tr>

<?php

if(isset($_GET['searchQuery'])){
	$querySong = strtolower($_GET['song']);
	$queryArtist = strtolower($_GET['artist']);
	$queryAlbum = strtolower($_GET['album']);
	
	$searchQueryArray = songSearchQuery($querySong, $queryAlbum, $queryArtist);
	
	if($searchQueryArray == "no rows"){
		echo "<b>No results found, please try again.</b><br><br>";
	}

	elseif(((empty($_GET['song'])) && (empty($_GET['artist'])) && (empty($_GET['album'])))){
                        echo "<b>Too many search fields left empty, please try again.</b><br><br>";
        }
	
	else{
		
		for($i = 0; $i<count($searchQueryArray); $i++){
                ?>

		<tr>
		<td> <?php echo $searchQueryArray[$i][2];?> </td>
                <td> <?php echo $searchQueryArray[$i][3];?> </td>
                <td> <?php echo $searchQueryArray[$i][4];?> </td>
                <td> <?php echo $searchQueryArray[$i][5];?> </td>
                <td> <?php echo milliConversion($searchQueryArray[$i][6]);?> </td>
                <td> <?php echo $searchQueryArray[$i][7];?> </td>
		<td><form action="./songSearcher.php">
                <input type="submit" name=<?php echo $searchQueryArray[$i][1]; ?> value="Add">
		<input type="hidden" name="Add" value=<?php echo $searchQueryArray[$i][1];?>>
		<input type="hidden" name="songName" value="<?php echo $searchQueryArray[$i][2];?>">
		<input type="hidden" name="artistName" value="<?php echo $searchQueryArray[$i][4];?>">	
                </form></td>	
		</tr>
		<?php
		}	
	}	
}

elseif((!isset($_GET['searchQuery']) && isset($_SESSION['username']))){
	$songInfoArray = songSearch();
	for($i=1; $i<count($songInfoArray); $i++){
		?>

		<tr>
		<td> <?php echo $songInfoArray[$i][2]; ?> </td>
        	<td> <?php echo $songInfoArray[$i][3]; ?> </td>
		<td> <?php echo $songInfoArray[$i][4]; ?> </td>
		<td> <?php echo $songInfoArray[$i][5]; ?> </td>
		<td> <?php echo milliConversion($songInfoArray[$i][6]); ?> </td>
        	<td> <?php echo $songInfoArray[$i][7]; ?> </td>
		<td><form action=./songSearcher.php>
		<input type="submit" name=<?php echo $songInfoArray[$i][1]; ?> value="Add">
		<input type="hidden" name="Add" value=<?php echo $songInfoArray[$i][1];?>>
		<input type="hidden" name="songName" value="<?php echo $songInfoArray[$i][2];?>">
		<input type="hidden" name="artistName" value="<?php echo $songInfoArray[$i][4];?>">
		</form></td>
		</tr>
 		
		<?php
	}
}

?>

</table>

<?php

echo '<br><a href="homepage.php">Homepage</a>';
echo ' | ';
echo '<a href="songDiscovery.php">Song Discovery</a>';
echo ' | ';
echo '<a href="profile.php">Your Profile</a>';
echo ' | ';
echo '<a href="logout.php?logout">Logout</a>';

?>


</body>
</html>


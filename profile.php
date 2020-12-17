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

<div class="w3-quarter w3-black">
<?php

date_default_timezone_set('America/New_York');
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include('testRabbitMQClient.php');
include('functionHolder.php');

session_start();

#$check = accountExistsCheck($_GET['username']);

#if(((!$check) && !isset($_SESSION['username']))){
#	header("location:./homepage.php?check=failed");
#	exit(0);
#}

if(!isset($_SESSION['username'])){
	header("location:../loginPage.php");
	exit(0);
}

if((isset($_SESSION['username']) and (!isset($_GET['profileSearch']))) or ($_SESSION['username'] == $_GET['username']) or (isset($_GET['recommendations'])) or (isset($_GET['Add']))){
	
	echo '<div class="w3-quarter w3-black"> <a href="homepage.php">Homepage</a></div>';

	echo '<div class="w3-quarter w3-black"> <a href="songDiscovery.php">Song Discovery</a></div>';

	echo '<div class="w3-quarter w3-black"><a href="songSearcher.php">Song Search</a></div>';

	echo '<div class="w3-quarter w3-black"> <a href="logout.php?logout">Logout</a></div>';

	?>
  </div>
  <div class="w3-container w3-cyan">
  <h1><u>Welcome to Your Profile Page</u></h1></div>
  
  
<div class="w3-row">
  <div class="w3-half">
	<?php

	$privacy = privacyGet($_SESSION['username']);

        if($privacy == 1){
                echo "<b>Your account is currently public.</b>";
        }
        elseif($privacy == 0){
                echo "<b>Your account is currently private.</b>";
        }

	?>
  </div>
  <div class="w3-half">
	<form action="./profilePrivacy.php">
	<input type="submit" name="public" value="Make Profile Public">
	<input type="submit" name="private" value="Make Profile Private">
	</form>	
  </div>
</div>
  <div class="w3-row">
  <div class="w3-quarter">
	<h3><u>Your Playlist</h3></u>
  
  <?php

	if(isset($_GET['Add'])){
        $songAdded = addSongToProfile($_SESSION['username'], $_GET['Add']);

        if($songAdded){
                echo "<b>Song has been successfully added to your profile.</b><br><br>";
}
        else{
                echo "<b>Song not added because it is already added to your profile.</b><br><br>";
        }
}

	$outputArray = retrieveProfileSongs($_SESSION['username']);
	?>
	</div>
  <div class="w3-threequarter w3-dark-grey">
	<table border=2>
	
	<tr>
	<th>Like?</th>
	<th>Total Likes</th>
        <th>Song</th>
        <th>Album</th>
        <th>Artist</th>
        <th>Release Date</th>
        <th>Song Length</th>
        <!--<th>Popularity</th>
        <th>Mode/Scale</th>
        <th>Key</th>-->
        </tr> 
	
	<?php
	if($outputArray != false){
	for($i = 0; $i < count($outputArray); $i++){
		?>

		<tr>
		<td><form action="./likeSong.php">
                <input type="submit" name=<?php echo $outputArray[$i][1]; ?> value="Like/Unlike">
                <input type="hidden" name="like" value=<?php echo $outputArray[$i][1];?>>
                </form></td>
		<td> <?php echo $outputArray[$i][10];?> </td>
		<td> <?php echo $outputArray[$i][2];?> </td>
                <td> <?php echo $outputArray[$i][3];?> </td>
                <td> <?php echo $outputArray[$i][4];?> </td>
		<td> <?php echo $outputArray[$i][5];?> </td>
		<td> <?php echo milliConversion($outputArray[$i][6]);?> </td>
		<!--<td> <?php echo $outputArray[$i][7];?> </td>
		<td> <?php echo convertMode($outputArray[$i][8]);?> </td>
		<td> <?php echo convertKey($outputArray[$i][9]);?> </td>-->
		</tr>

		<?php
	}
}
	?></table>
  </div>
</div>
<div class="w3-row">
  <div class="w3-quarter">
	<h3><u>Songs We Think You'd Like Based on Your Playlist</u></h3>

	<?php
	$recommendationArray = getRecommendedSongs($_SESSION['username']);
	?>
  </div>
  <div class="w3-threequarter w3-dark-grey">
	<table border=2>

        <tr>
        <th>Song</th>
        <th>Album</th>
        <th>Artist</th>
        <th>Release Date</th>
        <th>Song Length</th>
        <th>Popularity</th>
        <th>Mode/Scale</th>
	<th>Key</th>
	<th>Add Song to Profile</th>
        </tr>

	<?php
	if($recommendationArray != false){	
	for($i=0; $i<count($recommendationArray); $i++){
                ?>

                <tr>
                <td> <?php echo $recommendationArray[$i][2];?> </td>
                <td> <?php echo $recommendationArray[$i][3];?> </td>
                <td> <?php echo $recommendationArray[$i][4];?> </td>
                <td> <?php echo $recommendationArray[$i][5];?> </td>
                <td> <?php echo milliConversion($recommendationArray[$i][6]);?> </td>
                <td> <?php echo $recommendationArray[$i][7];?> </td>
                <td> <?php echo convertMode($recommendationArray[$i][8]);?> </td>
		<td> <?php echo convertKey($recommendationArray[$i][9]);?> </td>
		<td><form action="./profile.php">
                <input type="submit" name=<?php echo $recommendationArray[$i][1]; ?> value="Add">
                <input type="hidden" name="Add" value=<?php echo $recommendationArray[$i][1];?>>
                </form></td>	
                </tr>

                <?php
        }
}
	?>
		
	</table>	
  </div>
</div>
<div class="w3-row">
  
	<br>	
	<form action="./profile.php">
	<input type="submit" value="Get New Recommendations" name="recommendations"</input>
	</form>

	<h3><u>Comment Section</u></h3>
		
	<?php
	$comments = getComments($_SESSION['username']);
		
	for($i = 0; $i < count($comments); $i++){
		echo $comments[$i][0];
		echo "<br>";
		echo $comments[$i][1];
		echo "<br>";
		?><b><?php echo $comments[$i][2]; ?></b><?php
		echo "<br><br>";
	}	

	?>

	<form action='./personalComments.php'>
		<input type='hidden' name='userProfile' value='<?php echo $_SESSION['username']?>'>
		<input type='hidden' name='date' value='<?php echo date('Y-m-d  H:i:s')?>'>
		<textarea name='message' rows='5' cols='50'></textarea>
		<br><button type='submit' name='commentSubmit'>Comment</button>
	</form>
 
 
</div>
<div class="w3-quarter w3-black">
	<?php
}

elseif(isset($_GET['profileSearch'])){
	
	$userProfile = $_GET['username'];
	$privacyIs = privacyGet($userProfile);

	if($privacyIs==1){

	echo '<div class="w3-quarter w3-black"> <a href="homepage.php">Homepage</a></div>';

	echo '<div class="w3-quarter w3-black"> <a href="songDiscovery.php">Song Discovery</a></div>';

	echo '<div class="w3-quarter w3-black"><a href="songSearcher.php">Song Search</a></div>';

	echo '<div class="w3-quarter w3-black"> <a href="logout.php?logout">Logout</a></div>';

	?>
</div>
<div class="w3-container w3-cyan">
  <h1><u>Welcome to <?php echo $_GET['username'];?>'s Profile Page</u></h1></div>
<div class="w3-row">
  <div class="w3-quarter">
	<?php
	echo "<h3><u>Below(To the right for mobile) is $userProfile's playlist.</u></h3>";
	$outputArray = retrieveProfileSongs($_GET['username']);
        ?>
    </div>
    <div class="w3-threequarter w3-dark-grey">
        <table border=2>
        <tr>
        <th>Song</th>
        <th>Album</th>
        <th>Artist</th>
        <th>Release Date</th>
	<th>Song Length</th>
        <th>Popularity</th>
        <th>Mode/Scale</th>
        <th>Key</th>
        </tr>

        <?php
        for($i = 0; $i < count($outputArray); $i++){
                ?>

                <tr>
                <td> <?php echo $outputArray[$i][2];?> </td>
                <td> <?php echo $outputArray[$i][3];?> </td>
                <td> <?php echo $outputArray[$i][4];?> </td>
                <td> <?php echo $outputArray[$i][5];?> </td>
                <td> <?php echo milliConversion($outputArray[$i][6]);?> </td>
                <td> <?php echo $outputArray[$i][7];?> </td>
                <td> <?php echo convertMode($outputArray[$i][8]);?> </td>
                <td> <?php echo convertKey($outputArray[$i][9]);?> </td>
                </tr>
                <?php
        }
	
	?></table>
  </div>
</div>
<div class="w3-row">
  
	<h3><u>Comment Section</u></h3>
  
	<?php
        $comments = getComments($_GET['username']);
	
        for($i = 0; $i < count($comments); $i++){
		echo $comments[$i][0];
		echo "<br>";
                echo $comments[$i][1];
		echo "<br>";
		?><b><?php echo $comments[$i][2]; ?></b><?php
		echo "<br><br>";
        }

        ?>

	<form action='./publicComments.php'>
                <input type='hidden' name='userProfile' value='<?php echo $_GET['username']?>'>
		<input type='hidden' name='date' value='<?php echo date('Y-m-d H:i:s')?>'>
		<input type='hidden' name='username' value='username'>
                <textarea name='message' rows='5' cols='50'></textarea>
                <br><button type='submit' name='commentSubmit'>Comment</button>
        </form>
 
</div>
<div class="w3-quarter w3-black">
<?php
	}

	elseif($privacyIs == 0){
		echo "$userProfile has their profile set to private, sorry.";
	}
}

  echo '<div class="w3-quarter w3-black"> <a href="homepage.php">Homepage</a></div>';

	echo '<div class="w3-quarter w3-black"> <a href="songDiscovery.php">Song Discovery</a></div>';

	echo '<div class="w3-quarter w3-black"><a href="songSearcher.php">Song Search</a></div>';

	echo '<div class="w3-quarter w3-black"> <a href="logout.php?logout">Logout</a></div>';

?>
</div>
</center>
</body>
</html>

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

date_default_timezone_set('America/New_York');
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include('testRabbitMQClient.php');
include('functionHolder.php');

session_start();

#$check = accountExistsCheck($_GET['username']);

#if(((!$check) && !isset($_SESSION['username']))){
# header("location:./homepage.php?check=failed");
# exit(0);
#}

if(!isset($_SESSION['username'])){
  header("location:../loginPage.php");
  exit(0);
}

if((isset($_SESSION['username']) and (!isset($_GET['profileSearch']))) or ($_SESSION['username'] == $_GET['username']) or (isset($_GET['recommendations'])) or (isset($_GET['Add']))){
  
  echo '<a href="homepage.php">Homepage</a>';
  echo ' | ';
  echo '<a href="songDiscovery.php">Song Discovery</a>';
  echo ' | ';
  echo '<a href="songSearcher.php">Song Search</a>';
  echo ' | ';
  echo '<a href="logout.php?logout">Logout</a>';

  ?><h1><u>Welcome to Your Profile Page</u></h1>
  <h3><u>Your Playlist</h3></u><?php

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
  
  <table>
  
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
  if($outputArray != false){
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
}
  ?></table>

  <h3><u>Songs We Think You'd Like Based on Your Playlist</u></h3>

  <?php
  $recommendationArray = getRecommendedSongs($_SESSION['username']);
  ?>

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

  <?php
}

elseif(isset($_GET['profileSearch'])){
  $userProfile = $_GET['username'];

  echo '<a href="homepage.php">Homepage</a>';
  echo ' | ';
  echo '<a href="songDiscovery.php">Song Discovery</a>';
  echo ' | ';
  echo '<a href="songSearcher.php">Song Search</a>';
  echo ' | ';
  echo '<a href="logout.php?logout">Logout</a>';

  ?><h1><u>Welcome to <?php echo $_GET['username'];?>'s Profile Page</u></h1>
  <?php
  echo "<h3><u>Below is $userProfile's playlist.</u></h3>";
  $outputArray = retrieveProfileSongs($_GET['username']);
        ?>

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
<?php
}

echo '<br><a href="homepage.php">Homepage</a>';
echo ' | ';
echo '<a href="songDiscovery.php">Song Discovery</a>';
echo ' | ';
echo '<a href="songSearcher.php">Song Search</a>';
echo ' | ';
echo '<a href="logout.php?logout">Logout</a>';

?>
</body>
</html>

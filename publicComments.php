<!DOCTYPE html>
<html>
<body>

<?php
include 'testRabbitMQClient.php';
session_start();

$userCommenting = $_SESSION['username'];
$userProfile = $_GET['userProfile'];
$date = $_GET['date'];
$message = $_GET['message'];

if(empty($message)){
        header("location:./profile.php?username=$userProfile&profileSearch=Search");
        exit(0);
}

setComments($userProfile, $userCommenting, $date, $message);

header("location:./profile.php?username=$userProfile&profileSearch=Search");

?>

</body>
</html>

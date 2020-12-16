<!DOCTYPE html>
<html lang="en">
<style>
  body {
    background-color: black;
  }

  <body>
</style>

<center>

  <head>
    <title>Login Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  </head>

  <div class="w3-container w3-cyan">
    <h1>CDKT</h1>
    <p>Log in to your account or make one!</p>
  </div>

  <?php
if($_GET['registration'] == "successful"){
?>

  <div>
    <?php echo "Your account has been created successfuly, feel free to login." ?> </div>

  <?php
}
?>

  <?php
if($_GET['login'] == "blank"){  
?>

  <div>
    <?php echo "Login field(s) left blank, please try again." ?> </div>

  <?php
}
?>

  <?php
if($_GET['login'] == "failed"){
?>

  <div>
    <?php echo "Incorrect login credentials were used. Please try again" ?> </div>

  <?php
}
?>

  <div class="w3-third w3-black">
    <form action="./php/login.php">
      <h3><u>Login</u></h3>

      <input type="text" placeholder="Username" name="username"><br>
    <input type="password" placeholder="Password" name="password"><br><br>

    <input type="submit" value="Login" name="login"><br>
</form>
</div>
<div class="w3-third w3-black">
    <h2>- OR -</h2>
</div>

<?php
if($_GET['registration'] == "blank"){
?>

<div> <?php echo "Registration field(s) left blank, please try again." ?> </div>

<?php
}
?>

<?php
if($_GET['registration'] == "failed"){
?>

<div> <?php echo "Account could not be created because username already exists. Please try again with a different username." ?> </div>

<?php
}
?>

<div class="w3-third w3-black">
<form action="./php/registration.php">

    <h3><u>Register Non-Existing User Account</u></h3>

    <input type="text" placeholder="New Username" name="registerUsername"><br>
    <input type="password" placeholder="New Password" name="registerPassword"><br><br>

    <input type="submit" value="Register Account" name="registerAccount"><br>

</form>

</div>
</div>
</center>
</div>
</body>
</html>

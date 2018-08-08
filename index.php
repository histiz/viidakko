<?php
include "EmailValidator.php";
$validator = new EmailValidator();
?>
<!DOCTYPE html>
<html>
<body>

<h2>Email collector:</h2>

<form action="/index.php" method="post">
  <br>
  Email:<br>
  <input type="text" name="email" value="">
  <br>
  <input type="submit" name="Submit" value="Submit">
</form> 
<?php
 if(isset($_POST['Submit'])){
     ?>
     <p><?php echo $validator->valid_email($_POST["email"])?></p>
     <?php
 }
?>

</body>
</html>
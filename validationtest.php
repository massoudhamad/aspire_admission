<?php include("validation.php"); ?>
<form method="post" action="" name="form">
Full name : <input type="text" name="name" value="<?php echo $valid_name; ?>" /> 
<?php echo $error_name; ?> 
Email : <input type="text" name="name" value="<?php echo $valid_email; ?>" /> 
<?php echo $error_email; ?> 
Username : <input type="text" name="name" value="<?php echo $valid_username; ?>" /> 
<?php echo $error_username; ?> 
Password : <input type="password" name="name" value="<?php echo $valid_password; ?>" /> 
<?php echo $error_password; ?> 
Gender : <select name="gender"> 
<option value="0">Gender</option>
<option value="1">Male</option>
<option value="2">Female</option>
</select>
<?php echo $error_gender; ?> 
<input type="submit" name="" value="Save">
</form>
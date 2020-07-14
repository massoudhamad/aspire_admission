<?php
if($_POST)
{
$name = $_POST['name']; 
$email = $_POST['email']; 
$username = $_POST['username']; 
$password = $_POST['password']; 
$gender = $_POST['gender']; 
// Full Name
if (eregi('^[A-Za-z0-9 ]{3,20}$',$name))
{
$valid_name=$name;
}
else
{ 
$error_name='Enter valid Name.'; 
}

// Email
if (eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$', $email))
{
$valid_email=$email; 
}
else
{ 
$error_email='Enter valid Email.'; 
}

// Usename min 2 char max 20 char
if (eregi('^[A-Za-z0-9_]{3,20}$',$username))
{
$valid_username=$username;
}
else
{ 
$error_username='Enter valid Username min 3 Chars.'; 
}

// Password min 6 char max 20 char
if (eregi('^[A-Za-z0-9!@#$%^&*()_]{6,20}$',$password))
{
$valid_password=$password;
}
else
{ 
$error_password='Enter valid Password min 6 Chars.'; 
}

// Gender
if ($gender==0)
{
$error_gender='Select Gender'; 
}
else
{ 
$valid_gender=$gender;
}

if((strlen($valid_name)>0)&&(strlen($valid_email)>0)
&&(strlen($valid_username)>0)&&(strlen($valid_password)>0) && $valid_gender>0 )
{
mysql_query(" SQL insert statement ");
header("Location: thanks.html");
}
else{ }

}
?>
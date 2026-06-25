<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$db=new DBHelper();
$username=$db->getData("users","userName","userID",$_SESSION['user_session']);
$password=$db->getData("users","lastName","userID",$_SESSION['user_session']);
?>

 <div class="page-title">
          <div>
            <h1><i class="fa fa-dashboard"></i> Welcome Page</h1>
            <p>Start your aplication</p>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <h3 class="card-title">Congratulations</h3>
              <p>You have sucessfully created an account. Your username and password are given bellow. You must always use your username and password to login into Admission System. You may change your password whenever you want.</p>
               <h4 class="text text-danger">Username: <?php echo $username;?></h4>
               <h4 class="text text-danger">Password: <?php echo strtoupper($password);?></h4>
               <p>
                   Please save this information for next use, You may find these data in your email.
               </p>
              			<div class="row">
               			<form name="" method="post" action="action_success.php">
                        <div class="col-lg-3">
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="submit" name="doSubmit" value="Proceed to Application" class="btn btn-success form-control" />
                        </div>
   					 </form>
   					 </div>
              
           
          </div>
          
        </div></div>


                        

<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>   
<script type="text/javascript">
$(document).ready(function(){
    $("#role").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".4").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".4").hide();
            }
        });
    }).change();
});
</script>
<h1>Edit User Data</h1>
<?php
$db = new DBHelper();
$userData = $db->getRows('users',array('where'=>array('userID'=>$_GET['id']),'return_type'=>'single'));
if(!empty($userData)){
?>

<form name="" method="post" action="action_user.php">
 <div class="row">
        <div class="col-lg-12">
<div class="row">
<div class="col-lg-4">
<div class="form-group">
<label for="courseCode">First Name:</label>
<input type="text" id="fname" name="fname" placeholder="First Name" value="<?php echo $userData['firstName'];?>" class="form-control" required="required" />
</div>
</div>
<div class="col-lg-4">
<div class="form-group">
<label for="email">Middle Name</label>
<input type="text" id="mname" name="mname" placeholder="Middle Name" value="<?php echo $userData['middleName'];?>" class="form-control" required="required" />
</div>
</div>
    <div class="col-lg-4">
<div class="form-group">
<label for="email">Last Name</label>
<input type="text" id="lname" name="lname" placeholder="Last Name" value="<?php echo $userData['lastName'];?>" class="form-control" required="required" />
</div></div></div>
<div class="row">
<div class="col-lg-4">
<div class="form-group">
<label for="email">Gender</label>
<select name="gender" class="form-control" required="">
    <?php 
    if($userData['gender']=="Male")
    {
        echo "<option value='Male' selected>Male</option>";
        echo "<option value='Female'>Female</option>";
    }
    else 
    {
        echo "<option value='Male'>Male</option>";
        echo "<option value='Female' selected>Female</option>";
    }
    ?>
    
</select>
</div>
</div>
<div class="col-lg-4">
<div class="form-group">
<label for="email">Email</label>
<input type="text" id="email" name="email" placeholder="Email" value="<?php echo $userData['email'];?>" class="form-control" required="required email" />
</div>
</div>
    <div class="col-lg-4">
        
<div class="form-group">
<label for="email">Phone Number</label>
<input type="text" id="phone" name="phone" value="<?php echo $userData['phoneNumber'];?>" placeholder="Phone Number" class="form-control" required="required" />
</div>
    </div>
    
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
<label for="email">User Roles</label>
<select name="roleID" id="role" class="form-control" required="">
    <?php 
    $userRole=$db->getRows("userroles",array('where'=>array('userID'=>$userData['userID']),'order_by roleID ASC'));
    if(!empty($userRole))
        {
            $count=0;
            foreach($userRole as $role)
            {
                $roleID=$role['roleID'];
                $roleName=$db->getData("roles","roleName", "roleID",$roleID);
            }
        }
    ?>
    <option value="<?php echo $roleID;?>" selected="">
    <?php echo $db->getData("roles","roleName","roleID",$roleID);?>
    </option>
    <option value="">Select Here</option>
           <?php
           $roles = $db->getRows('roles',array('order_by roleID ASC'));
           if(!empty($roles)){ $count = 0; foreach($roles as $role){ $count++;
            $roleName=$role['roleName'];
            $roleID=$role['roleID'];
           ?>
           <option value="<?php echo $roleID;?>"><?php echo $roleName;?></option>
           <?php }}?>
</select>

</div>
    </div>
    <div class="col-lg-4">
<div class="4">
<div class="role">
<div class="form-group">
<label for="email">School Name</label>
<select name="schoolID" class="form-control">
    <option value="<?php echo $db->getData("schools","schoolID","schoolID",$userData['sectionID']);?>" selected="">
    <?php echo $db->getData("schools","schoolName","schoolID",$userData['sectionID']);?>
    </option>
           <option value="">Select Here</option>   
            <?php
           $schools = $db->getRows('schools',array('order_by'=>'schoolID DESC'));
           if(!empty($schools)){ $count = 0; foreach($schools as $level){ $count++;
            $schoolName=$level['schoolName'];
            $schoolID=$level['schoolID'];
           ?>
           <option value="<?php echo $schoolID;?>"><?php echo $schoolName;?></option>
           <?php }}?>
</select>

</div>
        </div></div>
    </div>
</div>
            
            
<div class="row">
<div class="col-lg-6"></div>
<div class="col-lg-3">
        <input type="hidden" name="action_type" value="edit"/>
        <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
        <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">
</div>
<div class="col-lg-3">
    <input type="reset" name="doSubmit" value="Cancel" class="btn btn-primary form-control">
</div>
</div>
</div>
</div>
<?php } ?>
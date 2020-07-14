<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script> 
<link href="css/validation.css" rel="stylesheet">
<script type="text/javascript">
$(document).ready(function(){
    $("#roleID").change(function(){
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
<?php 
$db=new DBHelper();
?>
<div class="container">
<h2>Add New User</h2>
<hr>
<form name="" method="post" action="action_user.php">
    <div class="row">
        <div class="col-lg-12">
<div class="row">
<div class="col-lg-4">
<div class="form-group">
<label for="courseCode">First Name:</label>
<input type="text" id="fname" name="fname" placeholder="First Name" class="form-control" required="required" />
</div>
</div>
<div class="col-lg-4">
<div class="form-group">
<label for="email">Middle Name</label>
<input type="text" id="mname" name="mname" placeholder="Middle Name" class="form-control" required="required" />
</div>
</div>
    <div class="col-lg-4">
<div class="form-group">
<label for="email">Last Name</label>
<input type="text" id="lname" name="lname" placeholder="Last Name" class="form-control" required="required" />
</div></div></div>
<div class="row">
<div class="col-lg-3">
<div class="form-group">
<label for="email">Gender</label>
<select name="gender" class="form-control" required="">
    <option value="">Select Here</option>
    <option value="Male">Male</option>
    <option value="">Female</option>
</select>
</div>
</div>
<div class="col-lg-3">
<div class="form-group">
<label for="email">Email</label>
<input type="text" id="email" name="email" placeholder="Email" class="form-control" required="required email" />
</div>
</div>
<div class="col-lg-3">
<div class="form-group">
<label for="email">Physical Address</label>
<input type="text" id="address" name="address" placeholder="Address" class="form-control" required="required" />
</div></div>
    <div class="col-lg-3">
        
<div class="form-group">
<label for="email">Phone Number</label>
<input type="text" id="phone" name="phoneNumber" placeholder="Phone Number" class="form-control" required="required" />
</div>
    </div>
    
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
<label for="email">User Roles</label>
<select name="roleID" id="roleID" class="form-control" required="">
    <option value="">Select Here</option>
   <?php
           $roles = $db->getRows('roles',array('order_by roleID ASC'));
           if(!empty($roles)){ $count = 0; foreach($roles as $role){ $count++;
            $roleName=$role['roleName'];
            $roleID=$role['roleID'];
            if($roleID==1 ||$roleID==3 || $roleID==4) {
                ?>
                <option value="<?php echo $roleID; ?>"><?php echo $roleName; ?></option>
                <?php
            }
           }
           }?>
</select>

</div>
    </div>
<div class="col-lg-4">
    <div class="row" id="roleID">
<div class="form-group">
<label for="email">School Name</label>
<select name="schoolID" class="form-control">
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

</div></div>
    </div>
</div>
            
            
<div class="row">
<div class="col-lg-6"></div>
<div class="col-lg-3">
        <input type="hidden" name="action_type" value="add"/>
        <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">
</div>
<div class="col-lg-3">
    <input type="reset" name="doSubmit" value="Cancel" class="btn btn-primary form-control">
</div>
</div>
</div>
</div>
</form>
</div>
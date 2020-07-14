<h1>Edit Department Data</h1>
<?php
$db = new DBHelper();
$userData = $db->getRows('departments',array('where'=>array('departmentID'=>$_GET['id']),'return_type'=>'single'));
if(!empty($userData)){
?>
<div class="row">
<div class="col-lg-6">
<form name="" method="post" action="action_department.php">
<div class="row">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">Department Name</label>
<input type="text" id="name" name="name" value="<?php echo $userData['departmentName'];?>" class="form-control" />
</div>

<div class="form-group">
<label for="email">Department Code</label>
<input type="text" id="code" name="code" value="<?php echo $userData['departmentCode'];?>"  class="form-control" />
</div>

<div class="form-group">
<label for="email">School Name</label>
<select name="schoolID" class="form-control">
           <option value="<?php echo $db->getData('schools','schoolID','schoolID',$userData['schoolID']);?>"><?php echo $db->getData('schools','schoolName','schoolID',$userData['schoolID']);?></option>   
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

<div class="form-group">
<label for="email">Department Status</label>
<?php if($userData['status']==1)
{?>
<input type="radio" name="status" value="1" checked>Active <input type="radio" name="status" value="0">Not Active
<?php }else {?>
<input type="radio" name="status" value="1">Active <input type="radio" name="status" value="0" checked>Not Active
<?php }?>
</div>

</div>
<div class="row">
<div class="col-md-3"></div>
<div class="col-md-3">
<input type="hidden" name="action_type" value="edit"/>
<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
<input type="submit" name="doSubmit" value="Update Records" class="btn btn-success" tabindex="8">
</div></div>

</form>
</div>
</div>
</div>
</div>
<?php } ?>
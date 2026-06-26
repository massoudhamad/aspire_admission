<?php
if (!class_exists('DBHelper')) {
    require_once __DIR__ . '/DB.php';
}
$db = new DBHelper();
?>
<h1>Edit Study Level Data</h1>
<?php
$userData = $db->getRows('studylevels',array('where'=>array('studyLevelID'=>$_GET['id']),'return_type'=>'single'));
if(!empty($userData)){
?>
<div class="row">
<div class="col-lg-6">
<form name="" method="post" action="action_study.php">
<div class="row">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">Study Level Name</label>
<input type="text" id="name" name="name" value="<?php echo $userData['studyLevelName'];?>" class="form-control" />
</div>

<div class="form-group">
<label for="email">Study Level Code</label>
<input type="text" id="code" name="code" value="<?php echo $userData['studyLevelCode'];?>"  class="form-control" />
</div>  
    
<div class="form-group">
<label for="email">Allowed Qualification Types:</label>
<select name="qualificationTypeID[]" id="limitedNumbChosen" multiple="true" class="form-control" required="required">
<?php
$qualificationType = $db->getRows('qualificationtype',array('order_by'=>'`rank` ASC'));
if(!empty($qualificationType)){ $count = 0; foreach($qualificationType as $type){ $count++;
 $qualificationName=$type['qualificationName'];
 $qualificationID=$type['qualificationTypeID'];
?>
<option value="<?php echo $qualificationID;?>"><?php echo $qualificationName;?></option>
<?php }}?>
</select>
</div>

<div class="form-group">
<label for="email">Study Level Status</label>
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
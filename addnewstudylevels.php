<?php
$db=new DBHelper();
?>

<div class="container">
<div class="modal-header">
<h2>Add New Study Level</h2>
<hr>
</div>
<div class="row">
<div class="col-lg-5">    
<form name="" method="post" action="action_study.php">

<div class="row">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">Study Level Name</label>
<input type="text" id="name" name="name" placeholder="Programme Level Name" class="form-control" />
</div>

<div class="form-group">
<label for="email">Study Level Code</label>
<input type="text" id="code" name="code" placeholder="Programme Level Code" class="form-control" />
</div>
<div class="form-group">
<label for="email">Qualification Types:</label>
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
</div>
<div class="row">
    <div class="col-lg-3"></div>
    <div class="col-lg-3">
    <input type="hidden" name="action_type" value="add"/>
    <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">
   
</div>
    <div class="col-lg-3"> <input type="submit" name="doCancel" value="Cancel" class="btn btn-primary"></div>
</div>
</div>
    
</div>
    </form>

</div>
    </div>
</div>
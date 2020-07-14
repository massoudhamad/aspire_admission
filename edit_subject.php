<h1>Edit User Data</h1>
<?php
$db = new DBHelper();
$userData = $db->getRows('subjects',array('where'=>array('subjectID'=>$_GET['id']),'return_type'=>'single'));
if(!empty($userData)){
?>
<form name="" method="post" action="action_subjects.php">
<div class="row">
<div class="col-md-6">
<div class="modal-body">

<div class="form-group">
<label for="email">Subject Name</label>
<input type="text" id="name" name="name" value="<?php echo $userData['subjectName'];?>" class="form-control" />
</div>

<div class="form-group">
<label for="email">Subject Code</label>
<input type="text" id="code" name="code" value="<?php echo $userData['subjectCode'];?>" class="form-control" />
</div>
<?php
if($userData['stream']==1)
  {
      $stream="Ordinary Level";
  }
 elseif($userData['stream']==2)
 {
     $stream="Advanced Level";
 }
 else {
    $stream="Both Levels";
 }
?>
    


<div class="form-group">
<label for="email">Stream</label>
<select name="stream"  class="form-control">
    <option value='<?php echo $db->getData('subjects','stream','subjectID',$userData['subjectID']);?>'>
         <?php echo $stream;?></option> 
         <option value="">Select Subject</option>
    <option value="1">Ordinary Level</option>
    <option value="2">Advanced Level</option>
    <option value="3">Both Level</option>
</select>

</div>
    
<div class="form-group">
<label for="email">Subject Status</label>
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
<?php }?>

<h1>Edit Programme Data</h1>
<?php
$db = new DBHelper();
$userData = $db->getRows('programs',array('where'=>array('programID'=>$_GET['id']),'return_type'=>'single'));
if(!empty($userData)){
?>
<form name="" method="post" action="action_programme.php">
<div class="row">
<div class="col-md-6">
<div class="modal-body">

<div class="form-group">
<label for="email">Programme Name</label>
<input type="text" id="name" name="name" value="<?php echo $userData['programName'];?>" class="form-control" />
</div>

<div class="form-group">
<label for="email">Programme Code</label>
<input type="text" id="code" name="code" value="<?php echo $userData['organizationCode'];?>" class="form-control" />
</div>

<div class="form-group">
<label for="email">Programme Duration</label>
<input type="number" id="duration" name="duration" value="<?php echo $userData['programDuration'];?>" class="form-control" />
</div>



<div class="form-group">
<label for="email">Programme Level</label>
<select name="studyLevelID"  class="form-control">
     <option value='<?php echo $db->getData('studylevels','studyLevelID','studyLevelID',$userData['studyLevelID']);?>'>
         <?php echo $db->getData('studylevels','studyLevelName','studyLevelID',$userData['studyLevelID']);?></option>       
    <?php
           $programme_level = $db->getRows('studylevels',array('order_by'=>'studyLevelName DESC'));
           if(!empty($programme_level)){ $count = 0; foreach($programme_level as $level){ $count++;
            $programme_level=$level['studyLevelName'];
            $programme_level_id=$level['studyLevelID'];
           ?>
           <option value="<?php echo $programme_level_id;?>"><?php echo $programme_level;?></option>
           <?php }}?>
</select>

</div>

    <div class="form-group">
        <label for="email">Organization Name</label>
        <select name="organizationID"  class="form-control">
            <option value='<?php echo $db->getData('sector','sectorID','sectorID',$userData['organizationID']);?>'>
                <?php echo $db->getData('sector','sectorName','sectorID',$userData['organizationID']);?></option>
            <?php
            $sector = $db->getRows('sector',array('order_by'=>'sectorName DESC'));
            if(!empty($sector)){ $count = 0; foreach($sector as $sct){ $count++;
                $sectorName=$sct['sectorName'];
                $sectorID=$sct['sectorID'];
                ?>
                <option value="<?php echo $sectorID;?>"><?php echo $sectorName;?></option>
            <?php }}?>
        </select>
    </div>

    <div class="form-group">
        <label for="email">Organization Code (Eg. MUM01,MUM02 or 72374234hjhh3247324)</label>
        <input type="text" id="org_code" name="org_code" value="<?php echo $userData['programCode'];?>" class="form-control" />
    </div>

<div class="form-group">
<label for="email">Department Name</label>
<select name="departmentID"  class="form-control">
            <option value='<?php echo $db->getData('departments','departmentID','departmentID',$userData['departmentID']);?>'>
         <?php echo $db->getData('departments','departmentName','departmentID',$userData['departmentID']);?></option>    
           <?php
           $department = $db->getRows('departments',array('order_by'=>'departmentName DESC'));
           if(!empty($department)){ $count = 0; foreach($department as $dept){ $count++;
            $department_name=$dept['departmentName'];
            $department_id=$dept['departmentID'];
           ?>
           <option value="<?php echo $department_id;?>"><?php echo $department_name;?></option>
           <?php }}?>
</select>

</div>
    
    <div class="form-group">
<label for="email">Campus Name</label>
<select name="campusID"  class="form-control">
     <option value='<?php echo $db->getData('campus','campusID','campusID',$userData['campusID']);?>'>
         <?php echo $db->getData('campus','campusName','campusID',$userData['campusID']);?></option>    
           <?php
           $campus = $db->getRows('campus',array('order_by'=>'campusName ASC'));
           if(!empty($department)){ $count = 0; foreach($campus as $dept){ $count++;
            $campusName=$dept['campusName'];
            $campusID=$dept['campusID'];
           ?>
           <option value="<?php echo $campusID;?>"><?php echo $campusName;?></option>
           <?php }}?>
</select>

</div>

<div class="form-group">
<label for="email">Programme Status</label>
<?php if($userData['programStatus']==1)
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
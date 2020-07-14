<div class="container">
<div class="row"> 
    <h2>Application Fees</h2>
<div class="col-md-12">
<div class="pull-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Fees</button>
            </div>   
 </div>
</div>
<div class="row">
        <div class="col-md-12">
            <hr>
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Subject data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Subject data has been edited Successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=='unsucc')
  {
    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Subject Code/Subject Name is already Exists</strong>.
</div>";
  }
 else {
      echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Something Wrong happen, Contact System Administrator for more Information</strong>.
</div>";
  }
}
?> 


        </div>
    </div>
<div class="row">
 <div class="col-md-12">   
<?php
          
            $db = new DBHelper();
            $users = $db->getRows('applicationfees',array('order_by'=>'studyLevelID ASC'));
?>
<table  id="example" class="display" cellspacing="0" width="100%">
  <thead>
  <tr>
      <th>No.</th>
    <th>Study Level</th>
    <th>Academic Year</th>
    <th>Fees</th>
    <th>Drop</th>
     </tr>
  </thead>
  <tbody>
<?php 
 if(!empty($users)){ $count = 0; foreach($users as $user){ $count++;
 $applicationFeesID=$user['applicationFeesID'];
 $studyLevelID=$user['studyLevelID'];
 $academicYearID=$user['academicYearID'];
 $fees=$user['fees'];
 ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $db->getData("studylevels","studyLevelName","studyLevelID",$studyLevelID); ?></td>
                <td><?php echo $db->getData("academicyears","academicYear","academicYearID",$academicYearID) ?></td>
                <td><?php echo number_format($fees,2); ?></td>
               
                
              <td>
                  <a href="action_applicationfees.php?action_type=drop&id=<?php echo $user['applicationFeesID']; ?>" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this Application Fees?');"></a>
                   
                </td>
            </tr>
            <?php } }else{ ?>
           
            <?php } ?>
</tbody>
 </table>
 </div></div>  
</div>


<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<form name="" method="post" action="action_applicationfees.php">
<h4 class="modal-title" id="myModalLabel">Add New Record</h4>
</div>
<div class="row">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">Study Level</label>
<select name="studyLevelID"  class="form-control" required="">
    <option value="">Select Here</option>
           <?php
           $programme_level = $db->getRows('studylevels',array('order_by'=>'studyLevelName ASC'));
           if(!empty($programme_level)){ $count = 0; foreach($programme_level as $level){ $count++;
            $programme_level=$level['studyLevelName'];
            $programme_level_id=$level['studyLevelID'];
           ?>
           <option value="<?php echo $programme_level_id;?>"><?php echo $programme_level;?></option>
           <?php }}?>
</select>
</div>

<div class="form-group">
<label for="email">Academic Year</label>
<select name="admissionYearID" class="form-control" required="">
                              <?php
                               $adYear = $db->getRows('academicyears',array('where'=>array('academicYearStatus'=>1),'order_by'=>'academicYear ASC'));
                               if(!empty($adYear)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($adYear as $year){ $count++;
                                $academic_year=$year['academicYear'];
                                $academic_year_id=$year['academicYearID'];
                               ?>
                               <option value="<?php echo $academic_year_id;?>"><?php echo $academic_year;?></option>
                               <?php }}
           ?>
                           </select>
</div>
    
<div class="form-group">
<label for="email">Amount</label>
<input type="number" size="0.01" id="amount" name="amount" placeholder="Fees Amount" class="form-control" required="" />
</div>



</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<input type="hidden" name="action_type" value="add"/>
<input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
<!--<button type="button" class="btn btn-primary" onclick="addRecord()">Add Record</button>-->
</form>
</div>
</div>
</div>
</div>
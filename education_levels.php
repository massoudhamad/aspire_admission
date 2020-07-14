<div class="container">
<div class="row"> 
    <h2>Education Level</h2>
<div class="col-md-12">
<div class="pull-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Education Levels</button>
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
            $users = $db->getRows('programsfees',array('order_by'=>'programID ASC'));
?>
<table  id="example" class="display" cellspacing="0" width="100%">
  <thead>
  <tr>
      <th>No.</th>
    <th>Programme Name</th>
    <th>Academic Year</th>
    <th>Fees TSH</th>
    <th>Fees $</th>
    <th>Edit</th>
     </tr>
  </thead>
  <tbody>
<?php 
 if(!empty($users)){ $count = 0; foreach($users as $user){ $count++;
 $programFeeID=$user['programFeeID'];
 $programmeID=$user['programID'];
 $academicYearID=$user['academicYearID'];
 $feestz=$user['feestz'];
 $feesUsa=$user['feesUsa'];
 ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $db->getData("programs","programName","programID",$programmeID); ?></td>
                <td><?php echo $db->getData("academicyears","academicYear","academicYearID",$academicYearID) ?></td>
                <td><?php echo number_format($feestz,2); ?></td>
                <td><?php echo number_format($feesUsa,2);?></td>
                
              <td>
                    <a href="index3.php?sp=edit_subject&id=<?php echo $user['subjectID']; ?>" class="glyphicon glyphicon-edit"></a>
                   
                </td>
            </tr>
            <?php } }else{ ?>
            <tr><td colspan="4">No Subject(s) found......</td>
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
<form name="" method="post" action="action_subjects.php">
<h4 class="modal-title" id="myModalLabel">Add New Record</h4>
</div>
<div class="row">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">Subject Name</label>
<input type="text" id="name" name="name" placeholder="Subject Name" class="form-control" required="" />
</div>

<div class="form-group">
<label for="email">Subject Code</label>
<input type="text" id="code" name="code" placeholder="Subject Code" class="form-control" required="" />
</div>
    
<div class="form-group">
<label for="email">Subject Stream</label>
<select name="stream" class="form-control" required="">
    <option value="">Select Subject</option>
    <option value="1">Ordinary Level</option>
    <option value="2">Advanced Level</option>
    <option value="3">Both Level</option>
</select>
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
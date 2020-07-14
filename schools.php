<div class="container">
<h3>Schools Management</h3>
<hr>
<div class="row"> 
<div class="col-md-12">
<div class="pull-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New School</button>
            </div>   
 </div>
</div>
<div class="row">
        <div class="col-md-12">
            <br>
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>School data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>School data has been edited Successfully</strong>.
</div>";
  }
  else
  {
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
            $users = $db->getRows('schools',array('order_by'=>'status DESC'));
?>
<h3 class="text-info">List of Registered Schools</h3>
<table  id="example" class="display nowrap" cellspacing="0" width="100%">
  <thead>
  <tr>
      <th>No.</th>
    <th>School Name</th>
    <th>School Code</th>
    <th>Campus Name</th>
    <th>Status</th>
    <th>Edit</th>
     </tr>
  </thead>
  <tbody>
<?php 
 if(!empty($users)){ $count = 0; foreach($users as $user){ $count++;

  if($user['status']==1)
  {
    $status="Active";
  }
  else
  {
    $status="Not Active";
  }

 ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $user['schoolName']; ?></td>
                <td><?php echo $user['schoolCode']; ?></td>
                <td><?php echo $db->getData('campus','campusName','campusID',$user['campusID']);?></td>
                <td><?php echo $status;?></td>
              <td>
                    <a href="index3.php?sp=edit_school&id=<?php echo $user['schoolID']; ?>" class="glyphicon glyphicon-edit"></a>
                   
                </td>
            </tr>
            <?php } }else{ ?>
            <tr><td colspan="4">No user(s) found......</td>
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

<h4 class="modal-title" id="myModalLabel">Add New Record</h4>
</div>

<div class="row">
    <form name="" method="post" action="action_school.php">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">School Name</label>
<input type="text" id="name" name="name" placeholder="School Name" class="form-control" />
</div>

<div class="form-group">
<label for="email">School Code</label>
<input type="text" id="code" name="code" placeholder="Code" class="form-control" />
</div>

<div class="form-group">
<label for="email">Campus Name</label>
<select name="campusID" class="form-control">
           <option value="">Select Here</option>   
            <?php
           $campus = $db->getRows('campus',array('order_by'=>'campusID DESC'));
           if(!empty($campus)){ $count = 0; foreach($campus as $level){ $count++;
            $campusName=$level['campusName'];
            $campusID=$level['campusID'];
           ?>
           <option value="<?php echo $campusID;?>"><?php echo $campusName;?></option>
           <?php }}?>
</select>
</div>


</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<input type="hidden" name="action_type" value="add"/>
<input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
<!--<button type="button" class="btn btn-primary" onclick="addRecord()">Add Record</button>-->

</div>
</div></form>
</div>
</div>
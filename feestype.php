<div class="container">
<div class="row"> 
    <h2>Fees Type Available</h2>
<div class="col-md-12">
<div class="pull-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Fees Type</button>
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
            $users = $db->getRows('feestype',array('order_by'=>'feesTypeID ASC'));
?>
<table  id="example" class="display" cellspacing="0" width="100%">
  <thead>
  <tr>
      <th>No.</th>
    <th>Fees Type</th>
    <th>Fees Type Description</th>
    <th>Status</th>
    <th>Delete</th>
     </tr>
  </thead>
  <tbody>
<?php 
 if(!empty($users)){ $count = 0; foreach($users as $user){ $count++;
  if($user['feesTypeStatus']==1)
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
                <td><?php echo $user['feesType']; ?></td>
                <td><?php echo $user['feesTypeDesc']; ?></td>
                <td><?php echo $status;?></td>
                
              <td>
                    <a href="index3.php?sp=delete&action_type=delete&&id=<?php echo $user['feesTypeID']; ?>" class="glyphicon glyphicon-trash"></a>
                   
                </td>
            </tr>
            <?php } }else{ ?>
            <tr><td colspan="4">No Fees(s) found......</td>
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
<form name="" method="post" action="action_feestype.php">
<h4 class="modal-title" id="myModalLabel">Add New Record</h4>
</div>
<div class="row">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">Fees Type</label>
<input type="text" id="name" name="name" placeholder="Fees Type Name" class="form-control" required="" />
</div>

<div class="form-group">
<label for="email">Fees Type Description</label>
<input type="text" id="code" name="code" placeholder="Fees Type Description" class="form-control" required="" />
</div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<input type="hidden" name="action_type" value="add"/>
<input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
</form>
</div>
</div>
</div>
</div>
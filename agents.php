<div class="container">
<h3>Agents Management</h3>
<hr>
<script type="text/javascript">
  $(document).ready(function () {
            $('#Agents').DataTable(
                {
                   "scrollX":true,
                    paging: true,
                    dom: 'Blfrtip',
                    buttons:[
                        {
                            extend:'excel',
                            title: 'List of Agents',
                            footer:false,
                            exportOptions:{
                                columns:[0,1,2,3]
                            }
                        },
                        ,
                        {
                            extend: 'print',
                            title: 'List of Agents',
                            footer: false,
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'List of Agents',
                            footer: true,
                           exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                            //orientation: 'landscape',
                        }

                        ]
                });
          });
</script>
<div class="row"> 
<div class="col-md-12">
<div class="pull-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Agent</button>
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
    <strong>Agents data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Agents data has been edited Successfully</strong>.
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
            $users = $db->getRows('agents',array('order_by'=>'status ASC'));
?>
<h3 class="text-info">List of Registered Agents</h3>
<table  id="Agents" class="display nowrap" cellspacing="0" width="100%">
  <thead>
  <tr>
      <th>No.</th>
    <th>Agent Name</th>
    <th>Region</th>
    <th>Address</th>
    <th>Phone Number</th>
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
                <td><?php echo $user['agentName']; ?></td>
                <td><?php echo $db->getData('region','regionName','regionID',$user['regionID']);?></td>
                <td><?php echo $user['agentAddress']; ?></td>
                <td><?php echo $user['phoneNumber']; ?></td>
                <td><?php echo $status;?></td>
              <td>

                  <button class="btn btn-success fa fa-pencil" data-toggle="modal" data-target="#<?php echo $user['agentID'];?>"></button
                </td>
            </tr>

         <div class="modal fade" id="<?php echo $user['agentID'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
             <div class="modal-dialog" role="document">
                 <div class="modal-content">
                     <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <form name="" method="post" action="action_agent.php">
                             <h4 class="modal-title" id="myModalLabel">Edit Record for <?php echo $user['agentName'];?></h4>
                     </div>
                     <div class="row">
                         <div class="col-md-12">
                             <div class="modal-body">

                                 <div class="form-group">
                                     <label for="email">Agent Name</label>
                                     <input type="text" id="name" name="name" value="<?php echo $user['agentName'];?>" class="form-control" />
                                 </div>

                                 <div class="form-group">
                                     <label for="email">Agent Physical Address</label>
                                     <input type="text" id="code" name="address" value="<?php echo $user['agentAddress'];?>" class="form-control" />
                                 </div>

                                 <div class="form-group">
                                     <label for="email">Agent Phone Number</label>
                                     <input type="text" id="code" name="phone" value="<?php echo $user['phoneNumber'];?>" class="form-control" />
                                 </div>

                                 <div class="form-group">
                                     <label for="email">Region Name</label>
                                     <select name="regionID" class="form-control">
                                         <?php
                                         if(!empty($user['regionID']))
                                         {
                                            ?>
                                             <option value="<?php echo $user['regionID'];?>"><?php echo $db->getData('region','regionName','regionID',$user['regionID']);?></option>
                                             <?php
                                         }else {
                                             ?>
                                             <option value="">Select Here</option>
                                             <?php
                                         }
                                         $region = $db->getRows('region',array('order_by'=>'regionName ASC'));
                                         if(!empty($region)){ foreach($region as $level){
                                             $schoolName=$level['regionName'];
                                             $schoolID=$level['regionID'];
                                             ?>
                                             <option value="<?php echo $schoolID;?>"><?php echo $schoolName;?></option>
                                         <?php }}?>
                                     </select>
                                 </div>

                                 <div class="form-group">
                                     <label for="email">Status</label>
                                     <select name="status" class="form-control">
                                         <?php
                                         if($user['status']==1)
                                         {
                                             ?>
                                             <option value="1" selected>Active</option>
                                             <option value="0">Not Active</option>
                                             <?php
                                         }else {
                                             ?>
                                             <option value="0" selected>Not Active</option>
                                             <option value="1">Active</option>
                                             <?php
                                         }?>
                                     </select>
                                 </div>


                             </div>
                             <div class="modal-footer">
                                 <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                 <input type="hidden" name="action_type" value="edit"/>
                                 <input type="hidden" name="id" value="<?php echo $user['agentID'];?>">
                                 <input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
                                 </form>
                             </div>
                         </div>
                     </div>
                 </div>

            <?php } } ?>
</tbody>
 </table>
 </div></div>  
</div>


<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<form name="" method="post" action="action_agent.php">
<h4 class="modal-title" id="myModalLabel">Add New Record</h4>
</div>
<div class="row">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">Agent Name</label>
<input type="text" id="name" name="name" placeholder="Agent Name" class="form-control" />
</div>

<div class="form-group">
<label for="email">Agent Physical Address</label>
<input type="text" id="code" name="address" placeholder="Agent Address" class="form-control" />
</div>

<div class="form-group">
<label for="email">Agent Phone Number</label>
<input type="text" id="code" name="phone" placeholder="Agent Phone" class="form-control" />
</div>

<div class="form-group">
<label for="email">Region Name</label>
<select name="regionID" class="form-control">
           <option value="">Select Here</option>   
            <?php
           $region = $db->getRows('region',array('order_by'=>'regionName ASC'));
           if(!empty($region)){ $count = 0; foreach($region as $level){ $count++;
            $schoolName=$level['regionName'];
            $schoolID=$level['regionID'];
           ?>
           <option value="<?php echo $schoolID;?>"><?php echo $schoolName;?></option>
           <?php }}?>
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
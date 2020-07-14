<script type="text/javascript">
  $(document).ready(function () {
            $('#campus').DataTable(
                {
                   "scrollX":true,
                    paging: true,
                    dom: 'Blfrtip',
                    buttons:[
                        {
                            extend:'excel',
                            footer:false,
                            message:"Campus Information",
                            title:"Campus information",
                            exportOptions:{
                                columns:[0,1,2,3]
                            }
                        },
                        ,
                        {
                            extend: 'print',
                            title: 'Campus information',
                            footer: false,
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Campus information',
                            footer: true,
                            exportOptions: {
                                columns: [0,1,2,3,4,5,6]
                            },
                            orientation: 'landscape',
                            
                        }

                        ]
                });
          });
</script>
<div class="container">
<h3>Campus Management</h3>
<hr>
<div class="row"> 
<div class="col-md-12">
<div class="pull-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Campus</button>
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
    <strong>Campus data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Campus data has been edited Successfully</strong>.
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
            $users = $db->getRows('campus',array('order_by'=>'campusName DESC'));
?>
<h3 class="text-info">List of Registered Campus</h3>
<table  id="campus" class="display nowrap" cellspacing="0" width="100%">
  <thead>
  <tr>
      <th>No.</th>
    <th>Campus Name</th>
    <th>Campus Address</th>
    <th>Account Number</th>
    <th>Account Name</th>
    <th>Bank Name</th>
    <th>Swift Code</th>
    <th>Edit</th>
  </tr>
  </thead>
  <tbody>
<?php 
 if(!empty($users)){ $count = 0; foreach($users as $user){ $count++;
 ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $user['campusName']; ?></td>
                <td><?php echo $user['campusAddress']; ?></td>
                <td><?php echo $user['accountNumber'];?></td>
                 <td><?php echo $user['accountName'];?></td>
                <td><?php echo $user['bankName'];?></td>
                <td><?php echo $user['swiftCode'];?></td>
                
              <td>
                    <a href="index3.php?sp=edit_campus&id=<?php echo $user['campusID']; ?>" class="glyphicon glyphicon-edit"></a>
                   
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
<form name="" method="post" action="action_campus.php">
<h4 class="modal-title" id="myModalLabel">Add New Record</h4>
</div>
<div class="row">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">Campus Name</label>
<input type="text" id="name" name="name" placeholder="Campus Name" class="form-control" />
</div>

<div class="form-group">
<label for="email">Campus Address</label>
<input type="text" id="code" name="code" placeholder="Code" class="form-control" />
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
<div class="container">
  <script type="text/javascript">
  $(document).ready(function () {
            $('#programmes').DataTable(
                {
                   "scrollX":true,
                    paging: true,
                    dom: 'Blfrtip',
                    buttons:[
                        {
                            extend:'excel',
                            footer:false,
                            exportOptions:{
                                columns:[0,1,2,3,4,5,6,7,8]
                            }
                        },
                        ,
                        {
                            extend: 'print',
                            title: 'List of Programmes',
                            footer: false,
                            exportOptions: {
                                columns:[0,1,2,3,4,5,6,7,8]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'List of Programmes',
                            footer: true,
                           exportOptions: {
                                columns:[0,1,2,3,4,5,6,7,8]
                            },
                            orientation: 'landscape',
                        }

                        ]
                });
          });
</script>
<h2>Programme Management</h2>
<div class="row"> 
<div class="col-md-12">
<div class="pull-right">
    <a href="index3.php?sp=addnewprogramme"><span class="btn btn-success">Add New Programme</span></a>
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
    <strong>Programme data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme data has been edited Successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="drop")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme data has been droped Successfully</strong>.
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
            $users = $db->getRows('programs',array('order_by'=>'studyLevelID ASC'));
?>
<table  id="programmes" class="display nowrap" cellspacing="0" width="100%">
  <thead>
  <tr>
      <th>No.</th>
    <th>Name</th>
      <th>Prog.Code</th>
      <th>Org.Name</th>
    <th>Org.Code</th>
    <th>Prog.Major</th>
    <th>Duration</th>
     <th>Study Level</th>
    <th>School/Faculty Name</th>
    <th>Campus</th>
    <th>Status</th>
    <th>Edit</th>
    <th>Delete</th>
     </tr>
  </thead>
  <tbody>
<?php 
 if(!empty($users)){ $count = 0; foreach($users as $user){ $count++;

 ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $user['programName']; ?></td>
                <td><?php echo $user['organizationCode']; ?></td>
                <td><?php echo $db->getData("sector","sectorName","sectorID",$user['organizationID']); ?></td>
                <td><?php echo $user['programCode']; ?></td>
                <td>
                    <?php 
                    $programmeMajor=$db->getRows("programmemajor",array('where'=>array('programmeID'=>$user['programID']),'order_by programmeID'));
                    if(!empty($programmeMajor))
                    {
                        $data=array();
                        foreach($programmeMajor as $major)
                        {
                            $data[]=$major['major'];
                        }
                        echo implode(",", $data);
                    }
                    ?>
                </td>
                <td><?php echo $user['programDuration']; ?></td>
                <td><?php echo $db->getData("studylevels","studyLevelName","studyLevelID",$user['studyLevelID']); ?></td>
                <td><?php echo $db->getData("departments","departmentName","departmentID",$user['departmentID']); ?></td>
                <td><?php echo $db->getData("campus","campusName","campusID",$user['campusID']); ?></td>
                <td>
                  <?php if($user['programStatus']==1)
                  echo "Active";
                  else
                    echo "Not Active";?>
                </td>
              <td>
                    <a href="index3.php?sp=edit_programme&id=<?php echo $user['programID']; ?>" class="glyphicon glyphicon-edit"></a>
                   
                </td>
                 <td>
                     <a href="action_programme.php?action_type=drop&id=<?php echo $user['programID']; ?>" class="glyphicon glyphicon-trash"
                        onclick="return confirm('Are you sure you want to delete this programme ?');"></a>
                     
                   
                </td>
            </tr>
            <?php } }else{ ?>
            <?php } ?>
</tbody>
 </table>
 </div></div>  
</div>



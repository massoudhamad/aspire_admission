<div class="container">
<h1>Study Levels Management</h1>
<div class="row"> 
<div class="col-md-12">
<div class="pull-right">
    <a href="index3.php?sp=addnewstudylevel"><span class="btn btn-primary">Add New Study Levels</span></a>
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
    <strong>Study Levels data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Study Level data has been edited Successfully</strong>.
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
            $users = $db->getRows('studylevels',array('order_by'=>'status DESC'));
?>
<table  id="example" class="display nowrap" cellspacing="0" width="100%">
  <thead>
  <tr>
      <th>No.</th>
    <th>Study Level Name</th>
    <th>Study Level Code</th>
    <th>Allowed Qualification</th>
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
                <td><?php echo $user['studyLevelName']; ?></td>
                <td><?php echo $user['studyLevelCode']; ?></td>
                <td>
                    <?php
                    $data=array();
                    $qualificationID=$db->getRows("education_levels",array('where'=>array('studyLevelID'=>$user['studyLevelID']),'order_by studyLevelID'));
                    foreach($qualificationID as $id)
                    {
                        $data[]=$db->getData("qualificationtype","qualificationName","qualificationTypeID",$id['qualificationTypeID']);
                    }
                    echo implode(",",$data);
                    ?>
                </td>
                <td><?php echo $status; ?></td>
              <td>
                    <a href="index3.php?sp=edit_levels&id=<?php echo $user['studyLevelID']; ?>" class="glyphicon glyphicon-edit"></a>
                   
                </td>
            </tr>
            <?php } }else{ ?>
            <tr><td colspan="4">No user(s) found......</td>
            <?php } ?>
</tbody>
 </table>
 </div></div>  
</div>

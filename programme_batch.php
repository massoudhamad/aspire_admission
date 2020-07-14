<div class="container">
<div class="row"> 
    <h2>Programme Batch</h2>
<div class="col-md-12">
<div class="pull-right">
                <a href="index3.php?sp=addprogrammebatch"><span class="btn btn-success">Define Programme Batch</span></a>
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
    <strong>Programme feess data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="drop")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme fess data has been droped successfully</strong>.
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
          
            
?>
     
<table  id="example" class="display" cellspacing="0" width="100%">
  <thead>
  <tr>
      <th>No.</th>
    <th>Programme Name</th>
    <th>Programme Code</th>
    <th>Batch Number</th>
    <th>Academic Year</th>
    <th>Serial Number</th>
    <th>Drop</th>
     </tr>
  </thead>
  <tbody>
<?php 
$db = new DBHelper();
$batch = $db->getRows('programbatch',array('order_by'=>'programID ASC')); 
if(!empty($batch)){ $count = 0; foreach($batch as $btch){ $count++;
$programmeID=$btch['programID'];
$batchNumber=$btch['batchNumber'];
$academicYearID=$btch['academicYearID'];
$serialNumber=$btch['serialNumber'];
?>
            <tr>
            <td><?php echo $count;?></td>
              <td><?php echo $db->getData("programs","programName","programID",$programmeID);?></td>
              <td><?php echo $db->getData("programs","programCode","programID",$programmeID);?></td>
              <td><?php echo $batchNumber;?></td>
              <td><?php echo $db->getData("academicyears","academicYear","academicYearID",$academicYearID);?></td>
              <td><?php echo $serialNumber;?></td>
              <td>
                    <a href="action_add_programme_batch.php?action_type=drop&id=<?php echo $btch['programID']; ?>&yearID=<?php echo $academicYearID;?>" onclick="return confirm('Are you sure you want to Drop this Programme Fees?');" class="glyphicon glyphicon-trash"></a>
                   
                </td>
            </tr>
            <?php } }?>
</tbody>
 </table>
 </div></div>  
</div>
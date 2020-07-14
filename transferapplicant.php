<?php
include 'DB.php';
$db=new DBHelper();
$applicantID=$_REQUEST['id'];
$programmeMajorID=$_REQUEST['programmeMajorID'];

$applicantsData=$db->getRows('applicants',array('where'=>array('applicantID'=>$applicantID),'order_by'=>'applicantID ASC'));
if(!empty($applicantsData)) {
    foreach ($applicantsData as $apps) {
        $fname=$apps['firstName'];
        $mname=$apps['middleName'];
        $lname=$apps['lastName'];
    }
}
else
{
    $fname="";
    $mname="";
    $lname="";
}
$name = "$fname $mname $lname";
?>
   
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="id">Transfer <?php echo $name; ?><br>  from <?php echo $db->getData("programmemajor","programmeMajor", "programmeMajorID",$programmeMajorID);?></h4>
</div>
<div class="modal-body">
    <form id="form1" method="post" action="action_transfer.php" class="form-vertical">
        <div class="row">
        <div class="col-lg-12">
        <label class="control-label">To:Programme Name</label>
       <select name="transferProgrammeID" id="subjectID" class="form-control chosen-select" required="required">
                <?php
                $programmes=$db->getRows("programmemajor");
                    if(!empty($programmes)){ 
                      foreach($programmes as $prg)
                      { 
                          $programmeName=$prg['programmeMajor'];
                          $programmeID=$prg['programmeMajorID'];
                          echo "<option value='$programmeID'>$programmeName</option>";
                      }
                    }
                ?>
        </select>
        </div>
        </div>
        <div class="row"><br></div>
        <div class="row">
            <div class="col-lg-8"></div>
            <div class="col-lg-4">
        <input type="hidden" name="action_type" value="add"/>
                <input type="hidden" name="programmeMajorID" value="<?php echo $programmeMajorID;?>">
       <input type="hidden" name="applicantID" value="<?php echo $applicantID;?>">
        <input  type="submit" class="btn btn-success" name="btnSave" value="Save Records"/>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
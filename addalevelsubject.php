<?php
include 'DB.php';
$db=new DBHelper();

$applicantResultID=$_GET['id'];
$indexYear=$_GET['indexYear'];
$level=$_GET['level'];
if($level=="olevel")
     $eLevel=1;
else
    $eLevel=2;
?>
   
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="id">Add Other Subject For <?php echo $db->getData("applicantresults","indexNumber", "applicantResultID", $applicantResultID) ;?></h4>
</div>
<div class="modal-body">
    <form id="form1" method="post" action="action_addsubject.php" class="form-vertical">
        <div class="row">
        <div class="col-lg-4">
        <label class="control-label">Subject Name</label>
       <select name="subjectCode" id="subjectID" class="form-control" required="required">
                        <option value="">--Select Subject--</option>
                        <?php
                        
                        $subject = $db->getAlevelSubject($applicantResultID);
                        if(!empty($subject)){
                            foreach($subject as $sbj){
                         $subjectID=$sbj['subjectID'];
                         $subjectName=$sbj['subjectName'];
                        ?>
                        <option value="<?php echo $subjectID;?>"><?php echo $subjectName;?></option>
                        <?php }}else {?>
                        <option value="">No Data Found</option>
                        <?php }?>
        </select>
        </div>
        <div class="col-lg-4">
        <label class="control-label">Grade</label>
        <select name="gradeCode" class="form-control">
       <?php
       if($elevel=="O")
       {
        if($indexYear==2014 or $indexYear==2015)
        $gradeRange=2014;
        else 
        $gradeRange=2013;
       }
       else
         $gradeRange=2013;  
        $grade = $db->getRows('grades',array('where'=>array('gradeRangeYear'=>$gradeRange,'gradeLevel'=>$eLevel),'order_by'=>'gradeID ASC'));
        if(!empty($grade)){ 
          echo"<option value=''>Please Select Here</option>";
          foreach($grade as $gd)
           { 
              $gradeID=$gd['gradeID'];
              $gradeCode=$gd['gradeCode'];
              echo "<option value='$gradeID'>$gradeCode</option>";

           }
      
    }?>
        </select>
        </div>
        </div>
        <div class="row"><br></div>
        <div class="row">
            <div class="col-lg-6"></div>
            <div class="col-lg-4">
        <input type="hidden" name="action_type" value="add"/>
       <input type="hidden" name="applicantResultID" value="<?php echo $applicantResultID;?>">
        <input  type="submit" class="btn btn-success" name="btnSave" value="Save Records"/>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
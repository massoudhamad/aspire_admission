<?php
$db=new DBHelper();
?>
<script src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
            $('#admit').dataTable(
                {
                    paging: false,
                    searching:false
                });
          });
</script>
<form name="" method="post" action="action_add_programme_batch.php">
<div class="container">
<h4>Define Programme Batch</h4>
<hr>
<div class="row">
              <table id="admit" class="display nowrap" cellspacing="0">
                  <thead>
                <tr>
                  <th>No</th>
                  <th>Programme Name</th>
                  <th>Academic Year</th>
                  <th>Batch Number</th>
                  <th>Serial number</th>
                </tr>
                  </thead>
                  <tbody>
       <?php
       $programBatch = $db->getProgrammeBatch();    
       if(!empty($programBatch)){ $count = 0; foreach($programBatch as $btch){ $count++;
       $studyLevelID=$db->getData("programs","studyLevelID","programID",$btch['programID']);
         ?>       
             <tr>
                <td><?php echo $count; ?></td>
                  <input type="hidden" name="programID<?php echo $count;?>" value="<?php echo $btch['programID'];?>">
                <td><?php echo $db->getData("programs","programName","programID",$btch['programID']);?>-
               <?php echo $db->getData("studylevels","studyLevelCode","studyLevelID",$studyLevelID);?>
                </td>
                <td>
                <select name="academicYearID<?php echo $count;?>" class="form-control" required="">
                <?php
                               $adYear = $db->getRows('academicyears',array('where'=>array('academicYearStatus'=>1),'order_by'=>'academicYear ASC'));
                               if(!empty($adYear)){ 
                               
                                foreach($adYear as $year){
                                $academic_year=$year['academicYear'];
                                $academic_year_id=$year['academicYearID'];
                               ?>
                               <option value="<?php echo $academic_year_id;?>"><?php echo $academic_year;?></option>
                               <?php }}?>
                               </select>
                </td>
                <td><input type="text" name="batchnumber<?php echo $count;?>"></td>
                <td><input type="text" name="serialnumber<?php echo $count;?>"></td>
            </tr>
            <?php } }?>   
                  </tbody>
              </table>
 </div>
</div>
    <div class="row">  
        <div class="col-lg-6"></div>
<div class="col-lg-3">
<input type="hidden" name="action_type" value="add"/>
<input type="hidden" name="number" value="<?php echo $count;?>">
<input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">        
</div>
<div class="col-lg-3">
<input type="reset" name="doSubmit" value="Cancel" class="btn btn-primary form-control">        
</div></div>
</form>

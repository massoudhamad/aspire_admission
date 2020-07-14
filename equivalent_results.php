<?php $db=new DBHelper();
/*if(isset($_POST['doProceed']))
    $db->redirect("index2.php?sz=education_background");
if(isset($_POST['doExit']))
    $db->redirect("logout.php?logout=true");*/
?>
<script src="js/jquery-1.4.2.min.js"></script>
<link href="css/validation.css" rel="stylesheet">    
<script type="text/javascript">
var FormStuff = {
  init: function() {
    this.applyConditionalRequired();
    this.bindUIActions();
  },
  
  bindUIActions: function() {
    $("input[type='radio']").on("change", this.applyConditionalRequired);
  },
  applyConditionalRequired: function() {
  	
    $(".require-if-active").each(function() {
      var el = $(this);
      if ($(el.data("require-pair")).is(":checked")) {
        el.prop("required", true);
      } else {
        el.prop("required", false);
      }
    });
    
  }
  
};

FormStuff.init();
</script>

        
        
        <script type="text/javascript">
        /*function displayGpa(value,name){
	if (value=="GPA"){
		document.getElementById("gpa").style.display="inline-block";
		document.getElementById("NonGpa").style.display="none";
		}
	
	else{
		document.getElementById("gpa").style.display="none";
		document.getElementById("NonGpa").style.display="inline-block";
	}
}*/
        </script>
<script type="text/javascript">
$(function(){
    $("input[type='radio']").change(function(){
        
        if($(this).is(":checked"))
        {
            $("#"+$(this).attr("id")+"_box").show();
        }
        else{
          $("#"+$(this).attr("id")+"_box").find("input[type='text']").val("")  ;
$("#"+$(this).attr("id")+"_box").hide(); 
             }
    });
});

</script>
<style>

</style>
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="error")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Sory-There is an error in your data, Please check your data and resubmit again</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme data has been edited Successfully</strong>.
</div>";
  }
}
?> 
<div class="row" style="padding-bottom: 0.5%;">
<form class="form-horizontal" name="register" id="register" action="action_equivalent_results.php" method="post">   
<div class="col-lg-12">    
              <div class="well">
                  <fieldset>
                  <legend>Please fill Equivalent Level Results (Certificates,NTAs, Diploma, Advanced Diploma etc)</legend>
                  <div class="row">
                      <div class="col-lg-12">
                          <label class="form-inline">Do you want to be considered for Equivalent Entry Qualification?</label>
                          <label class="form-inline"> <input type="radio" name="entry_qualification" id="entry_qualification"  value="1" checked> Yes </label>
                            <label class="form-inline"> <input type="radio" name="entry_qualification" id="entry_qualification" value="0"> No </label>   
                      </div>
                  </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="FirstName">Programme Name</label>
                            <input type="text" name="programmeName" placeholder="Enter Programme Name" class="form-control" required="required">
                        </div>
                        <div class="col-lg-6">
                            <label for="FirstName">Institute Name</label>
                            <input type="text" name="instituteName" placeholder="Enter Institute Name" class="form-control" required="required">
                        </div>
                    </div>
                  
                    <div class="row">
                        
                        <div class="col-lg-6">
                            <label for="Physical Address">Registration Number</label>
                            <input type="text" name="registrationNumber" id="registrationNumber" placeholder="Enter Registration Number"  class="form-control" required="required" />
                        </div>
                        
                         <div class="col-lg-6">
                            <label for="Email">Graduation Year</label>
                            <select name="indexYear" id="indexYear" class="form-control" required="required">
                                <option value="">Select Year</option>
                                <?php 
                                $year=date('Y');
                                $year1=date('Y')-40;
                                for($x=$year;$x>=$year1;$x--)
                                {
                                    echo "<option value='$x'>$x</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                    </div>
                  
                  
                  <div class="row">
                        
                        <div class="col-lg-6">
                            <label for="grade">Grade</label>
                               <div class="form-inline">
                                   <input type="radio" id="from" name="gradeType" value="GPA" checked=""> GPA
                                     
                                     <select name="gradePoints" id="gpa" class="form-control">
                                           <option value="">--Select GPA--</option>
                                            <?php
                                            for($x=3.0;$x<=5.0;$x+=0.1)
                                            {
                                                echo "<option value='$x'>$x</option>";
                                            }
                                            ?>
                                            </select> /
                                       <select name="gradeValue2" id="gpa" class="form-control">
                                           <option value="5" selected="">5</option>
                                        <option value="4">4</option>
                                       </select>
                                            <input type="radio" id="from" name="gradeType" value="Non GPA"> Non GPA
                                   <select name="gradeValue" id="NonGpa" class="form-control">
                                       <option value="">--Select Non GPA--</option>
					<?php 
                                        $grade = $db->getRows('grades',array('where'=>array('gradeLevel'=>'3'),'order_by'=>'gradeID ASC'));
                                        if(!empty($grade)){ 
                                          foreach($grade as $gd)
                                           { 
                                              $gradeCode=$gd['gradeCode'];
                                              echo "<option value='$gradeCode'>$gradeCode</option>";
                                           }

                                           }
                                        ?>
                                     </select>
                                
                              </div>
                        </div>
                        
                         <div class="col-lg-6">
                            <label for="Email">Qualification</label>
                            <select name="qualificationTypeID" id="qualificationTypeID" class="form-control" required="required">
                   
                        <option value="">Select Qualification Type</option>
                        <?php
                        $qualificationType = $db->getRows('qualificationtype',array('where'=>array('qualificationTypeRank'=>2),'order_by'=>'rank ASC'));
                        if(!empty($qualificationType)){ $count = 0; foreach($qualificationType as $type){ $count++;
                         $qualificationName=$type['qualificationName'];
                         $qualificationID=$type['qualificationTypeID'];
                        ?>
                        <option value="<?php echo $qualificationID;?>"><?php echo $qualificationName;?></option>
                        <?php }}?>
                    </select>
                        </div>
                        
                    </div>
                  
                  
                  
                  
                        </fieldset>
                  <fieldset>
                      <legend>Courses/Modules/Units/Subjects</legend>
                      <div class="row">
                          <div class="col-lg-1">
                             <label for="Physical Address">No</label>
                             
                          </div>
                          <!--<div class="col-lg-2">
                              <label for="Physical Address">Subject No</label>
                          </div>-->
                          <div class="col-lg-5">
                              <label for="Physical Address">Course Name</label>
                           
                          </div>
                          <div class="col-lg-3">
                              <label for="Physical Address">Grade</label>
                             
                          </div>
                      </div>
                      <?php
                      for($x=1;$x<=8;$x++)
                      {
                      ?>
                      
                      <div class="row">
                          <div class="col-lg-1">
                             <label for="Physical Address"><?php echo $x;?></label>
                             
                          </div>
                         
                          <div class="col-lg-5">
                             
                              <input type="text" name="subjectCode[]" id="courseName" maxlength="100"  class="form-control" required="required" />
                          </div>
                          <div class="col-lg-3">
                              
                              <select name="gradeCode[]" class="form-control">
                            <?php 
                            $grade = $db->getRows('grades',array('where'=>array('gradeLevel'=>3),'order_by'=>'gradeID ASC'));
                            if(!empty($grade)){ 
                              echo"<option value=''>Please Select Here</option>";
                              foreach($grade as $gd)
                               { 
                                  $gradeCode=$gd['gradeCode'];
                                  echo "<option value='$gradeCode'>$gradeCode</option>";

                               }

                               }
                               ?>
                              </select>
                          </div>
                      </div>
                      <?php }?>
                  </fieldset>
                        
                   </div>                
</div>
                        <div class="col-lg-4"></div>
                            <div class="col-lg-4">
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="hidden" name="examinationlevel" value="Advance">
                            <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control" />
                        </div>
                        <div class="col-lg-4">
                            <input type="button" name="doExit" value="Cancel" onclick="window.history.go(-1);" class="btn btn-success form-control" />
                        </div>
                        
                       
</form> 
                    </div>
                        

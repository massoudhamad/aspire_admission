<?php
$db=new DBHelper();
?>
<script src="js/jquery-1.4.2.min.js"></script>

<script type="text/javascript">
 $(document).ready(function()
  {
  $("#programmeMajorID").change(function()
  {
  var programmeMajorID=$(this).val();
  var dataString = 'programmeMajorID='+ programmeMajorID;

  $.ajax
  ({
  type: "POST",
  url: "ajax_subjects.php",
  data: dataString,
  cache: false,
  success: function(html)
  {
  $("#subjects").html(html);
  $("#subjects").trigger('chosen:updated');
  } 
  });

  });

  });
        </script>
<script type="text/javascript">
 $(document).ready(function()
  {
  $("#programmeMajorID").change(function()
  {
  var programmeMajorID=$(this).val();
  var dataString = 'programmeMajorID='+ programmeMajorID;

  $.ajax
  ({
  type: "POST",
  url: "ajax_subjects_grade.php",
  data: dataString,
  cache: false,
  success: function(html)
  {
  $("#subjectsgrade").html(html);
  $("#subjectsgrade").trigger('chosen:updated');
  } 
  });

  });

  });
        </script>
<div class="container">
<h4>Define Programme Requirements</h4>
<hr>
<form name="" method="post" action="action_add_programme_requirements.php">

<div class="row">
<div class="col-lg-12">
<div class="row">
<div class="col-lg-4">
<div class="form-group">
<label for="email">Programme Name</label>
<select name="programmeID"  class="form-control chosen-select" id="programmeMajorID"   required>
<?php
$programmes = $db->getProgrammeRequired();
if(!empty($programmes)){ 
  echo"<option value=''>Please Select Here</option>";
  foreach($programmes as $prg)
   { 
      $programmeName=$prg['programmeMajor'];
      $programmeID=$prg['programmeMajorID'];
      echo "<option value='$programmeID'>$programmeName</option>";
   }
   }
   ?> 
</select>
</div></div>
  <div class="col-lg-4">
<div class="form-group">
<label for="email">Compulsory Subjects</label>
<select name="compulsorySubjects[]" multiple="true"  class="form-control chosen-select" id="subjects">
</select>
</div>
        </div>
    <div class="col-lg-4">
<div class="form-group">
<label for="email">Compulsory Subject Grade</label>
<select name="compulsoryGrade" class="form-control chosen-select" id="subjectsgrade">
<?php    
$grade = $db->getRows('grades',array('where'=>array('gradeLevel'=>2),'order_by'=>'gradeCode ASC'));
if(!empty($grade)){ 
  echo"<option value=''>Please Select Here</option>";
  foreach($grade as $sub)
   { 
      $gradeCode=$sub['gradeCode'];
      $gradeID=$sub['gradeID'];
      echo "<option value='$gradeID'>$gradeCode</option>";
   }
   }
   ?> 
</select>
</div>
</div></div>
 <!--<div class="col-lg-4">
<div class="form-group">
<label for="email">Allowed Subjects</label>
<select name="allowedSubjects[]" multiple="true"  class="form-control chosen-select" id="subjects">
</select>
</div></div>-->
 <div class="row">   
<!--<div class="col-lg-4">        
<div class="form-group">
<label for="email">Excluded Subjects</label>
<select name="excludedSubjects[]" multiple="true" id="subjects"  class="form-control chosen-select">
</select>
</div>
    </div>  
        
        <div class="col-lg-4">
<div class="form-group">
<label for="email">Minimum Pass Grade</label>
<select name="minimumPassGrade" class="form-control chosen-select">
<?php    
//$grade = $db->getRows('grades',array('where'=>array('gradeLevel'=>2),'order_by'=>'gradeCode ASC'));
//if(!empty($grade)){ 
//  echo"<option value=''>Please Select Here</option>";
//  foreach($grade as $sub)
//   { 
//      $gradeCode=$sub['gradeCode'];
//      $gradeID=$sub['gradeID'];
//      echo "<option value='$gradeID'>$gradeCode</option>";
//
//   }
//   }
   ?> 
</select>
</div>
        </div>-->
<div class="col-lg-4">
<div class="form-group">
<label for="email">Number of Pass Grade</label>
<input type="number" id="numberPassGrade" name="numberPassGrade" placeholder="Number of Pass Grade" class="form-control" />
</div>
</div>
    

<div class="col-lg-4">
<div class="form-group">
<label for="email">Points Required</label>
<input type="number" step="0.1" id="pointsRequired" name="pointsRequired" placeholder="Points Required" class="form-control" />
</div>
</div>
        
        <div class="col-lg-4">
<div class="form-group">
<label for="email">Equivalent Entry GPA</label>
<select name="gpa" id="gpa" class="form-control chosen-select">
   <option value="">--Select GPA--</option>
    <?php
    for($x=2.7;$x<=5.0;$x+=0.1)
    {
        echo "<option value='$x'>$x</option>";
    }
    ?>
</select>
</div>
</div>
        
        <!--<div class="col-lg-4">
<div class="form-group">
<label for="email">Equivalent Entry Non GPA</label>
 <select name="nongpa" id="nongpa" class="form-control chosen-select">
   <option value="">--Select Non GPA--</option>
    <?php 
//    $grade = $db->getRows('grades',array('where'=>array('gradeLevel'=>3),'order_by'=>'gradeID ASC'));
//    if(!empty($grade)){ 
//      foreach($grade as $gd)
//       { 
//          $gradeCode=$gd['gradeCode'];
//          $gradeID=$gd['gradeID'];
//          echo "<option value='$gradeID'>$gradeCode</option>";
//
//       }
//
//       }
    ?>
 </select>
</div>
</div>-->
        
</div>
    <div class="row">  
        <div class="col-lg-6"></div>
<div class="col-lg-3">
<input type="hidden" name="action_type" value="add"/>
<input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">        
</div>
<div class="col-lg-3">
<input type="reset" name="doSubmit" value="Cancel" class="btn btn-primary form-control">        
</div></div>

</div>
</div>
    </form>
</div>

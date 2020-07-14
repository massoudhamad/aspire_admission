<?php 
$db = new DBHelper();
?>
<div class="container">
    <h4>View List of Applicants</h4>
    <hr>
<div class="row">
    <form name="" method="post" action="">
<div class="col-lg-4">

                            <label for="MiddleName">Programme Name</label>
                            <select name="programmeID" class="form-control chosen-select" required="">
                              <?php
                               $adYear = $db->getRows('program',array('order_by'=>'programName ASC'));
                               if(!empty($adYear)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($adYear as $year){ $count++;
                                $programName=$year['programName'];
                                $programID=$year['programID'];
                               ?>
                               <option value="<?php echo $programID;?>"><?php echo $programName;?></option>
                               <?php }}
           ?>
                           </select>
</div>
<div class="col-lg-4">

                            <label for="MiddleName">Admission Year</label>
                            <select name="admissionYearID" class="form-control" required="">
                              <?php
                               $adYear = $db->getRows('academicyears',array('order_by'=>'academicYear ASC'));
                               if(!empty($adYear)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($adYear as $year){ $count++;
                                $academic_year=$year['academicYear'];
                                $academic_year_id=$year['academicYearID'];
                               ?>
                               <option value="<?php echo $academic_year_id;?>"><?php echo $academic_year;?></option>
                               <?php }}
           ?>
                           </select>
</div>
 <div class="col-lg-4">
                      <label for=""></label>
  
                      <input type="submit" name="doSearch" value="Search Records" class="btn btn-primary form-control" />
 </div>
    </form>
</div>
    
    <br><br>
    <div class="row">
        <?php 
        if(isset($_POST['doSearch'])=="Search Records")
        {
            $academicYearID=$_POST['admissionYearID'];
            $programmeMajorID=$_POST['programmeMajorID'];
        ?>
        
        <div class="col-lg-12">
            <h4><span id="titleheader">List of Approved Applicants for <?php echo $db->getData("programmemajor","programmeMajor","programmeMajorID",$programmeMajorID);?>
            <?php echo $db->getData("academicyears","academicYear","academicYearID",$academicYearID);?></span></h4> 
        </div>
        <table id="selection_list" class="display nowrap" cellspacing="0">
          <thead>
           <tr>
               <th>SNo</th>
               <th>Name</th>
               <th>Sex</th>
               <th>Form IV</th>
               <th>Ordinary Subjects</th>
               <th>Points</th>
               <th>Form V</th>
               <th>Advanced Subjects</th>
               <th>Points</th>
               <th>Sec. Choice</th>
           </tr>     
         </thead>
         <tbody>
         <script type="text/javascript" src="ajax/index.js"></script>    
         <?php
                /* $applicantsData=$db->getApplicantsApproved($programmeMajorID,$academicYearID);
                 if(!empty($applicantsData))
                 {
                     $i=0;
                     foreach ($applicantsData as $data)
                     {
                         $i++;
                         $applicantID=$data['applicantID'];
                         $fname=$data['firstName'];
                         $mname=$data['middleName'];
                         $lname=$data['lastName'];
                         $gender=$data['gender'];
                         $name="$fname $mname $lname";
                         echo "<tr><td>$i</td><td>$name</td><td>$gender</td><td>";
                         
                         $osubjects=$db->getSelectionSubjects($applicantID,"Ordinary");
                         if(!empty($osubjects))
                         {
                             $odata=array();
                             foreach ($osubjects as $subject) {
                                 
                                 $subjectID=$subject['subjectID'];
                                 $subjectCode=$subject['subjectCode'];
                                 $gradeID=$subject['gradeID'];
                                 $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                                 $points=$subject['points'];
                                 $odata[]=$subjectCode."-".$grade;
                             }
                             echo implode(",",$odata);
                         }
                         echo "</td><td>";
                         $applicantPoints=$db->getSelectionPoints($applicantID,'Ordinary');
                         if(!empty($applicantPoints))
                         {
                             $totalPoints=0;
                             foreach ($applicantPoints as $appoints) {
                                 $points=$appoints['points'];
                                 $totalPoints+=$points;
                             }
                             echo $totalPoints;
                         }
                         
                         echo "</td><td>";
                         
                         $osubjects=$db->getSelectionSubjects($applicantID,"Advance");
                         if(!empty($osubjects))
                         {
                             $odata=array();
                             foreach ($osubjects as $subject) {
                                 
                                 $subjectID=$subject['subjectID'];
                                 $subjectCode=$subject['subjectCode'];
                                 $gradeID=$subject['gradeID'];
                                 $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                                 $points=$subject['points'];
                                 $odata[]=$subjectCode."-".$grade;
                             }
                             echo implode(",",$odata);
                         }
                         echo "</td><td>";
                         $applicantPoints=$db->getSelectionPoints($applicantID,'Advance');
                         if(!empty($applicantPoints))
                         {
                             $totalPoints=0;
                             foreach ($applicantPoints as $appoints) {
                                 $points=$appoints['points'];
                                 $totalPoints+=$points;
                             }
                             echo $totalPoints;
                         }
                         
                         echo "</td><td>";
                         
                      $programmeChoice=$db->getRows("applicantapplication", array('where'=>array('applicantID'=>$applicantID,'choice'=>2),'order_by applicantID ASC'));
                      if(!empty($programmeChoice))
                      {
                          foreach ($programmeChoice as $pChoice)
                          {
                              $applicantApplicationIDFirst=$pChoice['applicantApplicationID'];
                              $firstChoice=$pChoice['programmeMajorID'];
                              $programmeChoice=$db->getData("programmemajor","programmeMajor","programmeMajorID",$firstChoice);
                          }
                      }
                         echo $programmeChoice."</td>";
                         echo "</tr>";
                     }
                 }
                 else
                 {
                     
                 }*/
                 
         ?> 
         </tbody>
        </table>
        <?php
        }
        ?>
        
    </div>
    
</div>
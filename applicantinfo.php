<?php $db=new DBHelper();
$applicantID=$_REQUEST['applicantID'];
?>
<div class="row">
    <div class="col-lg-12">
    <div class="well">
        <?php
            $applicantsData=$db->getRows('applicants',array('where'=>array('applicantID'=>$applicantID),'order_by'=>'applicantID ASC'));
           if(!empty($applicantsData)){ 
           foreach($applicantsData as $apps)
           {
               
               $gender=$apps['gender'];
               $fname=$apps['firstName'];
               $mname=$apps['middleName'];
               $lname=$apps['lastName'];
               $refNumber=$apps['refNumber'];
               if($gender=="Male")
                   $sex="Mr";
               else 
                   $sex="Ms";
               $name="$sex $fname  $mname $lname";
           }
           }
           ?>
<!--        <h3>Application</h3>-->
        <p class="text-justify lead">Applicants Information for <b><?php echo $name;?></b>, Ref.Number <b><?php echo $refNumber;?></b>
        </p>
    </div>
    </div>
</div>
<div class="row">
    <!-- Program Choice-->
    <div class="col-lg-12">


        <div class="well">
            <fieldset>
                <legend>Study Programme</legend>
                <?php
                $programmeChoice=$db->getRows("applicantapplication", array('where'=>array('applicantID'=>$applicantID,'choice'=>1),'order_by applicantID ASC'));
                if(!empty($programmeChoice))
                {
                    foreach ($programmeChoice as $pChoice)
                    {
                        $applicantApplicationIDFirst=$pChoice['applicantApplicationID'];
                        $firstChoice=$pChoice['programmeMajorID'];
                    }
                }

                $programmeChoice2=$db->getRows("applicantapplication", array('where'=>array('applicantID'=>$applicantID,'choice'=>2),'order_by applicantID ASC'));
                if(!empty($programmeChoice2))
                {
                    foreach ($programmeChoice2 as $pChoice2)
                    {
                        $applicantApplicationIDSecond=$pChoice2['applicantApplicationID'];
                        $secondChoice=$pChoice2['programmeMajorID'];
                    }
                }
                ?>
                <div class="row">
                    <div class="col-lg-6">
                        <label for="FirstName">First Choice Programme</label>
                        <input type="text" name="" value="<?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $firstChoice);?>" class="form-control" disabled="">

                    </div>


                    <div class="col-lg-6">
                        <label for="Physical Address">Second Choice Programme</label>
                        <input type="text" name="" value="<?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $secondChoice);?>" class="form-control" disabled="">

                    </div>

                </div>




            </fieldset>

        </div>

    </div>
    <!-- Education Background-->
 <div class="col-lg-12">
        <fieldset>
    <div class="well">
        <legend>Education Background</legend>
   <!-- --><?php
/*     $results=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$applicantID),'stream'=>'O','order_by applicantID ASC'));
     if(!empty($results))
     {
         */?>
     <div class="row">
            
            <?php
            $results=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$applicantID,'examinationLevel'=>'Ordinary'),'order_by applicantID ASC'));
            if(!empty($results))
            {
            ?>
            
            <div class="col-lg-12">
                <fieldset>
                <legend>List of Registered Subjects for Ordinary Level (Form IV)</legend>
                <?php 
                   foreach($results as $matokeo)
                   {
                ?>
                <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th>School Name</th>
                        <th>Index Number</th>
                        <th>Year</th>
                        <th>Examination Autority</th>
                        
                     </tr>
                      </thead>
                      <tbody>
                        <?php
                        $applicantResultID=$matokeo['applicantResultID'];
                        $schoolName=$matokeo['schoolName'];
                        $yearTaken=$matokeo['yearTaken'];
                        $indexNumber=$matokeo['indexNumber'];
                        $exam_authority=$matokeo['examinationAuthority'];
                        echo "<tr><td>$schoolName</td><td>$indexNumber</td><td>$yearTaken</td><td>$exam_authority</td>";
                        ?>
                        <?php
                        echo "</tr>"
                        ?>
                      </tbody>
                </table>
                
                <?php 
                $resultSubjects=$db->getRows("applicantsubjects", array('where'=>array('applicantResultID'=>$applicantResultID),'order_by applicantResultID ASC'));
                if(!empty($resultSubjects))
                {
                    ?>
                <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th>No</th>
                        <th>Subject Name</th>
                        <th>Grade</th>
                        <th>Points</th>
                       
                      </tr>
                      </thead>
                      <tbody>
                          <?php 
                          $count=0;$totalPoints=0;
                            foreach ($resultSubjects as $subject) {
                                $count++;
                                $applicantSubjectID=$subject['applicantSubjectID'];
                                 $subjectID=$subject['subjectID'];
                                $gradeID=$subject['gradeID'];
                                $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                                $points=$db->getData("grades","gradePoint","gradeID",$gradeID);
                                $totalPoints=$totalPoints+$points;
                                echo "<tr><td>$count</td><td>".$db->getData("subjects","subjectName","subjectID",$subjectID)."</td><td>$grade</td><td>$points</td>";
                                ?>
                                    
                                    <?php
                                    echo "</tr>";
                            }
                          ?>
                                    <tr>
                                        <td colspan="3">Total Points</td><td><?php echo $totalPoints;?></td>
                                        
                                    </tr>
                      </tbody>
                </table>
                    <?php
                }
                
                }
                ?>
                </fieldset> 
            </div>
         
            <?php
            }
            ?>
     
         
         
         <!--Advanced Results-->
         <div class="col-lg-12">
            <fieldset>
                <legend>Advanced Level Results</legend>
                
                 <?php
            $results=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$applicantID,'examinationLevel'=>'Advance'),'order_by applicantID ASC'));
            if(!empty($results))
            {
            ?>
            
            <div class="col-lg-12">
                <fieldset>
                <legend>List of Registered Subjects for Advanced Level (Form FVI)</legend>
                <?php 
                   foreach($results as $matokeo)
                   {
                ?>
                <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th>School Name</th>
                        <th>Index Number</th>
                        <th>Year</th>
                        <th>Examination Autority</th>
                        
                     </tr>
                      </thead>
                      <tbody>
                        <?php
                        $applicantResultID=$matokeo['applicantResultID'];
                        $schoolName=$matokeo['schoolName'];
                        $yearTaken=$matokeo['yearTaken'];
                        $indexNumber=$matokeo['indexNumber'];
                        $exam_authority=$matokeo['examinationAuthority'];
                        echo "<tr><td>$schoolName</td><td>$indexNumber</td><td>$yearTaken</td><td>$exam_authority</td>";
                        ?>
                        <?php
                        echo "</tr>"
                        ?>
                      </tbody>
                </table>
                
                <?php 
                $resultSubjects=$db->getRows("applicantsubjects", array('where'=>array('applicantResultID'=>$applicantResultID),'order_by applicantResultID ASC'));
                if(!empty($resultSubjects))
                {
                    ?>
                <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th>No</th>
                        <th>Subject Name</th>
                        <th>Grade</th>
                        <th>Points</th>
                       
                      </tr>
                      </thead>
                       <tbody>
                          <?php 
                          $count=0;$totalPoints=0;
                            foreach ($resultSubjects as $subject) {
                                $count++;
                                $applicantSubjectID=$subject['applicantSubjectID'];
                                 $subjectID=$subject['subjectID'];
                                $gradeID=$subject['gradeID'];
                                $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                                $points=$db->getData("grades","gradePoint","gradeID",$gradeID);
                                $totalPoints=$totalPoints+$points;
                                echo "<tr><td>$count</td><td>".$db->getData("subjects","subjectName","subjectID",$subjectID)."</td><td>$grade</td><td>$points</td>";
                                ?>
                                    
                                    <?php
                                    echo "</tr>";
                            }
                          ?>
                                    <tr>
                                        <td colspan="3">Total Points</td><td><?php echo $totalPoints;?></td>
                                        
                                    </tr>
                      </tbody>
                </table>
                    <?php
                }
                
                }
                ?>
                </fieldset> 
            </div>
         
            <?php
            }
            ?>
            </fieldset>
        </div>
         <!--End of Advanced Level Results-->
         
         <!--Equivalent Results-->
         <div class="col-lg-12">
            <fieldset>
                <legend>Equivalent Results</legend>
                
                 <?php
            $results=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$applicantID,'examinationLevel'=>'Equivalent'),'order_by applicantID ASC'));
            if(!empty($results))
            {
            ?>
            
            <div class="col-lg-12">
                <fieldset>
                <legend>List of Equivalent Results(Certificate,NTAs,Diploma,Adv.Diploma,Degree)</legend>
                <?php 
                   foreach($results as $matokeo)
                   {
                ?>
                <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th>Institute Name</th>
                        <th>Reg. Number</th>
                          <th>AVN Number</th>
                        <th>Year Taken</th>
                        <th>Programme Name</th>
                        <th>Qualification</th>
                        <th>Grade Type</th>
                        <th>Points</th>
                       
                     </tr>
                      </thead>
                      <tbody>
                        <?php
                        $applicantResultID=$matokeo['applicantResultID'];
                        $schoolName=$matokeo['schoolName'];
                        $yearTaken=$matokeo['yearTaken'];
                        $indexNumber=$matokeo['indexNumber'];
                        $exam_authority=$matokeo['examinationAuthority'];
                        $exam_level=$matokeo['examinationLevel'];
                        $award=$matokeo['award'];
                        $gradeType=$matokeo['gradeType'];
                        $gradePoints=$matokeo['gradePoints'];
                        echo "<tr><td>$schoolName</td><td>$indexNumber</td><td>".$matokeo['avn_number']."</td><td>$yearTaken</td><td>$award</td><td>".$db->getData("qualificationtype","qualificationName","qualificationTypeID",$exam_authority)."</td><td>$gradeType</td><td>$gradePoints</td>";
                        ?>
                      
                        <?php
                        echo "</tr>"
                        ?>
                      </tbody>
                </table>
                
                <?php 
                $resultSubjects=$db->getRows("equivalentresults", array('where'=>array('applicantResultID'=>$applicantResultID),'order_by applicantResultID ASC'));
                if(!empty($resultSubjects))
                {
                    ?>
                <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th>No</th>
                        <th>Subject Name</th>
                        <th>Grade</th>
                        
                      </tr>
                      </thead>
                     <tbody>
                          <?php 
                          $count=0;$totalPoints=0;
                            foreach ($resultSubjects as $subject) {
                                $count++;
                                $applicantSubjectID=$subject['applicantSubjectID'];
                                $subjectCode=$subject['subjectName'];
                                $grade=$subject['grade'];
                                
                                echo "<tr><td>$count</td><td>$subjectCode</td><td>$grade</td>";
                                ?>
                                    
                                    <?php
                                    echo "</tr>";
                            }
                          ?>
                      </tbody>
                </table>
                    <?php
                }
                
                }
                ?>
                </fieldset> 
            </div>
         
            <?php
            }
          ?>
            </fieldset>
        </div>
         <!-- End of Equivant Results-->
         
        
                        
         
        </div>  
         <?php    
     //}
    ?>
        </div>
        </fieldset>
    </div>

    <div class="col-lg-12">
    <?php 
    $applicantsData=$db->getRows('applicants',array('where'=>array('applicantID'=>$applicantID),'order_by'=>'applicantID ASC'));
    if(!empty($applicantsData)){ 
    foreach($applicantsData as $apps)
    {
       $applicantID=$apps['applicantID'];
       $fname=$apps['firstName'];
       $mname=$apps['middleName'];
       $lname=$apps['lastName'];
       $oname=$apps['otherNames'];
       $gender=$apps['gender'];
       $pobirth=$apps['placeOfBirth'];
       $mstatus=$apps['maritalStatus'];
       $citizenship=$apps['citizenship'];
       $dob=$apps['dateOfBirth'];
       $paddress=$apps['physicalAddress'];
       $pnumber=$apps['phoneNumber'];
       $email=$apps['email'];
       $nkin=$apps['nextOfKinName'];
       $nphone=$apps['nextOfKinPhoneNumber'];
       $naddress=$apps['nextOfKinAddress'];
       $nrelation=$apps['relationship'];
       $dstatus=$apps['disabilityStatus'];
       $dname=$apps['disabilityName'];
       $ddescription=$apps['disabilityDescription'];
       $empStatus=$apps['employmentStatus'];
       $sponsor=$apps['sponsor'];
    ?>
    
    
                <div class="well">
                <fieldset>
                  <legend>Personal Information</legend>
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="FirstName">First Name</label>
                            <input type="text" name="fname" value="<?php echo $fname;?>" class="form-control" required="" readonly="" />
                        </div>

                        <div class="col-lg-3">
                            <label for="MiddleName">Middle Name</label>
                            <input type="text" name="mname" value="<?php echo $mname;?>"  class="form-control" readonly="" />
                        </div>
                        <div class="col-lg-3">
                            <label for="LastName">Last Name</label>
                            <input type="text" name="lname" value="<?php echo $lname;?>"  class="form-control" required="" readonly=""/>
                        </div>
                        
                        <div class="col-lg-3">
                            <label for="LastName">Other Names</label>
                            <input type="text" name="oname" value="<?php echo $oname;?>"  class="form-control" readonly=""/>
                        </div>

                        
                    </div>
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="Geder">Gender</label>
                            <input type="text" name="gender" value="<?php echo $gender;?>"  class="form-control" disabled="" />
                        </div>
                        <div class="col-lg-3">
                            <label for="Date of Birth">Date of Birth</label>
                             
                            <input type="date" id="pickyDate" name="dob" value="<?php echo $dob;?>"  class="form-control datepicker" required="" disabled="" />
                                
                        </div>
                        
                        <div class="col-lg-3">
                            <label for="Physical Address">Place of Birth</label>
                            <input type="text" name="placeOfBirth" value="<?php echo $pobirth;?>"  class="form-control" required="" readonly="" />
                        </div>
                        
                        <div class="col-lg-3">
                            <label for="Geder">Marital Status</label>
                           <select name="mstatus" class="form-control" required="" disabled="">
                             
                             <?php
                             if($mstatus=="")
                             {
                             ?><option value="">Select Here</option>
                             <?php }
                             else
                             {
                             ?>
                             <option value="<?php echo $mstatus;?>" selected><?php echo $mstatus;?></option>
                             <?php }?>
                             <option value="Single">Single</option>
                             <option value="Married">Married</option>
                             <option value="Windowed">Windowed</option>
                             <option value="Divorced">Divorced</option>
                           </select>
                        </div>
                    </div>
                  <div class="row">
                        <div class="col-lg-3">
                            <label for="Physical Address">Nationality</label>
                            
                            <input type="text" name="citizenship" value="<?php echo $citizenship;?>"  class="form-control" required="" disabled="" />
                        </div>

                        <div class="col-lg-3">
                            <label for="Physical Address">Physical Address</label>
                            <input type="text" name="address" value="<?php echo $paddress;?>"  class="form-control" required="" disabled="" />
                        </div>
                        <div class="col-lg-3">
                            <label for="Email">Email</label>
                            <input type="text" name="appemail" value="<?php echo $email;?>"  class="form-control" disabled="" />
                        </div>

                        <div class="col-lg-3">
                            <label for="Phone">Phone Number</label>
                            <input type="text" name="phoneNumber" value="<?php echo $pnumber;?>" class="form-control" required="" disabled="">
                        </div>
                    </div>
                  <div class="row">
                      

                        <div class="col-lg-3">
                            <label for="Geder">Do you have any disability?</label>
                            <select name="disability" id="disability" class="form-control" required="" disabled="">
                             <?php
                             if($dstatus=="")
                             {
                             ?>
                                <option value="" selected="" disabled="">Select Here</option>
                             <option value="ndio">Yes</option>
                             <option value="hapana">No</option>
                             <?php
                             }
                             else if($dstatus=='No')
                             {
                             ?>
                             <option value="ndio">Yes</option>
                             <option value="hapana" selected="">No</option>
                             <?php
                             }else
                             {
                             ?>
                             <option value="ndio" selected="">Yes</option>
                             <option value="hapana">No</option>
                             <?php 
                             }
                             ?>
                           </select>
                        </div>
                       <?php
                        if($dstatus=="Yes")
                        {
                            $disabilityData=$db->getRows("disability",array('where'=>array('applicantID'=>$applicantID),'order_by applicantID ASC'));
                            foreach($disabilityData as $sp)
                            {
                                $disabilityName=$sp['disabilityName'];
                                $disabilityDescription=$sp['disabilityDescription'];
                            }
                        }
                        ?>
                        <div class="ndio"><div class="col-lg-3">
                            <label for="FirstName">Disability Name</label>
                            <input type="text" name="dname"  value="<?php echo $disabilityName;?>" class="form-control" required="" disabled="" />
                        </div>

                        <div class="col-lg-6">
                            <label for="MiddleName">Disability Description</label>
                            <input type="text" name="ddescription" value="<?php echo $disabilityDescription;?>"  class="form-control" required="" disabled="" />
                        </div>
                        
                        </div></div>
                     </fieldset>
                    <fieldset>
                  <legend>Employment Information</legend>
               
                    <div class="row">
                      

                        <div class="col-lg-3">
                            <label for="Geder">Are You Employed?</label>
                            <select name="employed" id="employed" class="form-control" required="" disabled="">
                             <?php
                             if($empStatus=="")
                             {
                             ?>
                                <option value="" selected="" disabled="">Select Here</option>
                             <option value="yes">Yes</option>
                             <option value="no">No</option>
                             <?php
                             }
                             else if($empStatus=='no')
                             {
                             ?>
                             <option value="yes">Yes</option>
                             <option value="no" selected="">No</option>
                             <?php
                             }else
                             {
                             ?>
                             <option value="yes" selected="">Yes</option>
                             <option value="no">No</option>
                             <?php 
                             }
                             ?>
                           </select>
                        </div>
                        <?php
                        if($empStatus=="yes")
                        {
                            $sponsorData=$db->getRows("employmentstatus",array('where'=>array('applicantID'=>$applicantID),'order_by applicantID ASC'));
                            foreach($sponsorData as $sp)
                            {
                                $employer=$sp['employer'];
                                $placework=$sp['placeOfWork'];
                                $designation=$sp['designation'];
                            }
                        }
                        ?>
                        <div class="yes"><div class="col-lg-3">
                            <label for="FirstName">Name of Employer</label>
                            <input type="text" name="employer" value="<?php echo $employer;?>" class="form-control" required="" disabled="" />
                        </div>

                        <div class="col-lg-3">
                            <label for="MiddleName">Address/Place of Work</label>
                            <input type="text" name="placework" value="<?php echo $placework;?>"  class="form-control" required="" disabled="" />
                        </div>
                        <div class="col-lg-3">
                            <label for="LastName">Designation</label>
                            <input type="text" name="designation" value="<?php echo $designation;?>"  class="form-control" required="" disabled="" />
                        </div>
                        </div></div></fieldset>

                    <fieldset>
                  <legend>Emergency Information</legend>
               
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="FirstName">Name of Next of Kin</label>
                            <input type="text" name="nextName" value="<?php echo $nkin;?>" class="form-control" required="" disabled="" />
                        </div>

                        <div class="col-lg-3">
                            <label for="MiddleName">Address of Next of Kin </label>
                            <input type="text" name="nextAddress" value="<?php echo $naddress;?>" class="form-control" required="" disabled="" />
                        </div>
                        <div class="col-lg-3">
                            <label for="LastName">Phone Number</label>
                            <input type="text" name="nextPhoneNumber" value="<?php echo $nphone;?>"  class="form-control" required="" disabled="" />
                        </div>

                        <div class="col-lg-3">
                            <label for="Geder">Relationship</label>
                            <input type="text" name="relationship"  class="form-control" value="<?php echo $nrelation;?>" required="" disabled="" />
                        </div>
                    </div></fieldset>

                   <fieldset>
                  <legend>Sponsorship Information</legend>
               
                    <div class="row">
                    <div class="col-lg-3">
                            <label for="gender">Sponsor</label>
                            <select name="sponsor" id="sponsor" class="form-control" required="" disabled="">
                            <?php 
                            if($sponsor=="")
                            {
                            ?> 
                            <option value="">Select Here</option>
                            <?php }
                            else {
                                ?>
                             <option value="<?php echo $sponsor;?>"><?php echo $sponsor; ?></option>
                            <?php 
                            }
                            ?>
                            
                             <option value="Self">Self Financed</option>
                             <option value="ZHELB">ZHEB</option>
                             <option value="HESLB">HESLB</option>
                             <option value="others">Others</option>
                           </select>
                        </div>
                        <?php
                        if($sponsor=="others")
                        {
                            $sponsorData=$db->getRows("sponsor",array('where'=>array('applicantID'=>$applicantID),'order_by applicantID ASC'));
                            foreach($sponsorData as $sp)
                            {
                                $sponsorname=$sp['sponsorName'];
                                $sponsoraddress=$sp['sponsorAddress'];
                                $sponsorphone=$sp['sponsorPhoneNumber'];
                            }
                        }
                       ?>
                        <div class="others">
                        <div class="col-lg-3">
                            <label for="FirstName">Sponsor's Full Name</label>
                            <input type="text" name="sponsorname" value="<?php echo $sponsorname;?>" disabled="" class="form-control" required=""/>
                        </div>

                        <div class="col-lg-3">
                            <label for="MiddleName">Address</label>
                            <input type="text" name="sponsoraddress" disabled="" value="<?php echo $sponsoraddress;?>"  class="form-control" required="" />
                        </div>
                        <div class="col-lg-3">
                            <label for="MiddleName">Phone Number</label>
                            <input type="text" name="sponsorphonenumber" disabled="" value="<?php echo $sponsorphone;?>"  class="form-control" required="" />
                        </div>
                        </div>
                        
                    </div></fieldset>
                   
                    
                </div>
    <?php }}?>
</div>
</div>
    
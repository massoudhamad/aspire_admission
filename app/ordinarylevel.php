<?php $db=new DBHelper();
?>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>
<link href="css/validation.css" rel="stylesheet">


    <?php
    //get indexNumber details
    $indexNumber=$_REQUEST['inumber'];
    $applicantID=$_REQUEST['id'];
    $formfourdetails= $db->getRows('applicantresults',array('where'=>array('applicantID'=>$applicantID),'order_by'=>'indexNumber ASC'));
    if(!empty($formfourdetails)){
        $count = 0;
        foreach($formfourdetails as $ffdetails){
            $count++;
            $formfour=$ffdetails['indexNumber'];
            $ytaken=$ffdetails['yearTaken'];
            $ebody=$ffdetails['examinationAuthority'];
            $schoolName=$ffdetails['schoolName'];
        }
    }
    ?>

    <div class="row">
        <div class="col-md-10">

                <form class="form-horizontal" name="register" id="register" action="action_ordinary_level.php" method="post">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-8">
                            <label for="FirstName">Ordinary Level Examination Body</label>
                            <input type="text" name="exam_body" id="exam_body" value="<?php echo $ebody;?>" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="Email">Year of Sitting</label>
                            <input type="text" name="indexYear" id="indexYear" value="<?php echo $ytaken;?>" class="form-control" readonly>
                            <!--<select name="indexYear" id="indexYear" class="form-control" required="required">
                                <option value="">Select Year</option>
                                <?php /*
                               /* $year=date('Y');
                                $year1=date('Y')-40;
                                for($x=$year;$x>=$year1;$x--)
                                {
                                    echo "<option value='$x'>$x</option>";
                                }*/
                                ?>
                            </select>-->
                        </div>
                        <!--<div class="NECTA">-->
                            <div class="col-lg-4">
                            <label for="Physical Address">Form Four Index Number</label>
                            <input type="text" name="indexNumber" id="indexNumber" value="<?php echo $formfour;?>"  class="form-control" readonly/>
                            </div>
<!--                        </div>
-->                         <!--<div class="NECTAO"><div class="col-lg-4">
                            <label for="Physical Address">Form Four Index Number</label>
                            <input type="text" name="indexNumberOld" id="indexNumberOld"  class="form-control" required="required" />
                            </div></div>
                            <div class="Others"><div class="col-lg-4">
                            <label for="Physical Address">Ordinary Level Index Number</label>
                            <input type="text" name="indexNumberOther" id="indexNumberOther"  class="form-control" required="required" />
                            </div>
                            </div>-->
                    </div>
                    <div class="row">
                         <div class="col-lg-4">
                            <label for="Physical Address">School/Center Name</label>
                            <input type="text" name="schoolName" id="schoolName"  class="form-control" placeholder="Enter school/center name" value="<?php echo $schoolName;?>" required="required" />
                        </div>
                        
                    </div>
                  
                  
                  
                  <!-- <fieldset>
                      <legend>Choose Subjects</legend>
                      <table class="table-striped" width="40%">
                                  <thead>
                                  <tr>
                                      <td colspan="3">
                                      <table width="100%">
                                  <td align="left">CHK</td><td align="left">Subject Name</td><td>Grade</td>
                                  </td>
                                      </table>
                                      </tr>
                                  </thead>
                
                <tr><tbody>

                <td colspan="3">
                <TABLE id="dataTable" width="100%" border="0">       
		<TR>
                    <TD><INPUT type="checkbox" name="chk"/></TD>
                    <TD>
                        
                        <select name="subjectCode[]" id="subjectID" class="form-control" >
                        <option value="">Select Subject</option>
                        <?php
                        // $subject = $db->getRows('subjects',array('where'=>array('stream'=>'1'),'order_by'=>'subjectName ASC'));
                        // if(!empty($subject)){ $count = 0; foreach($subject as $sbj){ $count++;
                        //  $subjectName=$sbj['subjectName'];
                        //  $subjectID=$sbj['subjectID'];
                        // ?>
                        // <option value="<?php //echo $subjectID;?>"><?php //echo $subjectName;?></option>
                        // <?php //}}?>
                       </select>
                        
                    </TD>
                    <TD>
                        <select name="gradeCode[]" id="grade" class="form-control" >
                           <!-- <option value="">--Select Grade--</option>
                            <?php
                            //     if($ytaken==2014 or $ytaken==2015)
                            //         $gradeRange=2014;
                            //     else
                            //         $gradeRange=2013;
                            //  $grade = $db->getRows('grades',array('where'=>array('gradeRangeYear'=>$gradeRange,'gradeLevel'=>1),'order_by'=>'gradeID ASC'));
                            //  if(!empty($grade)) {
                            //      echo "<option value=''>Please Select Here</option>";
                            //      foreach ($grade as $gd) {
                            //          $gradeID = $gd['gradeID'];
                            //          $gradeCode = $gd['gradeCode'];
                            //          echo "<option value='$gradeID'>$gradeCode</option>";
                            //      }
                            //  }
                            //  else
                            //  {
                            //      echo "<option value=''>No Grade Found</option>";
                            //  }
?>
                        </select>

			</select>
                    </TD>
                        
		</TR>
                
                   </TABLE></td>
                </tbody></tr>
                <tr><td><br></td></tr>
                 <tr><td><INPUT type="button" class="btn btn-danger form-control" value="Delete" onclick="deleteRow('dataTable')" /></td>
                     <td></td>
               <td><INPUT type="button" class="btn btn-primary form-control" value="Add more Subjects" onclick="addRow('dataTable')" /></td>
                    
                    
                </tr>
                        </table>

                  </fieldset>
                   </div>                
</div>
                        <div class="col-lg-4"></div>
                            <div class="col-lg-4">
                                <input type="hidden" name="applicantID" value="<?php echo //$applicantID;?>">
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="hidden" name="examinationlevel" value="Ordinary">
                             <input type="hidden" name="examinationaward" value="formfour">
                            <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control" />
                        </div> -->
                       <!-- <div class="col-lg-4">
                            <input type="button" name="doExit" value="Cancel" onclick="window.history.go(-1);" class="btn btn-success form-control" />
                        </div>-->
</form> 
                    </div>
    </div>


                        

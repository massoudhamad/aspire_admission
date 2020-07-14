<?php $db=new DBHelper();
/*if(isset($_POST['doProceed']))
    $db->redirect("index2.php?sz=education_background");
if(isset($_POST['doExit']))
    $db->redirect("logout.php?logout=true");*/
?>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>
<link href="css/validation.css" rel="stylesheet">

<form class="form-horizontal" name="register" id="register" action="action_ordinary_level.php" method="post">   
<div class="col-lg-12">    
              <div class="well">
                  <fieldset>
                  <legend>Please fill Advanced Level(FVI) Results</legend>
                 
                     <div class="row">
                        <div class="col-lg-8">
                            <label for="FirstName">Ordinary Level Examination Body</label>
                            <select name="exam_body" id="exam_body" class="form-control" required="required">
                                <option value="NECTA" selected="">National Examination Council of Tanzania (NECTA)(From 1988-To Date)</option>
                                <option value="NECTAO">National Examination Council of Tanzania (NECTA)(Bellow 1988)</option>
                                <option value="Others">Other Examination Body</option>
                            </select>
                        </div>
                        
                        <div class="Others"><div class="col-lg-4">
                            <label for="Other Bodies">Other Body</label>
                            <input type="text" name="other_body" id="other_body"  class="form-control" required="true"/>
                            </select>
                            </div></div>
                        
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="Email">Year of Sitting</label>
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
                        <div class="NECTA"><div class="col-lg-4">
                            <label for="Physical Address">Advance Level Index Number</label>
                            <input type="text" name="indexNumber" id="indexNumber"  class="form-control" required="required" />
                            </div></div>
                         <div class="NECTAO"><div class="col-lg-4">
                            <label for="Physical Address">Advance Level Index Number</label>
                            <input type="text" name="indexNumberOld" id="indexNumberOld"  class="form-control" required="required" />
                            </div></div>
                        <div class="Others"><div class="col-lg-4">
                            <label for="Physical Address">Advanced Level Index Number</label>
                            <input type="text" name="indexNumberOther" id="indexNumberOther"  class="form-control" required="required" />
                            </div></div>
                        
                         <div class="col-lg-4">
                            <label for="Physical Address">School/Center Name</label>
                            <input type="text" name="schoolName" id="schoolName"  class="form-control" required="required" />
                        </div>
                        
                    </div>
                  
                  
                  
                        </fieldset>
                  <fieldset>
                      <legend>Choose Subjects</legend>
                      <table class="table-striped" width="40%">
                                  <thead><tr>
                                  <td align="left">CHK</td><td align="left">Subject Name</td><td>Grade</td>
                                  </tr></thead>
                
                <tbody>
                <td colspan="3"> 
                <TABLE id="dataTable" width="100%" border="0">       
		<TR>
                    <TD><INPUT type="checkbox" name="chk"/></TD>
                    <TD>
                        
                        <select name="subjectCode[]" id="subjectID" class="form-control" required="required">
                        <option value="">Select Subject</option>
                        <?php
                        $subject = $db->getRows('subjects',array('where'=>array('stream'=>2),'order_by'=>'subjectName ASC'));
                        if(!empty($subject)){ $count = 0; foreach($subject as $sbj){ $count++;
                         $subjectName=$sbj['subjectName'];
                         $subjectID=$sbj['subjectID'];
                        ?>
                        <option value="<?php echo $subjectID;?>"><?php echo $subjectName;?></option>
                        <?php }}?>
                       </select>
                        
                    </TD>
                    <TD>
                        <SELECT name="gradeCode[]" id="grade" class="form-control" required="required">
                            <option value="">--Select Grade--</option>
			</SELECT>
                    </TD>
                        
		</TR>
                
                   </TABLE></td>
                </tbody>
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
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="hidden" name="examinationlevel" value="Advance">
                            <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control" />
                        </div>
                        <div class="col-lg-4">
                            <input type="button" name="doExit" value="Cancel" onclick="window.history.go(-1);" class="btn btn-success form-control" />
                        </div>
                        
                       
</form> 
                    </div>
                        

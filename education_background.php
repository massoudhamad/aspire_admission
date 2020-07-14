<?php $db=new DBHelper();
if(isset($_POST['doProceed']))
{
    $db->redirect("index2.php?sz=programmechoice");
}
if(isset($_POST['doExit']))
{
    $db->redirect("logout.php?logout=true");
}
?>
<script src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
             $(document).ready(function()
              {
              $("#qualificationTypeID").change(function()
              {
              var qualificationTypeID=$(this).val();
              var dataString = 'qualificationTypeID='+ qualificationTypeID;

              $.ajax
              ({
              type: "POST",
              url: "ajax_study_level.php",
              data: dataString,
              cache: false,
              success: function(html)
              {
              $("#studyLevelID").html(html);
              } 
              });

              });

              });
        </script>
<script type="text/javascript">
  $('#myModal').on('hidden.bs.modal', function () {
  location.reload();
});
</script>
<div class="row">
  
<div class="col-lg-12"> 
         <div class="col-lg-12">
                <?php 
                   if(!empty($_REQUEST['msg']))
                    {
                        if($_REQUEST['msg']=="succ") {
                          echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Congratulations, Successfully you saved your Data</strong>.
                    </div>";
                      }
                      else if($_REQUEST['msg']=="unsucc") {
                          echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sory, Error: Not able to save your Data. Looks like your Index Number is already used.</strong>
                    </div>";
                      }
                      else if($_REQUEST['msg']=="dropSchool") {
                          echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Success, School Data has been droped</strong>.
                    </div>";
                      }
                      else if($_REQUEST['msg']=="dropSubject") {
                          echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Success, Subject has been droped</strong>.
                    </div>";
                      }
                      else if($_REQUEST['msg']=="error") {
                          echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sory, Error-Something wrong happen-Contact System Administrator</strong>.
                    </div>";
                      }
                    }
                ?> 
                </div>
    <?php
    $results=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$_SESSION['applicantID']),'examinationLevel'=>'Ordinary','order_by applicantID ASC'));
    if(!empty($results))
     {
         ?>
     <div class="row">
            
            <?php
            $results=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$_SESSION['applicantID'],'examinationLevel'=>'Ordinary'),'order_by applicantID ASC'));
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
                        <th>Action</th>
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
                      <td> <a href="action_ordinary_level.php?action_type=dropSchool&id=<?php echo $applicantResultID; ?>" onclick="return confirm('Are you sure you want to Drop this school and all results?');"><span class="btn btn-danger">Drop School</a></td>
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
                        <th>Action</th>
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
                                $points=$subject['points'];
                               /* if($indexYear==2014 || $indexYear==2015)
                                {
                                if($grade=="A")
                                    $points=5;
                                else if($grade=="B+")
                                    $points=4;
                                else if($grade=="B")
                                    $points=3;
                                else if($grade=="C")
                                    $points=2;
                                else
                                    $points=1;
                                }
                                else
                                {
                                    if($grade=="A")
                                        $points=5;
                                    else if($grade=="B")
                                        $points=4;
                                    else if($grade=="C")
                                        $points=3;
                                    else
                                        $points=2;
                                }*/
                                $totalPoints=$totalPoints+$points;
                                echo "<tr><td>$count</td><td>".$db->getData("subjects","subjectName","subjectID",$subjectID)."</td><td>$grade</td><td>$points</td>";
                                ?>
                                    <td> <a href="action_ordinary_level.php?action_type=dropSubject&id=<?php echo $applicantSubjectID; ?>" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to Drop this Subject?');"></a></td>
                                    <?php
                                    echo "</tr>";
                            }
                          ?>
                                    <tr>
                                        <td colspan="3">Total Points</td><td><?php echo $totalPoints;?></td>
<!--                                        <td><a data-toggle="modal" href="addsubject.php?id=<?php echo $applicantResultID;?>&indexYear=<?php echo $yearTaken;?>&level=olevel" data-target="#oModal" class="btn btn-link"><span class="btn btn-primary form-control">Add Other Subject</span</a></td>-->
                                        <td><a  href="index2.php?sz=newsubject&id=<?php echo $applicantResultID;?>&indexYear=<?php echo $yearTaken;?>&level=olevel"><span class="btn btn-primary">Add Other Subject</span</a></td>
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
      <div class="col-lg-12">
          <label class="">To add Ordinary Level (Form Four(IV)) Results for another Sitting, Please Click Here</label>  <a href="index2.php?sz=olevel"><i class="glyphicon glyphicon-hand-right"></i> <span class="btn btn-primary">Add Other School</span></a>
    </div>  
         
         <div class="col-lg-12">
             <hr>
         </div>    
         <!--Advanced Results-->
         <div class="col-lg-12">
            <fieldset>
                <legend>Advanced Level Results</legend>
                
                 <?php
            $results=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$_SESSION['applicantID'],'examinationLevel'=>'Advance'),'order_by applicantID ASC'));
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
                        <th>Action</th>
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
                      <td> <a href="action_ordinary_level.php?action_type=dropSchool&id=<?php echo $applicantResultID; ?>" onclick="return confirm('Are you sure you want to Drop this school and all results?');"><span class="btn btn-danger">Drop School</a></td>
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
                        <th>Action</th>
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
                                 $points=$subject['points'];
//                                if($grade=="A")
//                                    $points=5;
//                                else if($grade=="B")
//                                    $points=4;
//                                else if($grade=="C")
//                                    $points=3;
//                                else if($grade=="D")
//                                    $points=2;
//                                else if($grade=="S")
//                                    $points=1;
//                                else
//                                    $points=0.5;
                                $totalPoints=$totalPoints+$points;
                                echo "<tr><td>$count</td><td>".$db->getData("subjects","subjectName","subjectID",$subjectID)."</td><td>$grade</td><td>$points</td>";
                                ?>
                                    <td> <a href="action_ordinary_level.php?action_type=dropSubject&id=<?php echo $applicantSubjectID; ?>" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to Drop this Subject?');"></a></td>
                                    <?php
                                    echo "</tr>";
                            }
                          ?>
                                    <tr>
                                        <td colspan="3">Total Points</td><td><?php echo $totalPoints;?></td>
<!--                                        <td><a data-toggle="modal" href="addalevelsubject.php?id=<?php echo $applicantResultID;?>&indexYear=<?php echo $yearTaken;?>&level=alevel" data-target="#aModal" class="btn btn-link"><span class="btn btn-primary form-control">Add Other Subject</span</a></td>-->
                                     <td><a  href="index2.php?sz=newsubject&id=<?php echo $applicantResultID;?>&indexYear=<?php echo $yearTaken;?>&level=alevel"><span class="btn btn-primary">Add Other Subject</span</a></td>
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
                
                
                
                <label class="col-lg-12 control-label">If you have Advanced Level Results, Click Here <a href="index2.php?sz=alevel"><span class="btn btn-primary"> Add Advanced Level Results</span></a></label>
                <!--<div class="well">
                    <form class="form-horizontal" role="form">

                    <div class="form-group">
                        <label class="col-sm-5 control-label">If you have Advanced Level Results, Click Here <a href="index2.php?sz=olevel"><span class="btn btn-primary"> Add Advanced Level Results</span></a></label>
                        
                        <!--<div class="col-sm-3">
                            <label class="radio-inline"> <input type="radio" name="advance" id="advance" onclick="displayAdvance(this.value,<%=applicantID%>)" value="1" checked> Yes </label>
                            <label class="radio-inline"> <input type="radio" name="advance" id="advance" onclick="displayAdvance(this.value,<%=applicantID%>)" value="0"> No </label>   
                        </div>
                    </div>
                    </form>
                </div>-->
            </fieldset>
        </div>
         <!--End of Advanced Level Results-->
         <div class="col-lg-12">
             <hr>
         </div>  
         <!--Equivalent Results-->
         <div class="col-lg-12">
            <fieldset>
                <legend>Equivalent Results</legend>
                
                 <?php
            $equivalentresults=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$_SESSION['applicantID'],'examinationLevel'=>'Equivalent'),'order_by applicantID ASC'));
            if(!empty($equivalentresults))
            {
            ?>
            
            <div class="col-lg-12">
                <fieldset>
                <legend>List of Equivalent Results(Certificate,NTAs,Diploma,Adv.Diploma,Degree)</legend>
                <?php 
                   foreach($equivalentresults as $matokeo)
                   {
                ?>
                <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th>Institute Name</th>
                        <th>Reg. Number</th>
                        <th>Year Taken</th>
                        <th>Programme Name</th>
                        <th>Qualification</th>
                        <th>Grade Type</th>
                        <th>Points</th>
                        <th>Action</th>
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
                        echo "<tr><td>$schoolName</td><td>$indexNumber</td><td>$yearTaken</td><td>$award</td><td>".$db->getData("qualificationtype","qualificationName","qualificationTypeID",$exam_authority)."</td><td>$gradeType</td><td>$gradePoints</td>";
                        ?>
                      <td> <a href="action_equivalent_results.php?action_type=dropSchool&id=<?php echo $applicantResultID; ?>" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to drop this results?');"></td>
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
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                          <?php 
                          $count=0;$totalPoints=0;
                            foreach ($resultSubjects as $subject) {
                                $count++;
                                $equivalentResultsID=$subject['equivalentResultsID'];
                                $subjectCode=$subject['subjectName'];
                                $grade=$subject['grade'];
                                
                                echo "<tr><td>$count</td><td>$subjectCode</td><td>$grade</td>";
                                ?>
                      <td> <a href="action_equivalent_results.php?action_type=dropSubject&id=<?php echo $equivalentResultsID; ?>" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to Drop this Subject?');"></a></td>
                                    <?php
                                    echo "</tr>";
                            }
                          ?>
                                    <!--<tr>
                                        <td colspan="3">Total Points</td><td><?php echo $totalPoints;?></td>
                                        <td><a data-toggle="modal" href="addsubject.php?id=<?php echo $applicantResultID;?>&indexYear=<?php echo $yearTaken;?>&level=alevel" data-target="#myModal" class="btn btn-link"><span class="btn btn-primary form-control">Add Other Subject</span</a></td>
                                    </tr>-->
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
            else
            {
            ?>
                <label class="col-lg-12 control-label">If you have Equivalent Level Results, Click Here <a href="index2.php?sz=equivalent"><span class="btn btn-primary"> Add Equivalent Level Results</span></a></label>
            <?php }?>
            </fieldset>
        </div>
         <!-- End of Equivant Results-->
         
         <form name="" method="post" action="">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-3">
                            
                            <a href="index2.php?sz=level"><span class="btn btn-success form-control">Previous</span></a>
                            
                        </div>
                        <div class="col-lg-3">
                            <input type="submit" name="doProceed" value="Proceed to Application" class="btn btn-success form-control" />
                        </div>
                        <div class="col-lg-3">
                            <input type="submit" name="doExit" value="Exit & Continue Later" class="btn btn-success form-control" />
                        </div>
     </form>
         
        </div>  
         <?php    
     }
    else
    {
        echo '<script>window.location="index2.php?sz=olevel"</script>';
        //header("Location:index2.php?sz=olevel");
        //$db->redirect("index2.php?sz=olevel");
    }
    ?>
</div>
</form>
                    </div>
<div class="modal fade" id="oModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
             <strong>Loading...</strong>
        </div>
    </div>
</div>
<div class="modal fade" id="aModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
             <strong>Loading...</strong>
        </div>
    </div>
</div>
 
                        

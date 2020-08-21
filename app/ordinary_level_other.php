<?php $db=new DBHelper();
/*if(isset($_POST['doProceed']))
    $db->redirect("index2.php?sz=education_background");*/
/*if(isset($_POST['doExit'])=="Cancel")
    header("Location:index2.php?sz=education_background");*/
?>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>
<div class="page-title">
    <div>
        <?php if($level=="alevel"){?>
        <h1><i class="fa fa-graduation-cap"></i>Advanced Level(FVI) Results</h1>
        <p>Please fill Advanced Level(FVI) Results</p>
        <?php }
        else {
            ?>
            <h1><i class="fa fa-graduation-cap"></i>Other Ordinary Level(FIV) Results</h1>
            <p>Please fill Ordinary Level(FIV) Results</p>
        <?php
        }?>
    </div>
</div>
<div class="row" style="padding-bottom: 15.5%;">
    <div class="col-lg-12">
    </div>

    <?php
    $indexNumber=$_REQUEST['inumber'];
    $applicantID=$_REQUEST['id'];
    $iNumber = explode("/", $indexNumber);
    $centerNumber = $iNumber[0];
    $number = $iNumber[1];
    $yTaken = $iNumber[2];
    $level=$_REQUEST['level'];
    ?>

    <div class="row">
        <div class="col-md-10">
            <form class="form-horizontal" name="register" id="register" action="action_education_background.php" method="post" onsubmit="return validateOtherSchool();">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-8">
                            <label for="FirstName">Ordinary Level Examination Body</label>
                            <select name="exam_body" id="exam_body" class="form-control" readonly>
                                <option value="NECTA" selected="">National Examination Council of Tanzania (NECTA)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="Physical Address">Form Four Index Number</label>
                            <input type="text" name="indexNumber" id="indexNumber" value="<?php echo $indexNumber;?>"  class="form-control" readonly/>
                        </div>

                        <div class="col-lg-4">
                            <label for="Physical Address">School/Center Name</label>
                            <input type="text" name="schoolName" id="schoolName"  class="form-control" placeholder="Enter school/center name"/>
                        </div>

                    </div>
                    <?php
                    if($level=='alevel')
                    {
                        $stream=2;
                        $gradelevel=2;
                    }
                    else
                    {
                        $stream=1;
                        $gradelevel=1;
                    }
                    ?>
                    <fieldset>
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
                                                    $subject = $db->getRows('subjects',array('where'=>array('stream'=>$stream),'order_by'=>'subjectName ASC'));
                                                    if(!empty($subject)){ $count = 0; foreach($subject as $sbj){ $count++;
                                                        $subjectName=$sbj['subjectName'];
                                                        $subjectID=$sbj['subjectID'];
                                                        ?>
                                                        <option value="<?php echo $subjectID;?>"><?php echo $subjectName;?></option>
                                                    <?php }}?>
                                                </select>

                                            </TD>
                                            <TD>
                                                <select name="gradeCode[]" id="grade" class="form-control" >
                                                    <?php
                                                    if($ytaken==2014 or $ytaken==2015)
                                                        $gradeRange=2014;
                                                    else
                                                        $gradeRange=2013;
                                                    $grade = $db->getRows('grades',array('where'=>array('gradeRangeYear'=>$gradeRange,'gradeLevel'=>$gradelevel),'order_by'=>'gradeID ASC'));
                                                    if(!empty($grade)) {
                                                        echo "<option value=''>Please Select Here Ordinary Other</option>";
                                                        foreach ($grade as $gd) {
                                                            $gradeID = $gd['gradeID'];
                                                            $gradeCode = $gd['gradeCode'];
                                                            echo "<option value='$gradeID'>$gradeCode</option>";
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "<option value=''>No Grade Found</option>";
                                                    }
                                                    ?>
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
            <input type="hidden" name="applicantID" value="<?php echo $applicantID;?>">
            <input type="hidden" name="action_type" value="add"/>
            <?php if($level=="alevel")
            {
                ?>
                <input type="hidden" name="examinationlevel" value="Advance">
                <input type="hidden" name="examinationaward" value="formsix">
                <?php
            }
            else
            {
                ?>
                <input type="hidden" name="examinationlevel" value="Ordinary">
                <input type="hidden" name="examinationaward" value="formfour">
            <?php
            }?>
            <input type="hidden" name="indexYear" value="<?php echo $yTaken;?>">
            <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control" />
        </div>
        <div class="col-lg-4">
            <input type="button" name="doExit" value="Cancel" onclick="window.history.go(-1);" class="btn btn-success form-control" />
        </div>
        </form>
    </div>
</div>




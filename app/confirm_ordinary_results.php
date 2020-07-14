<?php
session_start();
?>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>
<div class="row">
    <div class="col-lg-12">
            <?php
            if(!empty($_REQUEST['msg']))
            {
                /*if($_REQUEST['msg']=="succ") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Congratulations, Successfully you saved your Data</strong>.
                    </div>";
                }
                else*/ if($_REQUEST['msg']=="unsucc") {
                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error: Not able to save your Data. Looks like your Index Number is already used.</strong>
                    </div>";
                }
               /* else if($_REQUEST['msg']=="dropSchool") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Success, School Data has been droped</strong>.
                    </div>";
                }
                else if($_REQUEST['msg']=="dropSubject") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Success, Subject has been droped</strong>.
                    </div>";
                }*/
                else if($_REQUEST['msg']=="error") {
                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error-Something wrong happen-Contact System Administrator</strong>.
                    </div>";
                }
            }
            ?>
        </div>
</div>
<?php
/*$indexNumber = $db->getRows('users', array('where' => array('userID' => $_SESSION['user_session']), 'order_by' => 'userID ASC'));
if (!empty($indexNumber)) {
    $count = 0;
    foreach ($indexNumber as $iNumber) {
        $count++;
        $formfour = $iNumber['userName'];
    }
}*/
$indexNumber = $db->getRows('applicantresults', array('where' => array('applicantID' => $_SESSION['applicantID']), 'order_by' => 'applicantID ASC'));
if (!empty($indexNumber)) {
    $count = 0;
    foreach ($indexNumber as $iNumber) {
        $count++;
        $formfour = $iNumber['indexNumber'];
    }
}
?>
<?php
if($_SESSION['eauthority']=='NECTA') {
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-10">
                    <div class="card">


                        <form class="form-horizontal" name="form-get-olevel-data" id="form-get-olevel-data" action=""
                              method="post" onsubmit="return ajax_ordinary_level();">
                            <fieldset>
                                <legend>Form Four Results</legend>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="inputEmail">Form Four Index
                                        Number</label>
                                    <div class="col-lg-6">
                                        <input class="form-control" id="indexNumber" type="text"
                                               value="<?php echo $formfour; ?>" readonly>
                                    </div>
                                    <div class="col-lg-4">
                                        <input type="submit" name="doSubmit" value="View Results"
                                               class="btn btn-success form-control"/>
                                    </div>
                                </div>
                            </fieldset>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10">
            <div id="result">
            </div>
        </div>
    </div>
    <?php
}
else
{
   /*echo "<h3 class='text-danger'>Please <a href='index.php?sz=ordinary_results&id=" . $_SESSION['applicantID'] . "&inumber=" . $formfour . "'>click here</a>
    to add your results</h3>";*/
   //header("Location:index.php?sz=ordinary_results&id=" . $_SESSION['applicantID'] . "&inumber=" . $formfour ."");
    /*echo '<script>window.location="index.php?sz=ordinary_results&id=" . $_SESSION["applicantID"] . "&inumber=" . $formfour ."</script>';*/
    ?>
    <?php
    $applicantID=$_SESSION['applicantID'];
    $data = $db->getRows('applicantresults', array('where' => array('applicantID' => $_SESSION['applicantID']), 'order_by' => 'applicantID ASC'));
    if (!empty($data)) {
        $count = 0;
        foreach ($data as $dt) {
            $formfour=$dt['indexNumber'];
            $yTaken=$dt['yearTaken'];
        }
    }
    ?>
    <div class="page-title">
        <div>
                <h1><i class="fa fa-graduation-cap"></i>Ordinary Level(FIV) Results</h1>
                <p>Please fill Ordinary Level(FIV) Results</p>
        </div>
    </div>
    <div class="row" style="padding-bottom: 15.5%;">
        <div class="col-lg-12">
        </div>



        <div class="row">
            <div class="col-md-10">
                <form class="form-horizontal" name="register" id="register" action="action_other_olevel.php" method="post" onsubmit="return validateOtherSchool();">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-8">
                                <label for="FirstName">Ordinary Level Examination Body</label>
                                <select name="exam_body" id="exam_body" class="form-control" readonly>
                                    <option value="<?php echo $_SESSION['eauthority']; ?>" selected><?php echo $_SESSION['eauthority']; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="Physical Address">Form Four Index Number</label>
                                <input type="text" name="indexNumber" id="indexNumber" value="<?php echo $formfour;?>"  class="form-control" readonly/>
                            </div>

                            <div class="col-lg-4">
                                <label for="Physical Address">School/Center Name</label>
                                <input type="text" name="schoolName" id="schoolName" class="form-control" placeholder="Enter school/center name"/>
                            </div>

                        </div>
                        <?php
                            $stream=1;
                            $gradelevel=1;
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
                                                            echo "<option value=''>Please Select Here</option>";
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
                    <input type="hidden" name="examinationlevel" value="Ordinary">
                    <input type="hidden" name="examinationaward" value="formfour">
                <input type="hidden" name="yearTaken" value="<?php echo $yTaken;?>">
                <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control" />
            </div>
            <div class="col-lg-4">
                <input type="button" name="doExit" value="Cancel" onclick="window.history.go(-1);" class="btn btn-success form-control" />
            </div>
            </form>
        </div>
    </div>

    <?php
}
?>
     

                        

<?php $db = new DBHelper();
?>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>
<link href="css/validation.css" rel="stylesheet">

<div class="page-title">
    <div>
        <h1><i class="fa fa-graduation-cap"></i>Advanced Level(FVI) Results</h1>
        <p>Add Advanced/Form Six Results</p>
    </div>
</div>
<div class="row" style="padding-bottom: 15.5%;">
    <div class="col-lg-12">
    </div>

    <div class="row">
        <div class="col-md-10">

            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-8">
                        <label for="FirstName">Advanced Level Examination Body</label>
                        <select name="exam_body" id="exam_body" class="form-control">
                            <option value="">Select Examination Body</option>
                            <option value="NECTA">National Examination Council of Tanzania (NECTA)</option>
                            <option value="Others">Other/Foreign Examination Body</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="NECTA">
                        <form name="form-get-olevel-data" id="form-get-olevel-data" method="post" onsubmit="return myFunction();">
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="inputEmail">Form Six/Alevel Index Number</label>
                                <div class="col-lg-6">
                                    <input class="form-control" id="indexNumber" type="text" name="indexNumber">
                                </div>
                                <div class="col-lg-4">
                                    <input type="hidden" name="level" id="level" value="alevel">
                                    <input type="submit" name="doSubmit" value="View Results" class="btn btn-success form-control" />
                                </div>
                            </div>

                        </form>
                    </div>
                </div>


                <!-- Modal Start here-->

                <div class="Others">
                    <form name="form-get-olevel-data" id="form-get-olevel-data" method="post" onsubmit="return ">
                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="inputEmail">Equivalence A-Level Number</label>
                            <div class="col-lg-6">
                                <input class="form-control" id="equivalenceNumber" type="text" name="indexNumber">
                            </div>
                            <div class="col-lg-4">
                                <input type="hidden" name="level" id="level" value="alevel">
                                <input type="submit" name="doSubmit" value="View Results" class="btn btn-success form-control" />
                            </div>
                        </div>

                    </form>
                    <!-- <form class="form-horizontal" name="register" id="register" action="action_education_background.php" method="post" onsubmit="return validateOtherBody();">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="Physical Address">Advanced Level Index Number</label>
                                <input type="text" name="indexNumberOther" id="indexNumberOther"  class="form-control" />
                            </div>

                            <div class="col-lg-4">
                                <label for="Email">Year of Sitting</label>
                                <select name="indexYear" id="indexYear" class="form-control">
                                    <option value="">Select Year</option>
                                    <?php
                                    // $year=date('Y');
                                    // $year1=date('Y')-40;
                                    // for($x=$year;$x>=$year1;$x--)
                                    // {
                                    //     echo "<option value='$x'>$x</option>";
                                    // }
                                    ?>
                                </select>
                            </div>
                            <input type="text" hidden name="level" id="level" value="alevel">
                            <div class="col-lg-4">
                                <label for="Physical Address">School/Center Name</label>
                                <input type="text" name="schoolName" id="schoolName"  class="form-control" placeholder="Enter school/center name"/>
                            </div>
                        </div>


                        <div class="row">
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
                                                            // $subject = $db->getRows('subjects',array('where'=>array('stream'=>'2'),'order_by'=>'subjectName ASC'));
                                                            // if(!empty($subject)){ $count = 0; foreach($subject as $sbj){ $count++;
                                                            //     $subjectName=$sbj['subjectName'];
                                                            //     $subjectID=$sbj['subjectID'];
                                                            //     
                                                            ?>
                                                            //     <option value="<?php //echo $subjectID;
                                                                                    ?>"><?php //echo $subjectName;
                                                                                                                ?></option>
                                                            // <?php //}}
                                                                ?>
                                                        </select>

                                                    </TD>
                                                    <TD>
                                                        <select name="gradeCode[]" id="grade" class="form-control" >
                                                            <option value="">--Select Grade--</option>

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
                        <div class="col-lg-4"></div>
                        <div class="col-lg-4">
                            <input type="hidden" name="applicantID" value="<?php //echo $applicantID;
                                                                            ?>">
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="hidden" name="examinationlevel" value="Advance">
                            <input type="hidden" name="examinationaward" value="formsix">
                            <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control" />
                        </div>
                        <div class="col-lg-4">
                            <input type="button" name="doExit" value="Cancel" onclick="window.history.go(-1);" class="btn btn-success form-control" />
                        </div>
                    </form> -->
                </div>
            </div>

            <div class="row">

                <div class="col-md-10">
                    <div id="result">
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
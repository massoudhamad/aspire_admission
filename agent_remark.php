<?php
$db = new DBHelper();
?>
<script type="text/javascript">

    $(document).ready(function () {
        var titleheader = $('#titleheader').text();
        $('#admit').dataTable(
            {
                paging: true,
                dom: 'Blfrtip',
                buttons:[
                    {
                        extend:'csvHtml5',
                        title: titleheader,
                        customize: function (csv) {
                            return titleheader+"\n"+  csv +"\n";
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: titleheader,
                        footer: true,
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6,7]
                        }

                    },
                    {
                        extend: 'excel',
                        title: titleheader,
                        footer: true,
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6,7]
                        }

                    }

                ]
            });
    });
</script>

<div class="container">
    <h4>View List of Applicants</h4>
    <hr>
    <div class="row">
        <form name="" method="post" action="">
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="qualificationType">Applicant Remarks</label>
                    <select name="remarksID" class="form-control" required="">
                        <option value="">Select Remarks</option>
                        <?php //echo $db->getData("remarks","remark","remarkID",$applicantRemarksID);?></option>
                        <?php
                        $remarks = $db->getRows("remarks");
                        if(!empty($remarks)){ $count = 0; foreach($remarks as $rmk){ $count++;
                            $remark=$rmk['remark'];
                            $remarkID=$rmk['remarkID'];
                            if($remarkID==1 || $remarkID==6) {
                                ?>
                                <option value="<?php echo $remarkID; ?>"><?php echo $remark; ?></option>
                                <?php
                            }

                        }

                        }?>
                    </select>
                </div></div>

            <div class="col-lg-3">

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

            <div class="col-lg-3">

                <label for="MiddleName">Admission Intake</label>
                <select name="admissionID" class="form-control" required="">
                    <?php
                    $aitake = $db->getRows('admission_setting',array('order_by'=>'academicYearID ASC'));
                    if(!empty($aitake)){
                        echo"<option value=''>Please Select Here</option>";
                        $count = 0; foreach($aitake as $ait){ $count++;
                            $admissionID=$ait['admissionID'];
                            $admissionInTakeID=$ait['admissionInTakeID'];
                            ?>
                            <option value="<?php echo $admissionID;?>"><?php echo $db->getData('admission_intake',"admissionInTake","admissionInTakeID",$admissionInTakeID);?></option>
                        <?php }}
                    ?>
                </select>
            </div>
            <div class="col-lg-3">
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
            $remarksID=$_POST['remarksID'];
            $admissionYearID=$_POST['admissionYearID'];
            $admissionID=$_POST['admissionID'];
            $admissionInTakeID=$db->getData('admission_setting','admissionInTakeID','admissionID',$admissionID);
            ?>
            <div class="col-lg-12">
                <h4><span id="titleheader">List of <?php echo $db->getData("remarks","remark","remarkID",$remarksID) ;?> Applicants in
                     <?php echo $db->getData('admission_intake',"admissionInTake","admissionInTakeID",$admissionInTakeID); ?> <?php echo $db->getData("academicyears","academicYear","academicYearID",$admissionYearID);?></span></h4>
            </div>
                <table id="admit" class="display nowrap" cellspacing="0">

                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Phone Number</th>
                        <th>Form IV</th>
                        <th>Form VI/AVN</th>
                        <th>Programme</th>
                        <th>Agent Name</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
/*                    $applicantsData=$db->getRows("applicants",array('where'=>array('applicantsRemarksID'=>$remarksID,'applicationYearID'=>$admissionYearID,'admissionID'=>$admissionID),'order_by firstName ASC'));*/
                    $applicantsData=$db->getApplicantByAgent($admissionYearID,$admissionID,$remarksID);
                    if(!empty($applicantsData))
                    {
                        $number=0;
                        foreach ($applicantsData as $row)
                        {
                            $number++;
                            $applicantID=$row['applicantID'];
                            $_SESSION['applicantID']=array();
                            $userID=$row['userID'];
                            $indexNumber=$db->getData("users","userName","userID",$userID);
                            $fname= $row['firstName'];
                            $mname=$row['middleName'];
                            $lname=$row['lastName'];
                            $agentID=$row['agentID'];
                            $name="$fname $mname $lname";
                            $entryQualification=$row['entryQualification'];


                            $programmeChoice=$db->getRows("applicantapplication", array('where'=>array('applicantID'=>$applicantID,'choice'=>1),'order_by applicantID ASC'));
                            if(!empty($programmeChoice))
                            {
                                foreach ($programmeChoice as $pChoice)
                                {
                                    $applicantApplicationIDFirst=$pChoice['applicantApplicationID'];
                                    $firstChoice=$pChoice['programmeMajorID'];
                                    $programmeChoice=$db->getData("programmemajor","programmeMajor","programmeMajorID",$firstChoice);
                                }
                            }


                            $oindexumber=$db->getIndexNumber($applicantID,"Ordinary");
                            if(!empty($oindexumber))
                            {
                                $formfour=array();
                                foreach ($oindexumber as $fnumber) {
                                    $indexNumber=$fnumber['indexNumber'];
                                    $formfour[]=$indexNumber;
                                }

                            }
                            else
                            {
                                $formfour[]="";
                            }



                            $aindexumber=$db->getIndexNumber($applicantID,"Advance");
                            $formsix=array();
                            if(!empty($aindexumber))
                            {
                                $formsix=array();
                                foreach ($aindexumber as $fsixnumber) {
                                    $indexNumber=$fsixnumber['indexNumber'];
                                    $formsix[]=$indexNumber;
                                }

                            }
                            else
                            {
                                $formsix[]="";
                            }


                            $equivalentresults=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$applicantID,'examinationLevel'=>'Equivalent'),'order_by applicantID ASC'));
                            if(!empty($equivalentresults))
                            {
                                foreach($equivalentresults as $matokeo)
                                {
                                    $eIndexNumber=$matokeo['indexNumber'];
                                    $avn_number=$matokeo['avn_number'];
                                }
                            }
                            else
                            {
                                $eIndexNumber="";
                                $avn_number="";

                            }


                            if($entryQualification==0)
                                $findexNumber=$formsix[0];
                            else
                            {
                                if($avn_number=="")
                                    $findexNumber=$eIndexNumber;
                                else
                                    $findexNumber=$avn_number;
                            }


                            $four=array();
                            for($x=0;$x<count($formfour);$x++)
                            {
                                $four[]=$formfour[$x];
                            }

                            $six=array();
                            for($x=0;$x<count($formsix);$x++)
                            {
                                $six[]=$formsix[$x];
                            }
                            $fourfour=implode(",",$four);
                            $sixsix=implode(",",$six);



                            echo "<tr><td>$number</td></td><td>$name</td>
                            <td>".$row['gender']."</td><td>".$row['phoneNumber']."</td><td>".$fourfour."</td><td>$sixsix</td>
                            <td>".$programmeChoice."</td><td>".$db->getData('agents','agentName','agentID',$agentID)."</td></tr>";
                        }
                    }


                    ?>
                    </tbody>
                </table>
            <?php
        }
        ?>

    </div>

</div>
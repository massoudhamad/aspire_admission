<script type="text/javascript">
  $(document).ready(function () {
      var titleheader = $('#titleheader').text();
            $('#selection_list').DataTable(
                {
                    scrollX:true,
                    paging: false,
                    dom: 'Blfrtip',
                    buttons:[
                        {
                            //extend:'excel',
                            extend: 'excelHtml5',
                            title: titleheader,
                            footer:true,
                            exportOptions:{
                                columns:[0,1,2,3,4,5,6,7]
                            }
                        },
                        {
                          extend:'csvHtml5',
                          title: titleheader,
                          customize: function (csv) {
                          return titleheader+"\n"+  csv +"\n";
                        }
                        },
                        {
                            extend: 'print',
                            title: titleheader,
                            footer: false,
                            exportOptions: {
                                columns:[0,1,2,3,4,5,6,7]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: titleheader,
                            footer: true,
                           exportOptions: {
                                columns:[0,1,2,3,4,5,6,7]
                            },
                            orientation: 'landscape',
                        }

                        ]
                });
          });
</script>
<?php 
$db = new DBHelper();
?> 
<div class="row">
        <div class="col-lg-12">
            <!--<h4><span id="titleheader">List of Admitted Applicants for <?php //echo $db->getData("programs","programName","programID",$programmeID);?>
            <?php //echo $db->getData("academicyears","academicYear","academicYearID",$academicYearID);?>-Direct Entry</span></h4> -->
        </div>
    <?php 
             $programme=$db->getProgrammeName();
             if(!empty($programme))
             {
                 foreach ($programme as $pm)
                 {
                     $programmeID=$pm['programID'];
                     $programmeName=$pm['programName'];
                     ?>
    <div class="col-lg-12"><?php echo $programmeName;?></div>
                     <?php
                        $applicantsData=$db->getApplicantsApproved($programmeID,1,1);
                        if(!empty($applicantsData))
                        {
                            ?>
                            <table id="selection_list<?php echo $i;?>" class="display nowrap table-bordered" cellspacing="0" >
                                <thead>
                               <tr>
                                   <th>SNo</th>
                                   <th>Name</th>
                                   <th>Sex</th>
                                   <th>Ordinary Subjects</th>
                                   <th>Points</th>
                                   <th>Advanced Subjects</th>
                                   <th>Points</th>
                                   <th>Sec. Choice</th>
                               </tr>     
                             </thead>
                             <tbody>
            <?php
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

                             $programmeChoice=$db->getProgramme($applicantID,2);
                             if(!empty($programmeChoice))
                             {
                                 foreach ($programmeChoice as $pChoice)
                                 {
                                    $programmeData=$pChoice['programCode'];
                                 }
                             }
                                echo $programmeData."</td>";
                                echo "</tr>";
                            }
                            ?>
                                 </tbody>
                                </table>
                                 <?php
                        }
                        else
                        {

                        }
                ?>
             
             <?php
                 }
             }
             ?>
         
    </div>
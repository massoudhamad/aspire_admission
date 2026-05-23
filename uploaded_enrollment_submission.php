<script type="text/javascript">

    $(document).ready(function () {
        var titleheader = $('#titleheader').text();
        $('#selection_list').dataTable(
            {
                paging: true,
                scrollX:true,
                dom: 'Blfrtip',
                "lengthMenu": [[10, 25, 50,100,200,300,400,500, -1], [10, 25, 50,100,200,300,400,500, "All"]],
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
                            columns: [0, 2,3,4,5,6]
                        }

                    }

                ]
            });
    });
</script>
<?php
$db = new DBHelper();

?>
<div class="container">
    <div class="row">
            <div class="col-lg-12">
                <h4><span id="titleheader">List of Uploaded Applicants</span></h4>
            </div>
             <form name="register" id="register" method="post" action="action_uploaded_enrolled_applicants.php">
            <table id="selection_list" class="display nowrap" cellspacing="0">
                <thead>
                <tr>
                    <th>No</th>
                    <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Nationality</th>
                    <th>Date of Birth</th>
                    <th>Award Category</th>
                    <th>Field Specilization</th>
                    <th>Year of Study</th>
                    <th>Study Mode</th>
                    <th>Is Year Repeat</th>
                    <th>Entry Qualification</th>
                    <th>Sponsorship</th>
                    <th>Physical Challenges</th>
                    <th>Form IV</th>
                    <th>Award Name</th>
                    <th>Registration Number</th>
                    <th>Institution Code</th>
                    <th>Enrollement Year</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $applicantsData=$db->getRows("uploaded_enrolled");
                if(!empty($applicantsData))
                {
                    $i=0;
                    foreach ($applicantsData as $data)
                    {
                        $i++;
                        $fname=$data['fname'];
                        $mname=$data['mname'];
                        $lname=$data['surname'];
                        $gender=$data['gender'];
                        $dob=$data['date_of_birth'];
                        $disabiliyStatus=$data['physical_challenges'];
                        $nationality=$data['nationality'];
                        $entryQualification=$data['entry_qualification'];
                        $sponsor=$data['sponsorship'];
                        $registrationNumber=$data['registration_number'];
                        $tcu_status=$data['tcu_status'];
                        $fieldspecialization=$data['field_specialization'];
                        $award_category=$data['award_category'];
                        $tcu_status=$data['tcu_status'];


                       
                        if($tcu_status==1) {
                            $box = "NA";
                        }
                        else {
                            $box = "<input type='checkbox' class='checkbox_class' name='regNumber[]' value='$registrationNumber'>";
                        }


                        echo "<tr><td>$i</td>
                           <td>$box</td>
                            <td>$fname</td>
                            <td>$mname</td>
                            <td>$lname</td>
                            <td>$gender</td>
                            <td>".strtoupper($nationality)."</td>
                            <td>$dob</td>
                            <td>$award_category</td>
                            <td>$fieldspecialization</td>
                            <td>First Year</td>
                            <td>Full Time</td>
                            <td>No</td>
                            <td>".$data['entry_qualification']."</td>
                            <td>$sponsor</td>
                            <td>".$data['physical_challenges']."</td>
                            <td>".$data['f4_index_number']."</td>
                            <td>".$data['award_name']."</td>
                            <td>$registrationNumber</td>
                            <td>".$data['institution_code']."</td>
                            <td>2020/2021</td>";

                    }
                }
                ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col-lg-6"></div>
                <input type="hidden" name="number_applicants" value="<?php echo $count; ?>">
                <div class="col-lg-3">
                    <input type="hidden" name="action_type" value="add_app"/>
                    <input type="hidden" name="academicYearID" value="<?php echo $academicYearID;?>">
                    <input type="hidden" name="admissionID" value="<?php echo $admissionID;?>">
                    <input type="submit" name="doAdmit" value="Submit Registered"
                           class="btn btn-success form-control">
                </div>
            </div>

             </form>


    </div>

</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#studentdata").DataTable({
            "dom": 'Blfrtip',
            "scrollX":true,
            "paging":true,
            "buttons":[
                {
                    extend:'excel',
                    title: 'List of all Register',
                    footer:false,
                    exportOptions:{
                        columns: [0, 1, 2, 3,5,6,7]
                    }
                },
                ,
                {
                    extend: 'print',
                    title: 'List of all Register',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3,5,6,7]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'List of all Register',
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3,5,6,7]
                    },

                }

            ],
            "order": []
        });
    });
</script>
<h1><b>Search Applicant</b></h1>
<hr>
<div class="row">
    <form name="" method="post" action="">
        <div class="col-lg-6">
            <label for="MiddleName"> Enter Student App.Number/Ref.number/First Name/LastName/Form Four Index Number:</label>
            <input  type="text"  name="search_student" class="form-control"/>
        </div>
</div>
<div class="row">
    <div class="col-lg-4"></div>
    <div class="col-lg-3">
        <label for=""></label>
        <input type="submit" name="doSearch" value="Search Applicant" class="btn btn-primary form-control" />
    </div>
</div>
</form>
<br><br>
<div class="row">
    <div class="col-lg-12">
    <?php
    $db=new DBhelper();
    if(isset($_POST['doSearch'])=="Search Applicant")
    {
    $searchText=$_POST['search_student'];
    $search=$db->searchApplicant($searchText);
    if(!empty($search))
    ?>
            <table id="studentdata" class="table table-striped table-bordered table-condensed">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Form Four</th>
                    <th>Reg.Number</th>
                    <th>App.Number</th>
                    <th>Ref.Number</th>
                    <th>Programme</th>
                    <th>Phone Number</th>
                    <th>Email</th>
<!--                    <th>Form Four</th>
-->                    <th>MUM Status</th>
                    <th>TCU Status</th>
                    <th>NACTE Status</th>
                    <th>Print Form</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count = 0;
                foreach($search as $std)
                {
                    $count++;
                    $applicantID=$std['applicantID'];
                    $applicationYearID=$std['applicationYearID'];
                    $fname=$std['firstName'];
                    $mname=$std['middleName'];
                    $lname=$std['lastName'];
                    $gender=$std['gender'];
                    $appNumber=$std['applicationNumber'];
                    $refNumber=$std['refNumber'];
                    $phoneNumber=$std['phoneNumber'];
                    $email=$std['email'];
                    $remarksID=$std['applicantsRemarksID'];
                    $tcu_final=$std['tcu_final'];
                    $nacte_status=$std['nacte_status'];
                    $indexNumber=$std['indexNumber'];


                    $programmeFirstChoice=$db->getProgramme($applicantID,1);
                    if(!empty($programmeFirstChoice))
                    {
                        foreach ($programmeFirstChoice as $pChoice)
                        {
                            $firstChoice=$pChoice['programCode'];
                            $fChoiceName=$pChoice['programName'];
                        }
                    }
                    else
                    {
                        $firstChoice="";
                        $fChoiceName="";
                    }


                    $name="$fname $mname $lname";

                    if($nacte_status==1)
                        $nacte_status="Admitted";
                    else if($nacte_status==0)
                        $nacte_status="None";

                    if($remarksID==6)
                    {
                        $print="<a href='printform.php?action=getPDF&applicantID=$applicantID&academicYearID=$applicationYearID' target='_blank'>Print</a>";
                    }
                    else
                    {
                        $print="No";
                    }
                    if($remarksID==6)
                    {
                        $regNumber=$db->getData("applicantregistration","registrationNumber","applicantID",$applicantID);
                    }
                    else
                    {
                        $regNumber="NAN";
                    }

                    echo "<tr>
                    <td><a href='index3.php?sp=view_applicant_info&applicantID=$applicantID'>$name</a></td>
                    <td>$indexNumber</td>
                    <td>$regNumber</td>
                    <td>$appNumber</td>
                    <td>$refNumber</td>
                    <td>$firstChoice</td>
                    <td>$phoneNumber</td>
                    <td>$email</td>
                    <td>".$db->getData("remarks","remark","remarkID",$remarksID)."</td>
                    <td>$tcu_final</td>
                    <td>$nacte_status</td>
                    <td>$print</td>
                    </tr>";
                }
                ?>
                </tbody>
            </table>
        <?php
        }
        ?>
    </div></div>

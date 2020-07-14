<?php 
$db = new DBHelper();
?>
<script type="text/javascript">
 
  $(document).ready(function () {
         var titleheader = $('#titleheader').text();
            $('#admit').dataTable(
                {
                    paging: false,
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
                                columns: [0, 2,3,4,5,6]
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

            <label for="MiddleName">Programme Name</label>
            <select name="programmeID" class="form-control chosen-select" required="">
                <?php
                $adYear = $db->getPublishedProgramme();
                if(!empty($adYear)){
                    echo"<option value=''>Please Select Here</option>";
                    $count = 0; foreach($adYear as $year){ $count++;
                        $programMajor=$year['programmeMajor'];
                        $programmeMajorID=$year['programmeMajorID'];
                        ?>
                        <option value="<?php echo $programmeMajorID;?>"><?php echo $programMajor;?></option>
                    <?php }}
                ?>
            </select>
        </div>

        <!--<div class="col-lg-3">

                                   <label for="MiddleName">Entry Level</label>
                                   <select name="entry" class="form-control" required="">
                                      <option value="">Select Here</option>
                                      <option value="0">Direct</option>
                                       <option value="1">Equivalent</option>
                                  </select>
        </div>-->
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

        <div class="col-lg-2">

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
</div>
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-3">
            <label for=""></label>

            <input type="submit" name="doSearch" value="Search Records" class="btn btn-primary form-control" />
        </div>
    </div>
    </form>
    
    <br><br>
    <div class="row">
        
        <?php 
        if(isset($_POST['doSearch'])=="Search Records")
        {
            $programmeID=$_POST['programmeID'];
            $applicationYearID=$_POST['admissionYearID'];
            $admissionID=$_POST['admissionID'];
        ?>
         <div class="col-lg-12">
            <h4><span id="titleheader">List of Rejected Applicants for <?php echo $db->getData("programmemajor","programmeMajor","programmeMajorID",$programmeID);?></span></h4>
        </div>
        <form name="register" id="register" method="post" action="action_unenroll.php">
        <table id="admit" class="display nowrap" cellspacing="0">
            
          <thead>
           <tr>
               <th width="5">SNo</th>
               <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
               <th>Name</th>
               <th>Sex</th>
               <th>Phone Number</th>
               <th>Index Number</th>
               <th>Ref.Number</th>
               <th>Action</th>
           </tr>     
         </thead>
         
         <tbody>
         <?php

         $applicantsData=$db->getRejected($programmeID,4,$applicationYearID,$admissionID);
                 if(!empty($applicantsData))
                 {
                     $i=0;$_SESSION['applicantID']=array();
                     foreach ($applicantsData as $data)
                     {
                         $i++;
                         $applicantID=$data['applicantID'];
                         $fname=$data['firstName'];
                         $mname=$data['middleName'];
                         $lname=$data['lastName'];
                         $gender=$data['gender'];
                         $refNumber=$data['refNumber'];
                         $phoneNumber=$data['phoneNumber'];
                         $userID=$data['userID'];
                         $name="$fname $mname $lname";
                         
                         echo "<tr><td>$i</td><td><input type='checkbox' class='checkbox_class' name='id[]' value='$applicantID'></td><td>$name</td>"
                                 . "<td>$gender</td><td>$phoneNumber</td><td>".$db->getData("users","userName","userID",$userID)."</td><td>$refNumber</td>";
                         echo '<td><a href="index3.php?sp=applicantinfo&applicantID='.$applicantID.'"><span class="glyphicon glyphicon-edit"></span> Details</a></td></tr>';
                     }
                 }
                
                 
         ?> 
         </tbody>
        </table>
        <div class="row">
            <div class="col-lg-9"></div>
            <input type="hidden" name="number_applicants" value="<?php echo $i;?>">
            <input type="hidden" name="programmeMajorID" value="<?php echo $programmeID;?>">
            <input type="hidden" name="choice" value="<?php echo $choice;?>">
             <div class="col-lg-3">
                 <input type="hidden" name="action_type" value="add"/>
                <input type="submit" name="doUpdate" value="Un Enrol" class="btn btn-danger form-control">
            </div>
        </div>
            </form>
        <?php
        }
        ?>
        
    </div>
    
</div>
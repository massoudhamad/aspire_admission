<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>
<link href="css/validation.css" rel="stylesheet">
<script type="text/javascript">
$(document).ready(function() {
    $("#regionID").change(function () {
        var regionID = $(this).val();
        var dataString = 'regionID=' + regionID;

        $.ajax
        ({
            type: "POST",
            url: "ajax_district.php",
            data: dataString,
            cache: false,
            success: function (html) {
                $("#districtID").html(html);

            }
        });

    });

});
</script>
<script type="text/javascript">
$(document).ready(function() {
    $("#regionID").change(function () {
        var regionID = $(this).val();
        var dataString = 'regionID=' + regionID;

        $.ajax
        ({
            type: "POST",
            url: "ajax_agents.php",
            data: dataString,
            cache: false,
            success: function (html) {
                $("#agentID").html(html);

            }
        });

    });

});
</script>
<?php
$db = new DBHelper();
?>

<div class="row">
<div class="col-lg-12">
                <?php 
                    if(!empty($_REQUEST['msg']))
                    {

                        if($_REQUEST['msg']=="succ") {
                            echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Congratulations,Your data has been saved to the system, please continue next step</strong>.
                    </div>";
                        }
                        else if($_REQUEST['msg']=="unsucc") {
                          echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sory, We are not able to save your data</strong>.
                    </div>";
                      }
                      else if($_REQUEST['msg']=="error") {
                          echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sory, Error-Something wrong happen-Contact System Administrator or Admission Office</strong>.
                    </div>";
                      }
                    }
                ?> 
                </div>

</div>
<div id="" class="">
    
    <?php 
    $applicantsData=$db->getRows('applicants',array('where'=>array('applicantID'=>$_SESSION['applicantID']),'order_by'=>'applicantID ASC'));
    if(!empty($applicantsData)){ 
    foreach($applicantsData as $apps)
    {
        $applicantID=$apps['applicantID'];
       $fname=$apps['firstName'];
       $mname=$apps['middleName'];
       $lname=$apps['lastName'];
       $oname=$apps['otherNames'];
       $gender=$apps['gender'];
       $pobirth=$apps['placeOfBirth'];
       $mstatus=$apps['maritalStatus'];
       $citizenship=$apps['citizenship'];
       $dob=$apps['dateOfBirth'];
       $paddress=$apps['physicalAddress'];
       $pnumber=$apps['phoneNumber'];
       $email=$apps['email'];
       $nkin=$apps['nextOfKinName'];
       $nphone=$apps['nextOfKinPhoneNumber'];
       $naddress=$apps['nextOfKinAddress'];
       $nrelation=$apps['relationship'];
       $dstatus=$apps['disabilityStatus'];
       $dname=$apps['disabilityName'];
       $ddescription=$apps['disabilityDescription'];
       $empStatus=$apps['employmentStatus'];
       $sponsor=$apps['sponsor'];
       $agentID=$apps['agentID'];
       $districtID=$apps['districtID'];
       $regionID=$db->getData("district","regionID","districtID",$districtID);
    ?>
    <form action="action_registration_form.php" method="post" name="register" id="register">
    
                <div class="well">
                <fieldset>
                    <div class="page-title">
                        <div>
                            <h1><i class="fa fa-list"></i>Personal Information</h1>
                            <p>Please fill your personal information</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="FirstName">First Name</label>
                            <input type="text" name="fname" value="<?php echo $fname;?>" class="form-control" required="" readonly/>
                        </div>

                        <div class="col-lg-3">
                            <label for="MiddleName">Middle Name</label>
                            <input type="text" name="mname" value="<?php echo $mname;?>"  class="form-control" readonly/>
                        </div>
                        <div class="col-lg-3">
                            <label for="LastName">Last Name</label>
                            <input type="text" name="lname" value="<?php echo $lname;?>"  class="form-control" required="" readonly/>
                        </div>
                        
                        <div class="col-lg-3">
                            <label for="LastName">Other Names</label>
                            <input type="text" name="oname" value="<?php echo $oname;?>"  class="form-control" />
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="Geder">Gender</label>
                            <input type="text" name="gender" value="<?php echo $gender;?>"  class="form-control" disabled="" />
                        </div>
                        <div class="col-lg-3">
                            <label for="Date of Birth">Date of Birth</label>
                            <?php
                            $dob2=explode("-",$dob);
                            $year=$dob2[0];
                            $month=$dob2[1];
                            $date=$dob2[2];
                            ?>
                            <div class="row">
                                <div class="col-lg-4 no-padding-right">
                                    <select name="date" class="form-control" required>
                                        <?php
                                        if(!empty($dob))
                                        {
                                            ?>
                                            <option value="<?php echo $date;?>" selected><?php echo $date;?></option>
                                            <?php
                                        }else
                                        {?>
                                            <option value="">--Date--</option>
                                            <?php
                                        }
                                        for($x=1;$x<=31;$x++)
                                        {
                                            echo "<option value='$x'>$x</option>";
                                        }
                                        ?>
                                    </select>
                                </div><div class="col-lg-4 no-padding-right no-padding-left">
                                    <select name="month" class="form-control" required>
                                        <?php
                                        $arrmonth=array();
                                        $arrmonth[1] ="January";
                                        $arrmonth[2] ="February";
                                        $arrmonth[3] ="March";
                                        $arrmonth[4] ="April";
                                        $arrmonth[5] ="May";
                                        $arrmonth[6] ="June";
                                        $arrmonth[7] ="July";
                                        $arrmonth[8] ="August";
                                        $arrmonth[9] ="September";
                                        $arrmonth[10] ="October";
                                        $arrmonth[11] ="November";
                                        $arrmonth[12] ="December";
                                        if(!empty($dob))
                                        {
                                            ?>
                                            <option value="<?php echo $month;?>" selected><?php echo $arrmonth[ltrim($month,0)];?></option>
                                            <?php
                                        }else
                                        {?>
                                            <option value="">--Month--</option>
                                            <?php
                                        }
                                        ?>
                                        <?php


                                        for($i = 1; $i<=12; $i++)
                                        {
                                            echo "<option value='$i'>$arrmonth[$i]</option>";
                                        }
                                        ?>
                                    </select>
                                </div><div class="col-lg-4 no-padding-left">
                                    <select name="year" class="form-control" required>
                                        <?php
                                        if(!empty($dob))
                                        {
                                            ?>
                                            <option value="<?php echo $year;?>" selected><?php echo $year;?></option>
                                            <?php
                                        }else
                                        {?>
                                            <option value="">--Year--</option>
                                            <?php
                                        }
                                        ?>

                                        <?php
                                        $year=date('Y');
                                        $year1=date('Y')-60;
                                        for($x=$year;$x>=$year1;$x--)
                                        {
                                            echo "<option value='$x'>$x</option>";
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>
                            <!--<input type="text" id="pickyDate" name="dob" value="<?php /*echo $dob;*/?>"  class="form-control" required=""/>-->
                           <!-- <div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyy MM dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" name="endDate" value="<?php /*echo $endDate;*/?>" id="pickyDate5">
                                <span class="input-group-addon"></span>
                            </div>-->
                                
                        </div>
                        
                        <div class="col-lg-3">
                            <label for="Physical Address">Place of Birth</label>
                            <input type="text" name="placeOfBirth" value="<?php echo $pobirth;?>"  class="form-control" required="" />
                        </div>
                        
                        <div class="col-lg-3">
                            <label for="Geder">Marital Status</label>
                           <select name="mstatus" class="form-control" required="">
                             
                             <?php
                             if($mstatus=="")
                             {
                             ?><option value="">Select Here</option>
                             <?php }
                             else
                             {
                             ?>
                             <option value="<?php echo $mstatus;?>" selected><?php echo $mstatus;?></option>
                             <?php }?>
                             <option value="Single">Single</option>
                             <option value="Married">Married</option>
                             <option value="Windowed">Windowed</option>
                             <option value="Divorced">Divorced</option>
                           </select>
                        </div>
                    </div>
                  <div class="row">
                        <div class="col-lg-3">
                            <label for="Physical Address">Nationality</label>
                            

                            <select name="citizenship" class="form-control" required="">

                                <?php
                                if($citizenship=="")
                                {
                                    ?><option value="">Select Here</option>
                                <?php
                                }
                                else
                                {
                                    ?>
                                    <option value="<?php echo $citizenship;?>" selected><?php echo $citizenship;?></option>
                                <?php }?>
                                <option value="Tanzania">Tanzania</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Uganda">Uganda</option>
                                <option value="Rwanda">Rwanda</option>
                                <option value="South Sudan">South Sudan</option>
                                <option value="Sudan">Sudan</option>
                                <option value="Burundi">Burundi</option>
                                <option value="Malawi">Malawi</option>
                                <option value="Zambia">Zambia</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label for="Physical Address">Physical Address</label>
                            <input type="text" name="address" value="<?php echo $paddress;?>"  class="form-control" required="" />
                        </div>
                        <div class="col-lg-3">
                            <label for="Email">Email</label>
                            <input type="text" name="appemail" value="<?php echo $email;?>"  class="form-control" />
                        </div>

                        <div class="col-lg-3">
                            <label for="Phone">Phone Number</label>
                            <input type="text" name="phoneNumber" value="<?php echo $pnumber;?>" class="form-control" required="">
                        </div>
                    </div>
                  <div class="row">
                      

                        <div class="col-lg-3">
                            <label for="Geder">Do you have any disability?</label>
                            <select name="disability" id="disability" class="form-control" required="">
                             <?php
                             if($dstatus=="")
                             {
                             ?>
                                <option value="" selected="">Select Here</option>
                             <option value="ndio">Yes</option>
                             <option value="hapana">No</option>
                             <?php
                             }
                             else if($dstatus=='No')
                             {
                             ?>
                             <option value="ndio">Yes</option>
                             <option value="hapana" selected="">No</option>
                             <?php
                             }else
                             {
                             ?>
                             <option value="ndio" selected="">Yes</option>
                             <option value="hapana">No</option>
                             <?php 
                             }
                             ?>
                           </select>
                        </div>
                       <?php
                        if($dstatus=="Yes")
                        {
                            $disabilityData=$db->getRows("disability",array('where'=>array('applicantID'=>$applicantID),'order_by applicantID ASC'));
                            foreach($disabilityData as $sp)
                            {
                                $disabilityName=$sp['disabilityName'];
                                $disabilityDescription=$sp['disabilityDescription'];
                            }
                        }
                        ?>
                        <div class="ndio"><div class="col-lg-3">
                            <label for="FirstName">Disability Name</label>
                            <input type="text" name="dname"  value="<?php echo $disabilityName;?>" class="form-control"  />
                        </div>

                        <div class="col-lg-6">
                            <label for="MiddleName">Disability Description</label>
                            <input type="text" name="ddescription" value="<?php echo $disabilityDescription;?>"  class="form-control"  />
                        </div>
                        
                        </div></div>
                     </fieldset>
                    <fieldset>
                  <legend>Employment Information</legend>
               
                    <div class="row">
                      

                        <div class="col-lg-3">
                            <label for="Geder">Are You Employed?</label>
                            <select name="employed" id="employed" class="form-control" required="">
                             <?php
                             if($empStatus=="")
                             {
                             ?>
                             <option value="" selected="">Select Here</option>
                             <option value="yes">Yes</option>
                             <option value="no">No</option>
                             <?php
                             }
                             else if($empStatus=='no')
                             {
                             ?>
                             <option value="yes">Yes</option>
                             <option value="no" selected="">No</option>
                             <?php
                             }else
                             {
                             ?>
                             <option value="yes" selected="">Yes</option>
                             <option value="no">No</option>
                             <?php 
                             }
                             ?>
                           </select>
                        </div>
                        <?php
                        if($empStatus=="yes")
                        {
                            $sponsorData=$db->getRows("employmentstatus",array('where'=>array('applicantID'=>$applicantID),'order_by applicantID ASC'));
       
                            foreach($sponsorData as $sp)
                            {
                                $employer=$sp['employer'];
                                $placework=$sp['placeOfWork'];
                                $designation=$sp['designation'];
                            }
                        }
                        ?>
                        <div class="yes"><div class="col-lg-3">
                            <label for="FirstName">Name of Employer</label>
                            <input type="text" name="employer" value="<?php echo $employer;?>" class="form-control"  />
                        </div>

                        <div class="col-lg-3">
                            <label for="MiddleName">Address/Place of Work</label>
                            <input type="text" name="placework" value="<?php echo $placework;?>"  class="form-control"  />
                        </div>
                        <div class="col-lg-3">
                            <label for="LastName">Designation</label>
                            <input type="text" name="designation" value="<?php echo $designation;?>"  class="form-control"  />
                        </div>
                        </div></div></fieldset>

                    <fieldset>
                  <legend>Emergency Information</legend>
               
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="FirstName">Name of Next of Kin</label>
                            <input type="text" name="nextName" value="<?php echo $nkin;?>" class="form-control" required="" />
                        </div>

                        <div class="col-lg-3">
                            <label for="MiddleName">Address of Next of Kin </label>
                            <input type="text" name="nextAddress" value="<?php echo $naddress;?>" class="form-control" required="" />
                        </div>
                        <div class="col-lg-3">
                            <label for="LastName">Phone Number</label>
                            <input type="text" name="nextPhoneNumber" value="<?php echo $nphone;?>"  class="form-control" required="" />
                        </div>

                        <div class="col-lg-3">
                            <label for="Geder">Relationship</label>
                            <input type="text" name="relationship"  class="form-control" value="<?php echo $nrelation;?>" required="" />
                        </div>
                    </div></fieldset>

                   <fieldset>
                  <legend>Sponsorship Information</legend>
               
                    <div class="row">
                    <div class="col-lg-3">
                            <label for="gender">Sponsor</label>
                            <select name="sponsor" id="sponsor" class="form-control" required="">
                            <?php 
                            if($sponsor=="")
                            {
                            ?> 
                            <option value="">Select Here</option>
                            <?php
                            }
                            else {
                                ?>
                             <option value="<?php echo $sponsor;?>"><?php echo $sponsor; ?></option>
                            <?php 
                            }
                            ?>
                            
                             <option value="Self">Self Financed</option>
                             <option value="ZHELB">ZHEB</option>
                             <option value="HESLB">HESLB</option>
                             <option value="others">Others</option>
                           </select>
                        </div>
                        <?php
                        if($sponsor=="others")
                        {
                            $sponsorData=$db->getRows("sponsor",array('where'=>array('applicantID'=>$applicantID),'order_by applicantID ASC'));
                            foreach($sponsorData as $sp)
                            {
                                $sponsorname=$sp['sponsorName'];
                                $sponsoraddress=$sp['sponsorAddress'];
                                $sponsorphone=$sp['sponsorPhoneNumber'];
                            }
                        }
                       ?>
                        <div class="others">
                        <div class="col-lg-3">
                            <label for="FirstName">Sponsor's Full Name</label>
                            <input type="text" name="sponsorname" value="<?php echo $sponsorname;?>" class="form-control" />
                        </div>

                        <div class="col-lg-3">
                            <label for="MiddleName">Address</label>
                            <input type="text" name="sponsoraddress" value="<?php echo $sponsoraddress;?>"  class="form-control" />
                        </div>
                        <div class="col-lg-3">
                            <label for="MiddleName">Phone Number</label>
                            <input type="text" name="sponsorphonenumber" value="<?php echo $sponsorphone;?>"  class="form-control" />
                        </div>
                        </div>
                        
                    </div></fieldset>

                    <fieldset>
                        <legend>Other Information</legend>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="gender">Region</label>
                                <select name="regionID" id="regionID" class="form-control" required>
                                    <?php
                                    if(!empty($regionID))
                                    {
                                        ?>
                                        <option value="<?php echo $regionID;?>"><?php echo $db->getData("region","regionName","regionID",$regionID); ?></option>
                                        <?php
                                    }
                                    else {
                                        ?>
                                        <option value="">Select Here</option>
                                        <?php
                                    }
                                    $location=$db->getRows("region",array('order_by regionID ASC'));
                                    foreach($location as $sp)
                                    {
                                        $regionID=$sp['regionID'];
                                        $regionName=$sp['regionName'];
                                        echo "<option value='$regionID'>$regionName</option>";
                                    }

                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="gender">District</label>
                                <select name="districtID" id="districtID" class="form-control" required="">
                                    <?php
                                    if(!empty($districtID)) {
                                        ?>
                                        <option value="<?php echo $districtID;?>"><?php echo $db->getData("district","districtName","districtID",$districtID);?></option>
                                        <?php
                                    }else
                                    {
                                        ?>
                                        <option value="">--Select District--</option>
                                        <?php
                                    }
                                        ?>
                                </select>
                            </div>
                           <!-- <div class="col-lg-4">
                                <label for="gender">Agent Name</label>
                                <select name="agentID" id="agent" class="form-control" required>
                                    <?php
/*                                    if(!empty($agentID))
                                    {
                                        */?>
                                        <option value="<?php /*echo $agentID;*/?>"><?php /*echo $db->getData("agents","agentName","agentID",$agentID);*/?></option>
                                        <?php
/*                                    }
                                    else
                                    {
                                      */?>
                                        <option value="">--Select Agent--</option>
                                    <?php
/*                                    }

                                    $agents=$db->getRows("agents",array('where'=>array('status'=>1),'order_by agentName ASC'));
                                    foreach($agents as $sp)
                                    {
                                    $agentID=$sp['agentID'];
                                    $agentName=$sp['agentName'];
                                    echo "<option value='$agentID'>$agentName</option>";
                                    }
                                    */?>
                                </select>
                            </div>-->
                        </div>

                </fieldset>
        <br />
                    <div class="row">
                        <div class="col-lg-9"></div>
                        <div class="col-lg-3">
                            <input type="hidden" name="action_type" value="edit"/>
                            <input type="submit" name="doSubmit" value="Save & Continue" class="btn btn-success form-control" />
                        </div>
                        <!--<div class="col-lg-3">
                            <input type="submit" value="Save & Exit" class="btn btn-success form-control" />
                        </div>-->
                    </div>
                    <br />
                </div>
    </form>
    <?php }}?>
</div>
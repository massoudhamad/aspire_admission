<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<link href="css/validation.css" rel="stylesheet">
<script type="text/javascript">
$(document).ready(function(){
    $("#exam_body").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".NECTA").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".NECTA").hide();
            }
        });
    }).change();
});
</script>
<script type="text/javascript">
$(document).ready(function(){
    $("#exam_body").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".Others").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".Others").hide();
            }
        });
    }).change();
});
</script>
<script type="text/javascript">
$(document).ready(function(){
    $("#exam_body").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".NECTAO").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".NECTAO").hide();
            }
        });
    }).change();
});
</script>
<?php 
$db=new DBHelper();
$activeYear=$db->getRows("academicyears",array('where'=>array('academicYearStatus'=>1),'order_by academicYearID'));
if(!empty($activeYear))
{
    foreach($activeYear as $ayear)
    {
        $academicYear=$ayear['academicYear'];
    }
}
?>
<div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3>New Applicants Sign Up</h3>
                </div>
                
                <div class="col-lg-12">
                <?php 
                    if(!empty($_REQUEST['msg']))
                    {
                        if($_REQUEST['msg']=="unsucc") {
                          echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Your Username/Index Number already exist</strong>.
                    </div>";
                      }
                      else if($_REQUEST['msg']=="error") {
                          echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error-Something wrong happen</strong>.
                    </div>";
                      }
                    }
                ?> 
                </div>
                
                <div class="col-lg-12 ">
                    <form name="register" id="register" method="post" action="action_register.php">
                    <div class="well">
                  <fieldset>
                  <legend>Please make sure that your names are typed the way they appear in your certificates.</legend>
                 
                    <div class="row">
                        <div class="col-lg-8">
                            <label for="FirstName">O-Level Examination Body</label>
                            <select name="exam_body" id="exam_body" class="form-control">
                                <option value="NECTA" selected="">National Examination Council of Tanzania (NECTA)(From 1988-To Date)</option>
                                <option value="NECTAO">National Examination Council of Tanzania (NECTA)(Bellow 1988)</option>
                                <option value="Others">Other Examination Body</option>
                            </select>
                        </div>

                        <div class="col-lg-4">
                            <label for="MiddleName">Application Year</label>
                            
                            <input type="text" name="applicationyear" value="<?php echo $academicYear;?>" readonly=""  class="form-control" />
                        </div>
                        
                    </div>
                    <div class="row">
                        
                         <div class="col-lg-4">
                            <label for="Email">Year of First Sitting</label>
                            <select name="indexYear" class="form-control">
                                <option value="">Select Year</option>
                                <?php 
                                $year=date('Y');
                                $year1=date('Y')-40;
                                for($x=$year;$x>=$year1;$x--)
                                {
                                    echo "<option value='$x'>$x</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="NECTA"><div class="col-lg-8">
                            <label for="Physical Address">Form Four Index Number of First Sitting</label>
                            <input type="text" name="indexNumber" id="indexNumber"  class="form-control" required="required" />
                            </div></div>
                         <div class="NECTAO"><div class="col-lg-8">
                            <label for="Physical Address">Form Four Index Number of First Sitting</label>
                            <input type="text" name="indexNumberOld" id="indexNumberOld"  class="form-control" required="required" />
                            </div></div>
                        <div class="Others"><div class="col-lg-8">
                            <label for="Physical Address">Ordinary Level Index Number of First Sitting</label>
                            <input type="text" name="indexNumberOther" id="indexNumberOther"  class="form-control" required="required" />
                            </div></div>
                        
                       

                        
                    </div>
                  
                  
                  <div class="row">
                        <div class="col-lg-4">
                            <label for="FirstName">First Name</label>
                            <input type="text" name="fname" class="form-control" required="" />
                        </div>

                        <div class="col-lg-4">
                            <label for="MiddleName">Middle Name</label>
                            <input type="text" name="mname"  class="form-control" />
                        </div>
                        <div class="col-lg-4">
                            <label for="LastName">Last Name</label>
                            <input type="text" name="lname"  class="form-control" required="" />
                        </div>
                  </div>
                  
                  <div class="row">
                        <div class="col-lg-4">
                            <label for="FirstName">Gender</label>
                            <select name="gender" class="form-control" required="">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div class="col-lg-4">
                            <label for="MiddleName">Date of Birth</label>
                            <!--<input type="date" name="mname"  class="form-control" />-->
                            <div class="row">
                                <div class="col-lg-4">
                                    <select name="date" class="form-control" required="">
                                        <option value="">--Date--</option>
                                        <?php 
                                        for($x=1;$x<=31;$x++)
                                        {
                                            echo "<option value='$x'>$x</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <select name="month" class="form-control" required="">
                                        <option value="">--Month--</option>
                                        <?php                 
                                        $month=array();
							$month[1] ="January";
							$month[2] ="February";
							$month[3] ="March";
							$month[4] ="April";
							$month[5] ="May";
							$month[6] ="June";
							$month[7] ="July";
							$month[8] ="August";
							$month[9] ="September";
							$month[10] ="October";
							$month[11] ="November";
							$month[12] ="December";
                                                        
							for($i = 1; $i<=12; $i++){
                                                            echo "<option value='$i'>$month[$i]</option>";
                                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <select name="year" class="form-control" required="">
                                        <option value="">--Year--</option>
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
                        </div>
                        <div class="col-lg-4">
                            <label for="LastName">Phone Number</label>
                            <input type="text" name="phoneNumber"  class="form-control" required="" />
                        </div>
                  </div>
                        </fieldset>
                        
                   </div>
                     <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-3">
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="submit" name="doSubmit" value="Sign Up" class="btn btn-success form-control" />
                        </div>
                        <div class="col-lg-3">
                            <input type="submit" value="Cancel" class="btn btn-success form-control" />
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-6">
                            By Clicking Sign up, you agree with our <a href="">Terms of Services</a> and <a href="">Privacy Policy</a>
                        </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
  
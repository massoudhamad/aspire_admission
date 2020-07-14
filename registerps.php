            <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3>New Applicants Sign Up</h3>
                </div>
                <div class="col-lg-12 ">
                    
                    <div class="well">
                  <fieldset>
                  <legend>Please make sure that your names are typed the way they appear in your certificates.</legend>
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
                            <label for="FirstName">Email Address</label>
                            <input type="text" name="email" class="form-control" required="email" />
                        </div>

                        <div class="col-lg-4">
                            <label for="MiddleName">Nationality</label>
                            <input type="text" name="mname"  class="form-control" />
                        </div>
                        <div class="col-lg-4">
                            <label for="LastName">Passport/ID</label>
                            <input type="text" name="lname"  class="form-control" required="" />
                        </div>
                  </div>
                  
                  <div class="row">
                        <div class="col-lg-4">
                            <label for="FirstName">Gender</label>
                            <select name="" class="form-control">
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
                                    <select name="" class="form-control">
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
                                    <select name="" class="form-control">
                                        <option value="">--Month--</option>
                                        <?php                 
                                        $month=array();
							$month[0] ="January";
							$month[1] ="February";
							$month[2] ="March";
							$month[3] ="April";
							$month[4] ="May";
							$month[5] ="June";
							$month[6] ="July";
							$month[7] ="August";
							$month[8] ="September";
							$month[9] ="October";
							$month[10] ="November";
							$month[11] ="December";
                                                        
							for($i = 0; $i<12; $i++){
                                                            echo "<option value='$i+1'>$month[$i]</option>";
                                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                   <select name="" class="form-control">
                                        <option value="">--Year--</option>
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
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label for="LastName">Phone Number</label>
                            <input type="text" name="lname"  class="form-control" required="" />
                        </div>
                  </div>
                        </fieldset>
                        
                   </div>
                     <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-3">
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
                    
                </div>
            </div>
        </div>
  
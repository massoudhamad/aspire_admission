<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(150);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<script>
    function goBack() {
        window.history.back();
    }
</script>

<h1>My Profile</h1>
<hr>
<?php
$db = new DBHelper();
if(!empty($_REQUEST['msg']))
{
    if($_REQUEST['msg']=="edited")
    {
        echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
        <strong>Organization data has been edited successfully</strong>.
        </div>";
    }
}
?>
<?php

$userData = $db->getRows('organization');
if(!empty($userData)) {
    foreach ($userData as $user) {
        $orgID=$user['organizationID'];
        $orgName=$user['organizationName'];
        $orgCode=$user['organizationCode'];
        $orgRef=$user['organizationReference'];
        $orgAddress=$user['organizationAddress'];
        $orgPostal=$user['organizationPostal'];
        $orgEmail=$user['organizationEmail'];
        $orgPhone=$user['organizationPhone'];
        $orgweb=$user['organizationWebsite'];
        $orgstar=$user['starLink'];
        $orgPicture=$user['organizationPicture'];
        $student_support=$user['student_support'];
        $name1=$user['name1'];
        $title1=$user['title1'];
        $name2=$user['name2'];
        $title2=$user['title2'];
        $name3=$user['name3'];
        $title3=$user['title3'];
    }
}
else
{
    $orgID="";
    $orgName="";
    $orgCode="";
    $orgRef="";
    $orgAddress="";
    $orgPostal="";
    $orgEmail="";
    $orgPhone="";
    $orgweb="";
    $orgPicture="";
    $orgstar="";
    $student_support="";
    $name1="";
    $title1="";
    $name2="";
    $title2="";
    $name3="";
    $title3="";
}
        ?>
        <form name="" method="post" enctype="multipart/form-data" action="action_organization.php">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="courseCode">Organization Name</label>
                                        <input type="text" id="name" name="name" placeholder="Eg. HM&Y Technologies"
                                               value="<?php echo $orgName; ?>" class="form-control"
                                               required="required"/>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="email">Organization Code</label>
                                        <input type="text" id="code" name="code" placeholder="HM&Y"
                                               value="<?php echo $orgCode; ?>" class="form-control"
                                               required="required"/>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="email">Student Support Number</label>
                                        <input type="text" id="student_support" name="student_support" placeholder="0774989898,0773500230"
                                               value="<?php echo $student_support; ?>" class="form-control"
                                               required="required"/>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="email">Organization Reference Number</label>
                                        <input type="text" id="refnumber" name="refnumber" placeholder="Eg. HMY/ACAD/"
                                               value="<?php echo $orgRef; ?>"
                                               class="form-control" required="required"/>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="email">Organization Physical Address</label>
                                        <input type="text" id="physicaladdress" name="physicaladdress"
                                               placeholder="Eg. Mwanakwerekwe Zanzibar"
                                               value="<?php echo $orgAddress; ?>" class="form-control"
                                               required/>
                                    </div>
                                </div>
                                <div class="col-lg-4">

                                    <div class="form-group">
                                        <label for="email">Organization Postal Address</label>
                                        <input type="text" id="address" name="address"
                                               value="<?php echo $orgPostal; ?>"
                                               placeholder="P.O.BOX 111 Zanzibar" class="form-control"
                                               required="required"/>
                                    </div>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="email">Organization Email</label>
                                        <input type="text" id="email" name="email"
                                               placeholder="Eg.hmy@hmyetechnologies.com"
                                               value="<?php echo $orgEmail; ?>"
                                               class="form-control" required="required"/>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="email">Organization Phone</label>
                                        <input type="text" id="phone" name="phone" placeholder="Eg:+255(0)2245324224"
                                               value="<?php echo $orgPhone; ?>" class="form-control"
                                               required/>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="email">Organization Website</label>
                                        <input type="text" id="website" name="website"
                                               value="<?php echo $orgweb; ?>"
                                               placeholder="Eg:https://www.hmyetchnologies.com" class="form-control"
                                               required="required"/>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="email">Organization StAR URL</label>
                                        <input type="text" id="star_link" name="star_link"
                                               value="<?php echo $orgstar; ?>"
                                               placeholder="Eg.http://star-demo.hmytechnologies.com/star"
                                               class="form-control" required="required"/>
                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <!-- Picture -->
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="Picture">Organization Logo</label>
                            <img id="image" src="img/<?php echo $user['organizationPicture'];?>" width="150" height="150"/>
                            <input type='file' name="image" accept=".jpg,.png" onchange="readURL(this);"/>
                        </div>
                    </div>
                    <!-- Picture -->
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h3>Report Signature</h3>
                </div>
                <div class="col-lg-4">

                    <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="courseCode">Name 1</label>
                                    <input type="text" id="name1" name="name1" placeholder="Eg. Dr. Abdullah"
                                           value="<?php echo $name1; ?>" class="form-control"
                                           required="required"/>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="email">Title 1</label>
                                    <input type="text" id="title1" name="title1" placeholder="Eg. Head of Examination Unit"
                                           value="<?php echo $title1; ?>" class="form-control"
                                           required="required"/>
                                </div>
                            </div>
                        </div>

                </div>
                <div class="col-lg-4">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="courseCode">Name 2</label>
                                <input type="text" id="name2" name="name2" placeholder="Eg. Dr. Abdullah"
                                       value="<?php echo $name2; ?>" class="form-control"
                                       required="required"/>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="email">Title 2</label>
                                <input type="text" id="title2" name="title2" placeholder="Eg. DVC Academics"
                                       value="<?php echo $title2; ?>" class="form-control"
                                       required="required"/>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="courseCode">Name 3</label>
                                <input type="text" id="name3" name="name3" placeholder="Eg. Dr. Abdullah"
                                       value="<?php echo $name3; ?>" class="form-control"
                                       required="required"/>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="email">Title 3</label>
                                <input type="text" id="title3" name="title3" placeholder="Eg. Vice Chancellor"
                                       value="<?php echo $title3; ?>" class="form-control"
                                       required="required"/>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-3">
                    <?php
                    if(!empty($orgID))
                    {
                    ?>
                    <input type="hidden" name="action_type" value="edit"/>
                    <input type="hidden" name="id" value="<?php echo $user['organizationID']; ?>">
                    <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">
                <?php
                    }
                    else
                    {
                        ?>
                        <input type="hidden" name="action_type" value="add"/>
                        <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">
                    <?php
                    }
                    ?>
                </div>
                <!--<div class="col-lg-3">
                    <button onclick="goBack()" class="btn btn-danger form-control">Cancel</button>
                </div>-->
            </div>
        </form>

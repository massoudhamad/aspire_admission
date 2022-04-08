<?php
session_start();
include_once "DB.php";
$user = new DBHelper();
$error = array();
if ($user->is_loggedin() != "") {
    $user->redirect('index.php');
}

if ($_SESSION['user_session'] == true) {
    if ($_SESSION['role_session'] == 2) {
        header('Location: app/index.php');
        exit;
    } else {
        $user->redirect('index3.php');
        exit;
    }
}

if (isset($_POST["doLogin"]) == "Sign In") {

    $username = strip_tags($_POST['usr']);
    $upass = strip_tags($_POST['pwd']);
    if (!empty($_POST['usr']) && !empty($_POST['pwd'])) {
        if ($user->doLogin($username, $upass)) {
            //set the cookies for 1 day, ie, 1*24*60*60 secs
            //change it to something like 30*24*60*60 to remember user for 30 days
            setcookie('userID', $_SESSION['user_session'], time() * 1 * 24 * 60 * 60);
            setcookie('role_session', $_SESSION['role_session'], time() * 1 * 24 * 60 * 60);
            if ($_SESSION['role_session'] == 2) {
                $user->redirect('app/index.php');
            } else {
                $user->redirect('index3.php');
            }
        } else {
            $error[] = "Invalid Username and/or Password !";
        }
    } else {

        $error[] = "Username and Password cannot be empty!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online University Admission System</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/form-elements.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script src="js/jquery-1.4.2.min.js"></script>
    <link href="css/validation.css" rel="stylesheet">

    <!-- <script type="text/javascript">
            $(document).ready(function(){});
        </script> -->

    <script type="text/javascript">
        $(document).ready(function() {
            $("#admission_level").change(function() {
                $(this).find("option:selected").each(function() {
                    var optionValue = $(this).attr("value");
                    if (optionValue == "UG") {
                        $(".UG").not("." + optionValue).hide();
                        $("." + optionValue).show();
                        $(".PG").hide();
                    } else if (optionValue == "PG") {
                        $(".PG").not("." + optionValue).hide();
                        $("." + optionValue).show();
                        $(".UG").hide();
                    } else {
                        $(".PG").hide();
                    }
                });
            }).change();
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#exam_body").change(function() {
                $(this).find("option:selected").each(function() {
                    var optionValue = $(this).attr("value");
                    if (optionValue == "NECTA") {
                        $(".NECTA").not("." + optionValue).hide();
                        $("." + optionValue).show();
                        $(".Others").hide();
                    } else if (optionValue == "Others") {
                        $(".Others").not("." + optionValue).hide();
                        $("." + optionValue).show();
                        $(".NECTA").hide();
                    } else if (optionValue == "NECTAO") {
                        $(".Others").not("." + optionValue).hide();
                        $("." + optionValue).show();
                        $(".NECTA").hide();
                    } else {
                        $(".Others").hide();
                    }
                });
            }).change();
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#exam_body").change(function() {
                $(this).find("option:selected").each(function() {
                    var optionValue = $(this).attr("value");
                    if (optionValue) {
                        $(".Others").not("." + optionValue).hide();
                        $("." + optionValue).show();
                    } else {
                        $(".Others").hide();
                    }
                });
            }).change();
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#exam_body").change(function() {
                $(this).find("option:selected").each(function() {
                    var optionValue = $(this).attr("value");
                    if (optionValue) {
                        $(".NECTAO").not("." + optionValue).hide();
                        $("." + optionValue).show();
                    } else {
                        $(".NECTAO").hide();
                    }
                });
            }).change();
        });
    </script>
</head>

<body>
    <?php
    $org = $user->getRows("organization");
    if (!empty($org)) {
        foreach ($org as $og) {
            $orgName = $og['organizationName'];
            $orgPicture = $og['organizationPicture'];
            $orgPhone = $og['organizationPhone'];
            $studentSupport = $og['student_support'];
        }
    }

    /* $activeYear=$user->getRows("academicyears",array('where'=>array('academicYearStatus'=>1),'order_by academicYearID'));
if(!empty($activeYear))
{
    foreach($activeYear as $ayear)
    {
        $academicYear=$ayear['academicYear'];
        $academicYearID=$ayear['academicYearID'];
    }
}
$today=date('Y-m-d');
$activeInTake=$user->getRows("admission_setting",array('where'=>array('academicYearID'=>$academicYearID,'yearStatus'=>1),'order_by academicYearID'));
if(!empty($activeInTake)) {
    foreach ($activeInTake as $intake) {
        $admissionID = $intake['admissionID'];
        $admissionInTakeID = $intake['admissionInTakeID'];
        $endDate=$intake['endDate'];
    }
}

    $admInTake = $user->getRows('admission_intake',array('where'=>array('admissionInTakeID'=>$admissionInTakeID),' order_by'=>' admissionInTakeID ASC'));
    if(!empty($admInTake))
    {
        foreach($admInTake as $adintake)
        {
            $admissionInTake=$adintake['admissionInTake'];
        }
    } */
    $today = date('Y-m-d');
    $admissionSetting = $user->getAdmissionSetting();
    if (!empty($admissionSetting)) {
        foreach ($admissionSetting as $admin) {
            $academicYear = $admin['academicYear'];
            $academicYearID = $admin['academicYearID'];
            $admissionID = $admin['admissionID'];
            $admissionName = $admin['admissionName'];
            $admissionRound = $admin['admissionRound'];
            $endDate = $admin['endDate'];
        }
    } else {
        $academicYear = "";
        $academicYearID = "";
        $admissionID = "";
        $admissionName = "";
        $admissionRound = "";
        $endDate = "";
    }
    ?>

    <!-- Top content -->
    <div class="row">
        <div class="col-sm-12 col-sm-offset-0 text">
            <h1 style="color: white; font-weight: bold; font-size: 48px"><?php
                                                                            echo $orgName;
                                                                            ?></h1>
            <hr border-color="LightSlateGrey">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text">
            <h2><strong>
                    <font color="white">Online Admission System</font>
                </strong></h2>
        </div>
    </div>

    <div class="top-content">

        <div class="inner-bg">
            <div class="container">

                <div class="row">
                    <div class="col-sm-4">

                        <div class="form-box">
                            <div class="form-top">
                                <div class="form-top-left">
                                    <h3>Registered User Login</h3>
                                </div>
                                <div class="form-top-right">
                                    <i class="fa fa-lock"></i>
                                </div>
                            </div>
                            <?php
                            echo "<div class='col-lg-12'>";
                            if (!empty($error)) {
                                foreach ($error as $e) {
                                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                                            <strong>$e<br></strong>
                                        </div>";
                                }
                            }
                            echo '</div>';
                            ?>
                            <div class="form-bottom">
                                <form role="form" action="" method="post" class="login-form">
                                    <div class="form-group">
                                        <label class="sr-only" for="form-username">Username</label>
                                        <input type="text" name="usr" placeholder="Username... e.g. S001/001/2010" class="form-username form-control" id="usr">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="form-password">Password</label>
                                        <input type="password" name="pwd" placeholder="Password..." class="form-password form-control" id="pwd">
                                    </div>
                                    <!-- 				                        <input type="submit" name="doLogin" class="form-control btn btn-default btn-success input-lg" value="Sign In!"> -->
                                    <button type="submit" class="btn" name="doLogin">Sign in</button>
                                </form>
                                <a href="#">Forgot Password?</a>
                            </div>
                        </div>


                    </div>


                    <div class="col-sm-8">
                        <?php
                        if ($today <= $endDate) {
                        ?>
                            <div class="form-box">
                                <div class="form-top">
                                    <div class="form-top-left">
                                        <h3>Registration for new applicants for <?php echo $admissionName; ?>-<?php echo $admissionRound; ?>-<span class="text-danger">Deadline is <?php echo date('d-m-Y', strtotime($endDate)); ?></span></h3>
                                        <p>To register please fill in the form below:</p>
                                    </div>
                                    <div class="form-top-right">
                                        <i class="fa fa-pencil"></i>
                                    </div>
                                    <div class="col-lg-12">
                                        <div id="result">
                                            <?php
                                            if (!empty($_REQUEST['msg'])) {
                                                if ($_REQUEST['msg'] == "index") {
                                                    echo "<div class='alert alert-danger fade in'><a href='index.php' class='close' data-dismiss='alert'>&times;</a>
                                            <strong>Invalid Index Number</strong>.
                                        </div>";
                                                }

                                                if ($_REQUEST['msg'] == "details") {
                                                    echo "<div class='alert alert-danger fade in'><a href='index.php' class='close' data-dismiss='alert'>&times;</a>
                                            <strong>Sorry,Your Index Number and Personal Details does not match</strong>.
                                        </div>";
                                                }

                                                if ($_REQUEST['msg'] == "emailexists") {
                                                    echo "<div class='alert alert-danger fade in'><a href='index.php' class='close' data-dismiss='alert'>&times;</a>
                                            <strong>Sorry, Your Email exist</strong>.
                                        </div>";
                                                }

                                                if ($_REQUEST['msg'] == "exists") {
                                                    echo "<div class='alert alert-danger fade in'><a href='index.php' class='close' data-dismiss='alert'>&times;</a>
                                            <strong>Sorry, Your Index Number already exist</strong>.
                                        </div>";
                                                } else if ($_REQUEST['msg'] == "error") {
                                                    echo "<div class='alert alert-danger fade in'><a href='index.php' class='close' data-dismiss='alert'>&times;</a>
                                            <strong>Sorry, Error-Something wrong happen,Contact System Administrator</strong>.
                                        </div>";
                                                } else if ($_REQUEST['msg'] == "succ") {
                                                    echo "<div class='alert alert-success fade in'><a href='index.php' class='close' data-dismiss='alert'>&times;</a>
                                            <strong>Congratulations,Your account has been created successfully, Please login to your email to see your username and password</strong>.
                                        </div>";
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-bottom">
                                    <form name="register" id="register" method="post" action="confirm_profile.php">
                                        <div class="row">

                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <select name="admission_level" id="admission_level" class="form-control selectpicker" required>
                                                        <option value="">What Level are you applying?</option>
                                                        <?php
                                                        $admissionLevel = $user->getRows('programme_level', array('where' => array('status' => 1), 'order_by' => 'programmeLevelCode ASC'));
                                                        if (!empty($admissionLevel)) {
                                                            foreach ($admissionLevel as $alevel) {
                                                                $plevelCode = $alevel['programmeLevelCode'];
                                                                $plevel = $alevel['programmeLevel'];
                                                        ?>
                                                                <option value="<?php echo $plevelCode; ?>"><?php echo $plevel; ?></option>
                                                        <?php }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                   <!--  <div class="UG"> -->
                                            <div class="row">


                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <select name="exam_body" id="exam_body" class="form-control selectpicker">
                                                            <option value="">Choose Examination Authority for your first sitting at O-Level (FIV)</option>
                                                            <option value="NECTA">National Examination Council of Tanzania (NECTA) 1987- To Date</option>
                                                            <option value="Others">CSEE Before 1987/GCE/Foreign Examination Authority</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="NECTA">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label class="sr-only" for="form-index-number">Index Number</label>
                                                            <input type="text" name="indexNumber" id="indexNumber" placeholder="Form Four Index Number... Eg.S0001/0001/2000" class="form-index-number form-control" required>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="Others">

                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="sr-only" for="equivalence-index-number">Equivalent Number</label>
                                                            <input type="text" name="equivalence_number" id="equivalence_number" placeholder="Equivalence Number...EQ0000000" class="equivalence-index-number form-control" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="sr-only" for="equivalence-index-number">Examination Year</label>
                                                            <input type="text" name="exam_year" id="exam_year" placeholder="Equivalence Number.. YYYY" class="equivalence-index-number form-control" required>
                                                        </div>
                                                    </div>



                                                    <!-- <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-first-name">First name</label>
                                                    <input type="text" name="fname" id="fname" placeholder="First name..." class="form-first-name form-control" required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-middle-name">Middle name</label>
                                                    <input type="text" name="mname" id="mname" placeholder="Middle name..." class="form-middle-name form-control">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-last-name">Last name</label>
                                                    <input type="text" name="lname" id="lname" placeholder="Last name..." class="form-last-name form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="input-group">
											<span class="input-group-addon"><span>
											<select name="gender" id="gender" class="form-control selectpicker" required>
												<option value="">Please select you Gender</option>
												<option>Male</option>
												<option>Female</option>
											</select>
                                                </div>
                                            </div>-->

                                                </div>
                                            </div>
                                       <!--  </div> -->

                                        <!-- Postgraduate -->
                                       <!--  <div class="PG">
                                            <div class="row">

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="sr-only" for="form-first-name">First name</label>
                                                        <input type="text" name="pfname" id="fname" placeholder="First name..." class="form-first-name form-control" required>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="sr-only" for="form-middle-name">Middle name</label>
                                                        <input type="text" name="pmname" id="mname" placeholder="Middle name..." class="form-middle-name form-control">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="sr-only" for="form-last-name">Last name</label>
                                                        <input type="text" name="plname" id="lname" placeholder="Last name..." class="form-last-name form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <select name="pgender" id="gender" class="form-control" required>
                                                        <option value="">Please select you Gender</option>
                                                        <option>Male</option>
                                                        <option>Female</option>
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
 -->

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-telephone">Telephone</label>
                                                    <input type="text" name="phoneNumber" id="phoneNumber" placeholder="Telephone No. Eg. 255777020304" class="form-telephone form-control" required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-telephone">Email Address</label>
                                                    <input type="text" name="email" placeholder="Valid email address..." class="form-control required email">
                                                </div>
                                            </div>

                                            <!-- <div class="Others">
                                                    <div class="form-group">
                                                        <label class="sr-only" for="form-telephone">Email Address</label><input type="text" name="emailad" placeholder="Valid email address..." class="form-control required email">
                                                    </div>
                                                </div> -->

                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <input type="hidden" name="action_type" value="confirm" />
                                                <!--<input type="submit" class="btn form-control" name="doSubmit" value="Register">-->
                                                <button type="submit" class="btn">Register</button>
                                            </div>
                                        </div>
                                        <p>
                                            <font color="BurlyWood">By clicking register and signing up to the admission system, you declare that all the information provided is correct and you agree that provision of false or incorrect information might result into rejection and/or legal actions. You also agree to our terms and conditions.</font>
                                        </p>

                                    </form>
                                </div>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="form-bottom">
                                <form role="form" action="" method="post" class="registration-form">

                                    <div class="jumbotron">
                                        <h2>Registration Closed!</h2>
                                        <p>The system has been closed for new applications. If you are already registered please login to check your admission status/results.</p>
                                    </div>

                                </form>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade bs-example-modal-sm" id="myPleaseWait" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            <span class="glyphicon glyphicon-time">
                            </span>Please wait...page is loading
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="progress">
                            <div class="progress-bar progress-bar-info progress-bar-striped active" style="width: 100%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Javascript -->
    <script src="assets/js/jquery-1.11.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.backstretch.min.js"></script>
    <script src="assets/js/scripts.js"></script>


    <script src="assets/js/jquery.validate.js"></script>
    <script src="assets/js/validation.js"></script>
    <script src="assets/js/jquery.mask.min.js"></script>

    <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->
    <p>
        <font color="white"><strong> Programmes| How to apply | FAQ | Support: <?php echo $studentSupport; ?></strong></font>
    </p>
</body>
<footer class="main-footer">
    <hr />
    <p>Aspire UAS. This product is licensed to the <?php echo $orgName; ?> | <strong>&copy;2014-<?php echo date('Y'); ?> <a href="http://www.hmytechnologies.com" target="_blank">HM&Y Technologies</a></strong></p>
</footer>

</html>
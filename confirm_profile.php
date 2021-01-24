<?php
session_start();
include_once "DB.php";
$db = new DBHelper();
$error = array();
$org = $db->getRows("organization");
if (!empty($org)) {
    foreach ($org as $og) {
        $orgName = $og['organizationName'];
        $orgPicture = $og['organizationPicture'];
        $orgPhone = $og['organizationPhone'];
    }
}


$admissionSetting = $db->getAdmissionSetting();
if (!empty($admissionSetting)) {
    foreach ($admissionSetting as $admin) {
        $academicYear = $admin['academicYear'];
        $academicYearID = $admin['academicYearID'];
        $admissionID = $admin['admissionID'];
        $admissionName = $admin['admissionName'];
        $admissionRound = $admin['admissionRound'];
        $endDate = $admin['endDate'];
    }
}

if (isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
    if ($_REQUEST['action_type'] == 'confirm') {
        $academicYear = $db->getData("academicyears", "academicYear", "academicYearStatus", 1);
        $year = explode("/", $academicYear);
        $year1 = $year[0];
        $year1Sub = substr((string)$year1, 2, 3); //17
        $year2 = $year[1];
        $year2Sub = substr((string)$year2, 2, 3);
        $applicationNumber = $year1Sub . $year2Sub . rand(1, 99999);
        if ($db->isFieldExist('applicants', 'applicationNumber', $applicationNumber))
            $applicationNumber = $year1Sub . $year2Sub . rand(1, 99999);
        else
            $applicationNumber = $applicationNumber;

        $admission_level = $_POST['admission_level'];
        $applicationYearID = $db->getData("academicyears", "academicYearID", "academicYearStatus", 1);
        $admissionID = $db->getData("admission_setting", "admissionID", "yearStatus", 1);
        $boolStatus = false;
        if ($admission_level == "UG") {
            if ($db->isFieldExist('users', 'userName', $_POST['indexNumber'])) {
                $boolStatus = false;
                $msg = "exists";
            } else if ($db->isFieldExist('users', 'email', $_POST['email'])) {
                $boolStatus = false;
                $msg = "emailexists";
            } else {
                $exam_body = $_POST['exam_body'];
                if ($exam_body == "NECTA") {
                    $api_token = $db->getAPI("NECTA", "token");
                    if (!empty($api_token)) {
                        foreach ($api_token as $api) {
                            $apitToken = $api['token'];
                        }
                    }
                    $token = $db->getAPIToken($apitToken);
                    $indexNumber = strtoupper($_POST['indexNumber']);
                    $indexNumber2 = explode("/", $indexNumber);
                    $center = $indexNumber2[0];
                    $number = $indexNumber2[1];
                    $year = $indexNumber2[2];

                    $number_kituo = $center . "-" . $number;
                    $exam_id = 1;
                    $exam_year = $year;
                    $apiNumber = $number_kituo . "/" . $exam_id . "/" . $exam_year;
                    $index_number = $center . "/" . $number;
                    $url = "https://api.necta.go.tz/api/public/particulars/" . $apiNumber . "/" . $token;
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_HTTPGET, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $response_json = curl_exec($ch);
                    curl_close($ch);
                    $data = json_decode($response_json, true);

                    if ($data['status']['code'] == 1) {
                        $fname = $data['particulars']['first_name'];
                        $mname = $data['particulars']['middle_name'];
                        $lname = $data['particulars']['last_name'];
                        $gender = $data['particulars']['sex'];

                        if ($gender == "M") {
                            $gender = "Male";
                        } else {
                            $gender = "Female";
                        }
                        $phoneNumber = $_POST['phoneNumber'];
                        $email = $_POST['email'];
                        $indexYear = $exam_year;
                        $boolStatus = true;
                    } else {
                        $boolStatus = false;
                        $msg = "apierror";
                    }
                } elseif ($exam_body == "Others") {
                    /* $indexNumber = $_POST['indexNumberOther'];
                        $fname = strtoupper($_POST['fname']);
                        $mname = strtoupper($_POST['mname']);
                        $lname = strtoupper($_POST['lname']);
                        $gender = $_POST['gender'];
                        $phoneNumber = $_POST['phoneNumber'];
                        $email = $_POST['emailad'];
                        $equivalence_number = $_POST['equivalence_number'];
                        $index_number = $indexNumber;
                        $boolStatus = true; */
                    $api_token = $db->getAPI("NECTA", "token");
                    if (!empty($api_token)) {
                        foreach ($api_token as $api) {
                            $apitToken = $api['token'];
                        }
                    }
                    $token = $db->getAPIToken($apitToken);
                    $equivalence_number = $_POST['equivalence_number'];
                    $exam_id = 1;
                    $exam_year = $_POST['exam_year'];
                    $apiNumber = $equivalence_number . "/" . $exam_id . "/" . $exam_year;
                    $indexNumber = $equivalence_number . "/" . $exam_year;
                    $url = "https://api.necta.go.tz/api/public/particulars/" . $apiNumber . "/" . $token;
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_HTTPGET, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $response_json = curl_exec($ch);
                    curl_close($ch);
                    $data = json_decode($response_json, true);


                    if ($data['status']['code'] == 1) {
                        $fname = $data['particulars']['first_name'];
                        $mname = $data['particulars']['middle_name'];
                        $lname = $data['particulars']['last_name'];
                        $gender = $data['particulars']['sex'];

                        if ($gender == "M") {
                            $gender = "Male";
                        } else {
                            $gender = "Female";
                        }
                        $phoneNumber = $_POST['phoneNumber'];
                        $email = $_POST['email'];
                        $indexYear = $exam_year;
                        $boolStatus = true;
                    } else {
                        $boolStatus = false;
                        $msg = "apierror";
                    }
                }
            }
        } else if ($admission_level == "PG") {
            //Admission for PHD
            $fname = strtoupper($_POST['pfname']);
            $mname = strtoupper($_POST['pmname']);
            $lname = strtoupper($_POST['plname']);
            $gender = $_POST['pgender'];
            $phoneNumber = $_POST['phoneNumber'];
            $email = $_POST['email'];
            if ($db->isFieldExist('users', 'email', $_POST['email'])) {
                $boolStatus = false;
                $msg = "emailexists";
            } else {
                $boolStatus = true;
            }
        }
    }
}

if ($boolStatus == false) {
        header("Location:index.php?msg=$msg");
    }else 
    {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online University Admission System</title>

    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/form-elements.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script src="js/jquery-1.4.2.min.js"></script>
    <link href="css/validation.css" rel="stylesheet">
</head>

<body>

    <!-- Top content -->
    <div class="row">
        <div class="col-sm-12 col-sm-offset-0 text">
            <h1 style="color: white; font-weight: bold; font-size: 48px"><?php echo $orgName; ?></h1>
            <hr border-color="LightSlateGrey">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text">
            <h2><strong>
                    <font color="white">Online University Admission System</font>
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

                                    <button type="submit" class="btn" name="doLogin">Sign in</button>
                                </form>
                                <a href="#">Forgot Password?</a>
                            </div>
                        </div>


                    </div>


                    <div class="col-sm-8">

                        <div class="form-box">
                            <div class="form-top">
                                <div class="form-top-left">
                                    <h4>Registration for new applicants for <?php echo $admissionName; ?>-<?php echo $admissionRound; ?></h4>
                                    <p style="color: black; background-color:peachpuff">Please confirm the following details,if you feel is not yours, please exit application and start new application</p>
                                </div>
                            </div>
                            <div class="form-bottom">
                                <form name="" id="" method="post" action="action_confirm_register.php">
                                    <div class="row">

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span>
                                                            <select name="admission_level" id="admission_level" class="form-control selectpicker" required readonly>
                                                                <option value="<?php echo $admission_level; ?>"><?php echo "Admission Level-" . $admission_level; ?></option>
                                                            </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!--<div class="UG">-->
                                    <?php if ($admission_level == "UG") { ?>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span>
                                                                <select name="exam_body" id="exam_body" class="form-control selectpicker" readonly>
                                                                    <option value="<?php echo $exam_body; ?>"><?php echo "Examination Authority- " . $exam_body; ?></option>
                                                                </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-index-number">Index Number</label>
                                                    <input type="text" name="indexNumber" value="<?php echo $indexNumber; ?>" class="form-index-number form-control" readonly>
                                                </div>
                                            </div>

                                            <input type="hidden" hidden name="indexYear" value="<?php echo $indexYear; ?>">

                                            <!-- <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-index-number">Equivalence Number</label>
                                                    <input type="text" name="equivalence_number" value="<?php //echo $equivalence_number; 
                                                                                                        ?>" class="form-control" readonly>
                                                </div>
                                            </div> -->

                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-first-name">First name</label>
                                                    <input type="text" name="fname" id="fname" value="<?php echo $fname; ?>" class="form-first-name form-control" required readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-middle-name">Middle name</label>
                                                    <input type="text" name="mname" id="mname" value="<?php echo $mname; ?>" class="form-middle-name form-control" readonly>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-last-name">Last name</label>
                                                    <input type="text" name="lname" id="lname" value="<?php echo $lname; ?>" class="form-last-name form-control" required readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span>
                                                            <select name="gender" id="gender" class="form-control selectpicker" required readonly>
                                                                <option value="<?php echo $gender; ?>"><?php echo $gender; ?></option>

                                                            </select>
                                                </div>
                                            </div>

                                        </div>



                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-telephone">Telephone</label>
                                                    <input type="text" name="phoneNumber" id="phoneNumber" value="<?php echo $phoneNumber; ?>" class="form-telephone form-control" required readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-telephone">Email Address</label>
                                                    <input type="text" name="email" value="<?php echo $email; ?>" class="form-email form-control" readonly>
                                                </div>
                                            </div>



                                        </div>

                                    <?php } else if ($admission_level == "PG") {
                                    ?>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-first-name">First name</label>
                                                    <input type="text" name="fname" id="fname" value="<?php echo $fname; ?>" class="form-first-name form-control" required readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-middle-name">Middle name</label>
                                                    <input type="text" name="mname" id="mname" value="<?php echo $mname; ?>" class="form-middle-name form-control" readonly>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-last-name">Last name</label>
                                                    <input type="text" name="lname" id="lname" value="<?php echo $lname; ?>" class="form-last-name form-control" required readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span>
                                                            <select name="gender" id="gender" class="form-control selectpicker" required readonly>
                                                                <option value="<?php echo $gender; ?>"><?php echo $gender; ?></option>

                                                            </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-telephone">Telephone</label>
                                                    <input type="text" name="phoneNumber" id="phoneNumber" value="<?php echo $phoneNumber; ?>" class="form-telephone form-control" required readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="sr-only" for="form-telephone">Email Address</label>
                                                    <input type="text" name="email" value="<?php echo $email; ?>" class="form-email form-control" readonly>
                                                </div>
                                            </div>
                                        </div>

                                    <?php
                                    }
                                    ?>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="hidden" name="action_type" value="proceed" />
                                            <input type="hidden" name="applicationNumber" value="<?php echo $applicationNumber; ?>">
                                            <input type="hidden" name="admissionID" value="<?php echo $admissionID; ?>">
                                            <input type="hidden" name="admissionRound" value="<?php echo $admissionRound; ?>">
                                            <input type="hidden" name="applicationYearID" value="<?php echo $applicationYearID; ?>">

                                            <?php
                                            if ((!empty($fname)) || (!empty($lname))) {
                                            ?>
                                                <input type="submit" name="doProceed" value="Proceed to Application" class="btn btn-success  form-control" />
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="hidden" name="action_type" value="exit" />
                                            <a href='index.php' class="btn btn-danger form-control">Exit Application</a>
                                        </div>

                                    </div>

                                </form>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>


</html>
<?php }
?>
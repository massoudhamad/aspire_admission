<?php 
  require_once("../session.php");
  require_once("../DB.php");

 

  $db = new DBHelper();
  $userID = $_SESSION['user_session'];
  if($userID=="")
  {
      header("Location:logout.php?logout=true");
      exit;
  }
  else {
          $userRoleID = $db->getData("userroles", "roleID", "userID", $userID);
          if ($userRoleID == 2) {
              $applicantsData = $db->getRows('applicants', array('where' => array('userID' => $userID), 'order_by' => 'applicantID ASC'));
              if (!empty($applicantsData)) {
                  foreach ($applicantsData as $apps) {
                      $applicantID = $apps['applicantID'];
                      $_SESSION['applicantID'] = $applicantID;
                      $fname = $apps['firstName'];
                      $mname = $apps['middleName'];
                      $lname = $apps['lastName'];
                      $admissionLevel = $apps['admissionLevel'];
                      $admissionNumber = $apps['admissionNumber'];
                      $eauthority=$apps['eauthority'];
                      $_SESSION['admissionLevel'] = $admissionLevel;
                      $_SESSION['admissionNumber'] = $admissionNumber;
                      $_SESSION['eauthority']=$eauthority;
                      $name = "$fname $lname";
                  }
              }
              $userData = $db->getRows('users', array('where' => array('userID' => $userID), 'order_by' => 'userID ASC'));
              if (!empty($userData)) {
                  foreach ($userData as $apps) {
                      $fname = $apps['firstName'];
                      $mname = $apps['middleName'];
                      $lname = $apps['lastName'];
                      $userName = $apps['userName'];
                  }
              }
          }

                $organization = $db->getRows('organization', array('order_by' => 'organizationName DESC'));
                if (!empty($organization)) {
                    foreach ($organization as $org) {
                        $organizationName = $org['organizationName'];
                        $organizationCode = $org['organizationCode'];
                        $organizationPicture = "../img/" . $org['organizationPicture'];
                        $studentSupport = $org['student_support'];
                        $orgCode=$org['organizationCode'];
                        $_SESSION['orgCode']=$orgCode;
                    }
                } else {
                    $organizationName = "Imperial College of Health and Allied Sciences";
                    $organizationCode = "ICHAS";
                    $organizationPicture = "../img/ichas-logo.png";
                }

                $activeYear = $db->getRows("academicyears", array('where' => array('academicYearStatus' => 1), 'order_by academicYearID'));
                if (!empty($activeYear)) {
                    foreach ($activeYear as $ayear) {
                        $academicYear = $ayear['academicYear'];
                        $academicYearID = $ayear['academicYearID'];
                    }
                }
                $today = date('Y-m-d');
                $activeInTake = $db->getRows("admission_setting", array('where' => array('academicYearID' => $academicYearID, 'yearStatus' => 1), 'order_by academicYearID'));
                if (!empty($activeInTake)) {
                    foreach ($activeInTake as $intake) {
                        $admissionID = $intake['admissionID'];
                        $admissionInTakeID = $intake['admissionInTakeID'];
                        $endDate = $intake['endDate'];
                    }
                }

          ?>
          <!DOCTYPE html>
          <html>
          <head>
              <meta charset="utf-8">
              <meta charset="utf-8">
              <meta http-equiv="X-UA-Compatible" content="IE=edge">
              <meta name="viewport" content="width=device-width, initial-scale=1">
              <script type="js/jquery-1.12.3.js"></script>
              <script src="js/jquery-2.1.4.min.js"></script>
              <!-- CSS-->
              <link rel="stylesheet" type="text/css" href="css/main.css">
              <!-- Font-icon css-->
              <!--    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
              -->
              <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css">
              <link rel="stylesheet" href="css/chosen.css">
              <link rel="stylesheet" href="css/chosen.min.css">
              <link rel="stylesheet" href="css/bootstrap-chosen.css">
              <link rel="stylesheet" href="../assets/css/ichas-brand.css?v=<?php echo @filemtime(__DIR__ . '/../assets/css/ichas-brand.css'); ?>">

              <title>ICHAS Admission Portal</title>
          </head>
          <body class="sidebar-mini fixed">
          <div class="wrapper">
              <!-- Navbar-->
              <header class="main-header hidden-print"><a class="logo" href="#"><?php echo $orgCode;?> Admission System<!--<img src="../assets/img/<?php /*echo $db->getOrganizationValue("organizationPicture"); */?>" width="50" height="50">--></a>
                  <nav class="navbar navbar-static-top">
                      <!-- Sidebar toggle button--><a class="sidebar-toggle" href="#" data-toggle="offcanvas"></a>
                      <!-- Navbar Right Menu-->
                      <div class="navbar-custom-menu">
                         <!-- <div class="row">
                              <div class="titles"><?php /*echo $db->getOrganizationValue("organizationName"); */?></div>
                              <div class="sub_titles">Logged in as <?php /*echo $name; */?></div>
                              <div class="sub_titles">Today's Date:<?php /*echo date('d-m-Y');*/?></div>

                              <div class="sub_titles"><span style="font-weight: bold">Close Date:18-09-2018</span></div>


                          </div>-->
                          <ul class="top-nav">
                              <li class="dropdown notification-menu"><a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-question-circle fa-lg"></i></a>
                                  <ul class="dropdown-menu">
                                      <li class="not-head"><?php echo $organizationName;?></li>
                                      <li><a class="media" href="javascript:;"><span class="media-left media-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-primary"></i><i class="fa fa-envelope fa-stack-1x fa-inverse"></i></span></span>
                                              <div class="media-body"><span class="block">Today's Date: <?php echo date('d-m-Y');?></span></div></a></li>
                                      <li><a class="media" href="javascript:;"><span class="media-left media-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-danger"></i><i class="fa fa-hdd-o fa-stack-1x fa-inverse"></i></span></span>
                                              <div class="media-body"><span class="block">Close Date: <?php echo $endDate;?> </span><span class="text-muted block"></span></div></a></li>
                                     <!-- <li><a class="media" href="javascript:;"><span class="media-left media-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-success"></i><i class="fa fa-money fa-stack-1x fa-inverse"></i></span></span>
                                              <div class="media-body"><span class="block">Transaction xyz complete</span><span class="text-muted block">2min ago</span></div></a></li>-->
                                  </ul>
                              </li>

                              <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user fa-lg"></i></a>
                                  <ul class="dropdown-menu settings-menu">
                                      <li><a href=""><i class="fa fa-user fa-lg"></i> <?php echo $name;?></a></li>
                                      <li><a href=""><i class="fa fa-user"></i> <?php echo $userName;?></a></li>
                                      <li><a href="logout.php?logout=true"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
                                  </ul>
                              </li>
                          </ul>
                      </div>
                  </nav>
              </header>
              <!-- Side-Nav-->
              <aside class="main-sidebar hidden-print">
                  <?php include 'menu.php'; ?>
              </aside>
              <div class="content-wrapper">
                  <div class="container-fluid" style="padding-top:14px;">
                      <?php include __DIR__ . '/_progress.php'; ?>
                  </div>
                  <?php include 'main_index.php'; ?>
                  <div class="modal fade bs-example-modal-sm" id="myPleaseWait" tabindex="-1" role="dialog"
                       aria-hidden="true" data-backdrop="static">
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
                                      <div class="progress-bar progress-bar-info progress-bar-striped active"
                                           style="width: 100%">
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <!-- Javascripts-->
          <script src="js/jquery.min.js"></script>
          <script src="js/jquery-2.1.4.min.js"></script>
          <script src="js/bootstrap.min.js"></script>
          <script src="js/plugins/pace.min.js"></script>
          <script src="js/main.js"></script>
          <script src="js/bootbox.min.js"></script>
          </body>
          </html>
          <?php
  }
  ?>

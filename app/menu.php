  <?php
  if (session_status() === PHP_SESSION_NONE) session_start();
  $page=$_REQUEST['sz'];
  if($page=="education_background"||$page=="olevel" || $page=="confirm_ordinary_results" || $page=="ordinary_results"|| $page=="alevel"|| $page=="other_ordinary")
      $estatus="active";
  else if($page=="pg_education")
      $pgstatus="active";
  else if($page=="programmechoice")
      $pstatus="active";
  else if($page=="personalinfo")
      $pestatus="active";
  else if($page=="changepwd")
      $chstatus="active";
  else if($page=="working")
      $wstatus="active";
  else if($page=="referees")
      $rstatus="active";
  else if($page=="payments")
      $paymentstatus="active";
  else if($page=="attachment")
      $attachmentstatus="active";
  else if($page=="attachment")
      $attachmentstatus="active";
  else if($page=="submit")
      $submitstatus="active";
  else
      $wpstatus="active";


  ?>
  <section class="sidebar">
          <!-- Sidebar Menu-->
          <ul class="sidebar-menu">
              <?php
              $db=new DBHelper();
              $userID=$_SESSION['user_session'];
              $login = $db->getData("users", "login", "userID", $userID);
                  ?>
                  <li class="<?php echo $wpstatus; ?>"><a href="index.php"><i class="fa fa-dashboard"></i><span>Welcome Page</span></a>
                  </li>

                  </li>
                  <!--            <li class="<?php /*echo $cstatus;*/
                  ?>"><a href="index.php?sz=level"><i class="fa fa-th-list"></i><span>Choose Study Level</span><i class="fa fa-angle-right"></i></a>
-->
                  <?php
                  if($login==1) {
                      if($_SESSION['remarkID']==7 || $_SESSION['remarkID']==5 || $_SESSION['remarkID']==1) {
                          /* ICHAS uses the same NECTA/NACTE-driven Educational
                             Background page for every admissionLevel. The old PG
                             branch pointed to pg_education.php (University-style
                             CGPA form) which does not apply to ICHAS applicants. */
                          ?>
                          <li class="<?php echo $estatus; ?>"><a href="index.php?sz=education_background"><i
                                          class="fa fa-graduation-cap"></i><span>Educational Background</span><i
                                          class="fa fa-angle-right"></i></a></li>
                          <?php
                          ?>

                          <li class="<?php echo $pstatus; ?>"><a href="index.php?sz=programmechoice"><i
                                          class="fa fa-file-text"></i><span>Study Plan</span><i
                                          class="fa fa-angle-right"></i></a>

                          </li>
                          <?php
                      }
                          ?>

                  <li class="<?php echo $pestatus; ?>"><a href="index.php?sz=personalinfo"><i
                                  class="fa fa-user"></i><span>Personal information</span><i
                                  class="fa fa-angle-right"></i></a>

                  </li>
                  <?php
                  if ($_SESSION['admissionLevel'] == "PG") {
                      ?>
                      <li class="<?php echo $wstatus; ?>"><a href="index.php?sz=working"><i class="fa fa-briefcase"></i><span>Working Experience</span><i
                                      class="fa fa-angle-right"></i></a>

                      </li>
                      <?php
                  }
                  ?>

                  <?php
                  if ($_SESSION['admissionLevel'] == "PG") {
                      ?>
                      <li class="<?php echo $rstatus; ?>"><a href="index.php?sz=referees"><i
                                      class="fa fa-university"></i><span>Referees</span><i
                                      class="fa fa-angle-right"></i></a>

                      </li>
                      <?php
                  }
                  ?>

              <?php
                      /*if($db->checkApplicantStudyLevel($_SESSION['applicantID'])!=1) {

                          */?><!--
                          <li class="<?php /*echo $paymentstatus; */?>"><a href="index.php?sz=payments"><i
                                          class="fa fa-cc-visa"></i><span>Application Payment</span><i
                                          class="fa fa-angle-right"></i></a></li>
                          --><?php
/*                     }
                  */?>

                  <?php
                 if ($_SESSION['admissionLevel'] == "PG") {
                  if($_SESSION['remarkID']==7 || $_SESSION['remarkID']==5 || $_SESSION['remarkID']==1) {
                      ?>
                      <li class="<?php echo $attachmentstatus; ?>"><a href="index.php?sz=attachment"><i
                                      class="fa fa-upload"></i><span>Attachment</span><i class="fa fa-angle-right"></i></a>
                      </li>
                      <?php
                      }
                 }
                      ?>

                      <li class="<?php echo $submitstatus; ?>"><a href="index.php?sz=submit"><i
                                      class="fa fa-send-o"></i><span>Submit Application</span><i
                                      class="fa fa-angle-right"></i></a></li>

                      <li class="treeview">
                          <hr>
                      </li>
                      <?php
                  //}
                      ?>

                  <li class="<?php echo $chstatus; ?>"><a href="index.php?sz=changepwd"><i
                                  class="fa fa-pencil-square-o"></i><span>Change Password</span><i
                                  class="fa fa-angle-right"></i></a>

                  </li>

                  </li>
                  <li class="<?php echo $lstatus; ?>"><a href="logout.php?logout=true"><i
                                  class="fa fa-sign-out"></i><span>Logout</span><i class="fa fa-angle-right"></i></a>

                  </li>
                  <?php
              }
              ?>
          </ul>
        </section>

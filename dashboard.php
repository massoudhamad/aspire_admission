<?php
$db=new DBHelper();
  $applicationYear=$db->getRows('academicyears',array('where'=>array('academicYearStatus'=>1),'order_by'=>'academicYearID'));
    foreach ($applicationYear as $appYear) {
        $applicationYearID=$appYear['academicYearID'];
    }
$activeInTake=$db->getRows("admission_setting",array('where'=>array('academicYearID'=>$applicationYearID,'yearStatus'=>1),'order_by academicYearID'));
if(!empty($activeInTake)) {
    foreach ($activeInTake as $intake) {
        $admissionID = $intake['admissionID'];
        $admissionInTakeID = $intake['admissionInTakeID'];
    }
}
    /*
'1', 'Submit', 'Applicant has due submit form', NULL
'2', 'Approved', 'Applicant can due pay applicantion fee', NULL
'3', 'Admitted', 'Applicant that accepted to join university ', NULL
'4', 'Rejected', 'Applicant that not admitted universty', NULL
'5', 'Incomplete', 'Applicant not Complete Applicantion', NULL
'6', 'Registered', 'Applicant Registered for Subjects', NULL     */
/*Summary for all applicants*/
        //started
      $started=$db->getCountApplicants(7, $applicationYearID,$admissionID, "all",null);
       //applied/submit
      $applied=$db->getCountApplicants(1, $applicationYearID,$admissionID, "all",null);
      //$pappplied= number_format(($applied/700)*100,2);
      //approved
      $approved=$db->getCountApplicants(2, $applicationYearID,$admissionID, "all",null);
      
      //imcomplete
      $incomplete=$db->getCountApplicants(5, $applicationYearID,$admissionID, "all",null);
     
      //admitted
      $admitted=$db->getCountApplicants(3, $applicationYearID,$admissionID, "all",null);
      
      //rejected
      $rejected=$db->getCountApplicants(4, $applicationYearID,$admissionID, "all",null);
      
      //registered
      $registered=$db->getCountApplicants(6, $applicationYearID,$admissionID, "all",null);
      //totalnumberincomplete
      
      //total
      $totalapplied=$registered+$rejected+$admitted+$incomplete+$approved+$applied+$started;
      
      $complete=$registered+$rejected+$admitted+$incomplete+$approved+$applied;
      $inprogress=$started;
      $totalIncomplete=$incomplete;
      $totalApproved=$approved+$admitted+$registered+$rejected;
      $totalAdmitted=$admitted+$registered;
      
      
     /* $prejestered= number_format(($registered/$totalAdmitted)*100,2);
      $prejected= number_format(($rejected/$totalApproved)*100,2);
      $padmitted= number_format(($admitted/$totalApproved)*100,2);
      $pincomplete= number_format(($incomplete/$complete)*100,2);
      $papproved= number_format(($approved/$complete)*100,2);
      $pinprogress= number_format(($inprogress/$totalapplied)*100,2);
       */           
?>
<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <div class="box">
            <div class="box-header">
              <h3 class="box-title">Application Summary</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <table class="table table-condensed">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Items</th>
                  <th>Progress</th>
                  <th style="width: 40px">Value</th>
                </tr>
                <?php
                
                ?>
                <tr>
                  <td>1.</td>
                  <td>Number Applied</td>
                  <td>
                    <div class="progress progress-xs">
                      <div class="" style="width: 55%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-gray"><?php echo $totalapplied;?></span></td>
                </tr>
                <tr>
                  <td>2.</td>
                  <td>Number Inprogress</td>
                  <?php
                  if($inprogress==0)
                  {
                      $percent=0;
                  }
                  else
                  {
                    $percent=($inprogress/$totalapplied)*100;
                  }
                  ?>
                       <?php 
                        if($percent>=70)
                        {
                       ?>
                        <td><div class="progress progress-xs">
                        <div class="progress-bar progress-bar-success" style="width: <?php echo $percent;?>%"></div>
                       </div></td>
                       <td><span class="badge bg-success"><?php echo $inprogress;?>(<?php echo number_format($percent);?>%)</span></td>
                      <?php 
                        }
                        else if($percent>=50)
                        {
                            ?>
                       <td><div class="progress progress-xs">
                        <div class="progress-bar progress-bar-primary" style="width: <?php echo $percent;?>%"></div>
                           </div></td>
                           <td><span class="badge bg-light-blue"><?php echo $inprogress;?>(<?php echo number_format($percent);?>%)</span></td>
                      <?php 
                        }
                        else
                        {
                            ?>
                           <td><div class="progress progress-xs">
                        <div class="progress-bar progress-bar-yellow" style="width: <?php echo $percent;?>%"></div>
                               </div></td>
                               <td><span class="badge bg-yellow"><?php echo $inprogress;?>(<?php echo number_format($percent);?>%)</span></td>
                      <?php
                        }
                        ?>
                </tr>
                <tr>
                  <td>3.</td>
                  <td>Number Complete</td>
                  <?php
                  if($totalapplied==0)
                  {
                      $percent=0;
                  }
                  else
                  {
                    $percent=($complete/$totalapplied)*100;
                  }
                  ?>
                       <?php 
                        if($percent>=70)
                        {
                       ?>
                        <td><div class="progress progress-xs">
                        <div class="progress-bar progress-bar-success" style="width: <?php echo $percent;?>%"></div>
                       </div></td>
                       <td><span class="badge bg-success"><?php echo $complete;?>(<?php echo number_format($percent);?>%)</span></td>
                      <?php 
                        }
                        else if($percent>=50)
                        {
                            ?>
                       <td><div class="progress progress-xs">
                        <div class="progress-bar progress-bar-primary" style="width: <?php echo $percent;?>%"></div>
                           </div></td>
                           <td><span class="badge bg-light-blue"><?php echo $complete;?>(<?php echo number_format($percent);?>%)</span></td>
                      <?php 
                        }
                        else
                        {
                            ?>
                           <td><div class="progress progress-xs">
                        <div class="progress-bar progress-bar-yellow" style="width: <?php echo $percent;?>%"></div>
                               </div></td>
                               <td><span class="badge bg-yellow"><?php echo $complete;?>(<?php echo number_format($percent);?>%)</span></td>
                      <?php
                        }
                        ?>
                </tr>
                 <tr>
                  <td>4.</td>
                  <td>Number Incomplete</td>
                   <?php
                  if($totalapplied==0)
                  {
                      $percent=0;
                  }
                  else
                  {
                       $percent=($totalIncomplete/$totalapplied)*100;
                  }
                  
                  ?>
                  <?php 
                        if($percent>=70)
                        {
                       ?>
                  <td>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-red" style="width: <?php echo $percent;?>%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-red"><?php echo $totalIncomplete;?>(<?php echo number_format($percent);?>%)</span></td>
                        <?php 
                        
                        }
                        else if($percent>=50)
                        {
                                ?>
                  <td>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-red" style="width: <?php echo $percent;?>%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-red"><?php echo $totalIncomplete;?>(<?php echo number_format($percent);?>%)</span></td> 
                            
                  <?php
                        }
                        else
                        {
                            ?>
                  <td>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-yellow" style="width: <?php echo $percent;?>%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-yellow"><?php echo $totalIncomplete;?>(<?php echo number_format($percent);?>%)</span></td>
                            <?php
                        }
                        ?>
                </tr>
                <tr>
                  <td>5.</td>
                  <td>Number Approved</td>
                 <?php 
                 if($complete==0)
                 {
                     $percent=0;
                 }
                 else
                 {
                    $percent=($totalApproved/$complete)*100;
                 }
                 if($percent>=70)
                 {
                 ?>
                  <td>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-success" style="width: <?php echo $percent;?>%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-success"><?php echo $totalApproved;?>(<?php echo number_format($percent);?>%)</span></td>
                <?php
                 }
                 else if($percent>=50)
                 {
                  ?>
                   <td>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-primary" style="width: <?php echo $percent;?>%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-light-blue"><?php echo $totalApproved;?>(<?php echo number_format($percent);?>%)</span></td>
                  <?php
                 }
                 else 
                     {
                        ?>
                   <td>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-yellow" style="width: <?php echo $percent;?>%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-yellow"><?php echo $totalApproved;?>(<?php echo number_format($percent);?>%)</span></td>
                  <?php
                     }
                 ?>
                </tr>
                
                <tr>
                  <td>6.</td>
                  <td>Number Admitted</td>
                  <?php
                  if($totalApproved==0)
                  {
                      $percent=0;
                  }
                  else
                  {
                    $percent=($totalAdmitted/$totalApproved)*100;
                  }
                  if($percent>=70)
                  {  
                  ?>
                  <td>
                    <div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-success" style="width: <?php echo $totalAdmitted;?>%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-success"><?php echo $totalAdmitted;?>(<?php echo number_format($percent);?>%)</span></td>
                 <?php
                  }
                  else if($percent>=50)
                  {
                      ?>
                   <td>
                    <div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-primary" style="width: <?php echo $totalAdmitted;?>%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-light-blue"><?php echo $totalAdmitted;?>(<?php echo number_format($percent);?>%)</span></td>
                  <?php
                  }
                  else
                  {
                      ?>
                   <td>
                    <div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-yellow" style="width: <?php echo $totalAdmitted;?>%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-yellow"><?php echo $totalAdmitted;?>(<?php echo number_format($percent);?>%)</span></td>
                  <?php
                  }
                  ?>
                </tr>
                <tr>
                  <td>7.</td>
                  <td>Number Rejected</td>
                  <?php
                  if($totalApproved==0)
                  {
                      $percent=0;
                  }
                  else
                  {
                    $percent=($rejected/$totalApproved)*100;
                  }
                  if($percent>=70)
                  {
                  ?>
                  <td>
                    <div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-success" style="width: <?php echo $prejected;?>%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-green"><?php echo $rejected;?>(<?php echo number_format($percent);?>%)</span></td>
                  <?php
                  }
                  else if($percent>=50)
                  {
                      ?>
                  <td>
                    <div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-primary" style="width: <?php echo $prejected;?>%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-light-blue"><?php echo $rejected;?>(<?php echo number_format($percent);?>%)</span></td>
                  <?php
                  }
                  else
                  {
                      ?>
                  <td>
                    <div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-yellow" style="width: <?php echo $prejected;?>%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-yellow"><?php echo $rejected;?>(<?php echo number_format($percent);?>%)</span></td>
                  <?php
                  }
                  ?>
                </tr>
                <tr>
                  <td>8.</td>
                  <td>Number Registered</td>
                  <?php
                  if($totalAdmitted==0)
                  {
                      $percent=0;
                  }
                  else
                  {
                    $percent=($registered/$totalAdmitted)*100;
                  }
                  if($percent>=70)
                  {
                  ?>
                  <td>
                    <div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-success" style="width: <?php echo $registered;?>%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-green"><?php echo $registered;?>(<?php echo number_format($percent);?>%)</span></td>
                  <?php
                  }
                  else if($percent>=50)
                  {
                      ?>
                  <td>
                    <div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-primary" style="width: <?php echo $registered;?>%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-light-blue"><?php echo $registered;?>(<?php echo number_format($percent);?>%)</span></td>
                  <?php
                  }
                  else
                  {
                      ?>
                  <td>
                    <div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-yellow" style="width: <?php echo $registered;?>%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-yellow"><?php echo $registered;?>(<?php echo number_format($percent);?>%)</span></td>
                  <?php
                  }
                  ?>
                </tr>
              </table>
            </div>
            <!-- /.box-body -->
            <br><br>
<!--            <div class="box-footer">
                <div class="col-lg-6"> </div>
                <button type="submit" class="btn btn-flat btn-info">Approve Applicants</button>
                <button type="submit" class="btn btn-flat btn-info">Admit Applicants</button>
               
              </div>-->
           
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        

      <div class="col-md-6">
          <!-- Horizontal Form -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Application Summary</h3>
            </div>
            <!-- /.box-header -->
      <?php
      //start
       $dmstarted=$db->getCountApplicants(7, $applicationYearID,$admissionID, "Male",0);
       $dfstarted=$db->getCountApplicants(7, $applicationYearID,$admissionID, "Female",0);
       
       $emstarted=$db->getCountApplicants(7, $applicationYearID,$admissionID, "Male",1);
       $efstarted=$db->getCountApplicants(7, $applicationYearID,$admissionID, "Female",1);
       
      //applied
      $dmapplied=$db->getCountApplicants(1, $applicationYearID,$admissionID, "Male",0);
      $dfapplied=$db->getCountApplicants(1, $applicationYearID,$admissionID, "Female",0);
      
      $emapplied=$db->getCountApplicants(1, $applicationYearID,$admissionID, "Male",1);
      $efapplied=$db->getCountApplicants(1, $applicationYearID,$admissionID, "Female",1);

      
      //incomplete
      $dmincomplete=$db->getCountApplicants(5, $applicationYearID,$admissionID, "Male",0);
      $dfincomplete=$db->getCountApplicants(5, $applicationYearID,$admissionID, "Female",0);
      
      $emincomplete=$db->getCountApplicants(5, $applicationYearID,$admissionID, "Male",1);
      $efincomplete=$db->getCountApplicants(5, $applicationYearID,$admissionID, "Female",1);
     
      //approved
      $dmapproved=$db->getCountApplicants(2, $applicationYearID,$admissionID, "Male",0);
      $dfapproved=$db->getCountApplicants(2, $applicationYearID,$admissionID, "Female",0);
      
      $emapproved=$db->getCountApplicants(2, $applicationYearID,$admissionID, "Male",1);
      $efapproved=$db->getCountApplicants(2, $applicationYearID,$admissionID, "Female",1);
      
      //admitted
      $dmadmitted=$db->getCountApplicants(3, $applicationYearID,$admissionID, "Male",0);
      $dfadmitted=$db->getCountApplicants(3, $applicationYearID,$admissionID, "Female",0);

      $emadmitted=$db->getCountApplicants(3, $applicationYearID,$admissionID, "Male",1);
      $efadmitted=$db->getCountApplicants(3, $applicationYearID,$admissionID, "Female",1);
      
      
      //rejected
      $dmrejected=$db->getCountApplicants(4, $applicationYearID,$admissionID, "Male",0);
      $dfrejected=$db->getCountApplicants(4, $applicationYearID,$admissionID, "Female",0);
      $dtrejected=$dmrejected+$dfrejected;
      
      $emrejected=$db->getCountApplicants(4, $applicationYearID,$admissionID, "Male",1);
      $efrejected=$db->getCountApplicants(4, $applicationYearID,$admissionID, "Female",1);
      $etrejected=$emrejected+$efrejected;
      $trejeceted=$dtrejected+$etrejected;
      
      
      //registered
      $dmregistered=$db->getCountApplicants(6, $applicationYearID,$admissionID, "Male",0);
      $dfregistered=$db->getCountApplicants(6, $applicationYearID,$admissionID, "Female",0);
      $dtregistered=$dmregistered+$dfregistered;
      
      $emregistered=$db->getCountApplicants(6, $applicationYearID,$admissionID, "Male",1);
      $efregistered=$db->getCountApplicants(6, $applicationYearID,$admissionID, "Female",1);
      $etregistered=$emregistered+$efregistered;
      $tregistered=$dtregistered+$etregistered;
      
      
      //Number Admitted
       $dnmadmitted=$dmadmitted+$dmregistered;
       $dnfadmitted=$dfadmitted+$dfregistered;
       $dntadmitted=$dnmadmitted+$dnfadmitted;
       
       $enmadmitted=$emadmitted+$emregistered;
       $enfadmitted=$efadmitted+$efregistered;
       $entadmitted=$enmadmitted+$enfadmitted;
       
       $tnadmitted=$dntadmitted+$entadmitted;
       
       //Number Approved
       $dnmapproved=$dmapproved+$dnmadmitted+$dmrejected;
       $dnfapproved=$dfapproved+$dnfadmitted+$dfrejected;
       $dntapproved=$dnmapproved+$dnfapproved;
       
       $enmapproved=$emapproved+$enmadmitted+$emrejected;
       $enfapproved=$efapproved+$enfadmitted+$efrejected;
       $entapproved=$enmapproved+$enfapproved;
       
       $tnapproved=$dntapproved+$entapproved;
       
       //Number Complete
       $dnmcomplete=$dmapplied+$dnmapproved+$dmincomplete;
       $dnfcomplete=$dfapplied+$dnfapproved+$dfincomplete;
       $dntcomplete=$dnmcomplete+$dnfcomplete;
       
       $enmcomplete=$emapplied+$enmapproved+$emincomplete;
       $enfcomplete=$efapplied+$enfapproved+$efincomplete;
       $entcomplete=$enmcomplete+$enfcomplete;
      
       $tncomplete=$dntcomplete+$entcomplete;
       
       //number incomplete
       $dnmincomplete=$dmincomplete;
       $dnfincomplete=$dfincomplete;
       $dntincomplete=$dnmincomplete+$dnfincomplete;
       
       $enmincomplete=$emincomplete;
       $enfincomplete=$efincomplete;
       $entincomplete=$enmincomplete+$enfincomplete;
       
       $tnincomplete=$dntincomplete+$entincomplete;
       
       //Number Applied
       $dnmapplied=$dnmcomplete+$dmstarted;
       $dnfapplied=$dnfcomplete+$dfstarted;
       $dntapplied=$dnmapplied+$dnfapplied;
       
       $enmapplied=$enmcomplete+$emstarted;
       $enfapplied=$enfcomplete+$efstarted;
       $entapplied=$enmapplied+$enfapplied;
       
       $tnapplied=$dntapplied+$entapplied;
      ?>
            <div class="box-body no-padding">
              <table class="table table-condensed table-bordered">
                <tr>
                  <th>#</th>
                  <th>Items</th>
                  <th colspan="3" style="text-align:center">Direct</th>
                  <th colspan="3" style="text-align:center">Equivalent</th>
                  <th>Total</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                        <th>Male</th>
                        <th>Female</th>
                        <th>Total</th>
                        
                        <th>Male</th>
                        <th>Female</th>
                        <th>Total</th>
                    <th></th>
                </tr>
                <tr>
                  <td>1.</td>
                  <td>Number Applied</td>
                  <td><?php echo $dnmapplied;?></td>
                  <td><?php echo $dnfapplied;?></td>
                  <td><?php echo $dntapplied;?></td>
                   <td><?php echo $enmapplied;?></td>
                  <td><?php echo $enfapplied;?></td>
                  <td><?php echo $entapplied;?></td>
                  <td><?php echo $tnapplied;?></td>
                </tr>
                 <tr>
                  <td>2.</td>
                  <td>Number Inprogress</td>
                  <td><?php echo $dmstarted;?></td>
                  <td><?php echo $dfstarted;?></td>
                  <td><?php echo $dmstarted+$dfstarted;?></td>
                   <td><?php echo $emstarted;?></td>
                  <td><?php echo $efstarted;?></td>
                  <td><?php echo $emstarted+$efstarted;?></td>
                  <td><?php echo $dmstarted+$dfstarted+$emstarted+$efstarted;?></td>
                </tr>
                <tr>
                  <td>3.</td>
                  <td>Number Complete</td>
                  <td><?php echo $dnmcomplete;?></td>
                  <td><?php echo $dnfcomplete;?></td>
                  <td><?php echo $dntcomplete;?></td>
                   <td><?php echo $enmcomplete;?></td>
                  <td><?php echo $enfcomplete;?></td>
                  <td><?php echo $entcomplete;?></td>
                  <td><?php echo $tncomplete;?></td>
                </tr>
                <tr>
                  <td>4.</td>
                  <td>Number Incomplete</td>
                 
                  <td><?php echo $dnmincomplete;?></td>
                  <td><?php echo $dnfincomplete;?></td>
                  <td><?php echo $dntincomplete;?></td>
                   <td><?php echo $enmincomplete;?></td>
                  <td><?php echo $enfincomplete;?></td>
                  <td><?php echo $entincomplete;?></td>
                  <td><?php echo $tnincomplete;?></td>
                </tr>
                <tr>
                  <td>5.</td>
                  <td>Number Approved</td>
                  <td><?php echo $dnmapproved;?></td>
                  <td><?php echo $dnfapproved;?></td>
                  <td><?php echo $dntapproved;?></td>
                   <td><?php echo $enmapproved;?></td>
                  <td><?php echo $enfapproved;?></td>
                  <td><?php echo $entapproved;?></td>
                  <td><?php echo $tnapproved;?></td>
                </tr>
                 
                <tr>
                  <td>6.</td>
                  <td>Number Admitted</td>
                <td><?php echo $dnmadmitted;?></td>
                  <td><?php echo $dnfadmitted;?></td>
                  <td><?php echo $dntadmitted;?></td>
                   <td><?php echo $enmadmitted;?></td>
                  <td><?php echo $enfadmitted;?></td>
                  <td><?php echo $entadmitted;?></td>
                  <td><?php echo $tnadmitted;?></td>
                </tr>
                <tr>
                  <td>7.</td>
                  <td>Number Rejected</td>
                  <td><?php echo $dmrejected;?></td>
                  <td><?php echo $dfrejected;?></td>
                  <td><?php echo $dtrejected;?></td>
                   <td><?php echo $emrejected;?></td>
                  <td><?php echo $efrejected;?></td>
                  <td><?php echo $etrejected;?></td>
                  <td><?php echo $trejeceted;?></td>
                </tr>
                <tr>
                  <td>8.</td>
                  <td>Number Registered</td>
                  <td><?php echo $dmregistered;?></td>
                  <td><?php echo $dfregistered ;?></td>
                  <td><?php echo $dtregistered;?></td>
                   <td><?php echo $emregistered;?></td>
                  <td><?php echo $efregistered;?></td>
                  <td><?php echo $etregistered;?></td>
                  <td><?php echo $tregistered;?></td>
                </tr>
              </table>
            </div>
            <!-- /.box-body -->
            
<!--            <div class="box-footer">
                <div class="col-lg-7"></div>
                <button type="submit" class="btn btn-flat btn-info">View Summary by Programmes</button>
               
              </div>-->
          </div>
          </div> 


      </div>
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

                  <!-- general form elements -->
              <div class="box">
            <div class="box-header">
              <h3 class="box-title">Application Summary by Programmes</h3>
            </div>
            <!-- /.box-header -->

            <?php
$db = new DBHelper();
$applicationYear=$db->getRows('academicyears',array('where'=>array('academicYearStatus'=>1),'order_by'=>'academicYearID'));
    foreach ($applicationYear as $appYear) {
        $applicationYearID=$appYear['academicYearID'];
    }

$activeInTake=$db->getRows("admission_setting",array('where'=>array('academicYearID'=>$applicationYearID,'yearStatus'=>1),'order_by academicYearID'));
if(!empty($activeInTake)) {
    foreach ($activeInTake as $intake) {
        $admissionID = $intake['admissionID'];
        $admissionInTakeID = $intake['admissionInTakeID'];
    }
}
?>
<div class="box-body no-padding">
              <table class="table table-condensed">
                    <thead>
                    <tr>
                  <th style="width: 10px">#</th>
                  <th>Programmes Name</th>
                  <th>Complete</th>
                  <th>Incomplete</th>
                  <th>Approved</th>
                  <th>Admitted</th>
                  <th>Rejected</th>
                  <th>Registered</th>
                  
                    </tr></thead>
                <tbody>
                <?php
                 $schools=$db->getRows('programs',array('where'=>array('programStatus'=>1),'order_by'=>'studyLevelID ASC'));
                 $count=0;$totalC=0;$totalAd=0;$totalApp=0;$totalRej=0;$totalReg=0;$totalInc=0;
                 foreach ($schools as $sch)
                 {
                     $count++;
                     $programmeID=$sch['programID'];
                     $programmeName=$sch['programName'];
                     
                     $registered=$db->getProgrammeCount($programmeID,6,$applicationYearID,$admissionID);
                     $rejected=$db->getProgrammeCount($programmeID,4,$applicationYearID,$admissionID);
                     $admitted=$db->getProgrammeCount($programmeID,3,$applicationYearID,$admissionID);
                     $approved=$db->getProgrammeCount($programmeID,2,$applicationYearID,$admissionID);
                     $complete=$db->getProgrammeCount($programmeID,1,$applicationYearID,$admissionID);
                     $incomplete=$db->getSchoolCount($programmeID,5,$applicationYearID,$admissionID);
                     
                     $tadmitted=$registered+$admitted; 
                     $tapproved=$tadmitted+$approved+$rejected+$registered;
                     $tincomplete=$incomplete;
                     $trejected=$rejected;
                     $tcomplete=$approved+$admitted+$rejected+$registered+$incomplete+$complete;
                     $totalInc+=$tincomplete;
                     $totalC+=$tcomplete;
                     $totalApp+=$tapproved;
                     $totalAd+=$tadmitted;
                     $totalRej+=$trejeceted;
                     $totalReg+=$registered;
                     $totalRej+=$rejected;
                     ?>
                <tr>
                  <td><?php echo $count;?></td>
                  <td><a href="index3.php?sp=listbyprogrammes&programmeID=<?php echo $programmeID;?>&admissionID=<?php echo $admissionID;?>">
                  <?php echo $programmeName;?></a></td>
                  <td><?php echo $tcomplete;?></td>
                  <td><?php echo $tincomplete;?></td>
                  <td><?php echo $tapproved;?></td>
                  <td><?php echo $tadmitted;?></td>
                  <td><?php echo $trejected;?></td>
                  <td><?php echo $registered;?></td>
                </tr>
                <?php 
                 }
                ?>
                 </tbody>
                 <tfoot>
                 <th></th><th>Total</th>
                 <th><?php echo $totalC;?></th>
                 <th><?php echo $totalInc;?></th>
                <th><?php echo $totalApp;?>    </th>
                <th><?php echo $totalAd;?></th>
                <th><?php echo $totalRej;?></th>
                <th><?php echo $totalReg;?></th>
                </tfoot>
              </table>
      </div>


            <!-- <div class="box-body no-padding">
              <table class="table table-condensed">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>School Name</th>
                  <th>Complete</th>
                  <th>Incomplete</th>
                  <th>Approved</th>
                  <th>Admitted</th>
                  <th>Rejected</th>
                  <th>Registered</th>
                  
                </tr> -->
                <?php
                 /* $schools=$db->getRows('schools',array('order_by'=>'schoolName ASC'));
                 $count=0;$totalC=0;$totalAd=0;$totalApp=0;$totalRej=0;$totalReg=0;$totalInc=0;
                 if(!empty($schools))
                 {
                 foreach ($schools as $sch)
                 {
                     $count++;
                     $schoolID=$sch['schoolID'];
                     $schoolName=$sch['schoolName'];
                     
                     $registered=$db->getSchoolCount($schoolID,6,$applicationYearID,$admissionID);
                     $rejected=$db->getSchoolCount($schoolID,4,$applicationYearID,$admissionID);
                     $admitted=$db->getSchoolCount($schoolID,3,$applicationYearID,$admissionID);
                     $approved=$db->getSchoolCount($schoolID,2,$applicationYearID,$admissionID);
                     $complete=$db->getSchoolCount($schoolID,1,$applicationYearID,$admissionID);
                     $incomplete=$db->getSchoolCount($schoolID,5,$applicationYearID,$admissionID);
                     
                     $tadmitted=$registered+$admitted; 
                     $tapproved=$tadmitted+$approved+$rejected;
                     $trejected=$rejected;
                     $tincomplete=$incomplete;
                     $tcomplete=$approved+$complete+$incomplete+$rejected+$admitted+$registered;
                     
                     $totalC+=$tcomplete;
                     $totalApp+=$tapproved;
                     $totalAd+=$tadmitted;
                     $totalRej+=$trejected;
                     $totalInc+=$tincomplete;
                     $totalReg+=$registered; */
                     ?>
                <!-- <tr>
                  <td><?php echo $count;?></td>
                  <td><a href='index3.php?sp=listbyschool&id=<?php echo $schoolID;?>'><?php echo $schoolName;?></a></td>
                  <td><?php echo $tcomplete;?></td>
                  <td><?php echo $tincomplete;?></td>
                  <td><?php echo $tapproved;?></td>
                  <td><?php echo $tadmitted;?></td>
                  <td><?php echo $trejected;?></td>
                  <td><?php echo $registered;?></td>
                </tr> -->
                <?php 
                 /* }
               } */
                ?>
               <!--  <th></th><th>Total</th>
                <th><?php echo $totalC;?></th>
                <th><?php echo $totalInc;?></th>
                <th><?php echo $totalApp;?></th>
                <th><?php echo $totalAd;?></th>
                <th><?php echo $totalRej;?></th>
                <th><?php echo $totalReg;?></th>
              </table>
            </div> -->
            <!-- /.box-body -->
            <!-- <br><br>
            <div class="box-footer">
                <div class="col-lg-9"> </div>
               
                <a href='index3.php?sp=viewsummaryprogrammes'><button type="submit" class="btn btn-flat btn-info">View Summary By Programmes</button></a>
               
              </div>
           
          </div> -->
          <!-- /.box -->
        </div>
               
         
      </div>
    
      </section>


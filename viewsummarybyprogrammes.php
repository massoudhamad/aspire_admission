<script type="text/javascript">
  $(document).ready(function () {
            $('#programmes').DataTable(
                {
                   "scrollX":true,
                    paging: true,
                    dom: 'Blfrtip',
                    buttons:[
                        {
                            extend:'excel',
                            footer:false,
                            exportOptions:{
                                columns:[0,1,2,3,4,5,6,7]
                            }
                        },
                        ,
                        {
                            extend: 'print',
                            title: 'Statiscal Report By Programmes',
                            footer: false,
                            exportOptions: {
                                columns:[0,1,2,3,4,5,6,7]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Statiscal Report By Programmes',
                            footer: true,
                           exportOptions: {
                                columns:[0,1,2,3,4,5,6,7]
                            },
                            
                        }

                        ]
                });
          });
</script>
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
<div class="row">
                <table id="programmes" class="display nowrap" cellspacing="0" width="100%">
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
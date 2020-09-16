<div class="container">
    <h1>Application Levels</h1>
    <!-- <div class="row"> 
<div class="col-md-12">
<div class="pull-right">
    <a href="index3.php?sp=addnewstudylevel"><span class="btn btn-primary">Add New Study Levels</span></a>
            </div>   
 </div>
</div> -->
    <div class="row">
        <div class="col-md-12">
            <hr>
            <?php
            if (!empty($_REQUEST['msg'])) {
                if ($_REQUEST['msg'] == "succ") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Application Level data has been inserted successfully</strong>.
</div>";
                } else if ($_REQUEST['msg'] == "edited") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Application Level data has been edited Successfully</strong>.
</div>";
            } else if ($_REQUEST['msg'] == "block") {
                echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Application Level data has been edited Successfully</strong>.
</div>";
            } else if ($_REQUEST['msg'] == "unblock") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Application Level data has been edited Successfully</strong>.
</div>";
                
            }else {
                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Something Wrong happen, Contact System Administrator for more Information</strong>.
</div>";
                }
            }
            ?>


        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php

            $db = new DBHelper();
            $users = $db->getRows('programme_level', array('order_by' => 'status DESC'));
            ?>
            <table id="example" class="display nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Application Level</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($users)) {
                        $count = 0;
                        foreach ($users as $user) {
                            $count++;
                            if ($user['status'] == 1) {
                                $status = "Active";
                            } else {
                                $status = "Not Active";
                            } ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $user['programmeLevel']; ?></td>
                                <td><?php echo $status; ?></td>
                                <?php 
                                if($user['status']==1)
                                {
                                $blockButton= '
                                <div class="btn-group">
                                    <a href="action_department.php?action_type=block&id='.$user['programmeLevelID'].'" class="fa fa-unlock" onclick="return confirm(" Are you sure You want to Block This User?");"></a>
                                </div>';
                                }
                                else
                                {
                                $blockButton='
                                <div class="btn-group">
                                    <a href="action_department.php?action_type=unblock&id='.$user['programmeLevelID'].'" class="glyphicon glyphicon-lock" onclick="return confirm(" Are you sure You want to Unblock This User?");"></a>
                                </div>';
                                }
                                ?>
                                <td><?php echo $blockButton;?></td>
                                <!--  <td>
                    <a href="index3.php?sp=edit_levels&id=<?php //echo $user['studyLevelID'];
                                                            ?>" class="glyphicon glyphicon-edit"></a>
                   
                </td> -->
                            </tr>
                    <?php
                        }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
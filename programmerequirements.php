<script type="text/javascript">
    $(document).ready(function() {
        $('#programmesreq').DataTable({
            "scrollX": true,
            paging: true,
            dom: 'Blfrtip',
            buttons: [{
                    extend: 'excel',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }, ,
                {
                    extend: 'print',
                    title: 'List of Programmes',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'List of Programmes',
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    },
                    orientation: 'landscape',
                }

            ]
        });
    });
</script>
<?php
$db = new DBHelper();
?>
<div class="container">
    <h4>Programme Requirements</h4>
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="index3.php?sp=addnewprogrammerequirements"><span class="btn btn-success">Define New Requirements</span></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <hr>
            <?php
            if (!empty($_REQUEST['msg'])) {
                if ($_REQUEST['msg'] == "succ") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme Requirements data has been inserted successfully</strong>.
</div>";
                } else if ($_REQUEST['msg'] == "edited") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme Requirements data has been edited Successfully</strong>.
</div>";
                } else if ($_REQUEST['msg'] == "drop") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme Requirements data has been droped Successfully</strong>.
</div>";
                } else {
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
            $users = $db->getRows('programrequirements', array('order_by' => 'programmeMajorID ASC'));
            ?>
            <table id="programmesreq" class="display nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Programme Name</th>
                        <th>Comp.Subjects</th>
                        <th>Comp.Grade</th>
                        <th>Allowed Subjects</th>
                        <th># Pass Grade</th>
                        <th>Points</th>
                        <th>GPA Points</th>
                        <th>Qualification</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($users)) {
                        $count = 0;
                        foreach ($users as $user) {
                            $count++;
                            $programmeReqID = $user['programRequirementID'];
                    ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $user['programmeMajorID']); ?></td>

                                <td><?php
                                    if ($user['compulsorySubject'] == 1) {
                                        $compulsorySubjects = $db->getRows("subjectrequirements", array('where' => array('programmeRequirementID' => $programmeReqID, 'subjectType' => 'compulsory'), 'order by subjectRequirementID'));
                                        $data = array();
                                        foreach ($compulsorySubjects as $sub) {
                                            $data[] = $db->getData("subjects", "subjectCode", "subjectID", $sub['subjectID']);
                                        }
                                        echo implode(",", $data);
                                    } else {
                                        $compulsorySubject = "None";
                                        echo $compulsorySubject;
                                    }
                                    ?>
                                </td>
                                <td><?php echo $db->getData("grades", "gradeCode", "gradeID", $user['compulsorySubjectGrade']); ?></td>
                                <td><?php
                                    if ($user['allowedSubject'] == 1) {
                                        $allowedSubjects = $db->getRows("subjectrequirements", array('where' => array('programmeRequirementID' => $programmeReqID, 'subjectType' => 'allowed'), 'order by subjectRequirementID'));
                                        $data = array();
                                        foreach ($allowedSubjects as $sub) {
                                            $data[] = $db->getData("subjects", "subjectCode", "subjectID", $sub['subjectID']);
                                        }
                                        echo implode(",", $data);
                                    } else {
                                        $allowedSubjects = "None";
                                        echo $allowedSubjects;
                                    }
                                    ?>
                                </td>
                                <td><?php echo $user['numberOfPassGrade']; ?></td>
                                <td><?php echo $user['pointsRequired']; ?></td>
                                <td><?php echo $user['equivalentEntryGPA']; ?></td>

                                <td><?php
                                    if ($user['entryQualification'] == 1) {
                                        $entrySubjects = $db->getRows("subjectrequirements", array('where' => array('programmeRequirementID' => $programmeReqID, 'subjectType' => 'qualification'), 'order by subjectRequirementID'));
                                        $data = array();
                                        foreach ($entrySubjects as $sub) {
                                            $data[] = $db->getData("qualification", "qualification", "qualificationID", $sub['subjectID']);
                                        }
                                        echo implode(",", $data);
                                    } else {
                                        $entrySubjects = "None";
                                        echo $entrySubjects;
                                    }
                                    ?>
                                </td>

                                <td>
                                    <a href="action_add_programme_requirements.php?action_type=drop&id=<?php echo $user['programRequirementID']; ?>" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this programme requirements?');"></a>

                                </td>
                            </tr>
                        <?php }
                    } else { ?>

                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
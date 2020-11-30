<?php
$db = new DBHelper();
?>
<div class="container">
    <h4>Upload Corrected File</h4>
    <hr>

    <div class="row">
        <div class="col-md-12">
            <?php
            if (!empty($_REQUEST['msg'])) {
                if ($_REQUEST['msg'] == "succ") {
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=submit_selected_tcu' class='close' data-dismiss='alert'>&times;</a>
    <strong>" . $_REQUEST['count'] . " of records has been submitted in TCU</strong>.
</div>";
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=submit_selected_tcu' class='close' data-dismiss='alert'>&times;</a>
                        <strong>TCU Status: " . $_SESSION['output'] . "</strong>.
                    </div>";
                } else if ($_REQUEST['msg'] == "unsucc") {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=submit_selected_tcu' class='close' data-dismiss='alert'>&times;</a>
    <strong>Sorry no data saved in database</strong>.
</div>";
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=submit_selected_tcu' class='close' data-dismiss='alert'>&times;</a>
                        <strong>TCU Status: " . $_SESSION['output'] . "</strong>.
                    </div>";
                }
            }
            ?>


        </div>
    </div>


    <div class="row">
        <form name="" method="post" action="action_upload_non_degree_tcu.php" enctype="multipart/form-data">

            <div class="col-lg-2">
                <label for="FirstName">Attachment</label>
                <input type='file' name="csv_file" accept=".csv" />
            </div>


            <div class="col-lg-3">
                <label for=""></label>
                <input type="hidden" name="action_type" value="add" />
                <input type="submit" name="doSearch" value="Upload File" class="btn btn-primary form-control" />
            </div>
        </form>
    </div>

    <br><br>
    <div class="row">

        <div class="col-lg-12">
            <h4><span id="titleheader">List of Uploaded Applicants for
                </span></h4>
        </div>
        <form name="register" id="register" method="post" action="action_submit_non_degree_uploaded_tcu.php">
            <table id="example" class="display nowrap" cellspacing="0">
                <thead>
                    <tr>
                        <th width="5">SNo</th>
                        <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                        <th>Name</th>
                        <th>Form IV</th>
                        <th>Form VI/AVN</th>
                        <th>Cert.Reg.Number</th>
                        <th>Gender</th>
                        <th>Nationality</th>
                        <th>Impairment</th>
                        <th>Date of Birth</th>
                        <th>Prog.Code</th>
                        <th>Prog.Name</th>
                        <th>Category</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $applicantsData = $db->getRows("applicants_non_degree");
                    if (!empty($applicantsData)) {
                        $i = 0;
                        foreach ($applicantsData as $data) {
                            $i++;
                            $id = $data['id'];
                            $name = $data['name'];
                            $formfour = $data['formfour'];
                            $formsix = $data['fromsix'];
                            $certreg = $data['certreg'];
                            $gender = $data['gender'];

                            $dob = $data['dob'];
                            $disabiliyStatus = $data['imapairment'];
                            $nationality = $data['nationality'];
                            $programmeCode = $data['progcode'];
                            $programmeName = $data['progname'];
                            $entryQualification = $data['category'];
                            $tcu_status = $data['tcu_status'];

                            if ($entryQualification == 1)
                                $category = "Advance";
                            else
                                $category = "Certificate";

                            /* if ($tcu_status == 12) {
                                $box = "NA";
                            } else { */
                                $box = "<input type='checkbox' class='checkbox_class' name='id[]' value='$id'>";
                            //}

                            echo "<tr><td>$i</td>
                           <td>$box</td>
                           <td>$name</td>
                           <td>" . $formfour . "</td>
                           <td>" . $formsix . "</td>
                           <td>$certreg</td>
                          <td>$gender</td>
                          <td>$nationality</td>
                          <td>$disabiliyStatus</td>
                          <td>$dob</td>
                          <td>$programmeCode</td>
                          <td>$programmeName</td>
                          <td>$category</td>
                           </tr>";
                        } ?>
                </tbody>
            </table>


            <div class="row">
                <div class="col-lg-6"></div>
                <input type="hidden" name="number_applicants" value="<?php echo $count; ?>">
                <div class="col-lg-3">
                    <input type="hidden" name="action_type" value="add_app" />
                    <input type="submit" name="doAdmit" value="Submit Applicants" class="btn btn-success form-control">
                </div>
            </div>
        <?php
                    }
        ?>

    </div>
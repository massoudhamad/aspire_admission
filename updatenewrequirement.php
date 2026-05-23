<?php
$db = new DBHelper();
?>
<script src="js/jquery-1.4.2.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#programmeMajorID").change(function() {
            var programmeMajorID = $(this).val();
            var dataString = 'programmeMajorID=' + programmeMajorID;
            $.ajax({
                type: "POST",
                url: "ajax_subjects.php",
                data: dataString,
                cache: false,
                success: function(html) {
                    $("#subjects").html(html);
                    $("#subjects").trigger('chosen:updated');
                    $("#allowedsubjects").html(html);
                    $("#allowedsubjects").trigger('chosen:updated');
                }
            });
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#programmeMajorID").change(function() {
            var programmeMajorID = $(this).val();
            var dataString = 'programmeMajorID=' + programmeMajorID;

            $.ajax({
                type: "POST",
                url: "ajax_subjects_grade.php",
                data: dataString,
                cache: false,
                success: function(html) {
                    $("#subjectsgrade").html(html);
                    $("#subjectsgrade").trigger('chosen:updated');
                }
            });

        });

    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#programmeMajorID").change(function() {
            var programmeMajorID = $(this).val();
            var dataString = 'programmeMajorID=' + programmeMajorID;

            $.ajax({
                type: "POST",
                url: "ajax_qualification.php",
                data: dataString,
                cache: false,
                success: function(html) {
                    $("#qualification").html(html);
                    $("#qualification").trigger('chosen:updated');
                }
            });

        });

    });
</script>

<div class="container">
    <h4>Define Programme Requirements</h4>
    <hr>
    <form name="" method="post" action="action_add_programme_requirements.php">

        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="email">Programme Name</label>
                            <select name="programmeID" class="form-control chosen-select" id="programmeMajorID" required>
                                <?php
                                $programmes = $db->getProgrammeRequirement();
                                if (!empty($programmes)) {
                                    echo "<option value=''>Please Select Here</option>";
                                    foreach ($programmes as $prg) {
                                        $programmeName = $prg['programmeMajor'];
                                        $programmeID = $prg['programmeMajorID'];
                                        $programmeRequirementID=$prg['programRequirementID'];
                                        echo "<option value='$programmeID'>$programmeName</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="email">Allowed Subjects</label>
                            <select name="allowedSubjects[]" multiple="true" class="form-control chosen-select" id="allowedsubjects">
                            </select>
                        </div>
                    </div>
                
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="email">Equivalent Qualification</label>
                            <select name="entryQualification[]" multiple="true" class="form-control chosen-select" id="qualification">
                            </select>
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-lg-6"></div>
                    <div class="col-lg-3">
                        <input type="hidden" name="action_type" value="edit" />
                        <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">
                    </div>
                    <div class="col-lg-3">
                        <input type="reset" name="doSubmit" value="Cancel" class="btn btn-primary form-control">
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
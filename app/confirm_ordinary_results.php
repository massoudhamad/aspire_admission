<?php
session_start();
?>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>
<div class="row">
    <div class="col-lg-12">
        <?php
        if (!empty($_REQUEST['msg'])) {
            /*if($_REQUEST['msg']=="succ") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Congratulations, Successfully you saved your Data</strong>.
                    </div>";
                }
                else*/
            if ($_REQUEST['msg'] == "unsucc") {
                echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error: Not able to save your Data. Looks like your Index Number is already used.</strong>
                    </div>";
            }
            /* else if($_REQUEST['msg']=="dropSchool") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Success, School Data has been droped</strong>.
                    </div>";
                }
                else if($_REQUEST['msg']=="dropSubject") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Success, Subject has been droped</strong>.
                    </div>";
                }*/ else if ($_REQUEST['msg'] == "error") {
                echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error-Something wrong happen-Contact System Administrator</strong>.
                    </div>";
            }
        }
        ?>
    </div>
</div>
<?php
/* Determine the Form IV index the applicant registered with. Prefer the
   value from applicantresults (set the first time we called NECTA at
   registration); fall back to applicants.formfour (the raw typed input). */
$formfour = null;
$indexRows = $db->getRows('applicantresults', array(
    'where' => array('applicantID' => $_SESSION['applicantID']),
    'order_by' => 'applicantID ASC'
));
if (!empty($indexRows)) {
    foreach ($indexRows as $iNumber) {
        if (!empty($iNumber['indexNumber'])) {
            $formfour = $iNumber['indexNumber'];
            break;
        }
    }
}
if (empty($formfour)) {
    /* Legacy applicants may not have a results row yet — read the index
       straight from applicants.formfour. */
    $applRow = $db->getRows('applicants', array(
        'where' => array('applicantID' => $_SESSION['applicantID'])
    ));
    if (!empty($applRow[0]['formfour'])) {
        $formfour = $applRow[0]['formfour'];
    }
}

/* If subjects are already saved (applicantResultStatus=1 AND at least one
   applicantsubjects row), short-circuit this whole page and show a friendly
   summary card with a "Continue" link instead of asking the applicant to
   fetch from NECTA again. */
$alreadySaved = false;
$savedResult  = null;
try {
    $r = $db->getRows('applicantresults', array(
        'where' => array('applicantID' => $_SESSION['applicantID'], 'applicantResultStatus' => 1)
    ));
    if (!empty($r)) {
        foreach ($r as $rw) {
            $subjRows = $db->getRows('applicantsubjects', array(
                'where' => array('applicantResultID' => $rw['applicantResultID'])
            ));
            if (!empty($subjRows)) {
                $alreadySaved = true;
                $savedResult  = $rw;
                $savedResult['subjects'] = $subjRows;
                break;
            }
        }
    }
} catch (Throwable $e) { /* ignore — fall through to legacy fetch flow */ }

if ($alreadySaved):
?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success" style="margin-bottom:14px;">
                <h4 style="margin-top:0;"><i class="fa fa-check-circle"></i> Your Form IV results are saved</h4>
                <p style="margin:6px 0 0;">We have already verified and stored your NECTA results. You do not need to fetch them again.</p>
            </div>

            <div class="box box-success">
                <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-graduation-cap"></i> Form IV Results Summary</h3></div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-4"><strong>Index Number:</strong><br><?php echo htmlspecialchars($savedResult['indexNumber'] ?? ''); ?></div>
                        <div class="col-sm-3"><strong>Year:</strong><br><?php echo htmlspecialchars($savedResult['yearTaken'] ?? ''); ?></div>
                        <div class="col-sm-5"><strong>School:</strong><br><?php echo htmlspecialchars($savedResult['schoolName'] ?? ''); ?></div>
                    </div>
                    <?php if (!empty($savedResult['gradePoints'])): ?>
                    <div class="row" style="margin-top:8px;">
                        <div class="col-sm-4"><strong>Total Points:</strong><br><?php echo htmlspecialchars($savedResult['gradePoints']); ?></div>
                        <div class="col-sm-4"><strong>Award:</strong><br><?php echo htmlspecialchars($savedResult['award'] ?? ''); ?></div>
                    </div>
                    <?php endif; ?>
                    <hr>
                    <strong>Subjects</strong>
                    <table class="table table-striped table-condensed" style="margin-top:8px;">
                        <thead><tr><th>Subject</th><th>Grade</th><th>Points</th></tr></thead>
                        <tbody>
                        <?php foreach ($savedResult['subjects'] as $s):
                            $subjName  = $db->getData('subjects', 'subjectName', 'subjectID', $s['subjectID'] ?? 0);
                            $gradeName = $db->getData('grades',   'grade',       'gradeID',   $s['gradeID']   ?? 0);
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($subjName ?: '—'); ?></td>
                                <td><strong><?php echo htmlspecialchars($gradeName ?: '—'); ?></strong></td>
                                <td><?php echo htmlspecialchars($s['points'] ?? ''); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="margin-top:16px;">
                <a href="index.php?sz=education_background" class="btn btn-success">
                    Continue to next step <i class="fa fa-arrow-right"></i>
                </a>
                <a href="index.php?sz=confirm_ordinary_results&refetch=1" class="btn btn-default" style="margin-left:6px;">
                    <i class="fa fa-refresh"></i> Re-fetch from NECTA
                </a>
            </div>
        </div>
    </div>
<?php
/* Bail before the legacy form so the applicant doesn't also see the
   "Form Four Index Number" input below. */
return;
endif;
?>
<?php
if ($_SESSION['eauthority'] == 'NECTA') {
?>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-10">
                    <div class="card">


                        <form class="form-horizontal" name="form-get-olevel-data" id="form-get-olevel-data" action="" method="post" onsubmit="return ajax_ordinary_level();">
                            <fieldset>
                                <legend>Form Four Results</legend>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="inputEmail">Form Four Index
                                        Number</label>
                                    <div class="col-lg-6">
                                        <input class="form-control" id="indexNumber" type="text" value="<?php echo $formfour; ?>" readonly>
                                    </div>
                                    <div class="col-lg-4">
                                        <input type="submit" name="doSubmit" value="View Results" class="btn btn-success form-control" />
                                    </div>
                                </div>
                            </fieldset>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10">
            <div id="result">
            </div>
        </div>
    </div>
<?php
} else {
?>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-10">
                    <div class="card">


                        <form class="form-horizontal" name="form-get-olevel-data" id="form-get-olevel-data" action="" method="post" onsubmit="return ajax_ordinary_level_equivalence();">
                            <fieldset>
                                <legend>Equivalence Form Four Results</legend>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="inputEmail">Equivalence Form Four Index
                                        Number</label>
                                    <div class="col-lg-6">
                                        <input class="form-control" id="indexNumber" type="text" value="<?php echo $formfour; ?>" readonly>
                                    </div>
                                    <div class="col-lg-4">
                                        <input type="submit" name="doSubmit" value="View Results" class="btn btn-success form-control" />
                                    </div>
                                </div>
                            </fieldset>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10">
            <div id="result">
            </div>
        </div>
    </div>
<?php
}
?>

<!-- Auto-fetch NECTA results on page load — the index is already known
     from registration, no need to make the applicant click "View Results". -->
<script type="text/javascript">
    (function () {
        function autoFetch() {
            var idx = document.getElementById('indexNumber');
            if (!idx || !idx.value) return;
            if (typeof ajax_ordinary_level === 'function') {
                try { ajax_ordinary_level(); return; } catch (e) {}
            }
            if (typeof ajax_ordinary_level_equivalence === 'function') {
                try { ajax_ordinary_level_equivalence(); return; } catch (e) {}
            }
        }
        // Delay slightly so jQuery + script.js are loaded and the DOM is ready.
        if (window.jQuery) { jQuery(document).ready(function(){ setTimeout(autoFetch, 300); }); }
        else { window.addEventListener('load', function(){ setTimeout(autoFetch, 300); }); }
    })();
</script>
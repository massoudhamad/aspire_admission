<?php
if (session_status() === PHP_SESSION_NONE) session_start();
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
/*$indexNumber = $db->getRows('users', array('where' => array('userID' => $_SESSION['user_session']), 'order_by' => 'userID ASC'));
if (!empty($indexNumber)) {
    $count = 0;
    foreach ($indexNumber as $iNumber) {
        $count++;
        $formfour = $iNumber['userName'];
    }
}*/
$indexNumber = $db->getRows('applicantresults', array('where' => array('applicantID' => $_SESSION['applicantID']), 'order_by' => 'applicantID ASC'));
if (!empty($indexNumber)) {
    $count = 0;
    foreach ($indexNumber as $iNumber) {
        $count++;
        $formfour = $iNumber['indexNumber'];
    }
}
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
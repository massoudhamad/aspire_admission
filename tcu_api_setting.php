<?php 
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */

?>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>


<h1>API Settings</h1>
<hr>
<?php
$db = new DBHelper();
if (!empty($_REQUEST['msg'])) {
    if ($_REQUEST['msg'] == "edited") {
        echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
        <strong>Organization data has been edited successfully</strong>.
        </div>";
    }
}
?>
<?php

$api_data = $db->getRows('api_setting', array('order_by' => 'apiSettingID'));
if (!empty($api_data)) {
?>
    <div class="row">
        <div class="col-lg-2">
            <div class="form-group">
                <label for="courseCode">Username</label>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="form-group">
                <label for="email">Token</label>
            </div>
        </div>

       

        <div class="col-lg-2">
            <div class="form-group">
                <label for="email">Token Type</label>
            </div>
        </div>

        <div class="col-lg-2">
            <div class="form-group">
                <label for="email">Source</label>
            </div>
        </div>

    </div>
    <?php
    foreach ($api_data as $api) {
        $api_id = $api['apiSettingID'];
        $api_name = $api['userName'];
        $api_token = $api['token'];
    ?>
        <div class="row">
            <div class="col-lg-2">
                <div class="form-group">
                    <input type="text" id="name" name="name" placeholder="IPA" value="<?php echo $api_name; ?>" class="form-control" required="required" disabled />
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <input type="text" id="token" name="token" placeholder="dq232ed" value="<?php echo $api_token; ?>" class="form-control" required="required" disabled />
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    <input type="text" id="url" name="url" placeholder="" value="<?php echo $api['tokenType']; ?>" class="form-control" required="required" disabled />
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    <input type="text" id="url" name="url" placeholder="" value="<?php echo $api['organizationName']; ?>" class="form-control" required="required" disabled />
                </div>
            </div>

        </div>


<?php
    }
} else {
    $api_name = "";
    $api_token = "";
    $url = "";
}
?>
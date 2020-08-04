<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>


<h1>TCU API Settings</h1>
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
    foreach ($api_data as $api) {
        $api_id = $api['apiSettingID'];
        $api_name = $api['userName'];
        $api_token = $api['token'];
        $url = $api['url'];
    }
} else {
    $api_name = "";
    $api_token = "";
    $url = "";
}
?>
<form name="" method="post" action="action_tcu_setting.php">
    <div class="row">
        <div class="col-lg-8">
            <div class="row">

                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="courseCode">Username</label>
                                <input type="text" id="name" name="name" placeholder="IPA" value="<?php echo $api_name; ?>" class="form-control" required="required" />
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="email">API Token</label>
                                <input type="text" id="token" name="token" placeholder="dq232ed" value="<?php echo $api_token; ?>" class="form-control" required="required" />
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="email">URL</label>
                                <input type="text" id="url" name="url" placeholder="" value="<?php echo $url; ?>" class="form-control" required="required" />
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-3">
            <?php
            if (!empty($api_data)) {
            ?>
                <input type="hidden" name="action_type" value="edit" />
                <input type="hidden" name="id" value="<?php echo $api_data['api_id']; ?>">
                <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">
            <?php
            } else {
            ?>
                <input type="hidden" name="action_type" value="add" />
                <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">
            <?php
            }
            ?>
        </div>
        <!--<div class="col-lg-3">
            <button onclick="goBack()" class="btn btn-danger form-control">Cancel</button>
        </div>-->
    </div>
</form>
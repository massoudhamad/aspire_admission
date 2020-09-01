<h1>Edit API Data</h1>
<?php
$db = new DBHelper();
$apiData = $db->getRows('api_setting', array('where' => array('apiSettingID' => $_GET['id']), 'return_type' => 'single'));
if (!empty($userData)) {
?>
    <div class="row">
        <div class="col-lg-6">
            <form name="" method="post" action="action_api_setting.php">
                <div class="row">
                    <div class="col-md-12">
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="email">Username</label>
                                <input type="text" id="username" name="username" value="<?php echo $apiData['userName']; ?>" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label for="email">Token</label>
                                <textarea name="token" id="token" cols="30" rows="4" class="form-control"><?php echo $apiData['token']; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="email">Token Type</label>
                                <select name="tokenType" class="form-control">
                                    <option value="<?php echo $apiData['tokenType']; ?>"><?php echo $apiData['tokenType']; ?></option>
                                    <option value="token">token</option>
                                    <option value="auth">auth</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Source</label>
                                <select name="organizationName" class="form-control">
                                    <option value="<?php echo $apiData['organizationName']; ?>"><?php echo $apiData['organizationName']; ?></option>
                                    <option value="TCU">TCU</option>
                                    <option value="NACTE">NACTE</option>
                                    <option value="NECTA">NECTA</option>
                                    <option value="MOODLE">MOODLE</option>
                                    <option value="StAR">Academic Records</option>
                                    <option value="Finance">Finance</option>
                                    <option value="OUT">OUT</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">URL</label>
                                <input type="text" id="url" name="url" value="<?php echo $apiData['url']; ?>" class="form-control" />
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-3">
                        <input type="hidden" name="action_type" value="edit" />
                        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                        <input type="submit" name="doSubmit" value="Update Records" class="btn btn-success" tabindex="8">
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php } ?>
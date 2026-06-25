<?php
require_once("DB.php");
$db = new DBHelper();
$org=$db->getRows("organization");
if(!empty($org))
{
    foreach($org as $og)
    {
        $orgName=$og['organizationName'];
        $orgPicture=$og['organizationPicture'];
    }
}

?>
<footer class="main-footer">
        <div class="footer-below">
            <div class="container">
                <div class="row">
                    <div class="pull-left hidden-xs">
                      This product is licensed to <?php echo $orgName; ?>
                    </div>
                    <div class="pull-right hidden-xs">
                        &copy;2014-<?php echo date('Y');?> <?php echo htmlspecialchars($orgName); ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
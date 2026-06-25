<?php
$db=new DBHelper();
$org = $db->getRows("organization");
if (!empty($org)) {
        foreach ($org as $og) {
                $orgName = $og['organizationName'];
                $orgPicture = $og['organizationPicture'];
                $orgPhone = $og['organizationPhone'];
                $studentSupport = $og['student_support'];
        }
}
else 
{
        $orgName = "Imperial College of Health and Allied Sciences";
}
?>
<footer class="main-footer navbar-fixed-bottom">
        <div class="pull-left hidden-xs">
            <?php echo htmlspecialchars($orgName); ?>
        </div>
        <div class="pull-right hidden-xs">
            <strong>&copy;2014-<?php echo date('Y');?> <?php echo htmlspecialchars($orgName); ?></strong>
        </div>
</footer>
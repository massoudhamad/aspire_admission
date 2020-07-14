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
        $orgName = "Soft Dev Academy";
}
?>
<footer class="main-footer navbar-fixed-bottom">
        <!-- To the right -->
        <div class="pull-left hidden-xs">
This product is licensed to <?php echo $orgName;?>
        </div>
        <!-- Default to the left -->
        <div class="pull-right hidden-xs">
        <strong>&copy;2014-<?php echo date('Y');?> <a href="http://www.hmytechnologies.com" target="_blank">HM&Y Technologies</a></strong>
      </div>
</footer>
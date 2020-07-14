<?php
include("../DB.php");
$db=new DBHelper();
$regionID=$_POST['regionID'];
if($regionID)
{
  $district = $db->getRows('agents',array('where'=>array('regionID'=>$regionID),'order_by'=>'agentName ASC'));
 if(!empty($district)){ 
   echo"<option value=''>Please Select Here</option>";
   foreach($district as $dist)
    { 
       $agentID=$dist['agentID'];
       $agentName=$dist['agentName'];
       echo "<option value='$agentID'>$agentName</option>";
        
    }
      
    }
    	echo "<option value='1000'>Others</option>";
}
?>
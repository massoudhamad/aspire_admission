<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>   
<script type="text/javascript">
$(document).ready(function(){
    $("#combination").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".yes").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".yes").hide();
            }
        });
    }).change();
});
</script>
<SCRIPT language="javascript">
		function addRow(tableID) {

			var table = document.getElementById(tableID);

			var rowCount = table.rows.length;
			var row = table.insertRow(rowCount);

			var colCount = table.rows[0].cells.length;

			for(var i=0; i<colCount; i++) {

				var newcell	= row.insertCell(i);

				newcell.innerHTML = table.rows[0].cells[i].innerHTML;
				//alert(newcell.childNodes);
				switch(newcell.childNodes[0].type) {
					case "text":
							newcell.childNodes[0].value = "";
							break;
					case "checkbox":
							newcell.childNodes[0].checked = false;
							break;
					case "select-one":
							newcell.childNodes[0].selectedIndex = 0;
							break;
				}
			}
		}

		function deleteRow(tableID) {
			try {
			var table = document.getElementById(tableID);
			var rowCount = table.rows.length;

			for(var i=0; i<rowCount; i++) {
				var row = table.rows[i];
				var chkbox = row.cells[0].childNodes[0];
				if(null != chkbox && true == chkbox.checked) {
					if(rowCount <= 1) {
						alert("Cannot delete all the rows.");
						break;
					}
					table.deleteRow(i);
					rowCount--;
					i--;
				}


			}
			}catch(e) {
				alert(e);
			}
		}

	</SCRIPT>
<?php
$db=new DBHelper();
?>
<div class="container">
<h2>Add New Programme</h2>
<hr>        
<div class="row">
<div class="col-lg-12">
<form name="" method="post" action="action_programme.php">
    <div class="row">
    <div class="col-lg-4">
<div class="form-group">
<label for="email">Programme Name</label>
<input type="text" id="name" name="name" placeholder="Programme Name" class="form-control" />
</div>
    </div><div class="col-lg-4">
<div class="form-group">
<label for="email">Programme Code</label>
<input type="text" id="code" name="code" placeholder="Programme Code" class="form-control" />
</div></div>
        <div class="col-lg-4">
<div class="form-group">
<label for="email">Programme Duration</label>
<input type="text" id="duration" name="duration" placeholder="Programme Duration" class="form-control" />
</div></div></div>
    <div class="row">
        <div class="col-lg-4">
<div class="form-group">
<label for="email">Programme Level</label>
<select name="studyLevelID"  class="form-control">
    <option value="">Select Here</option>
           <?php
           $programme_level = $db->getRows('studylevels',array('order_by'=>'studyLevelName ASC'));
           if(!empty($programme_level)){ $count = 0; foreach($programme_level as $level){ $count++;
            $programme_level=$level['studyLevelName'];
            $programme_level_id=$level['studyLevelID'];
           ?>
           <option value="<?php echo $programme_level_id;?>"><?php echo $programme_level;?></option>
           <?php }}?>
</select>

</div></div>
        <div class="col-lg-4">
<div class="form-group">
<label for="email">Department Name</label>
<select name="departmentID"  class="form-control">
    <option value="">Select Here</option>
           <?php
           $department = $db->getRows('departments',array('order_by'=>'departmentName ASC'));
           if(!empty($department)){ $count = 0; foreach($department as $dept){ $count++;
            $department_name=$dept['departmentName'];
            $department_id=$dept['departmentID'];
           ?>
           <option value="<?php echo $department_id;?>"><?php echo $department_name;?></option>
           <?php }}?>
</select>

</div></div>
        <div class="col-lg-4">    
<div class="form-group">
<label for="email">Campus Name</label>
<select name="campusID"  class="form-control">
    <option value="">Select Here</option>
           <?php
           $campus = $db->getRows('campus',array('order_by'=>'campusName ASC'));
           if(!empty($department)){ $count = 0; foreach($campus as $dept){ $count++;
            $campusName=$dept['campusName'];
            $campusID=$dept['campusID'];
           ?>
           <option value="<?php echo $campusID;?>"><?php echo $campusName;?></option>
           <?php }}?>
</select>
</div></div></div>

<div class="row">
    <div class="col-lg-4">
            <label for="">Does this programme has commbination/major?</label>
            <select name="hascombination" id="combination" class="form-control" required="">
            <option value="">Select Here</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>
    </div>
<div class="row">
<div class="col-lg-4">
    <div class="yes">
    <div class="row" id="combination">
        <table class="table-striped" width="50%" align="center">
             <thead>
                 <tr>
                    <td align="">CHK</td>
                    <td align="">Combination/Major</td>
                </tr>
             </thead>
            <tr><td colspan="3">
                <TABLE id="dataTable" width="100%" border="0">       
                    <TR>
                       
                      <TD><INPUT type="checkbox" name="chk"/></TD>
                       
                    <TD>
                        <INPUT type="text" name="combination[]" class="form-control">
                    </TD>   
		</TR>
                
                </TABLE></td></tr>
            <tr><td><br></td></tr>
                 <tr><td><INPUT type="button" class="btn btn-danger form-control" value="Delete" onclick="deleteRow('dataTable')" /></td>
                     <td></td>
               <td><INPUT type="button" class="btn btn-primary form-control" value="Add more Combination" onclick="addRow('dataTable')" /></td>  
                </tr>
        </table>
    </div>
    </div>
</div><div class="col-lg-8"></div>
</div>
    <div class="row">
        <div class="col-lg-12"><br><br></div></div> 
<div class="row">
<div class="col-lg-6"></div>
<div class="col-lg-3">
<input type="hidden" name="action_type" value="add"/>
<input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary">
</div>
 <div class="col-lg-3"> <input type="submit" name="doCancel" value="Cancel" class="btn btn-primary"></div>
</div>
</form>
</div>
</div></div>
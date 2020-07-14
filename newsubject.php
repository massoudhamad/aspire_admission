<?php $db=new DBHelper();
$applicantID=$_REQUEST['applicantID'];
?>
<script src="js/jquery-1.4.2.min.js"></script>
<link href="css/validation.css" rel="stylesheet">    
<script type="text/javascript">
             $(document).ready(function()
              {
              $("#indexYear").change(function()
              {
              var indexYear=$(this).val();
              var dataString = 'indexYear='+ indexYear;

              $.ajax
              ({
              type: "POST",
              url: "ajax_grade.php",
              data: dataString,
              cache: false,
              success: function(html)
              {
              $("#grade").html(html);
              } 
              });

              });

              });
        </script>


<div class="row" style="padding-bottom: 15.5%;">
     <div class="col-lg-12">
                </div>
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
$applicantResultID=$_GET['id'];
$indexYear=$_GET['indexYear'];
$level=$_GET['level'];
if($level=="olevel")
     $eLevel=1;
else
    $eLevel=2;

$applicantResult=$db->getRows("applicantresults",array('where'=>array('applicantResultID'=>$applicantResultID),'order_by'=>'applicantResultID'));
if(!empty($applicantResult))
{
    foreach($applicantResult as $appResult)
    {
        $examAuthority=$appResult['examinationAuthority'];
        $sname=$appResult['schoolName'];
        $yearTaken=$appResult['yearTaken'];
        $indexNumber=$appResult['indexNumber'];
        
    }
}
?>

        <form class="form-horizontal" name="register" id="register" action="action_edit_new_subject.php" method="post">   
<div class="col-lg-12">    
              <div class="well">
                  <fieldset>
                  <legend>Please add other subjects for </legend>
                 
                    <div class="row">
                        <div class="col-lg-8">
                            <label for="FirstName">Ordinary Level Examination Body</label>
                            <select name="exam_body" class="form-control" required="required" disabled="">
                                <option value="NECTA" selected="selected"><?php echo $examAuthority;?></option>
                                <option value="Others">Other Examination Body</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="Email">Year of Sitting</label>
                            <select name="indexYear" id="indexYear" class="form-control" required="required" disabled="">
                                <option value="<?php echo $yearTaken;?>" selected=""><?php echo $yearTaken;?></option>
                            </select>
                        </div>
                        
                        
                    </div>
                    <div class="row">
                        
                        <div class="col-lg-6">
                            <label for="Physical Address">Form Four Index Number</label>
                            <input type="text" name="indexNumber" value="<?php echo $indexNumber;?>" id="indexNumbe"  class="form-control" required="required" disabled="" />
                        </div>
                        
                         <div class="col-lg-6">
                            <label for="Physical Address">School/Center Number</label>
                            <input type="text" name="schoolName" value="<?php echo $sname;?>" id="schoolName"  class="form-control" required="required" disabled=""/>
                        </div>
                        
                    </div>
                  
                  
                  
                        </fieldset>
                  <fieldset>
                      <legend>Choose Subjects</legend>
                      <table class="table-striped" width="40%">
                                  <thead><tr>
                                  <td align="left">CHK</td><td align="left">Subject Name</td><td>Grade</td>
                                  </tr></thead>
                
                <tbody>
                <td colspan="3"> 
                <TABLE id="dataTable" width="100%" border="0">       
		<TR>
                    <TD><INPUT type="checkbox" name="chk"/></TD>
                    <TD>
                        
                        <select name="subjectCode[]" id="subjectID" class="form-control" required="required">
                        <option value="">Select Subject</option>
                        <?php
                        $subject = $db->getSubject($applicantResultID,$eLevel);
                        if(!empty($subject)){
                            foreach($subject as $sbj){
                         $subjectID=$sbj['subjectID'];
                         $subjectName=$sbj['subjectName'];
                        ?>
                        ?>
                        <option value="<?php echo $subjectID;?>"><?php echo $subjectName;?></option>
                        <?php }}?>
                       </select>
                        
                    </TD>
                    <TD>
        <select name="gradeCode[]" class="form-control">
       <?php
       if($elevel=="O")
       {
        if($indexYear==2014 or $indexYear==2015)
        $gradeRange=2014;
        else 
        $gradeRange=2013;
       }
       else
         $gradeRange=2013;  
        $grade = $db->getRows('grades',array('where'=>array('gradeRangeYear'=>$gradeRange,'gradeLevel'=>$eLevel),'order_by'=>'gradeID ASC'));
        if(!empty($grade)){ 
          echo"<option value=''>Please Select Here</option>";
          foreach($grade as $gd)
           { 
              $gradeID=$gd['gradeID'];
              $gradeCode=$gd['gradeCode'];
              echo "<option value='$gradeID'>$gradeCode</option>";

           }
      
    }?>
        </select>
                    </TD>
                        
		</TR>
                
                   </TABLE></td>
                </tbody>
                <tr><td><br></td></tr>
                 <tr><td><INPUT type="button" class="btn btn-danger form-control" value="Delete" onclick="deleteRow('dataTable')" /></td>
                     <td></td>
               <td><INPUT type="button" class="btn btn-primary form-control" value="Add more Subjects" onclick="addRow('dataTable')" /></td>
                    
                    
                </tr>
                        </table>
                  </fieldset>
                        
                   </div>                
</div>
                        <div class="col-lg-4"></div>
                            <div class="col-lg-4">
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="hidden" name="applicantID" value="<?php echo $applicantID;?>">
                            <input type="hidden" name="applicantResultID" value="<?php echo $applicantResultID;?>">
                            <input type="hidden" name="examinationlevel" value="Ordinary">
                             <input type="hidden" name="examinationaward" value="formfour">
                            <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control" />
                        </div>
                        <div class="col-lg-4">
                            <input type="button" name="doExit" value="Cancel" onclick="window.history.go(-1);" class="btn btn-success form-control" />
                        </div>
                        
                       
</form> 
                    </div>
                        

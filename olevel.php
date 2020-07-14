<?php $db=new DBHelper();
/*if(isset($_POST['doProceed']))
    $db->redirect("index2.php?sz=education_background");*/
/*if(isset($_POST['doExit'])=="Cancel")
    header("Location:index2.php?sz=education_background");*/
?>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<link href="css/validation.css" rel="stylesheet">    
<script type="text/javascript">
$(document).ready(function(){
    $("#exam_body").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".Others").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".Others").hide();
            }
        });
    }).change();
});
</script> 
<script type="text/javascript">
$(document).ready(function(){
    $("#exam_body").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".NECTA").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".NECTA").hide();
            }
        });
    }).change();
});
</script>
<script type="text/javascript">
$(document).ready(function(){
    $("#exam_body").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".NECTAO").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".NECTAO").hide();
            }
        });
    }).change();
});
</script>
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
        <form class="form-horizontal" name="register" id="register" action="action_edit_educational_background.php" method="post">   
<div class="col-lg-12">    
              <div class="well">
                  <fieldset>
                  <legend>Please fill Ordinary Level(FIV) Results</legend>
                 
                    <div class="row">
                        <div class="col-lg-8">
                            <label for="FirstName">Ordinary Level Examination Body</label>
                            <select name="exam_body" id="exam_body" class="form-control" required="required">
                                <option value="NECTA" selected="">National Examination Council of Tanzania (NECTA)(From 1988-To Date)</option>
                                <option value="NECTAO">National Examination Council of Tanzania (NECTA)(Bellow 1988)</option>
                                <option value="Others">Other Examination Body</option>
                            </select>
                        </div>
                        
                        <div class="Others"><div class="col-lg-4">
                            <label for="Other Bodies">Other Body</label>
                            <input type="text" name="other_body" id="other_body"  class="form-control"/>
                            </select>
                            </div></div>
                        
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="Email">Year of Sitting</label>
                            <select name="indexYear" id="indexYear" class="form-control" required="required">
                                <option value="">Select Year</option>
                                <?php 
                                $year=date('Y');
                                $year1=date('Y')-40;
                                for($x=$year;$x>=$year1;$x--)
                                {
                                    echo "<option value='$x'>$x</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="NECTA"><div class="col-lg-4">
                            <label for="Physical Address">Form Four Index Number</label>
                            <input type="text" name="indexNumber" id="indexNumber"  class="form-control" />
                            </div></div>
                         <div class="NECTAO"><div class="col-lg-4">
                            <label for="Physical Address">Form Four Index Number</label>
                            <input type="text" name="indexNumberOld" id="indexNumberOld"  class="form-control"/>
                            </div></div>
                        <div class="Others"><div class="col-lg-4">
                            <label for="Physical Address">Ordinary Level Index Number</label>
                            <input type="text" name="indexNumberOther" id="indexNumberOther"  class="form-control" />
                            </div></div>
                        
                         <div class="col-lg-4">
                            <label for="Physical Address">School/Center Name</label>
                            <input type="text" name="schoolName" id="schoolName"  class="form-control" required="required" />
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
                        $subject = $db->getRows('subjects',array('where'=>array('stream'=>'1'),'order_by'=>'subjectName ASC'));
                        if(!empty($subject)){ $count = 0; foreach($subject as $sbj){ $count++;
                         $subjectName=$sbj['subjectName'];
                         $subjectID=$sbj['subjectID'];
                        ?>
                        <option value="<?php echo $subjectID;?>"><?php echo $subjectName;?></option>
                        <?php }}?>
                       </select>
                        
                    </TD>
                    <TD>
                        <SELECT name="gradeCode[]" id="grade" class="form-control" required="required">
                            <option value="">--Select Grade--</option>
			</SELECT>
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
                            <input type="hidden" name="applicantID" value="<?php echo $_REQUEST['applicantID'];?>">
                            <input type="hidden" name="examinationlevel" value="Ordinary">
                             <input type="hidden" name="examinationaward" value="formfour">
                            <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control" />
                        </div>
                        <div class="col-lg-4">
                            <input type="button" name="doExit" value="Cancel" onclick="window.history.go(-1);" class="btn btn-success form-control" />
                        </div>
</form> 
                    </div>
                        

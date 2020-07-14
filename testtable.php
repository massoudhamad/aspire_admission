<HTML>
<HEAD>
	<TITLE> Add/Remove dynamic rows in HTML table </TITLE>
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
</HEAD>
<BODY>

	<INPUT type="button" value="Add Row" onclick="addRow('dataTable')" />

	<INPUT type="button" value="Delete Row" onclick="deleteRow('dataTable')" />

	<TABLE id="dataTable" width="" border="0">
		<TR>
                    <TD><INPUT type="checkbox" name="chk" class="form-control"/></TD>
                    <TD><INPUT type="text" name="txt" class="form-control" required="required"/></TD>
			<TD>
				<SELECT name="country" class="form-control" required="required">
					<OPTION value="in">India</OPTION>
					<OPTION value="de">Germany</OPTION>
					<OPTION value="fr">France</OPTION>
					<OPTION value="us">United States</OPTION>
					<OPTION value="ch">Switzerland</OPTION>
				</SELECT>
			</TD>
                        <td><INPUT type="button" value="Delete Row" onclick="deleteRow('dataTable')" /></td>
		</TR>
	</TABLE>
        <INPUT type="button" value="Add Row" onclick="addRow('dataTable')" />

	

</BODY>
</HTML>
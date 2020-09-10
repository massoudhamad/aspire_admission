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

    function myFunction() {
        var indexNumber = document.getElementById("indexNumber").value;
        var level = document.getElementById("level").value;
        var dataString = 'indexNumber=' + indexNumber + '&level='+level;
        var ireg=/^[EePpSsUu][0-9]+[/][0-9]+[/][0-9]{4}$/;
        if (indexNumber == ''){
            alert("Please fill all fields");
        } else if(!ireg.test(indexNumber)) {
            alert("Invalid Index Number Format");
        }
        else {
            $('#myPleaseWait').modal('show');
            $.ajax({
                type: "POST",
                url: "ajax_ordinary_level.php",
                data: dataString,
                cache: false,
                success: function(html) {
                    $('#myPleaseWait').modal('hide');
                    $("#result").html(html);
                }
            });
        }
        return false;
    }


function equivalentfunction() {
    var avn_number = document.getElementById("avn_number").value;
    var dataString = 'avn_number=' + avn_number;
    var ireg=/^[EePpSsUu][0-9]+[/][0-9]+[/][0-9]{4}$/;
    if (avn_number == ''){
        alert("Please fill all fields");
    }
    else {
        $('#myPleaseWait').modal('show');
        $.ajax({
            type: "POST",
            url: "ajax_equivalent_level.php",
            data: dataString,
            cache: false,
            success: function(html) {
                $('#myPleaseWait').modal('hide');
                $("#result").html(html);
            }
        });
    }
    return false;
}

function ajax_ordinary_level() {
    var indexNumber = document.getElementById("indexNumber").value;
    var dataString = 'indexNumber=' + indexNumber;
    var ireg=/^[EePpSsUu][0-9]+[/][0-9]+[/][0-9]{4}$/;
    if (indexNumber == ''){
        alert("Please fill all fields");
        } /*else if(!ireg.test(indexNumber)) {
            alert("Invalid Index Number Format");
    }*/
    else {
        $('#myPleaseWait').modal('show');
        $.ajax({
            type: "POST",
            url: "ajax_o_level.php",
            data: dataString,
            cache: false,
            success: function(html) {
                $('#myPleaseWait').modal('hide');
                $("#result").html(html);
            }
        });
    }
    return false;
}

function ajax_ordinary_level_equivalence() {
    var indexNumber = document.getElementById("indexNumber").value;
    var dataString = 'indexNumber=' + indexNumber;
    var ireg = /^[EePpSsUu][0-9]+[/][0-9]+[/][0-9]{4}$/;
    if (indexNumber == '') {
        alert("Please fill all fields");
    }
    /*else if(!ireg.test(indexNumber)) {
               alert("Invalid Index Number Format");
       }*/
    else {
        $('#myPleaseWait').modal('show');
        $.ajax({
            type: "POST",
            url: "ajax_equivalence_results.php",
            data: dataString,
            cache: false,
            success: function (html) {
                $('#myPleaseWait').modal('hide');
                $("#result").html(html);
            }
        });
    }
    return false;
}

function validateEquivalent()
{
    var entry_qualification = document.getElementById("entry_qualification");
    var pname = document.getElementById("programmeName");
    var instituteName = document.getElementById("instituteName");
    var registrationNumber = document.getElementById("registrationNumber");
    var indexYear = document.getElementById("indexYear");
    var gradeType = document.getElementById("gradeType");
    var qualificationTypeID = document.getElementById("qualificationTypeID");
   /* if(entry_qualification[0].checked != true || entry_qualification[1].checked != true)
    {
        alert("You must select a entry qualification");
        return false;
    }
    else*/ if(pname.value == '')
    {
        alert("Please,fill programme name");
        return false;
    }
    else if(instituteName.value == '')
    {
        alert("Please,fill institute name");
        return false;
    }
    else if(registrationNumber.value == '')
    {
        alert("Please,fill registration number");
        return false;
    }
    else if(indexYear.value == '')
    {
        alert("Please,select graduation year");
        return false;
    }
    /*else if(gradeType[0].checked != true || gradeType[1].checked != true)
    {
        alert("You must select Grade Type and your grade");
        return false;
    }*/
    else if(qualificationTypeID.value == '')
    {
        alert("Please,select qualification type");
        return false;
    }
    else {
        return true;
    }

}

function validateOtherSchool() {
    var sname = document.getElementById("schoolName");
    if (sname.value == ''){
        alert("Please fill all fields");
        sname.focus();
        return false;

    } else {
        return true;
    }
}

function validateOtherBody() {
    var inumber = document.getElementById("indexNumberOther");
    var year=document.getElementById("indexYear");
    var sname=document.getElementById("schoolName");
    if (inumber.value == '') {
        alert("Please Fill Index Number");
        inumber.focus();
        return false;
    }
    else if(year.value==""){
            alert("Please select year of sitting");
            year.focus();
            return false;
        }
        else if(sname.value == "")
        {
            alert("Please fill school name");
            sname.focus();
            return false;
        }
        else {
        return true;
    }
}



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

$(document).ready(function(){
    $("#qualificationTypeID").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue==2) {
                $(".diploma").not("." + optionValue).show();
                $("." + optionValue).show();
                $(".Others").hide();
            }
                else if(optionValue==1 || optionValue==3 || optionValue==9)
                {
                    $(".Others").not("." + optionValue).show();
                    $("." + optionValue).show();
                    $(".diploma").hide();
                }
                else {
                    $(".diploma").hide();
                    $(".Others").hide();
                }
        });
    }).change();
});

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

    $(document).ready(function()
    {
        $("#indexYear").change(function()
        {
            var indexYear=$(this).val();
            var level=$(level).val();
            var dataString = 'indexYear='+ indexYear+'level='+level;

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





/*$(document).ready(function() {
    $("#register").validate({
        rules: {
            indexYear:{
                required:true
            },
            name: "required",
            indexNumber:{
                required:true,
                minlength:15,
                maxlength:15
            },
            exam_body: {
                required: true
            },
            phoneNumber: {
                required: true,
                number: true
            },
            url: {
                required: false,
                url: true
            },
            username: {
                required: true,
                minlength: 6
            },
            password: {
                required: true,
                minlength: 6
            },
            confirm_password: {
                required: true,
                minlength: 6,
                equalTo: "#password"
            },
            agree: "required"
        },
        messages: {
            indexNumber: {
                required: "Please Enter Valid Index Number",
                maxlength: "The Maximum Length is 15",
                minlength:"The Minimum Length is 15"
            },
            indexYear: {
                required: "Please Select Index Year"
            },
            exam_body: {
                required: "Please Select Exam Body"
            },
            name: "Please enter your name",
            email: "Please enter a valid email address",
            phoneNumber: {
                required: "Please enter your phone number",
                number: "Please enter only numeric value"
            },
            url: {
                url: "Please enter valid url"
            },
            username: {
                required: "Please enter a username",
                minlength: "Your username must consist of at least 6 characters"
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long"
            },
            confirm_password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long",
                equalTo: "Please enter the same password as above"
            },
            agree: "Please accept our policy"
        }
    });

    $('#indexNumber').mask('TCCCC/SSSS/RRRR', {'translation': {
            T: {pattern: /[EePpSsUu]/},
            C: {pattern: /[0-9]/},
            S: {pattern: /[0-9]/},
            R: {pattern: /[0-9]/}
        }
    });
});*/

$(document).ready(function()
{
    $("#qualificationTypeID").change(function()
    {
        var qualificationTypeID=$(this).val();
        var dataString = 'qualificationTypeID='+ qualificationTypeID;

        $.ajax
        ({
            type: "POST",
            url: "ajax_study_level.php",
            data: dataString,
            cache: false,
            success: function(html)
            {
                $("#studyLevelID").html(html);
            }
        });

    });

});

/*$(function(){
    $("#chosen").chosen();
    $.validator.setDefaults({ ignore: ":hidden:not(select)" })
    $("#register").validate({
        rules: {chosen:"required"},
        message: {chosen:"Select Programmes"}
    });
});

    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#myTab a[href="' + activeTab + '"]').tab('show');
        }
    });


$(document).ready(function() {
    $('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
});



$(document).ready(function(){
    //Chosen
    $("#limitedNumbChosen").chosen({
        //max_selected_options: 3,
        placeholder_text_multiple: "Select Here"
    })
        .bind("chosen:maxselected", function (){
            window.alert("You reached your limited number of selections which is 2 selections!");
        })
});*/

/*$(document).ready(function()
    {
        $("#qualificationTypeID").change(function()
        {
            var qualificationTypeID=$(this).val();
            var dataString = 'qualificationTypeID='+ qualificationTypeID;

            $.ajax
            ({
                type: "POST",
                url: "ajax_study_level.php",
                data: dataString,
                cache: false,
                success: function(html)
                {
                    $("#studyLevelID").html(html);
                }
            });

        });

    });*/
//Programme Choice
$(document).ready(function()
{
    $("#studyLevelID").change(function()
    {
        var studyLevelID=$(this).val();
        var dataString = 'studyLevelID='+ studyLevelID;

        $.ajax
        ({
            type: "POST",
            url: "ajax_programmechoice.php",
            data: dataString,
            cache: false,
            beforeSend: function () {
                $('#programmeID').html('<img src="../assets/img/loader.gif" alt="" >');
            },
            success: function(html)
            {
                $("#programmeID").html(html);
            }
        });

    });

});


    $(document).ready(function(){
        $("#employed").change(function(){
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

    $(document).ready(function(){
        $("#disability").change(function(){
            $(this).find("option:selected").each(function(){
                var optionValue = $(this).attr("value");
                if(optionValue){
                    $(".ndio").not("." + optionValue).hide();
                    $("." + optionValue).show();
                } else{
                    $(".ndio").hide();
                }
            });
        }).change();
    });

    $(document).ready(function(){
        $("#sponsor").change(function(){
            $(this).find("option:selected").each(function(){
                var optionValue = $(this).attr("value");
                if(optionValue){
                    $(".others").not("." + optionValue).hide();
                    $("." + optionValue).show();
                } else{
                    $(".others").hide();
                }
            });
        }).change();
    });